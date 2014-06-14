<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$today = getDateTodayAsArray();

if (isset($_REQUEST["month"]) && strlen($_REQUEST["month"])==10)
  {
    $targetDate = dateToArray($_REQUEST["month"]);
  }
else
  {
    $targetDate = $today;
  }

if ($targetDate["year"] > $today["year"])
    $targetDate = $today;
else
if ($targetDate["year"] == $today["year"] && $targetDate["month"] > $today["month"])
    $targetDate = $today;

$targetDate["day"] = 1;
$target_date = dateArrayToString($targetDate);


if ($targetDate["year"] == $today["year"] && $targetDate["month"] == $today["month"])
  {   // Current Calendar Month -  No NEXT
    $nextMonth = null;
  }
else
  {
    $nextMonth = calStepMonths(1,$targetDate);
    $nextmm    = $nextMonth["month"];
    $nextyy    = $nextMonth["year"];
    $next="<a href=\"index.php?month=".dateArrayToString($nextMonth)."\">".sprintf("%s, %s",$month_names[$nextmm-1],$nextyy)."</a>";
  }

if ($target_date <= "2009-12-01")
  {
    $prevMonth = null;
  }
else
  {
    $prevMonth = calStepMonths(-1,$targetDate);
    $prevmm    = $prevMonth["month"];
    $prevyy    = $prevMonth["year"];
    $prev="<a href=\"index.php?month=".dateArrayToString($prevMonth)."\">".sprintf("%s, %s",$month_names[$prevmm-1],$prevyy)."</a>";
  }


$mm = $targetDate["month"];
$yy = $targetDate["year"];
$month = sprintf("%s, %s",$month_names[$mm-1],$yy);

$calData=calendar($targetDate);
$dim = $calData["DaysInMonth"];
$targetStart = $targetDate;
$targetStart["day"] = 1;
$targetEnd = $targetDate;
$targetEnd["day"] = $dim;

$target_start = dateArrayToString($targetStart);
$target_end   = dateArrayToString($targetEnd);

$db=getPushyDatabaseConnection();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />

<script type="text/javascript">
</script>

<title>Tracker</title>
</head>

<body>


<?php
//---

//---
$dateArray=getDateTodayAsArray();
$calData=calendar($dateArray);
$dow = $calData["DayOfWeek"];
$today_mm=$dateArray["month"];
$today_dd=$dateArray["day"];
$today_yy=$dateArray["year"];
$today_date=dateArrayToString($dateArray);
//---


function getUniqueVisits($db,$week,$dow)
  {
    // week => 1 .. 5
    // dow  => 0 .. 6

    $visits=0;
    $sql = "SELECT sum(w".$week."_h".$dow.") from ".TRACKER_AFFILIATE_PAGE;
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error());

    if ($result && ($myrow = mysql_fetch_array($result)))
       $visits = (int) $myrow[0];

    // printf("Visits=%d\n",$visits);
    return $visits;
  }


function getSignups($db,$dt)
  {
    $signups=0;
    $sql  = "SELECT COUNT(*) from member";
    $sql .= " WHERE date_registered='$dt'";
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error());

    if ($result && ($myrow = mysql_fetch_array($result)))
       $signups = (int) $myrow[0];

    // printf("Signups=%d\n",$signups);
    return $signups;
  }


function getOrders($db,$dt)
  {
    $orders = 0;

    $sql  = "SELECT receiptid,txtype,member_id,user_level,order_type from receipts";
    $sql .= " WHERE order_type='initial'";
    $sql .= " AND   yymmdd='$dt'";
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error());

    if ($result)
      {
        $orders=0;
        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
          {
            $txtype=$myrow["txtype"];
            if ($txtype==0)
              $orders++;
            else
              $orders--;
          }
      }

    return $orders;
  }


$visits  = getUniqueVisits($db,5,$dow);
$signups = getSignups($db,$today_date);
$orders  = getOrders($db,$today_date);               // can be Negative if Returns > NewOrders

$todaysActivity = array("visits"           => $visits,
                        "signups"          => $signups,
                        "orders"           => $orders
                       );


printf("<PRE>\n");
printf("TODAY\n");
printf("  Visits: %4d\n",$todaysActivity["visits"]);
printf("  Signups:%4d\n",$todaysActivity["signups"]);
printf("  Orders: %4d\n",$todaysActivity["orders"]);
printf("</PRE>\n");
?>


<div style="position:absolute; display:; top:0px; left:0px; background:#D0D8EE; width:125px; height:92px;">
 <table width=120 cellpqdding=0 cellspacing=0 border=0>
   <tr height=34 valign=top>
     <td colspan=3 align=center width="100%"><b><?php echo $day_names[$dow]."<br>".$month_names[$today_mm-1]." ".$today_dd.", ".$today_yy?></b></td>
   </tr>
   <tr>
     <td align=right width="50%">Visits:&nbsp;</td>
     <td align=right width="40%"><?php echo $todaysActivity["visits"]?></td>
     <td align=right width="10%">&nbsp;</td>
   </tr>
   <tr>
     <td align=right width="50%">Signups:&nbsp;</td>
     <td align=right width="40%"><?php echo $todaysActivity["signups"]?></td>
     <td align=right width="10%">&nbsp;</td>
   </tr>
   <tr>
     <td align=right width="50%">Orders:&nbsp;</td>
     <td align=right width="40%"><?php echo $todaysActivity["orders"]?></td>
     <td align=right width="10%">&nbsp;</td>
   </tr>
 </table>
</div>

&nbsp;<br>

<a href="index.php?month=<?php echo $_REQUEST["month"]?>">Refresh</a>

