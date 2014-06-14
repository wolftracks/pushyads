<?php
include_once("initialize.php");
include_once("pushy_tree.inc");

// include_once("pushy_get_response.inc");

include_once("pushy_jsontools.inc");

$firstname="";
$lastname="";
$email="";


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && is_integer(strpos($_SERVER['HTTP_X_REQUESTED_WITH'],'XMLHttpRequest')))
 {
   $isAjax=TRUE;
 }
else
 {
   $isAjax=FALSE;
 }


// -----  Cookie Referral Wins if Set - else whatever was used to bring up affiliate/home page
if (isset($_COOKIE["PAREF"]) && strlen($_COOKIE["PAREF"]) > 0)
  $paref=$_COOKIE["PAREF"];
else
  $paref=$_REQUEST["PAREF"];


if (isset($_REQUEST["firstname"]))
  $firstname=ucfirst_only($_REQUEST["firstname"]);
else
  $errormsg="First name is required";
if (isset($_REQUEST["lastname"]))
  $lastname=ucfirst_only($_REQUEST["lastname"]);
else
  $errormsg="Last name is required";
if (isset($_REQUEST["email"]))
  $email=strtolower($_REQUEST["email"]);
else
  $errormsg="Email address is required";

$ALREADY_REGISTERED="";
if (strlen($firstname)>0 && strlen($lastname)>0  && strlen($email)>0)
 {
   $db=getPushyDatabaseConnection();

   $refid="";
   if (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$paref)))
     {
       $refid=$affiliateRecord["member_id"];
     }

   $processed=FALSE;
   $errormsg="";
   $REMOTE_ADDR = $_SERVER["REMOTE_ADDR"];

   $sql  = "SELECT * from member ";
   $sql .= " WHERE email='$email'";
   $result = mysql_query($sql,$db);
   if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
     {
       $member_id    = $myrow["member_id"];
       $affiliate_id = $myrow["affiliate_id"];
       $registered   = $myrow["registered"];
       $confirmed    = $myrow["confirmed"];
       $refid        = $myrow["refid"];

       $firstname    = stripslashes($myrow["firstname"]);
       $lastname     = stripslashes($myrow["lastname"]);
       $fullname     = getMemberFullName($myrow);

            // Email Address Exists
       if ($registered == 0)  //--- Registration had not been previously completed
         {
            $sql  = "UPDATE member set ";
            $sql .= " firstname='".addslashes($firstname)."',";
            $sql .= " lastname='".addslashes($lastname)."',";
            $sql .= " user_ip='$REMOTE_ADDR', ";
            $sql .= " confirmed=0, ";
            $sql .= " registered=0, ";
            $sql .= " lastaccess=0, ";
            $sql .= " date_registered='', ";
            $sql .= " date_lastaccess='' ";
            $sql .= " WHERE email='$email'";
            $sql .= " AND   member_id='$member_id'";
            $sql .= " AND registered=0";
            $result = mysql_query($sql,$db);
            if ($result)
              {
                 $messageFile = MESSAGE_DIRECTORY."/general/not_confirmed_0.txt";

                 $vars=array();
                 $vars["firstname"]  = $firstname;
                 $vars["email"]      = $email;
                 $vars["mid"]        = $member_id;

                 sendMessageFile($messageFile, $fullname, $email, $vars);

                 $processed=TRUE;
              }
            else
              {
                $errormessage="Error: ".mysql_errno()." Msg: ".mysql_error();
              }
         }
       else
         {
           $ALREADY_REGISTERED=$email;
           $errormessage="Already Registered";
         }
     }
   else
     {
        $tree_result = tree_createNewMember($db, $refid, $email);

        if (is_array($tree_result) && count($tree_result)==3)
          {
            list($member_id, $refid, $insert_id) = $tree_result;

            $user_ip = $REMOTE_ADDR;
            $chars="bcdfghjkmnprstvwxz123456789";

            $password="";  // Default Password

            // for ($i=0;$i<8;$i++)
            //   {
            //     $n=rand(0,strlen($chars)-1);
            //     $password .= $chars[$n];
            //   }

            $affiliate_id = "".$insert_id."-";
            for ($i=0;$i<4;$i++)
              {
                $n=rand(0,strlen($chars)-1);
                $affiliate_id .= $chars[$n];
              }

            $tm=time();
            $sql  = "UPDATE member set ";
            $sql .= " password     = '$password', ";
            $sql .= " refid        = '$refid', ";
            $sql .= " affiliate_id = '$affiliate_id', ";
            $sql .= " user_level   = '$PUSHY_LEVEL_VIP', ";
            $sql .= " firstname    = '".addslashes($firstname)."', ";
            $sql .= " lastname     = '".addslashes($lastname)."', ";
            $sql .= " email        = '$email', ";
            $sql .= " country      = 'USA', ";
            $sql .= " user_ip      ='$REMOTE_ADDR', ";
            $sql .= " confirmed=0, ";
            $sql .= " lastaccess=0, ";
            $sql .= " registered=0, ";
            $sql .= " date_registered='', ";
            $sql .= " date_lastaccess='' ";
            $sql .= " WHERE member_id='$member_id'";
            $result = mysql_query($sql,$db);
            if ($result && mysql_affected_rows()==1)
              {
                 $messageFile = MESSAGE_DIRECTORY."/general/not_confirmed_0.txt";

                 $vars=array();
                 $fullname     = $firstname." ".$lastname;
                 $vars["firstname"]  = $firstname;
                 $vars["email"]      = $email;
                 $vars["mid"]        = $member_id;

                 sendMessageFile($messageFile, $fullname, $email, $vars);

                $processed=TRUE;
              }
            else
              {
                $errormessage="Error: ".mysql_errno()." Msg: ".mysql_error();
              }
          }
        else
          {
            $errormessage="Error: ".mysql_errno()." Msg: ".mysql_error();
          }
     }

   if ($processed)
     {

       if (IS_LOCAL || is_integer(strpos($email,"webtribune.com")))
         {
            /**** ----  *****/

            $toName="$firstname $lastname";
            $toEmail=$email;
            if (endsWith($toEmail,"@webtribune.com"))
               $toEmail="tim@webtribune.com";

            $subject="Your PushyAds.com Registration Must Be Confirmed";
            $message  = "Hi $firstname,\n\n";
            $message .= "Please Confirm your Pushy Ads.com registration by clicking the link below:\n\n";
            $message .= "  ".DOMAIN."/awbr_confirmed.php?mid=$member_id\n \n";

            send_mail_direct($toName, $toEmail, "PushyAds.com", EMAIL_NOREPLY, $subject, $message);

            /**** ----  *****/
         }

       if ($isAjax)
         {
            $response= new stdClass();
            $response->success   = "TRUE";
            $response->mid       = $member_id;
            $response->refid     = $refid;
            $response->signin_id = "$email";
            $response->url       = DOMAIN."/awbr_thankyou.php?mid=$member_id";

            sendJSONResponse(0, $response, null);
            exit;
         }


       $newLocation = DOMAIN."/awbr_thankyou.php?mid=$member_id";
       $locationHeader = "Location: ".$newLocation;
       header ($locationHeader);  // Redirect browser
       exit;

     }
 }
else
 {
   $errormessage="Error: ".mysql_errno()." Msg: ".mysql_error();
 }


if ($isAjax)
 {
   $response=new stdClass();
   $response->success   = "FALSE";
   $response->signin_id = "$email";
   $response->message   = "$errormessage;";

   $statusCode=201;
   if (strlen($ALREADY_REGISTERED) > 0)
     $statusCode=211;

   sendJSONResponse($statusCode, $response, $loginerrmsg);
   exit;
 }

echo "FAILED: $errormessage<br>";

?>
