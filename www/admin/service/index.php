<?php
   include_once("pushy_constants.inc");
   include_once("pushy_common.inc");
   include_once("pushy_commonsql.inc");
   include_once("pushy_sendmail.inc");
   include_once("../users.php");

   // dump_var($_REQUEST);

   $TRACE   = FALSE;
   $INCLUDE = 1;
   $EXCLUDE = 0;

// if (is_array($_COOKIE))
//   {
//     while (list($key, $value) = each($_COOKIE))
//       {
//         printf("A) %s=%s<br>\n",$key,$value);
//       }
//   }
//
// if (is_array($GLOBALS))
//   {
//     while (list($key, $value) = each($GLOBALS))
//       {
//         printf("B) %s=%s<br>\n",$key,$value);
//       }
//   }
//
//
// if (is_array($_SERVER))
//   {
//     while (list($key, $value) = each($_SERVER))
//       {
//         printf("C) %s=%s<br>\n",$key,$value);
//       }
//   }

if (!isset($SVCAUTHOR) || strlen($SVCAUTHOR)==0)
  $SVCAUTHOR=$_COOKIE["SVCAUTHOR"];

// printf("x SVCAUTHOR: %s<br>\n",$SVCAUTHOR);
// printf("x SVCAUTHOR[]: %s<br>\n",$_COOKIE["SVCAUTHOR"]);

   $db=GetPushyDatabaseConnection();

   // echo "AUTHOR2: $SVCAUTHOR<br>\n";

   if (strlen($SVCAUTHOR)==0)
     {
       include("signin.php");
       exit;
     }


      //****************--------------- MAIN ---------------------***************
   if ((!isset($op)) || (strlen($op)==0) || ($op=="ListReports"))
     {
       include("msglist.php");
       exit;
     }

   if ($op=="ShowWeek")
     {
       $EXPANDED="";
       include("week.php");
       exit;
     }

   if ($op=="REASSIGN")
     {
       $EXPANDED="";

       $assignee_email="";
       $old_assignee="";
       $old_assignee_email="";

       if ($assignee=="ANY")
         $assignee="";
       if (strlen($assignee) > 0 && isset($users[$assignee]))
         $assignee_email=$users[$assignee];

       $sql  = "SELECT * from service";
       $sql .= " WHERE service_id='$service_id' LIMIT 1";
       $result = exec_query($sql,$db);
        //     printf("SQL: %s<br>\n",$sql);
        //     printf("ERR: %s<br>\n",mysql_error());
       if (($result) && ($myrow = mysql_fetch_array($result)) )
         {
           $custname      = stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"]);
           $old_assignee  = $myrow["assignee"];
           if (strlen($old_assignee) > 0 && isset($users[$old_assignee]))
             $old_assignee_email = $myrow["assignee"];
         }

       $sql  = "UPDATE service set assignee='$assignee'";
       $sql .= " WHERE service_id='$service_id'";
       $result = exec_query($sql,$db);
       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());
       $count=mysql_affected_rows();
       // printf("COUNT: %d<br>\n",$count);
       if ($count > 0)
         {
           if (strtolower($assignee) != strtolower($old_assignee))
             {
               if (strlen($assignee_email) > 0)
                 {
                    $subject = "You Have Been Assigned Service Request: $service_id by $SVCAUTHOR";

                    $msg  = "You Have Been Assigned Service Request: $service_id by $SVCAUTHOR\n\n";
                    $msg .= "Date-Time: ".getDateTime()."\n";
                    $msg .= "Customer : $custname\n\n";
                    $msg .= "Please Handle As Soon As Possible  -  To Handle Now, Click Link Below\n";
                    $msg .= "\n";
                    $msg .= "http://pushyads.com/admin/service/index.php?op=Exp&WB=$WB&WE=$WE&sid=$service_id";
                    $msg .= "\n";
                    send_mail_direct($assignee, $assignee_email, "Customer Service", EMAIL_NOREPLY, $subject, $msg);
                 }
               if (strlen($old_assignee_email) > 0)
                 {
                    $subject = "Service Request: $service_id  has been Re-Assigned to $assignee by $SVCAUTHOR";

                    $msg  = "Service Request: $service_id  has been Re-Assigned to $assignee by $SVCAUTHOR\n\n";
                    $msg .= "Date-Time: ".getDateTime()."\n";
                    $msg .= "Customer : $custname\n\n";
                    $msg .= "\n";

                    send_mail_direct($old_assignee, $old_assignee_email, "Customer Service", EMAIL_NOREPLY, $subject, $msg);
                 }
             }
         }

       include("week.php");
       exit;
     }


   if ($op=="SetAnswered")
     {
       $EXPANDED=$service_id;

       // printf("SVCAUTHOR=%s<br>\n",$SVCAUTHOR);
       if (strlen($SVCAUTHOR) > 0)
         {
           $tm=time();
           $sql  = "UPDATE service set";
           $sql .= " ts_response=$tm,";
           $sql .= " response='--- NO RESPONSE NEEDED / CLOSED --',";
           $sql .= " responder='$SVCAUTHOR'";
           $sql .= " WHERE service_id='$service_id'";
           $sql .= " AND seq=$seq";
           $sql .= " AND ts_response=0";
           $result = exec_query($sql,$db);
           // printf("SQL: %s<br>\n",$sql);
           // printf("ERR: %s<br>\n",mysql_error());
         }

       include("week.php");
       exit;
     }

   if ($op=="Locate")
     {
       $EXPANDED="";
       include("locate.php");
       exit;
     }

   if ($op=="Expand" || $op=="Exp")
     {
       $op="Expand";
       if (isset($sid) && strlen($sid)>0 && strlen($service_id)==0)
         $service_id=$sid;

       if (strlen($WB)==0 || strlen($WE)==0)
         {
           $sql  = "SELECT * FROM service";
           $sql .= " WHERE service_id='$service_id'";
           $result = exec_query($sql,$db);
           // printf("SQL: %s<br>\n",$sql);
           // printf("ERR: %s<br>\n",mysql_error());
           if ( ($result) && ($myrow = mysql_fetch_array($result)) )
             {
               $date_received  = $myrow["date_received"];
               $dateAsArray=dateToArray($date_received);
               $calData=calendar($dateAsArray);
               $dow=$calData["DayOfWeek"];
               if ($dow==0)
                 $dateArray=$dateAsArray;
               else
                 $dateArray=calStepDays(-$dow,$dateAsArray);

               $beginning = $dateArray;
               $ending    = calStepDays(6,$beginning);

               $WB=dateArrayToString($beginning);
               $WE=dateArrayToString($ending);
             }
         }

       $EXPANDED=$service_id;
       include("week.php");
       exit;
     }

   if ($op=="FExpand")
     {
       $EXPANDED=$service_id;
       include("locate.php");
       exit;
     }

   if ($op=="SendList" || $op=="SendMessage")
     {
       $EXPANDED=$service_id;
  //   $EXPANDED="";
       $sql  = "SELECT * FROM service";
       $sql .= " WHERE service_id='$service_id'";
       if ($op == "SendList")
          $sql .= " AND seq=$seq";
       $result = exec_query($sql,$db);
       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());
       if ( ($result) && ($myrow = mysql_fetch_array($result)) )
         {
           $member_id      = $myrow["member_id"];
           $firstname      = stripslashes($myrow["firstname"]);
           $lastname       = stripslashes($myrow["lastname"]);
           $email          = $myrow["email"];
           $subject        = $myrow["subject"];
           $request        = $myrow["request"];
           $custname       = $firstname." ".$lastname;
           $date_received  = $myrow["date_received"];
           $ts_request     = $myrow["ts_request"];
           $ts_response    = $myrow["ts_response"];


               $db = GetPushyDatabaseConnection();

               $sql  = "SELECT * FROM member";
               $sql .= " WHERE member_id ='$member_id'";
               $temp_result = exec_query($sql,$db);
               if ( ($temp_result) && ($temp_row = mysql_fetch_array($temp_result)) )
                 {
                   $signin_id = $temp_row["signin_id"];
                   $password  = $temp_row["password"];
                 }


           $from = "PushyAds Customer Service";
           $to   = "$custname";

           $datetimePosted = formatDateTime($ts_request);

           $subject = striplt($subject);
           $subject = str_replace("Re: ", "", $subject);
           $subject = str_replace("Fwd: ", "", $subject);
           $subject = str_replace("RE: ", "", $subject);
           $subject = str_replace("FWD: ", "", $subject);
           $subject = striplt($subject);
           $subject = str_replace("\\", "", $subject);

           if ($op == "SendList")
             $subject = "Re: ".$subject;

           $author = strtoupper(substr($SVCAUTHOR,0,1)).substr($SVCAUTHOR,1);
           $message = "";
           $message .= "Hi $firstname,\r\n";
           $message .= "\r\n";
           $message .= "\r\n";

           $message .= "\r\n";
           $message .= "\r\n";
           $message .= "Have a great day!\r\n";
           $message .= "\r\n";
           $message .= "Regards,\r\n";
           $message .= "$author\r\n";
           $message .= "PushyAds.com\r\n";
           $message .= "\r\n";
         }



       include("sendlist.php");
       exit;
     }


   if ($op=="SendMail")
     {
      // $EXPANDED=$service_id;
       $EXPANDED="";
       $sql  = "SELECT * FROM service";
       $sql .= " WHERE service_id='$service_id'";
       if (isset($source) && $source == "SendList" && strlen($seq) > 0)
         $sql .= " AND seq=$seq";
       $sql .= " ORDER BY seq DESC";
       $result = exec_query($sql,$db);
       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());
       $newseq=1;
       if ( ($result) && ($myrow = mysql_fetch_array($result)) )
         {
           $member_id      = $myrow["member_id"];
           $firstname      = stripslashes($myrow["firstname"]);
           $lastname       = stripslashes($myrow["lastname"]);

       //  $email          = $myrow["email"];

           $request        = $myrow["request"];
           $custname       = $firstname." ".$lastname;
           $date_received  = $myrow["date_received"];
           $ts_request     = $myrow["ts_request"];
           $ts_response    = $myrow["ts_response"];
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

           if (isset($source) && $source == "SendMessage")
              $newseq      = $myrow["seq"]+1;

           if (isset($in_subject) && strlen($in_subject) > 0)
               $subject    = stripslashes($in_subject);
           else
             {
               $subject = stripslashes($myrow["subject"]);
               $subject = striplt($subject);
               $subject = str_replace("Re: ", "", $subject);
               $subject = str_replace("Fwd: ", "", $subject);
               $subject = str_replace("RE: ", "", $subject);
               $subject = str_replace("FWD: ", "", $subject);
               $subject = striplt($subject);
               $subject = "Re: ".$subject;
             }

           $mheader  = "";
           if (isset($source) && $source == "SendList")
             {
               $mheader .= "In response to your message posted ".formatDateTime($ts_request)."\r\n";
             }

           $mheader .= "\r\n";
           $msg  = $mheader.$message;
           $msg .= "\r\n";
           if (isset($source) && $source == "SendList")
             {
               $msg .= "\r\n";
               $msg .= "--------- Your Message - posted ".formatDateTime($ts_request)." -----------\r\n";
               $msg .= str_replace("\\", "", $request);
             }

           $dt=getDateToday();
           $tm=time();

           if (isset($source) && $source == "SendList")
              {
                $sql  = "UPDATE service set";
                $sql .= " ts_response=$tm,";
                $sql .= " seen=0,";
                $sql .= " response='".addslashes(stripslashes($message))."',";
                $sql .= " responder='$SVCAUTHOR'";
                $sql .= " WHERE service_id='$service_id'";
                $sql .= " AND seq=$seq";
              }
           else
           if (isset($source) && $source == "SendMessage")
              {
                $sql  = "INSERT into service set";
                $sql .= " service_id='$service_id',";
                $sql .= " seq=$newseq,";
                $sql .= " seen=0,";
                $sql .= " date_received='$dt',";
                $sql .= " ts_request=$tm,";
                $sql .= " ts_response=$tm,";
                $sql .= " member_id='$member_id',";
                $sql .= " firstname='".addslashes(stripslashes($firstname))."',";
                $sql .= " lastname='".addslashes(stripslashes($lastname))."',";
                $sql .= " email='$email',";
                $sql .= " subject='".addslashes(stripslashes($subject))."',";
                $sql .= " request='- Unsolicited Customer Email -',";
                $sql .= " assignee='$followup_member',";
                $sql .= " responder='$SVCAUTHOR',";
                $sql .= " response='".addslashes(stripslashes($message))."'";
              }

           $result = exec_query($sql,$db);

           // printf("SQL: %s<br>\n",$sql);
           // printf("ERR: %s<br>\n",mysql_error());

           $fromName  = "PushyAds";
           $fromEmail = EMAIL_NOREPLY;


  if (!$result)
    {
      $temp  = sprintf("SQL: %s\n",$sql);
      $temp .= sprintf("ERR: %s\n",mysql_error());
      send_mail_direct("Service Notice", EMAIL_TIM, $fromName, $fromEmail, "Service Failure", $temp);
    }

           //--------------------------------------------------------------------------------

           // --- BEING SENT FROM PGLEADS.COM ----
           // send_mail_direct($custname, $email, $fromName, $fromEmail, $subject, $msg);

           $replyID    = "Customer Service - ".$SVCAUTHOR;
           $replyEmail = EMAIL_NOREPLY;
           if (isset($users[$SVCAUTHOR]))
             {
               $replyEmail = $users[$SVCAUTHOR];
             }

           $prefix ="Customer Service Response Sent:  ".getDateTime()."\r\n";
           $prefix.="  You may use the Service Tracking ID below if you need to\r\n";
           $prefix.="  submit a new service ticket that references this response.\r\n\r\n";
           $prefix.="  Service Tracking ID: ".$service_id."\r\n";
           $prefix.="  Sent By: ".$SVCAUTHOR."\r\n";
           $prefix.="  Sent To: ".$firstname."\r\n\r\n";

           $msg .= "\r\n";
           $ulist = explode("/", $COPYLIST);
           for ($i=0; $i<count($ulist); $i++)
             {
               $user=$ulist[$i];
               if (strlen($user) > 0 && isset($users[$user]))
                 {
                   $em = $users[$user];
                   send_mail_direct($user, $em, $fromName, $fromEmail, "[BCC:] ".$subject, $prefix.$msg);
                 }
             }


           send_mail_direct("$firstname $lastname", $email, $fromName, $fromEmail, $subject, $msg);
         }

       if (strlen($WB) > 0)
         {
           include("week.php");
         }
       if (strlen($searchterm) > 0)
         {
           include("locate.php");
         }
       exit;
     }



   if ($op=="OpenMessage")
     {
       $EXPANDED=$service_id;
       $sql  = "SELECT * FROM service";
       $sql .= " WHERE service_id='$service_id'";
       $sql .= " AND seq=$seq";
       $result = exec_query($sql,$db);
       // printf("SQL: %s<br>\n",$sql);
       // printf("ERR: %s<br>\n",mysql_error());
       if ( ($result) && ($myrow = mysql_fetch_array($result)) )
         {
           $member_id      = $myrow["member_id"];
           $firstname      = stripslashes($myrow["firstname"]);
           $lastname       = stripslashes($myrow["lastname"]);
           $email          = $myrow["email"];
           $subject        = $myrow["subject"];
           $request        = $myrow["request"];
           $response       = $myrow["response"];
           $custname       = $firstname." ".$lastname;
           $date_received  = $myrow["date_received"];
           $ts_request     = $myrow["ts_request"];
           $ts_response    = $myrow["ts_response"];
           $from = "$custname ($email)";

           $subject = striplt($subject);
           $subject = str_replace("Re: ", "", $subject);
           $subject = str_replace("Fwd: ", "", $subject);
           $subject = str_replace("RE: ", "", $subject);
           $subject = str_replace("FWD: ", "", $subject);
           $subject = striplt($subject);
           $subject = str_replace("\\", "", $subject);

           $message .= "------ Customer Message - posted ".formatDateTime($ts_request)." -------\r\n";
           $message .= str_replace("\\", "", $request);
           $message .= "\r\n";
           $message .= "\r\n";

           if ($ts_response > 0)
             {
               $message .= "------ Response - posted ".formatDateTime($ts_response)." -------\r\n";
               $message .= str_replace("\\", "", $response);
               $message .= "\r\n";
               $message .= "\r\n";
             }
         }

       include("messageviewer.php");
       exit;
     }
?>