<table cellpadding="0" cellspacing="0" width="900">
  <tr height=35>
     <td width="10%">&nbsp;</td>
     <td colspan=3 align=right  width="30%" style="background:#FFFFFF; font-size:12px; font-weight:bold; color:#2020BB"><?php echo $prev?></td>
     <td colspan=3 align=center width="30%" style="background:#FFFFFF; font-size:20px; font-weight:bold; color:#2020BB"><?php echo $month?></td>
     <td colspan=3 align=left   width="30%" style="background:#FFFFFF; font-size:12px; font-weight:bold; color:#2020BB"><?php echo $next?></td>
  </tr>

  <tr>
     <td width="10%">&nbsp;</td>
     <td colspan=3 align=center width="30%" style="background:#C0D0FF; font-size:14px; font-weight:bold;">DAY TOTALS</td>
     <td colspan=3 align=center width="30%" style="background:#FFD0C0; font-size:14px; font-weight:bold;">WEEK TOTALS</td>
     <td colspan=3 align=center width="30%" style="background:#C0FFD0; font-size:14px; font-weight:bold;">MONTH TOTALS</td>
  </tr>

  <tr>
     <td width="10%" style="background:#D0D0D0; font-size:12px; font-weight:bold;">Date</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Visits &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Signups &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Orders &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Visits &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Signups &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Orders &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Visits &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Signups &nbsp;</td>
     <td width="10%" align=right style="background:#D0D0D0; font-size:12px; font-weight:bold;">Orders &nbsp;</td>
  </tr>

<?php
$month_visits=0;
$month_signups=0;
$month_orders=0;

$week_visits=0;
$week_signups=0;
$week_orders=0;

$sql  = "SELECT * from activity";
$sql .= " WHERE date >= '$target_start'";
$sql .= " AND   date <= '$target_end'";
$sql .= " ORDER by date";
$result = mysql_query($sql,$db);

// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());

if ($result)
  {
    while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        $date      = $myrow["date"];
        $dateArray = dateToArray($date);
        $calData   = calendar($dateArray);
        $dow       = $calData["DayOfWeek"];
        $dim       = $calData["DaysInMonth"];
        $day       = $dateArray["day"];

        if ($dow==0)  // Sunday   - Start Of Week
          {
            $week_visits=0;
            $week_signups=0;
            $week_orders=0;
          }
        if ($day==1)  // Start Of Month
          {
            $month_visits=0;
            $month_signups=0;
            $month_orders=0;
          }


        $visits  = $myrow["visits"];
        $signups = $myrow["signups"];
//      $orders  = $myrow["neworders_pro"]   +
//                 $myrow["upgrades_pro"]    +
//                 $myrow["renewals_pro"]    +
//                 $myrow["neworders_elite"] +
//                 $myrow["upgrades_elite"]  +
//                 $myrow["renewals_elite"];

        $orders  = $myrow["neworders_pro"]   +
                   $myrow["neworders_elite"];

        $week_visits   += $visits;
        $week_signups  += $signups;
        $week_orders   += $orders;

        $month_visits  += $visits;
        $month_signups += $signups;
        $month_orders  += $orders;


        // if ($dow==6)  // Saturday - End Of Week
        if (TRUE)  // Saturday - End Of Week
          {
            $wvisits  = $week_visits;
            $wsignups = $week_signups;
            $worders  = $week_orders;
          }
        else
          {
            $wvisits  = "";
            $wsignups = "";
            $worders  = "";
         }
        //if ($day==$dim)  // End Of Month
        if (TRUE)  // End Of Month
          {
            $mvisits  = $month_visits;
            $msignups = $month_signups;
            $morders  = $month_orders;
          }
        else
          {
            $mvisits  = "";
            $msignups = "";
            $morders  = "";
          }



        if ($dow==6)  // End Of Month
          $wbg="#FFD0C0";
        else
          $wbg="#FFFFFF";

        if ($day==$dim)  // End Of Month
          $mbg="#C0FFD0";
        else
          $mbg="#FFFFFF";
?>

        <tr>
             <td width="10%" style="background:#FFFFFF; font-size:12px;"><?php echo $date?></td>
             <td width="10%" align=right style="background:#FFFFFF; font-size:12px;"><?php echo $visits?> &nbsp;</td>
             <td width="10%" align=right style="background:#FFFFFF; font-size:12px;"><?php echo $signups?> &nbsp;</td>
             <td width="10%" align=right style="background:#FFFFFF; font-size:12px;"><?php echo $orders?> &nbsp;</td>
             <td width="10%" align=right style="background:<?php echo $wbg?>; font-size:12px;"><?php echo $wvisits?> &nbsp;</td>
             <td width="10%" align=right style="background:<?php echo $wbg?>; font-size:12px;"><?php echo $wsignups?> &nbsp;</td>
             <td width="10%" align=right style="background:<?php echo $wbg?>; font-size:12px;"><?php echo $worders?> &nbsp;</td>
             <td width="10%" align=right style="background:<?php echo $mbg?>; font-size:12px;"><?php echo $mvisits?> &nbsp;</td>
             <td width="10%" align=right style="background:<?php echo $mbg?>; font-size:12px;"><?php echo $msignups?> &nbsp;</td>
             <td width="10%" align=right style="background:<?php echo $mbg?>; font-size:12px;"><?php echo $morders?> &nbsp;</td>
        </tr>
<?php
        if ($day==$dim)  // End Of Month
          echo "<tr><td colspan=10>&nbsp;</tr>\n";
      }
  }
?>
</table>



</body>
</html>
