<?php
 $FILE_INFO=getFileInfo(__FILE__);

// PUSHYSIGNIN=tim@webtribune.com
// PAREF=1201-x1rr

$email = strtolower($_REQUEST["email"]);
$mid   = "";
if (is_integer(strpos($email,"@")) && is_array($memberRecord=getMemberInfoForEmail($db,$email)))
 {
   $mid=$memberRecord["member_id"];
   $email = $memberRecord["email"];
 }
else
 {
   $PUSHYSIGNIN = $_REQUEST["PUSHYSIGNIN"];
   if (is_integer(strpos($PUSHYSIGNIN,"@")) && is_array($memberRecord=getMemberInfoForEmail($db,$PUSHYSIGNIN)))
    {
      $mid   = $memberRecord["member_id"];
      $email = $memberRecord["email"];
    }
 }

$firstname  = ucfirst_only($_REQUEST["firstname"]);
$lastname   = ucfirst_only($_REQUEST["lastname"]);
$fullname   = $firstname." ".$lastname;
$subject    = $_REQUEST["subject"];
$message    = $_REQUEST["message"];
$service_id = $_REQUEST["service_id"];

include("../../admin/users.php");

                                /** Check for Duplicate Submission **/
// $tm = time() - 120;
//
// $sql  = "SELECT * from service";
// $sql .= " WHERE (member_id='$mid' OR email='$email')";
// $sql .= " AND ts_request>$tm order by ts_request DESC";
// $result=exec_query($sql,$db);
// // printf("SQL: %s<br>\n",$sql);
// // printf("ERR: %s<br>\n",mysql_error());
// if (($result) && ($myrow = mysql_fetch_array($result)))
//   {
//     $t1_subject=urldecode($myrow["subject"]);
//     $t1_subject=stripchr($t1_subject,"\r");
//     $t1_subject=stripchr($t1_subject,"\n");
//     $t1_subject=stripchr($t1_subject,"\\");
//
//     $t2_subject=urldecode($in_subject);
//     $t2_subject=stripchr($t2_subject,"\r");
//     $t2_subject=stripchr($t2_subject,"\n");
//     $t2_subject=stripchr($t2_subject,"\\");
//
//     $t1_request=urldecode($myrow["request"]);
//     $t1_request=stripchr($t1_request,"\r");
//     $t1_request=stripchr($t1_request,"\n");
//     $t1_request=stripchr($t1_request,"\\");
//
//     $t2_request=urldecode($in_message);
//     $t2_request=stripchr($t2_request,"\r");
//     $t2_request=stripchr($t2_request,"\n");
//     $t2_request=stripchr($t2_request,"\\");
//
//     if ($t1_subject == $t2_subject &&
//         $t1_request == $t2_request)
//       {
//         $service_id = $myrow["service_id"];
//       }
//   }
// else
//   {


         $followup_member = "";
         if (isset($service_id) && strlen($service_id) > 10)
           {
             $service_id = strtoupper($service_id);
             if (substr($service_id,0,1) == "[")
               $service_id = substr($service_id,1);

             $sidlen=strlen($service_id);
             if (substr($service_id,$sidlen-1,1) == "]")
               $service_id = substr($service_id,0,$sidlen-1);

             $sql  = "SELECT * from service";
             $sql .= " WHERE service_id='$service_id'";
             $sql .= " ORDER BY seq DESC";
             $result=exec_query($sql,$db);

             // printf("SQL: %s<br>\n",$sql);
             // printf("ERR: %s<br>\n",mysql_error());

             if (($result) && ($myrow = mysql_fetch_array($result)))
               {

                 // printf("FOUND IT: %s<br>\n",mysql_error());

                 $exists=TRUE;
                 $service_id     = $myrow["service_id"];
                 $seq            = $myrow["seq"]+1;
                 $assignee       = $myrow["assignee"];
                 $responder      = $myrow["responder"];

                 if (strlen($responder) > 0 && isset($users[$responder]))
                   {
                     $followup_member = $responder;
                     $followup_member_email = $users[$followup_member];
                   }
                 else
                 if (strlen($assignee) > 0 && isset($users[$assignee]))
                   {
                     $followup_member = $assignee;
                     $followup_member_email = $users[$followup_member];
                   }
               }

           }
         else
           {
             $service_id = newServiceID();
             $seq=1;
           }

         //  service_id      varchar(20)  DEFAULT ''  NOT NULL,
         //  seq             int(11)      DEFAULT '0',
         //  date_received   varchar(10)  DEFAULT ''  NOT NULL,
         //  ts_request      int(11)      DEFAULT '0',
         //  ts_response     int(11)      DEFAULT '0',
         //  member_id       varchar(15)  DEFAULT ''  NOT NULL,
         //  firstname       varchar(30)  DEFAULT ''  NOT NULL,
         //  lastname        varchar(30)  DEFAULT ''  NOT NULL,
         //  email           varchar(45)  DEFAULT ''  NOT NULL,
         //  subject         varchar(80)  DEFAULT ''  NOT NULL,
         //  request         text         DEFAULT ''  NOT NULL,
         //  responder       varchar(16)  DEFAULT ''  NOT NULL,
         //  response        text         DEFAULT ''  NOT NULL,

         $dt=getDateToday();
         $tm=time();
         $sql  = "INSERT into service set";
         $sql .= " service_id='$service_id',";
         $sql .= " seq=$seq,";
         $sql .= " date_received='$dt',";
         $sql .= " ts_request=$tm,";
         $sql .= " ts_response=0,";
         if (strlen($mid) == 0)
           $sql .= " member_id='NON-MEMBER',";
         else
           $sql .= " member_id='$mid',";
         $sql .= " firstname='".addslashes(stripslashes($firstname))."',";
         $sql .= " lastname='".addslashes(stripslashes($lastname))."',";
         $sql .= " email='$email',";
         $sql .= " subject='".addslashes(stripslashes($subject))."',";
         $sql .= " request='".addslashes(stripslashes($message))."',";
         $sql .= " assignee='$followup_member',";
         $sql .= " responder='',";
         $sql .= " response=''";
         $result=exec_query($sql,$db);

         $custname=$firstname." ".$lastname;

         $fromEmail=EMAIL_NOREPLY;
         $fromName="Pushyads.com";

         // printf("SQL: %s<br>\n",$sql);
         // printf("ERR: %s<br>\n",mysql_error());

         $msg  = "";

         $msg .= "Hi $firstname,\n";
         $msg .= "\n";
         $msg .= "Thank you for contacting PushyAds Support.\n\n";
         $msg .= "The following service request has been opened:\n";
         $msg .= "\n";
         $msg .= "SERVICE ID: $service_id\n";
         $msg .= "DATE      : ".formatDate($tm)."\n";
         $msg .= "TIME      : ".formatTime($tm)."\n";
         $msg .= "SUBJECT   : ".stripslashes($subject)."\n";
         $msg .= "\n";
         $msg .= "As always, we make every attempt to respond to all customer questions\n";
         $msg .= "or requests within one business day.  Requests received on weekends\n";
         $msg .= "will be answered on the following business day.\n";
         $msg .= "\n";
         $msg .= "The response will come to you at: ".$email."\n";
         $msg .= "\n";
         $msg .= "The request you submitted is as follows:\n";
         $msg .= "-------------------------------------------------------------\n";
         $msg .= stripslashes($message);
         $msg .= "\n------------------------------------------------------------\n";
         $msg .= "\n";
         $msg .= "We'll be getting back to you shortly $firstname.\n";
         $msg .= "\n";
         $msg .= "Thank you,\n";
         $msg .= "PushyAds.com Support Team\n\n";

         $subject="Your Support Ticket Has Been Received: $service_id";

         send_mail_direct($custname, $email, $fromName, $fromEmail, $subject, $msg);

         if (($exists) && (strlen($followup_member)>0))
           {
              $fmember = strtoupper(substr($followup_member,0,1)).substr($followup_member,1);
              $subject = "$fmember, A Response or Followup Request was Received for Service ID: $service_id";

              $msg  = "A Response or Followup Request was Received for Service ID: $service_id\n";
              $msg .= "This Service Request was assigned to or responded to by you, ".$fmember."\n\n";
              $msg .= "Date-Time: ".getDateTime()."\n";
              $msg .= "Customer : $custname\n\n";
              $msg .= "Please Handle As Soon As Possible  -  To Handle Now, Click Link Below\n";
              $msg .= "\n";
              $msg .= "http://".$HTTP_HOST."/admin/service/index.php?op=Exp&WB=$WB&WE=$WE&sid=$service_id";
              $msg .= "\n";

              send_mail_direct("Service Request", EMAIL_TEAM, $fromName, $fromEmail, $subject, $msg);
           }



