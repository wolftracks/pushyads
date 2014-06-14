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

 //-- Target -----------------------------------------------------------
 //
 // AllMembers
 // Aff-Enabled
 // Aff-Pending
 //
 // ----------------------- Not Implemented
 // Campaign
 // AutoShip
 // ActiveParticipants
 //-----------------------------------------------------------------

 // sleep(2);

 if ($target == "AllMembers"  ||
     $target == "Aff-Enabled" ||
     $target == "Aff-Pending")
   {
           // ADD AS A SHARERED MAIL RESOURCE" To Mailbox
           // Do this first - against Service Database - and Before Attaching Remove/Reply info

       $db = getServiceDatabaseConnection();

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

       if ($target == "AllMembers")
         {
           $sql .= " member_id='@PUBLIC@',";
           $sql .= " firstname='ALL',";
           $sql .= " lastname='MEMBERS',";
           $sql .= " request=' -- To PushyAds Members --',";
         }
       else
       if ($target == "Aff-Enabled")
         {
           $sql .= " member_id='@AFFENABLED@',";
           $sql .= " firstname='AFFILIATES',";
           $sql .= " lastname='ENABLED',";
           $sql .= " request=' -- To PushyAds Affiliates --',";
         }
       else
       if ($target == "Aff-Pending")
         {
           $sql .= " member_id='@AFFPENDING@',";
           $sql .= " firstname='AFFILIATES',";
           $sql .= " lastname='PENDING',";
           $sql .= " request=' -- To Affiliate Applicants --',";
         }

       $sql .= " email='',";
       $sql .= " subject='".addslashes(stripslashes($subject))."',";
       $sql .= " assignee='',";
       $sql .= " responder='',";
       $sql .= " response='".addslashes(stripslashes($message))."'";
       $result=exec_query($sql,$db);
   }



 $db = getPushyDatabaseConnection();

 if ($target == "AllMembers"  ||
     $target == "Aff-Enabled" ||
     $target == "Aff-Pending")
   {
     if ($target == "AllMembers")
       {
         $message    .= $replyText;
         $message    .= $removeText;

         $sql  = "SELECT member_id,firstname,lastname,email,signin_id,password,aff_status,affiliate_id,aff_website_domain from member";
         $sql .= " WHERE email_disabled=0";
       }
     else
     if ($target == "Aff-Enabled")
       {
         $message    .= $replyText;

         $sql  = "SELECT member_id,firstname,lastname,email,signin_id,password,aff_status,affiliate_id,aff_website_domain from member";
         $sql .= " WHERE aff_status=$AFFILIATE_STATUS_ENABLED";
         // $sql .= " AND   email_disabled=0";    --- We don't need to Honor the UNSUBSCRIBE Notion for Affiliates
       }
     else
     if ($target == "Aff-Pending")
       {
         $message    .= $replyText;

         $sql  = "SELECT member_id,firstname,lastname,email,signin_id,password,aff_status,affiliate_id,aff_website_domain from member";
         $sql .= " WHERE aff_status=$AFFILIATE_STATUS_PENDING";
         // $sql .= " AND   email_disabled=0";    --- We don't need to Honor the UNSUBSCRIBE Notion for Affiliates
       }
     $sql .= " ORDER by email";
     $result = exec_query($sql,$db);
     $total=0;
     $count=0;

     $tm1=time();  // START TIME

     $last_email="";
     if (($result) && mysql_num_rows($result) > 0)
       {
         unset($recipientList);
         $recipientList = array();

//       for ($z=0; $z<3200; $z++)
//         {
//           $myrow["firstname"]  = genRandomString(6,16);
//           $myrow["lastname"]   = genRandomString(6,16);
//           $myrow["email"]      = genRandomString(6,12)."_".$z."@".genRandomString(7,15).".com";
//           $myrow["signin_id"]  = genRandomString(7,18)."_".$z;
//           $myrow["password"]   = genRandomString(7,15)."_".$z;
//           $myrow["aff_status"] = 2;
//           $myrow["affiliate_id"] = genRandomString(7,22)."_".$z;
//           $myrow["aff_website_domain"] = "apr51.com";


         while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
           {
             $email              = strtolower($myrow["email"]);
             if ($email == $last_email)
                continue;

             $firstname          = stripslashes($myrow["firstname"]);
             $lastname           = stripslashes($myrow["lastname"]);
             $signin_id          = $myrow["signin_id"];
             $member_id          = $myrow["member_id"];
             $password           = $myrow["password"];
             $aff_status         = $myrow["aff_status"];

             $affiliate_id       = "";
             $aff_website_domain = "";
             $affiliate_website  = "";

             if ($target == "Aff-Enabled" && $aff_status == $AFFILIATE_STATUS_ENABLED)
               {
                 $affiliate_id       = $myrow["affiliate_id"];
                 $aff_website_domain = $myrow["aff_website_domain"];
                 $affiliate_website  = "";
                 if (strlen($affiliate_id) >= 6 && strlen($aff_website_domain) >= 5 && is_integer(strpos($aff_website_domain,".")))
                   {
                     $affiliate_website = "http://".$affiliate_id.".".$aff_website_domain;
                   }
               }

             $recipient = array("email"             => $email,
                                "firstname"         => $firstname,
                                "lastname"          => $lastname,
                                "uid"               => $signin_id,
                                "m_i_d"             => $member_id,    // private use - removal
                                "affiliate_id"      => $affiliate_id,
                                "affiliate_website" => $affiliate_website
                               );

             $recipientList[$count]=$recipient;
             $total++;
             $count++;
             if (($total % $BULK_RECIPIENTS) == 0)
               {
                 bulkSend($fromName, $fromEmail, $subject, $message, $recipientList);
                 // v_printf("BULKSEND: recipients=%d\n",$count);

                 $count=0;
                 unset($recipientList);
                 $recipientList = array();
               }
             // v_printf("%s\n",print_r($recipient,TRUE));
           }
       }

     if ($count > 0)
       {
         $res=bulkSend($fromName, $fromEmail, $subject, $message, $recipientList);
         // v_printf("BULKSEND: recipients=%d\n",$count);
       }


     $tm2=time();  // END TIME
     $elapsed = $tm2-$tm1;  // ELAPSED SECONDS

     // v_printf("--TOTAL--  BULKSEND: recipients=%d  ELAPSED SECONDS=%d\n",$total,$elapsed);

     if ($total == 0)
       {
         $RESPONSE["message"]="No Recipients in the Specified Target:&nbsp;&nbsp; '$target'<br> <br>No Mail Sent";
       }
     else
       {
         $RESPONSE["message"]="Mail Has Been Queued to $total Recipients in Target:&nbsp;&nbsp; '$target'<br> <br>Elapsed Time = $elapsed seconds";
       }

     sendJSONResponse($RESPONSE["result"], NULL, $RESPONSE["message"]);
     exit;
   }


 sendJSONResponse(101, NULL, "Error: Invalid Target ($target)");
 exit;
?>
