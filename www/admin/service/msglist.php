<html>
<head>
<?php
  $MAX_WEEKS = 12;
  $START_MONTH = "2002-02-01";
  $startMonth = dateToArray($START_MONTH);
  $today = getDateTodayAsArray();
  $SVCAUTHOR = $_COOKIE["SVCAUTHOR"];
?>

<meta http-equiv="Page-Enter" content="RevealTrans (Duration=.6,Transition=12)">

<script language="JavaScript">
<!--
function VerifyForm(theForm)
 {
   if (theForm.op.value=="Locate")
     {
       if (theForm.searchvalue.value=="")
         {
           alert("  No Search Value Specified for  '"+theForm.searchterm.value+"'"+"   ");
           return false;
         }
     }
   return true;
 }
function prepareTo(option)
 {
   document.MESSAGES.op.value=option;
 }
function setSearchTerm(term)
 {
   // alert("TERM="+term);
   document.MESSAGES.op.value="Locate";
   document.MESSAGES.searchterm.value=term;
 }
function refresh(theForm)
 {
   theForm.submit();
 }
// -->
</script>
<title>PushyAds Administration - Customer Service Center</title>
</head>

<body LINK="#0000DD" VLINK="#0000DD" ALINK="#0000DD">
<div align="left">

<table border="0" cellPadding="0" cellSpacing="0" width="760">
<tbody>
  <tr>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><strong>Customer Service Center<small><br>Author:&nbsp;&nbsp;<font color="#CC0000"><?php echo $SVCAUTHOR?></font></small></strong></font></td>
    <td width="33%" bgcolor="#E8E8FF"><p align="center"><font face="Arial"><font color="#CC0000"><big><em><strong>PushyAds</strong></em></big></font><br>Administration</font></td>
    <td width="33%" bgcolor="#E8E8FF"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo getDateToday()?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo getTimeNow()?></font></small></strong></font></td>
  </tr>
</tbody>
</table>

<div align="left">
<!-- form name="MESSAGES" method="POST" action="http://teligis.com/tools/showme/showme.pl" -->
<form name="MESSAGES" method="POST" action="index.php" onSubmit="return VerifyForm(this)">
<input type="hidden" name="op" value="ListReports">
<input type="hidden" name="searchterm" value="">
<table bgcolor="#E0E0FF" border="0" width="760" cellpadding="1" cellspacing="1">
  <tr>
    <td align="left" colspan="2" width="100%">
       <font face="Arial" color="#CC0000"><small><b>Message Selection:</b></small></font>
    </td>
  </tr>
  <tr height="25">
    <td align="left" width="100%">
       <input type="Submit" name="by_lastname" value="Last Name" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick=setSearchTerm('lastname')>
       &nbsp;&nbsp;
       <input type="Submit" name="by_memberid" value="Member ID" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick=setSearchTerm('memberid')>
       &nbsp;&nbsp;
       <input type="Submit" name="by_email"    value="Email" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick=setSearchTerm('email')>
       &nbsp;&nbsp;
       <input type="Submit" name="by_serviceid" value="Service ID" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick=setSearchTerm('serviceid')>
       &nbsp;&nbsp;
       <input type="Submit" name="by_subject"  value="Subject contains" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick=setSearchTerm('subject')>
       &nbsp;&nbsp;<b>:</b>&nbsp;&nbsp;
       <input type="text" name="searchvalue" size="60" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;">
    </td>
  </tr>
<!-------
  <tr height="40">
    <td align="left" width="100%">
       <input type="Submit" name="for_today"     value="Today" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick="setSeachTerm('today')">
       &nbsp;&nbsp;
       <input type="Submit" name="for_last7days" value="Last 7 Days" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick="setSeachTerm('last7days')">
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <font face="Arial" color="#000099"><small>Month:&nbsp;</small></font>
       <select name="for_month" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onChange=setSeachTerm('month')><option value="none" selected>-SELECT-</option>
         <?php
            $temp=$today;
            while(dateDifferenceInMonths($startMonth, $temp) >= 0)
              {
                $dt = dateArrayToString($temp);
                $dtString = substr($month_names[$temp["month"]-1],0,3).", ".$temp["year"];
                echo "       <option value=\"$dt\">$dtString</option>\n";
                $temp=calStepMonths(-1, $temp);
              }
         ?>
       </select>
    </td>
  </tr>
