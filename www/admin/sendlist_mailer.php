<?php
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_sendmail.inc");
 include_once("pushy_jsontools.inc");

 $BULK_RECIPIENTS = 2000;   // Recipients per BULK MAIL SEND  (Tested 2500)

 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

 $target   =$_REQUEST["target"];
 $fromName =$_REQUEST["fromName"];
 $fromEmail=$_REQUEST["fromEmail"];
 $subject  =$_REQUEST["subject"];
 $message  =$_REQUEST["message"];

 if (strlen($subject) < 5)
   {
     sendJSONResponse(112, NULL, "Error: NO SUBJECT");
     exit;
   }
 if (strlen($message) < 5)
   {
     sendJSONResponse(114, NULL, "Error: NO MESSAGE");
     exit;
   }


function sendVars($toName, $toEmail, $fromName, $fromEmail, $subject, $message, $vars)
  {
    if (!is_array($vars))
      $vars=array();

        // Supply Standard System Variables if not Provided
    if (!isset($vars["%date%"]))   $vars["%date%"] = getDateToday();
    if (!isset($vars["%time%"]))   $vars["%time%"] = getTimeNow()." MST";

    if (is_array($vars))
      {
        foreach($vars as $key=>$data)
         {
           if (!is_integer(strpos($key,"%")))
              $key="%".$key."%";
           $subject = str_replace($key, $data, $subject);
           $message = str_replace($key, $data, $message);
         }
      }

    $result = send_mail_direct($toName, $toEmail, $fromName, $fromEmail, $subject, $message);

    return($result);
  }


   $replyText   = "";
// $replyText   = "\n\n\n";
// $replyText  .= "---------------------------------------------------------------------------------------\n";
// $replyText  .= "~ ~ ~ ~ ~   TO RESPOND TO THIS EMAIL - DO NOT HIT REPLY ~ ~ ~ ~  ~          \n";
// $replyText  .= "Instead, go to http://$HTTP_HOST/members/service.php?m=%m_i_d%\n";
// $replyText  .= "---------------------------------------------------------------------------------------\n\n";
// $replyText  .= "\n";

   $removeText  = "";
// $removeText  = "---- If you no longer wish to continue your PushyAds Membership, click below ----\n";
// $removeText .= "http://$HTTP_HOST/members/rm.php?m=%m_i_d%\n";
// $removeText .= "---------------------------------------------------------------------------------------\n\n";
// $removeText .= "\n";



 set_time_limit(0);

 $db = getPushyDatabaseConnection();

 $service_id = newServiceID();
 $seq=1;

 $dt=getDateToday();
 $tm=time();
 $sql  = "INSERT into service set";
 $sql .= " service_id='$service_id',";
 $sql .= " seq=$seq,";
 $sql .= " date_received='$dt',";
 $sql .= " ts_request=$tm,";
 $sql .= " ts_response=$tm,";

 $sql .= " member_id='@PUBLIC@',";
 $sql .= " firstname='ALL',";
 $sql .= " lastname='MEMBERS',";
 $sql .= " request=' -- To PushyAds Members --',";

 $sql .= " email='',";
 $sql .= " subject='".addslashes(stripslashes($subject))."',";
 $sql .= " assignee='',";
 $sql .= " responder='',";
 $sql .= " response='".addslashes(stripslashes($message))."'";
 $result=exec_query($sql,$db);

 $message    .= $replyText;
 $message    .= $removeText;

 $sql  = "SELECT member_id,firstname,lastname,email,password,affiliate_id from member";
 $sql .= " WHERE email_disabled=0 AND registered>0";
 $sql .= " ORDER by email";
 $result = exec_query($sql,$db);
 $total=0;

 $tm1=time();  // START TIME

 $last_email="";
 if (($result) && mysql_num_rows($result) > 0)
   {
     while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
       {
         $email              = strtolower($myrow["email"]);
         if ($email == $last_email)
            continue;

         $firstname          = stripslashes($myrow["firstname"]);
         $lastname           = stripslashes($myrow["lastname"]);
         $signin_id          = $email;
         $member_id          = $myrow["member_id"];
         $password           = $myrow["password"];

         $affiliate_id       = $myrow["affiliate_id"];
         $affiliate_website  = DOMAIN."/".$affiliate_id;

         $toName  = $firstname." ".$lastname;
         $toEmail = $email;

         $vars = array("email"             => $email,
                       "firstname"         => $firstname,
                       "lastname"          => $lastname,
                       "uid"               => $signin_id,
                       "m_i_d"             => $member_id,    // private use - removal
                       "password"          => $password,
                       "affiliate_id"      => $affiliate_id,
                       "affiliate_website" => $affiliate_website
                      );

         // send_mail_direct($toName, $toEmail, $fromName, $fromEmail, $subject, $message, $vars);
         // sendVars($toName, $toEmail, $fromName, EMAIL_NOREPLY, $subject, $message."\n".print_r($vars,TRUE), $vars);
         sendVars($toName, $toEmail, $fromName, EMAIL_NOREPLY, $subject, $message, $vars);

         $total++;
       }
   }

 $subject="Mail Message Sent: Count=$total\n";
 $message=$subject;

 send_mail_direct("Mail Sent", EMAIL_TEAM, "PushyAds.com", EMAIL_NOREPLY, $subject, $message);

 $tm2=time();  // END TIME
 $elapsed = $tm2-$tm1;  // ELAPSED SECONDS

 if ($total == 0)
   {
     $RESPONSE["message"]="No Recipients in the Specified Target<br> <br>No Mail Sent";
   }
 else
   {
     $RESPONSE["message"]="Mail Has Been Sent to $total Recipients<br> <br>Elapsed Time = $elapsed seconds";
   }

 sendJSONResponse($RESPONSE["result"], NULL, $RESPONSE["message"]);
 exit;
?>