//   }

?>

           <table width=680 align=center cellpadding=0 cellspacing=15 bgcolor="#FFEECC" class=bgborder>
             <tr valign=middle >
               <td align=center valign=top>

                 <table align=center width=100% cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 0px;">
                   <tr valign=bottom height=70>
                     <td colspan=3 style="border:0px; background-color:#FFC757;">

                       <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                         <tr height=70 bgcolor="#FFFFFF">
                           <td width=20% align=center valign=top style="padding-top: 25px;">
                             <img src="http://pds1106.s3.amazonaws.com/images/received.png" alt="Received Pushy Support Ticket">
                           </td>

                           <td width=80% colspan=2 class=largetext style="padding: 25px 10px 0 0;">
                             <span class="size20 green"><b>Got your message <?php echo $firstname?>!</b></span>


                             <?php
                                if (!$exists)
                                 {
                             ?>

                             <p class=text style="margin-right: 20px;">Your support ticket was assigned this tracking#: <b><?php echo $service_id?></b></p>

                             <p class=text style="margin-right: 20px;">We will send a response to your email address at:

                             <p class=text style="margin: 0 20px 0 20px;"><b><?php echo $email?></b></p>

                             <p class=text style="margin-right: 20px;">Please check for an email from us shortly to confirm our receipt of your support ticket.
                             If you can't find the email in your IN box, it could have been filtered into your "bulk" or "spam" folder, in which case you need to follow
                             the instructions below.

                             <p class=text style="margin-right: 20px;"><span class=green><b>IMPORTANT:</b></span>
                             Make sure you add <span class=green><b>noreply&nbsp;@&nbsp;pushyads.com</b></span> on your
                             Safe List so our emails to you don't get blocked by your email software. Here are some
                             <a href="javascript:openPopup('/popup.php?tp=pop-safelist',670,450,true)">
                             instructions</a> on how do to this.</p>

                             <p class=text style="margin: 0 20px 30px 0;">Please Give us up to 24-48 hours to respond. Normaly, we'll respond sooner.</p>

                             <?php
                                 }
                             ?>

                           </td>
                         </tr>
                       </table>

                     </td>
                   </tr>
                 </table>

               </td>
             </tr>
           </table>
             <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=670 height=31></div>
