<html>
<?php
   $timeNow   = getTimeNow();
   $today     = getDateTodayAsArray();
   $SVCAUTHOR=$_COOKIE["SVCAUTHOR"];
?>

<head>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/admin/admin.js"></script>
<script type="text/javascript">
<!--
 function VerifyRespond(theForm)
   {
     // alert("SVCID="+theForm.service_id.value);
     // alert("SEQ="+theForm.seq.value);
     return false;
   }

 function openMessage(svcid,seqno)
   {
     var leftmargin = screen.width - 700 - 12;
     win=window.open("index.php?op=OpenMessage&service_id="+svcid+"&seq="+seqno,"OpenMessage",
        'width=700,height=480,top=0,left='+leftmargin+
        ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
   }

 function respondTo(theForm,svcid,seqno)
   {
//   win=window.open("index.php?op=SendList&service_id="+svcid+"&seq="+seqno,"CustomerService",
//      'width=760,height=560,top=0,left=0'+
//      ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
//   refreshWindow();
     document.location.href="index.php?op=SendList&service_id="+svcid+"&seq="+seqno+"&searchterm=<?php echo $searchterm?>&searchvalue=<?php echo $searchvalue?>";
   }

 function reAssign(theForm,theObject,svcid,wb,we)
   {
//   alert ("Object="+theObject.name);
//   alert ("Object="+theObject.value);
//   alert ("ServiceID="+svcid);
//   alert ("Seq="+seqno);

     var msg ="You have Asked to Re-Assign Service Request: "+svcid+" to "+theObject.value+"\n\n";
         msg+="To Continue - Click OK\n";
         msg+="To Cancel   - Click CANCEL\n";
     res=confirm(msg);
     if (res!=null && (res))
       {
         document.location.href="index.php?op=REASSIGN&service_id="+svcid+"&assignee="+theObject.value+"&WB="+wb+"&WE="+we;
         // document.location.href="http://teligis.com/tools/showme/showme.pl?op=REASSIGN&service_id="+svcid+"&responder="+theObject.value+"&WB="+wb+"&WE="+we;
       }
     else
       {
         theObject.selectedIndex=0;
         alert("CANCELLED");
       }
   }

 function setAnswered(theForm,svcid,seqno,wb,we)
   {
//   alert ("ServiceID="+svcid);
//   alert ("Seq="+seqno);

     var msg ="You have Asked to Set This Service Request to 'Answered'\n\n";
         msg+="To Continue - Click OK\n";
         msg+="To Cancel   - Click CANCEL\n";
     res=confirm(msg);
     if (res!=null && (res))
       {
         document.location.href="index.php?op=SetAnswered&service_id="+svcid+"&seq="+seqno+"&WB="+wb+"&WE="+we;
       }
   }

 function refreshWindow()
   {
     document.location.href="index.php?op=Locate&searchterm=<?php echo $searchterm?>&searchvalue=<?php echo $searchvalue?>";
   }
// -->
</script>

<title>Customer Service Management System</title>
</head>

<body LINK="#0000DD" VLINK="#0000DD" ALINK="#0000DD">
<div align="left">

<table border="0" cellPadding="0" cellSpacing="0" width="760">
<tbody>
  <tr>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><strong>Customer Service</strong><br><small>Week Ending: <?php echo $WE?></small></font></td>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><font color="#CC0000"><big><em><strong>PushyAds</strong></em></big></font><br>Administration</font></td>
    <td width="33%" bgcolor="#E8E8FF"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo getDateToday()?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo getTimeNow()?></font></small></strong></font></td>
  </tr>
</tbody>
</table>
<hr width="760">
<font face="Arial"><small><b>Author:&nbsp;&nbsp;<font color="#CC0000"><?php echo $SVCAUTHOR?></font></b></small></font>
<form name="SERVICEREQUESTS" method="POST" action="index.php">
 <input type="hidden" name="EXPANDED" value="<?php echo $EXPANDED?>">
 <input type="hidden" name="searchterm" value="<?php echo $searchterm?>">
 <input type="hidden" name="searchvalue" value="<?php echo $searchvalue?>">
<table border="0" width="760" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50%" align="left"><input type="button" name="Return" value="Return to Weekly Summary" onclick="javascript:document.location.href='/admin/service'"></td>
    <td width="50%" align="right">&nbsp;</td>
  </tr>
</table>
</form>
<?php
  if ($ERROR > 0 && strlen($statusMessage)>0)
    {
      echo "<center><font face=\"Arial\" color=\"#CC0000\"><b><small>$statusMessage</small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font><br>&nbsp;</center>\n";
    }
?>
<div align="left" width="760">
  <table border="0" width="760" cellpadding="0" cellspacing="0">
    <tr>
      <td width="18%" align="left">  <font face="Arial"><small><strong><u>Service ID</u></strong></small></font></td>
      <td width="28%" align="left">  <font face="Arial"><small><strong><u>Customer</u></strong></small></font></td>
      <td width="12%" align="center"><font face="Arial"><small><strong><u>Last Message</u></strong></small></font></td>
      <td width="10%" align="center"><font face="Arial"><small><strong><u>#Msgs</u></strong></small></font></td>
      <td width="10%" align="center"><font face="Arial"><small><strong><u>#Ans</u></strong></small></font></td>
      <td width="10%" align="center"><font face="Arial"><small><strong><u>#Unans</u></strong></small></font></td>
      <td width="12%" align="center"><font face="Arial"><small><strong><u>Assigned-To</u></strong></small></font></td>
    </tr>

<?php
   $save_service_id = "";
   $messages=0;
   $answered=0;
   $unanswered=0;

   $sql  = "SELECT * FROM service";
   if ($searchterm=="lastname")
     $sql .= " WHERE lastname='$searchvalue'  ORDER BY service_id";
   if ($searchterm=="memberid")
     $sql .= " WHERE member_id='$searchvalue'  ORDER BY service_id";
   if ($searchterm=="email")
     $sql .= " WHERE lastname='$searchvalue'  ORDER BY service_id";
   if ($searchterm=="serviceid")
     $sql .= " WHERE service_id='$searchvalue'  ORDER BY service_id";
   if ($searchterm=="subject")
     $sql .= " WHERE subject LIKE '%$searchvalue%'  ORDER BY service_id";
   $result = exec_query($sql,$db);
   // printf("SQL: %s<br>\n",$sql);
   // printf("ERR: %s<br>\n",mysql_error());
   if ($result)
     {
       $myrow = mysql_fetch_array($result);
       while ($myrow)
         {
           if ($myrow["member_id"]=="") $myrow["member_id"]="NON-MEMBER";

           $service_id     = $myrow["service_id"];

           // printf("%s %s<br>\n",$service_id, $save_service_id);
           if ($service_id != $save_service_id)
             {
               if (strlen($save_service_id)>0)
                 {
                   echo "<tr>\n";
                     echo "<td width=\"18%\" align=\"left\">  <a href=\"index.php?op=FExpand&searchterm=$searchterm&searchvalue=$searchvalue&service_id=$save_service_id\"><font face=\"Arial\" color=\"#0000CC\"><small>$save_service_id</small></font></a></td>\n";
                     if ($unanswered > 0)
                       echo "<td width=\"28%\" align=\"left\" bgcolor=\"#FFE8E8\"><font face=\"Arial\"><small>$custname</small></font></td>\n";
                     else
                       echo "<td width=\"28%\" align=\"left\" >  <font face=\"Arial\"><small>$custname</small></font></td>\n";
                     echo "<td width=\"12%\" align=\"center\"><font face=\"Arial\"><small><small>$lastrequest</small></small></font></td>\n";
                     echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\"><small>$messages</small></font></td>\n";
                     if ($answered > 0)
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\" color=\"#0000CC\"><small><b>$answered</b></small></font></td>\n";
                     else
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\"><small>$answered</small></font></td>\n";

                     if ($unanswered > 0)
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><small><b>$unanswered</b></small></font></td>\n";
                     else
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\"><small>$unanswered</small></font></td>\n";

                     echo "<td width=\"12%\" align=\"center\"><font face=\"Arial\"><small>$assignee</small></font></td>\n";
                   echo "</tr>\n";
                 }
               $save_service_id = $service_id;
               $messages=0;
               $answered=0;
               $unanswered=0;
               $member_id      = $myrow["member_id"];
               $firstname      = $myrow["firstname"];
               $lastname       = $myrow["lastname"];
               $email          = $myrow["email"];
               $custname       = $firstname." ".$lastname;
               $assignee       = $myrow["assignee"];
               $responder      = $myrow["responder"];
               if ($member_id == "NON-MEMBER")
                 $custname .= "&nbsp;&nbsp;<small><font color=\"#CC0000\">(NM)</font></small>";
             }
           if ($service_id == $EXPANDED)
             {
               $seq            = $myrow["seq"];
               $subject        = $myrow["subject"];
               $date_received  = $myrow["date_received"];
               $member_id      = $myrow["member_id"];
               $firstname      = $myrow["firstname"];
               $lastname       = $myrow["lastname"];
               $email          = $myrow["email"];
               $custname       = $firstname." ".$lastname;
//             if ($member_id == "NON-MEMBER")
//               $custname .= "&nbsp;&nbsp;<small><font color=\"#CC0000\">(NM)</font></small>";
               $ts_request     = $myrow["ts_request"];
               $ts_response    = $myrow["ts_response"];
               $assignee       = $myrow["assignee"];
               $responder      = $myrow["responder"];
               $date_answered  = "";
               if ($ts_response > 0)
                 $date_answered  = formatDateTime($ts_response);
               $request_date   = formatDateTime($ts_request);
               if (strlen($subject) > 34)
                  $subject = substr($subject, 0, 32)." ...";

               echo "<tr><td colspan=\"8\">&nbsp;</td></tr>\n";

               echo "<tr>\n";
                echo "<td colspan=\"8\" width=\"100%\">\n";
                  echo "<form name=\"DETAIL\" metod=\"POST\" action=\"respond.php\" onSubmit='return VerifyRespond(this)'>\n";
                  echo "<input type=\"hidden\" name=\"service_id\" value=\"\">\n";
                  echo "<input type=\"hidden\" name=\"seq\" value=\"\">\n";
                  echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#E0E0E0\" width=\"100%\">\n";
                  echo "<tr>\n";
                    echo "<td width=\"6%\"  align=\"center\"><font face=\"Arial\"><small><b><u>Seq#</u></b></small></font></td>\n";
                    echo "<td width=\"22%\" align=\"left\"><font face=\"Arial\"><small><b><u>Customer</u></b></small></font></td>\n";
                    echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\"><small><b><u>Received</u></b></small></font></td>\n";
                    echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\"><small><b><u>Answered</u></b></small></font></td>\n";
                    echo "<td width=\"30%\" align=\"left\"><font face=\"Arial\"><small><b><u>Subject</u></b></small></font></td>\n";

                    if ($expanded_unanswered > 0)
                    // if ($ts_response==0)
                      {
                        if (strlen($assignee)>0 && isset($users[$assignee]))
                          $any="";
                        else
                          $any="selected";
                        echo "<td width=\"16%\" align=\"right\"><font face=\"Arial\"><small><small>Assign To:&nbsp;</small></small>";
                        echo "<select name=\"$service_id:$seq\" STYLE=\"font-family: Arial, Helvetica, sans-serif; font-size: 10px;\" size=1 onChange=reAssign(this.form,this,'$service_id','$WB','$WE')><option value=\"ANY\" $any>ANY</option>";
                        while (list($user,$email) = each($users))
                          {
                            if (strcasecmp($assignee,$user)==0)
                              $sel="selected";
                            else
                              $sel="";
                            echo "  <option value=\"$user\" $sel>$user</option>\n";
                          }
                        echo "</select>";
                        echo "</td>\n";
                      }
                    else
                      echo "<td width=\"16%\" align=\"right\"><font face=\"Arial\"><small><b><u>Responded</u></b></small></font></td>\n";
                  echo "</tr>\n";
                  echo "<tr>\n";
                    echo "<td width=\"6%\"  align=\"center\"><font face=\"Arial\"><small>$seq</small></font></td>\n";

                    if ($member_id == "NON-MEMBER")
                      echo "<td width=\"22%\" align=\"left\"><font face=\"Arial\" color=\"#CC0000\"><small><small>NM</small></small></font>&nbsp;<a href=\"javascript:openMessage('$service_id','$seq')\"><font face=\"Arial\" color=\"#0000DD\"><small>$custname</small></font></a></td>\n";
                    else
                      {
                        echo "<td width=\"22%\" align=\"left\"><a href=\"javascript:openMember('$member_id')\"><img src=\"http://pds1106.s3.amazonaws.com/images/member.gif\" border=0></a>&nbsp;<a href=\"javascript:openMessage('$service_id','$seq')\"><font face=\"Arial\" color=\"#0000DD\"><small>$custname</small></font></a></td>\n";
                      }
                    echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\"><small><small>$request_date</small></small></font></td>\n";
                    if ($ts_response==0)
                       {
                         if (($SVCAUTHOR == "tim" || $SVCAUTHOR == "mark"))
                           echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><small><b>No&nbsp;&nbsp;</b></small><a href=\"javascript:setAnswered(this.form,'$service_id','$seq','$WB','$WE')\"><img src=\"http://pds1106.s3.amazonaws.com/images/answered.gif\" border=0></a></font></td>\n";
                         else
                           echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><small><b>No&nbsp;</b></small></font></td>\n";
                       }
                    else
                      echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\"><small><small>$date_answered</small></small></font></td>\n";
                    echo "<td width=\"30%\" align=\"left\"><font face=\"Arial\"><small>$subject</small></font></td>\n";
                    if ($ts_response==0)
                      echo "<td width=\"16%\" align=\"right\"><input type=\"button\" value=\" Respond \" onClick=\"respondTo(this.form,'$service_id','$seq')\"  STYLE=\"font-family: Arial, Helvetica, sans-serif; font-size: 10px;\"></td>\n";
                    else
                      echo "<td width=\"16%\" align=\"right\"><font face=\"Arial\" color=\"#000099\"><small>$responder&nbsp;&nbsp;&nbsp;&nbsp;</small></font></td>\n";
                  echo "</tr>\n";
                  while ($myrow = mysql_fetch_array($result))
                    {
                      $service_id     = $myrow["service_id"];
                      if ($service_id != $EXPANDED)
                        break;

                      $seq            = $myrow["seq"];
                      $subject        = $myrow["subject"];
                      $date_received  = $myrow["date_received"];
                      $member_id      = $myrow["member_id"];
                      $firstname      = $myrow["firstname"];
                      $lastname       = $myrow["lastname"];
                      $email          = $myrow["email"];
                      $custname       = $firstname." ".$lastname;
//                    if ($member_id == "NON-MEMBER")
//                      $custname .= "&nbsp;&nbsp;<small><font color=\"#CC0000\">(NM)</font></small>";
                      $ts_request     = $myrow["ts_request"];
                      $ts_response    = $myrow["ts_response"];
                      $assignee       = $myrow["assignee"];
                      $responder      = $myrow["responder"];
                      $date_answered  = "";
                      if ($ts_response > 0)
                        $date_answered  = formatDateTime($ts_response);
                      $request_date   = formatDateTime($ts_request);
                      if (strlen($subject) > 34)
                         $subject = substr($subject, 0, 32)." ...";

                      echo "<tr>\n";
                        echo "<td width=\"6%\"  align=\"center\"><font face=\"Arial\"><small>$seq</small></font></td>\n";
                        if ($member_id == "NON-MEMBER")
                          echo "<td width=\"22%\" align=\"left\"><font face=\"Arial\" color=\"#CC0000\"><small><small>NM</small></small></font>&nbsp;<a href=\"javascript:openMessage('$service_id','$seq')\"><font face=\"Arial\" color=\"#0000DD\"><small>$custname</small></font></a></td>\n";
                        else
                          echo "<td width=\"22%\" align=\"left\"><a href=\"javascript:openMember('$member_id')\"><img src=\"http://pds1106.s3.amazonaws.com/images/member.gif\" border=0></a>&nbsp;<a href=\"javascript:openMessage('$service_id','$seq')\"><font face=\"Arial\" color=\"#0000DD\"><small>$custname</small></font></a></td>\n";
                        echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\"><small><small>$request_date</small></small></font></td>\n";
                        if ($ts_response==0)
                           {
                             if (($SVCAUTHOR == "tim" || $SVCAUTHOR == "mark"))
                               echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><small><b>No&nbsp;&nbsp;</b></small><a href=\"javascript:setAnswered(this.form,'$service_id','$seq','$WB','$WE')\"><img src=\"http://pds1106.s3.amazonaws.com/images/answered.gif\" border=0></a></font></td>\n";
                             else
                               echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><small><b>No&nbsp;</b></small></font></td>\n";
                           }
                        else
                          echo "<td width=\"13%\" align=\"center\"><font face=\"Arial\"><small><small>$date_answered</small></small></font></td>\n";
                        echo "<td width=\"30%\" align=\"left\"><font face=\"Arial\"><small>$subject</small></font></td>\n";
                        if ($ts_response==0)
                          echo "<td width=\"16%\" align=\"right\"><input type=\"button\" value=\" Respond \" onClick=\"respondTo(this.form,'$service_id','$seq')\"  STYLE=\"font-family: Arial, Helvetica, sans-serif; font-size: 10px;\"></td>\n";
                        else
                          echo "<td width=\"16%\" align=\"right\"><font face=\"Arial\" color=\"#000099\"><small>$responder&nbsp;&nbsp;&nbsp;&nbsp;</small></font></td>\n";
                      echo "</tr>\n";
                    }
                  echo "</table>\n";
                  echo "</form>\n";
                echo "</td>\n";
               echo "</tr>\n";
               $messages=0;
               $answered=0;
               $unanswered=0;
               $save_service_id = "";

               $messages++;
               if ($myrow["ts_response"] == 0)
                 $unanswered++;
               else
                 $answered++;
//             $lastrequest    = $myrow["date_received"];
               $lastrequest    = formatDateTime($myrow["ts_request"]);
               continue;
             }

           $messages++;
           if ($myrow["ts_response"] == 0)
             $unanswered++;
           else
             $answered++;
//         $lastrequest    = $myrow["date_received"];
           $lastrequest    = formatDateTime($myrow["ts_request"]);
           $myrow = mysql_fetch_array($result);
         }
     }
   if (strlen($save_service_id)>0)
     {
                   echo "<tr>\n";
                     echo "<td width=\"18%\" align=\"left\">  <a href=\"index.php?op=FExpand&searchterm=$searchterm&searchvalue=$searchvalue&service_id=$save_service_id\"><font face=\"Arial\" color=\"#0000CC\"><small>$save_service_id</small></font></a></td>\n";
                     if ($unanswered > 0)
                       echo "<td width=\"28%\" align=\"left\" bgcolor=\"#FFE8E8\"><font face=\"Arial\"><small>$custname</small></font></td>\n";
                     else
                       echo "<td width=\"28%\" align=\"left\" >  <font face=\"Arial\"><small>$custname</small></font></td>\n";
                     echo "<td width=\"12%\" align=\"center\"><font face=\"Arial\"><small><small>$lastrequest</small></small></font></td>\n";
                     echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\"><small>$messages</small></font></td>\n";
                     if ($answered > 0)
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\" color=\"#0000CC\"><small><b>$answered</b></small></font></td>\n";
                     else
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\"><small>$answered</small></font></td>\n";

                     if ($unanswered > 0)
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><small><b>$unanswered</b></small></font></td>\n";
                     else
                        echo "<td width=\"10%\" align=\"center\"><font face=\"Arial\"><small>$unanswered</small></font></td>\n";
                     echo "<td width=\"12%\" align=\"center\"><font face=\"Arial\"><small>$assignee</small></font></td>\n";
                   echo "</tr>\n";
     }
?>
  </table>
</div>

</body>
</html>
