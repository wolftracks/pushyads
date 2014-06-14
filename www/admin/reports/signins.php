<html>
<head>

<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<title>PushyAds Administration - Signins</title>
</head>

<body>
<div align="left">
<table border="0" cellpadding="4" cellspacing="0" width="760">
  <tr>
    <td width="20%"><p align="center"><font face="Arial" color="#0000A0"><strong>PushyAds<br>Signin Activity</strong></font></td>
    <td width="60%"><p align="center"><font face="Arial" color="#FF0000"><big><strong><big><strong>
    PushyAds</strong></big></strong></big></font><br>
    <font face="Arial" color="#0000A0"><strong>
    PushyAds Administration</strong></font></td>

    <td width="20%">
       <span class="smalldarkredbold">DATE:&nbsp;&nbsp;</span>
       <span class="smallbold"><?php echo getDateToday()?></span><br>
       <span class="smalldarkredbold">TIME:&nbsp;&nbsp;</span>
       <span class="smallbold"><?php echo getTimeNow()?></span>
    </td>
  </tr>
</table>
</div>
<br>

<table width="760" cellspacing=0 cellpadding=0>
  <tr>
    <td width="50%">
       <form method="POST" action="NULL">
         &nbsp; <input type="button" value=" BACK " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" onClick="javascript:history.back()">
       </form>
    </td>
    <td width="50%" align="right">&nbsp;</td>
  </tr>
</table>


<div align="left">
<!--------------------------------------------------------------------------------------------------------->
  <table border="0" width="760" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" align="left">&nbsp;</td>
      <td width="20%" align="left"><font face="Arial" color="#0000CC"><strong><u>Member Name</u></strong></font></td>
      <td width="20%" align="left"><font face="Arial" color="#0000CC"><strong><u>Signin ID</u></strong></font></td>
      <td width="20%" align="center"><font face="Arial" color="#0000CC"><strong><u>Last Signin</u></strong></font></td>
      <td width="20%" align="center"><font face="Arial" color="#0000CC"><strong><u>Date Registered</u></strong></font></td>
    </tr>
    <tr><td width="100%" colspan="5">&nbsp;</td></tr>

<?php
 $MAX_WEEKS = 12;
 $START_MONTH = "2002-02-01";
 $startMonth = dateToArray($START_MONTH);

 $today=getDateToday();
 $todayAsArray=getDateTodayAsArray();
 $calData=calendar($todayAsArray);
 $dow=$calData["DayOfWeek"];
 $today=getDateToday();

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
     $dtm_fromdate = $beginning[$i];
     $dtm_todate   = $ending[$i];

     $fromdate= dateArrayToString($dtm_fromdate);
     $todate  = dateArrayToString($dtm_todate);

     $dtm_fromdate["hour"]=0;
     $dtm_fromdate["minute"]=0;
     $dtm_fromdate["second"]=0;

     $dtm_todate["hour"]=23;
     $dtm_todate["minute"]=59;
     $dtm_todate["second"]=59;

     $ts_fromdate=timestampFromDateTimeArray($dtm_fromdate);
     $ts_todate  =timestampFromDateTimeArray($dtm_todate);

     $sicount=0;

     $sql  = "SELECT * FROM member";
     $sql .= " WHERE lastaccess >= '$ts_fromdate'";
     $sql .= " AND   lastaccess <= '$ts_todate'";
     $sql .= " ORDER BY lastaccess DESC";
     $result = exec_query($sql,$db);
     if ($result)
       {
         $sicount = mysql_num_rows($result);

         if ($i>0)
           {
//           $darray=$ending[$i];
//           $parray=$ending[$i-1];
//           if ($darray["month"] != $parray["month"])
//             {
                 echo "<tr><td width=\"100%\" colspan=\"5\">&nbsp;</td></tr>\n";
//             }
           }

         echo "<tr bgcolor=\"#D0D0D0\">\n";
           echo "<td width=\"20%\" align=\"left\">  <font face=\"Arial\"><b>$fromdate</b> to <b>$todate</b> </font></td>\n";
           echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\" color=\"#CC0000\"><b>$sicount</b></font></td>\n";
           echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\">&nbsp;</font></td>\n";
           echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\">&nbsp;</font></td>\n";
           echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\">&nbsp;</font></td>\n";
         echo "</tr>\n";

         while ($myrow = mysql_fetch_array($result))
           {
             $lastname=$myrow["lastname"];
             $firstname=$myrow["firstname"];
             $name=$lastname.", ".$firstname;
             $signin_id=$myrow["signin_id"];

             // $registered=$myrow["registered"];
             // $dt_registered = formatDate($registered);

             $dt_registered = $myrow["date_registered"];

             $accessed=$myrow["lastaccess"];
             if ($accessed==0)
               {
                 $dt_accessed = "-";
                 $tm_accessed = "-";
                 $dtm_accessed = "-";
               }
             else
               {
                 $dt_accessed = formatDate($accessed);
                 $tm_accessed = formatTime($accessed);
                 $dtm_accessed = $dt_accessed."&nbsp;&nbsp;&nbsp;".$tm_accessed;
               }

             echo "<tr>\n";
               echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\">&nbsp;</font></td>\n";
               echo "<td width=\"20%\" align=\"left\"><font face=\"Arial\">$name</font></td>\n";
               echo "<td width=\"20%\" align=\"left\"><font face=\"Arial\">$signin_id</a></font></td>\n";
               if ($dt_accessed == $today)
                 echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\" color=\"#0000DD\">$dtm_accessed</font></td>\n";
               else
                 echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\">$dt_accessed</font></td>\n";
               echo "<td width=\"20%\" align=\"center\"><font face=\"Arial\">$dt_registered</font></td>\n";
             echo "</tr>\n";
           }
       }

   }
?>
  </table>
</div>

</body>
</html>
