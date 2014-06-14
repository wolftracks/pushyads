<?php
include("initialize.php");
include_once("pushy_jsontools.inc");

if (isset($_REQUEST["signin_id"]))
 {
   $useremail = strtolower($_REQUEST["signin_id"]);

   $db=getPushyDatabaseConnection();

   $sql  = "SELECT * FROM member";
   $sql .= " WHERE email='$useremail'";
   $result = mysql_query($sql,$db);
   if ($result && ($memberRecord = mysql_fetch_array($result)))
     {
       $mid       =$memberRecord["member_id"];
       $confirmed =$memberRecord["confirmed"];
       $registered=$memberRecord["registered"];
       if ($confirmed == 0)
         {
            $response=new stdClass();
            $response->success   = "FALSE";
            $response->signin_id = "$useremail";
            $msg  = "You have not clicked the link in the email\n";
            $msg .= "that was sent to you, so a password has not\n";
            $msg .= "been created for you.\n\n";
            $msg .= "Please Click the confirmation link in the\n";
            $msg .= "email that was sent to you and you will then be\n";
            $msg .= "able to create yourself a password and sign in.\n\n";
            $response->message   = $msg;
            sendJSONResponse(202, $response, $msg);
            exit;
         }
       if ($registered == 0)
         {
            $response=new stdClass();
            $response->success   = "FALSE";
            $response->signin_id = "$useremail";
            $msg  = "You confirmed the email we sent you, but you\n";
            $msg .= "have not yet create your password.\n\n";
            $msg .= "To create a password and sign in to your\n";
            $msg .= "back office, follow the link below: \n\n";
            $msg .= "http://pushyads.com/confirmed.php?mid=$mid\n\n";
            $response->message   = $msg;
            sendJSONResponse(203, $response, $msg);
            exit;
         }

       $firstname = stripslashes($memberRecord["firstname"]);
       $lastname  = stripslashes($memberRecord["lastname"]);
       $password  = $memberRecord["password"];

       $toName="$firstname $lastname";
       $toEmail=$useremail;
       if (endsWith($toEmail,"@webtribune.com"))
          $toEmail="tim@webtribune.com";

       $subject="Password Request for PushyAds.com\n";
       $message  = "Hi $firstname,\n\n";
       $message .= "You have requested your password for PushyAds.com.\n\n";
       $message .= "Please keep your password in a safe place so we can\n";
       $message .= "ensure our site integrity while helping you protect\n";
       $message .= "your privacy:\n\n";
       $message .= "  Your Password: $password \n";
       $message .= "\n";

       if (substr($memberRecord["password"],0,2)=="p-")
         {
           $message .= "Your current password is a System-Assigned password.\n";
           $message .= "After signing in, you can change your password to \n";
           $message .= "something you will remember inside your Profile.\n";
           $message .= "\n";
         }

       $message .= "PushyAds.com Site Management\n";

       send_mail_direct($toName, $toEmail, "PushyAds.com", EMAIL_NOREPLY, $subject, $message);


       $response= new stdClass();
       $response->success   = "TRUE";
       $response->signin_id = "$email";
       sendJSONResponse(0, $response, null);
       exit;
     }
   else
     {
        $loginerrmsg="Email address Invalid - Please Re-Enter";
     }
 }
else
 {
   $loginerrmsg="Email address Required";
 }

$response=new stdClass();
$response->success   = "FALSE";
$response->signin_id = "$useremail";
$response->message   = "$loginerrmsg";
sendJSONResponse(201, $response, $loginerrmsg);
exit;
?>