------->
  <tr>
    <td width="100%" colspan="3">&nbsp;</td>
  </tr>
</table>

<div style="float:left; width:760px;"><hr width="760"></div>
<br>


<div align="left">
<!--------------------------------------------------------------------------------------------------------->
  <table border="0" width="760" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" align="left"> <font face="Arial"><small><u><strong><font color="#0000CC">Week Ending</font></strong><br><font color="#009900">(Saturday)</font></u></small></font></td>
      <td width="20%" align="center"><font face="Arial" color="#0000CC"><small><strong><u>Messages<br>Received</u></strong></small></font></td>
      <td width="20%" align="center"><font face="Arial" color="#0000CC"><small><strong><u>Unique<br>Customers</u></strong></small></font></td>
      <td width="20%" align="center"><font face="Arial" color="#0000CC"><small><strong><u>Messages<br>Answered</u></strong></small></font></td>
      <td width="20%" align="center"><font face="Arial" color="#0000CC"><small><strong><u>Messages<br>Unanswered</u></strong></small></font></td>
    </tr>
    <tr><td width="100%" colspan="5">&nbsp;</td></tr>

<?php

    $todayAsArray=getDateTodayAsArray();
    $calData=calendar($todayAsArray);
    $dow=$calData["DayOfWeek"];

    if ($dow==0)
      $dateArray=$todayAsArray;
    else
      $dateArray=calStepDays(-$dow,$todayAsArray);

    $beginning[0] = $dateArray;
    $ending   [0] = calStepDays(6,$beginning[0]);
    for ($i=1; $i<$MAX_WEEKS; $i++)
      {
        $beginning[$i] = calStepDays(-7,$beginning[$i-1]);
        $ending   [$i] = calStepDays(6, $beginning[$i]);
      }

    for ($i=0; $i<$MAX_WEEKS; $i++)
      {
        // printf("Sunday %s to %s<br>\n",dateArrayToString($beginning[$i]),dateArrayToString($ending[$i]));
        $fromdate= dateArrayToString($beginning[$i]);
        $todate  = dateArrayToString($ending[$i]);

        $received=0;
        $answered=0;
        $unanswered=0;
        $unique=0;

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

        $last_member="";
        $sql  = "SELECT member_id, ts_response FROM service";
        $sql .= " WHERE date_received >= '$fromdate'";
        $sql .= " AND   date_received <= '$todate' order by member_id";
        $result = exec_query($sql,$db);
        if ($result)
          {
            while ($myrow = mysql_fetch_array($result))
              {
                $received++;
                if ($myrow["ts_response"] == 0)
                  $unanswered++;
                else
                  $answered++;

                $member_id = $myrow["member_id"];
                if ($member_id != $last_member)
                  {
                    $unique++;
                    $last_member=$member_id;
                  }
              }
          }

        if ($i>0)
          {
            $darray=$ending[$i];
            $parray=$ending[$i-1];
            if ($darray["month"] != $parray["month"])
              {
                echo "<tr><td width=\"100%\" colspan=\"5\">&nbsp;</td></tr>\n";
              }
          }

        echo "<tr>\n";
          echo "<td width=\"20%\" align=\"left\">  <font face=\"Arial\"><small><a href=\"index.php?op=ShowWeek&WB=$fromdate&WE=$todate\">$todate</a></small></font></td>\n";
          echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\"><small>$received</small></font></td>\n";
          echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\"><small>$unique</small></font></td>\n";
          if ($answered > 0)
            echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\"><small><font color=\"#0000CC\"><b>$answered</b></font></small></font></td>\n";
          else
            echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\"><small>$answered</small></font></td>\n";
          if ($unanswered > 0)
            echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\"><small><font color=\"#CC0000\"><b>$unanswered</b></font></small></font></td>\n";
          else
            echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\"><small>$unanswered</small></font></td>\n";
        echo "</tr>\n";
      }
?>
  </table>
</div>

</body>
</html>
