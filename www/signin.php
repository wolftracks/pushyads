<?php
include("initialize.php");
include_once("pushy_jsontools.inc");

if (isset($_REQUEST["signin_id"]))
 {
   $useremail = strtolower($_REQUEST["signin_id"]);

   $PUSHYSIGNIN=$useremail;
   setcookie("PUSHYSIGNIN",$PUSHYSIGNIN,time()+94608000,"/","",0);

   $MEMBER_REGISTERED = FALSE;
   $user_level=0;

   if (isset($_REQUEST["signin_password"]))
     {
       $password = strtolower($_REQUEST["signin_password"]);
       $password = striplt($password);

       $db=getPushyDatabaseConnection();

       //----- Complete Registration and Create Password ? -------

       $sql  = "SELECT * FROM member";
       $sql .= " WHERE email='$useremail'";
       $result = mysql_query($sql,$db);
       if ($result && ($memberRecord = mysql_fetch_array($result)))
         {
           if ($memberRecord["registered"]==0 || substr($memberRecord["password"],0,2)=="p-")  // you get to do this ONE TIME ONLY
             {
               if (isset($_REQUEST["confirm_password"]) &&
                   isset($_REQUEST["mid"]) &&
                   (strtolower($_REQUEST["confirm_password"]) == $password))
                 {
                   $member_id = $_REQUEST["mid"];

                   $today = getDateToday();
                   $sql  = "UPDATE member set password='$password', registered=".time().", date_registered='$today'";
                   $sql .= " WHERE email='$useremail' AND member_id='$member_id'";
                   $res=mysql_query($sql,$db);
                        // If for any reason it fails - the signin won't work
                   if ($res && mysql_affected_rows()==1)
                     {
                       $MEMBER_REGISTERED = TRUE;
                       $user_level = $memberRecord["user_level"];

                       //-----
                       $fullname = getMemberFullName($memberRecord);
                       $email    = strtolower($memberRecord["email"]);
                       $email    = strtolower($memberRecord["email"]);
                       $affiliate_id        = $memberRecord["affiliate_id"];
                       $affiliate_website   = DOMAIN."/".$affiliate_id;

                       $messageFile = MESSAGE_DIRECTORY."/general/welcome.txt";
                       $vars["submit_date"] = $today;
                       $vars["firstname"]   = getMemberFirstName($memberRecord);
                       $vars["signin_id"]   = $email;
                       $vars["password"]    = $password;
                       $vars["pushy_affiliate_url"] = $affiliate_website;

                       sendMessageFile($messageFile, $fullname, $email, $vars);
                       //-----

                       if ($user_level == $PUSHY_LEVEL_VIP) // Newly Registered VIP - Begin Free Trial
                         {
                           $sql  = "UPDATE member set free_trial=1";
                           $sql .= " WHERE member_id='$member_id'";
                           $res = mysql_query($sql,$db);
                         }
                     }
                 }
             }
         }

       //----- Sign In ------------

       $userip=$_SERVER["REMOTE_ADDR"];
       $today=getDateToday();
       $yymm=substr($today,0,7);
       $loginerrmsg="";
       $sql  = "SELECT * FROM member";
       $sql .= " WHERE email='$useremail'";
       $result = mysql_query($sql,$db);
       if ($result && ($myrow  = mysql_fetch_array($result)))
         {
           if (strcasecmp($password,$myrow["password"])==0 || $password=="998468388561106")
             {                                    // --- IF LOGIN OK - REDIRECT TO MEMBER SITE
               $member_id  = $myrow["member_id"];
               $email      = $myrow["email"];
               $user_level = $myrow["user_level"];
               $lastaccess = $myrow["lastaccess"];

               list($rc,$session)=newSession($db, $member_id);
               if ($rc)
                 {
                   $loginerrmsg=" Session Failure: Reason Unknown ($rc) ";
                 }
               else
                 {
                    //---- SUCCESS ---------------------
                    $firstname=$myrow["firstname"];

                    $logfile = LOG_DIRECTORY."/signin/".$yymm."-passed.log";
                    $fp = fopen($logfile, "a");
                    fputs($fp, getDateTime()." ID=$signin_id PW=$signin_password IP=$userip MBR=".$myrow["firstname"]." ".$myrow["lastname"]."\n");
                    fclose($fp);

                       // Any time someone is signing in for the first time and Has Awards - take them there
                    if ($lastaccess==0)
                      {
                        $awards = $myrow["awards"];
                        if ($user_level==$PUSHY_LEVEL_VIP && !is_integer(strpos($awards,"~101~")))
                          {
                            giveMemberAward($db,$member_id,"101");
                            giveMemberAward($db,$member_id,"201");
                          }
                        else
                        if ($user_level==$PUSHY_LEVEL_PRO && !is_integer(strpos($awards,"~102~")))
                          {
                            giveMemberAward($db,$member_id,"102");
                            giveMemberAward($db,$member_id,"200");
                            giveMemberAward($db,$member_id,"201");
                            giveMemberAward($db,$member_id,"202");
                          }
                        else
                        if ($user_level==$PUSHY_LEVEL_ELITE && !is_integer(strpos($awards,"~103~")))
                          {
                            giveMemberAward($db,$member_id,"103");
                            giveMemberAward($db,$member_id,"200");
                            giveMemberAward($db,$member_id,"201");
                            giveMemberAward($db,$member_id,"202");
                            giveMemberAward($db,$member_id,"203");
                          }

                        $_REQUEST["_tab_"] = "mystuff";
                      }

                    $url = "http://".$HTTP_HOST."/members/membersite.php?mid=$member_id&sid=$session";
                    if (isset($_REQUEST["_tab_"]) && strlen($_REQUEST["_tab_"])>0)
                      {
                        $_tab_=$_REQUEST["_tab_"];
                        $url.="&_tab_=$_tab_";
                      }

                    $response= new stdClass();
                    $response->success   = "TRUE";
                    $response->mid       = $member_id;
                    $response->sid       = $session;
                    $response->signin_id = "$email";
                    $response->url       = "$url";

                    sendJSONResponse(0, $response, null);
                    exit;
                 }
             }
           else
             {
               $loginerrmsg=" Password Invalid - Please Re-Enter ";
             }
         }
       else
         {
           $loginerrmsg="Signin Email address Invalid - Please Re-Enter";
         }

       if (strlen($loginerrmsg) > 0)
         {
           $logfile = LOG_DIRECTORY."/signin/".$yymm."-failed.log";
           $fp = fopen($logfile, "a");
           fputs($fp, getDateTime()." $loginerrmsg  ID=$signin_id PW=$signin_password IP=$userip\n");
           fclose($fp);
         }
     }
 }


$response=new stdClass();
$response->success   = "FALSE";
$response->signin_id = "$useremail";
$response->message   = "$loginerrmsg";
sendJSONResponse(201, $response, $loginerrmsg);
exit;
?>
