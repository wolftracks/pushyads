<?php

$member_id=$mid;
//--------------------- TEST ONLY -----------------
// $member_id="pushy";
// $memberRecord["date_registered"] = "2009-04-23";
//--------------------- TEST ONLY -----------------

function dollar_format($amount)
 {
   if ($amount < 0)
     return "-$".number_format(-$amount,2,".",",");
   return "$".number_format($amount,2,".",",");
 }

//----------------------------------------------------------------------------------------

$dateTodayAsArray      = getDateTodayAsArray();
$dateToday             = dateArrayToString($dateTodayAsArray);
$yymmToday             = substr($dateToday,0,7);
$dayToday              = $dateTodayAsArray["day"];

$currentMonthAsArray   = getDateTodayAsArray();
$current_yymmdd        = dateArrayToString(getDateTodayAsArray());
$current_yymm          = substr($current_yymmdd,0,7);
$previousMonthAsArray  = calStepMonths(-1,$currentMonthAsArray);
$previous_yymm         = substr(dateArrayToString($previousMonthAsArray),0,7);
$calData=calendar($previousMonthAsArray);
$days_previousMonth = $calData["DaysInMonth"];

$currentMonthStart     = $currentMonthAsArray;
$currentMonthStart["day"]=1;
$currentMonthEnd       = $currentMonthStart;
$calData=calendar($currentMonthEnd);
$currentMonthEnd["day"]=$calData["DaysInMonth"];
$current_month_start = dateArrayToString($currentMonthStart);
$current_month_end   = dateArrayToString($currentMonthEnd);

$date_registered       = $memberRecord["date_registered"];
$dateRegisteredAsArray = dateToArray($date_registered);
$monthsRegistered      = dateDifferenceInMonths($dateRegisteredAsArray, $dateTodayAsArray);
if ($monthsRegistered < 3)
  {
    $dateRegisteredAsArray = calStepMonths(-(3-$monthsRegistered),$dateRegisteredAsArray);
    $date_registered = dateArrayToString($dateRegisteredAsArray);
  }

if ($dateRegisteredAsArray["year"] == $dateTodayAsArray["year"])
  {
    $Index_FirstYear = $dateTodayAsArray["year"];
    $Index_LastYear  = $dateTodayAsArray["year"];
    $Index_FirstMonth = $dateRegisteredAsArray["month"];
  }
else
  {
    $Index_FirstYear = $dateTodayAsArray["year"] - 1;
    $Index_LastYear  = $dateTodayAsArray["year"];
    if ($dateRegisteredAsArray["year"] == $Index_FirstYear)
      $Index_FirstMonth = $dateRegisteredAsArray["month"];
    else
      $Index_FirstMonth = 1;
  }


$reporting_period_start_date = sprintf("%04d-01-01",$Index_FirstYear);

$paystubs     = array();

$sql  = "SELECT * FROM earnings_semi_monthly ";
$sql .= " WHERE yymmdd >='$reporting_period_start_date'";
$sql .= " AND member_id='$member_id'";
$sql .= " ORDER BY yymmdd";
$result = mysql_query($sql,$db);

// printf("SQL: %s<br>\n",$sql);
// printf("ERR: %s<br>\n",mysql_error());

if (($result) && mysql_num_rows($result) > 0)
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
         $yymmdd    = $myrow["yymmdd"];
         $yymm      = substr($yymmdd,0,7);
         $dateArray = dateToArray($yymmdd);

         $stub = array (
            "yymmdd"         => $yymmdd,
            "yymm"           => $yymm,
            "year"           => $dateArray["year"],
            "month"          => $dateArray["month"],
            "day"            => $dateArray["day"],
            "sales_pro"      => $myrow["sales_pro"],
            "earnings_pro"   => $myrow["earnings_pro"],
            "sales_elite"    => $myrow["sales_elite"],
            "earnings_elite" => $myrow["earnings_elite"],
            "bonus_count"    => $myrow["bonus_count"],
            "bonus_amount"   => $myrow["bonus_amount"],
            "returns_count"  => $myrow["returns_count"],
            "returns_amount" => $myrow["returns_amount"],
            "count_totals"   => $myrow["sales_pro"]    + $myrow["sales_elite"]    + $myrow["bonus_count"]  - $myrow["returns_count"],
            "amount_totals"  => $myrow["earnings_pro"] + $myrow["earnings_elite"] + $myrow["bonus_amount"] + $myrow["returns_amount"],
         );

         if ($dateArray["day"]==15)
           $paystubs[$yymm]["P1"] = $stub;
         else
           $paystubs[$yymm]["P2"] = $stub;
      }
  }


//============= CURRENT MONTH must be Obtained from the Daily Records ============================
// ----- First-Period ------
$sql  = "SELECT ";
$sql .= "  SUM(sales_pro),      ";
$sql .= "  SUM(earnings_pro),   ";
$sql .= "  SUM(sales_elite),    ";
$sql .= "  SUM(earnings_elite), ";
$sql .= "  SUM(bonus_count),    ";
$sql .= "  SUM(bonus_amount),   ";
$sql .= "  SUM(returns_count),  ";
$sql .= "  SUM(returns_amount)  ";
$sql .= " FROM earnings_daily ";
$sql .= " WHERE yymmdd >='".$current_yymm."-01'";
$sql .= " AND   yymmdd <='".$current_yymm."-15'";
$sql .= " AND member_id='$member_id'";
$result = mysql_query($sql,$db);

// printf("SQL: %s<br>\n",$sql);
// printf("ERR: %s<br>\n",mysql_error());

if (($result) && mysql_num_rows($result) > 0)
  {
    if ($myrow=mysql_fetch_array($result,MYSQL_NUM))
      {
         $yymmdd    = $current_yymm."-15";
         $yymm      = $current_yymm;
         $dateArray = dateToArray($yymmdd);

         $stub = array (
            "yymmdd"         => $yymmdd,
            "yymm"           => $yymm,
            "year"           => $dateArray["year"],
            "month"          => $dateArray["month"],
            "day"            => $dateArray["day"],
            "sales_pro"      => (int) $myrow[0],
            "earnings_pro"   => number_format($myrow[1],2,".",""),
            "sales_elite"    => (int) $myrow[2],
            "earnings_elite" => number_format($myrow[3],2,".",""),
            "bonus_count"    => (int) $myrow[4],
            "bonus_amount"   => number_format($myrow[5],2,".",""),
            "returns_count"  => (int) $myrow[6],
            "returns_amount" => number_format($myrow[7],2,".",""),
         );
         $stub["count_totals"]  = $stub["sales_pro"]    + $stub["sales_elite"]    + $stub["bonus_count"]  - $stub["returns_count"];
         $stub["amount_totals"] = $stub["earnings_pro"] + $stub["earnings_elite"] + $stub["bonus_amount"] + $stub["returns_amount"];

         $paystubs[$yymm]["P1"] = $stub;
      }
  }


// ----- Second-Period ------
if ($dayToday > 15)  // if on or before the 15th of the month - the second period of the month will Default to Zeroes
  {
     $sql  = "SELECT ";
     $sql .= "  SUM(sales_pro),      ";
     $sql .= "  SUM(earnings_pro),   ";
     $sql .= "  SUM(sales_elite),    ";
     $sql .= "  SUM(earnings_elite), ";
     $sql .= "  SUM(bonus_count),    ";
     $sql .= "  SUM(bonus_amount),   ";
     $sql .= "  SUM(returns_count),  ";
     $sql .= "  SUM(returns_amount)  ";
     $sql .= " FROM earnings_daily ";
     $sql .= " WHERE yymmdd >='".$current_yymm."-16'";
     $sql .= " AND   yymmdd <='$current_month_end'";
     $sql .= " AND member_id='$member_id'";
     $result = mysql_query($sql,$db);

     // printf("SQL: %s<br>\n",$sql);
     // printf("ERR: %s<br>\n",mysql_error());

     if (($result) && mysql_num_rows($result) > 0)
       {
         if ($myrow=mysql_fetch_array($result,MYSQL_NUM))
           {
              $yymmdd    = $current_yymm."-15";
              $yymm      = $current_yymm;
              $dateArray = dateToArray($yymmdd);

              $stub = array (
                 "yymmdd"         => $yymmdd,
                 "yymm"           => $yymm,
                 "year"           => $dateArray["year"],
                 "month"          => $dateArray["month"],
                 "day"            => $dateArray["day"],
                 "sales_pro"      => (int) $myrow[0],
                 "earnings_pro"   => number_format($myrow[1],2,".",""),
                 "sales_elite"    => (int) $myrow[2],
                 "earnings_elite" => number_format($myrow[3],2,".",""),
                 "bonus_count"    => (int) $myrow[4],
                 "bonus_amount"   => number_format($myrow[5],2,".",""),
                 "returns_count"  => (int) $myrow[6],
                 "returns_amount" => number_format($myrow[7],2,".",""),
              );
              $stub["count_totals"]  = $stub["sales_pro"]    + $stub["sales_elite"]    + $stub["bonus_count"]  - $stub["returns_count"];
              $stub["amount_totals"] = $stub["earnings_pro"] + $stub["earnings_elite"] + $stub["bonus_amount"] + $stub["returns_amount"];

              $paystubs[$yymm]["P2"] = $stub;
          }
       }
  }

// ksort($paystubs);
// printf("<PRE>%s\n</PRE><br>\n",print_r($paystubs,TRUE));




//----------- Now Pull it all together for the number of Months we are displaying

for ($year=$Index_FirstYear, $firstMonth=$Index_FirstMonth;  $year<=$Index_LastYear;  $year++, $firstMonth=1)
  {
    $yearStartAsArray  = array("month"=>1, "day"=>1, "year"=>$year);
    $yearStart         = dateArrayToString($yearStartAsArray);


    $ytd_sales_pro       = 0;
    $ytd_earnings_pro    = 0;
    $ytd_sales_elite     = 0;
    $ytd_earnings_elite  = 0;
    $ytd_bonus_count     = 0;
    $ytd_bonus_amount    = 0;
    $ytd_returns_count   = 0;
    $ytd_returns_amount  = 0;
    $ytd_count_totals    = 0;
    $ytd_amount_totals   = 0;

    for ($month=$firstMonth; $month<=12; $month++)
      {
         $yymm         = sprintf("%04d-%02d", $year, $month);

         $p1_yymmdd    = sprintf("%04d-%02d-%02d", $year, $month, 15);
         $p1_dateArray = dateToArray($p1_yymmdd);

         $calData      = calendar($p1_dateArray);
         $p2_yymmdd    = sprintf("%04d-%02d-%02d", $year, $month, $calData["DaysInMonth"]);
         $p2_dateArray = dateToArray($p2_yymmdd);


                 /* First Monthly Pay Period */
         if (isset($paystubs[$yymm]["P1"]))
           {
             $stub = $paystubs[$yymm]["P1"];
             $ytd_sales_pro      += $stub["sales_pro"];
             $ytd_earnings_pro   += $stub["earnings_pro"];
             $ytd_sales_elite    += $stub["sales_elite"];
             $ytd_earnings_elite += $stub["earnings_elite"];
             $ytd_bonus_count    += $stub["bonus_count"];
             $ytd_bonus_amount   += $stub["bonus_amount"];
             $ytd_returns_count  += $stub["returns_count"];
             $ytd_returns_amount += $stub["returns_amount"];
             $ytd_count_totals   += $stub["count_totals"];
             $ytd_amount_totals  += $stub["amount_totals"];
           }
         else
           {
             $stub = array (
                "yymmdd"         => $p1_yymmdd,
                "yymm"           => $yymm,
                "year"           => $p1_dateArray["year"],
                "month"          => $p1_dateArray["month"],
                "day"            => $p1_dateArray["day"],
                "sales_pro"      => 0,
                "earnings_pro"   => 0,
                "sales_elite"    => 0,
                "earnings_elite" => 0,
                "bonus_count"    => 0,
                "bonus_amount"   => 0,
                "returns_count"  => 0,
                "returns_amount" => 0,
                "count_totals"   => 0,
                "amount_totals"  => 0,
             );
             $paystubs[$yymm]["P1"]=$stub;
           }

                 /* Second Monthly Pay Period */
         if (isset($paystubs[$yymm]["P2"]))
           {
             $stub = $paystubs[$yymm]["P2"];
             $ytd_sales_pro      += $stub["sales_pro"];
             $ytd_earnings_pro   += $stub["earnings_pro"];
             $ytd_sales_elite    += $stub["sales_elite"];
             $ytd_earnings_elite += $stub["earnings_elite"];
             $ytd_bonus_count    += $stub["bonus_count"];
             $ytd_bonus_amount   += $stub["bonus_amount"];
             $ytd_returns_count  += $stub["returns_count"];
             $ytd_returns_amount += $stub["returns_amount"];
             $ytd_count_totals   += $stub["count_totals"];
             $ytd_amount_totals  += $stub["amount_totals"];
           }
         else
           {
             $stub = array (
                "yymmdd"         => $p2_yymmdd,
                "yymm"           => $yymm,
                "year"           => $p2_dateArray["year"],
                "month"          => $p2_dateArray["month"],
                "day"            => $p2_dateArray["day"],
                "sales_pro"      => 0,
                "earnings_pro"   => 0,
                "sales_elite"    => 0,
                "earnings_elite" => 0,
                "bonus_count"    => 0,
                "bonus_amount"   => 0,
                "returns_count"  => 0,
                "returns_amount" => 0,
                "count_totals"   => 0,
                "amount_totals"  => 0,
             );
             $paystubs[$yymm]["P2"]=$stub;
           }


         $paystubs[$yymm]["YTD"] = array(
            "yymmdd"         => $p2_yymmdd,
            "yymm"           => $yymm,
            "year"           => $p2_dateArray["year"],
            "month"          => $p2_dateArray["month"],
            "day"            => $p2_dateArray["day"],
            "sales_pro"      => $ytd_sales_pro,
            "earnings_pro"   => $ytd_earnings_pro,
            "sales_elite"    => $ytd_sales_elite,
            "earnings_elite" => $ytd_earnings_elite,
            "bonus_count"    => $ytd_bonus_count,
            "bonus_amount"   => $ytd_bonus_amount,
            "returns_count"  => $ytd_returns_count,
            "returns_amount" => $ytd_returns_amount,
            "count_totals"   => $ytd_count_totals,
            "amount_totals"  => $ytd_amount_totals,
         );
        ksort($paystubs[$yymm]);

        if ($yymm == $yymmToday)
           break;
      }
  }
krsort($paystubs);
$PAYSTUB_COUNT=count($paystubs);

// echo("<PRE>\n");
// foreach($paystubs AS $yymm => $period)
//   {
//     echo("===================== $yymm ============================<br>");
//     print_r($period);
//   }
// echo("</PRE>\n");
// exit;
//
// printf("<PRE>%s\n</PRE><br>\n",print_r($paystubs,TRUE));

?>

<div align=right style="margin: -41px 0 0 640px;">
  <a href=javascript:openVideo('http://pushyads.com/members/reports-sales.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
</div>

<table width=680 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF">
      <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
        <tr>
          <td class="text">

            <b class=red>HOLY COW, <?php echo $firstname?>!</b> Look at the gobs of money you've made from telling the world to
            <b>GET</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">&#8482 What? You don't see any? Well, come on then!
            <b>GO GET</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">&#8482 He's dying to shower you with gobs and gobs
            of money. It's just too easy! If you're not sure how, <a href="javascript: void(0)">CLICK HERE</a>

            <table width="100%" align=center border="0" cellspacing="0" cellpadding="0" class="text red bold" style="margin: 20px 0 5px 0;">
              <tr>
                <td width="60%" class="black">Commissions you've earned by <b>GETTING</b>
                  <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -2px">&#8482</td>
                <td width="20%" align=right valign=bottom><b>SALES</b></td>
                <td width="20%" align=right  valign=bottom style="padding-right: 27px;"><b>EARNINGS</b></td>
              </tr>
            </table>

            <table width="100%" border=0 cellspacing="0" cellpadding="0" style="border-top: 1px solid #CFF0C7;">

            <?php
               $COUNT=-1;
               foreach($paystubs AS $yymm => $period)
                 {
                    // echo("===================== $yymm ============================<br>");
                    // print_r($period);

                    $COUNT++;

                    if ($yymm == $current_yymm)
                      $display="";
                    else
                      $display="none";

                    $P1  = $period["P1"];
                    $P2  = $period["P2"];
                    $YTD = $period["YTD"];

                    $p1_stub  = $period["P1"];
                    $p2_stub  = $period["P2"];
                    $ytd_stub = $period["YTD"];

                    $p1_yymmdd    = $p1_stub["yymmdd"];
                    $p1_dateArray = dateToArray($p1_yymmdd);

                    $calData      = calendar($p1_dateArray);
                    $p2_yymmdd    = $p1_stub["yymmdd"];
                    $p2_dateArray = dateToArray($p2_yymmdd);

                    $tot_stub                     = $p2_stub;       // Start with the P2 Stub (we want the dates) and Add P1 Stub Data to it
                    $tot_stub["sales_pro"]       += $p1_stub["sales_pro"];
                    $tot_stub["earnings_pro"]    += $p1_stub["earnings_pro"];
                    $tot_stub["sales_elite"]     += $p1_stub["sales_elite"];
                    $tot_stub["earnings_elite"]  += $p1_stub["earnings_elite"];
                    $tot_stub["bonus_count"]     += $p1_stub["bonus_count"];
                    $tot_stub["bonus_amount"]    += $p1_stub["bonus_amount"];
                    $tot_stub["returns_count"]   += $p1_stub["returns_count"];
                    $tot_stub["returns_amount"]  += $p1_stub["returns_amount"];
                    $tot_stub["count_totals"]    += $p1_stub["count_totals"];
                    $tot_stub["amount_totals"]   += $p1_stub["amount_totals"];

            ?>
                    <tr>
                      <td style="border: 1px solid #CFF0C7;">
                        <table width=100% class="text darkgray aff_rpts4" cellspacing=3 cellpadding=0>
                          <tr height=22>
                            <td width="60%">&nbsp;&nbsp;<b><a href=javascript:sales_report_monthClicked(<?php echo $COUNT?>,'<?php echo $yymm?>','<?php echo $previous_yymm?>','<?php echo $current_yymm?>')><?php echo $month_names[$p1_stub["month"]-1]?>, <?php echo $p1_stub["year"]?></a></b></td>
                            <td width="21%" align=right style="padding-right: 2px;"><b><?php echo $tot_stub["count_totals"]?></b></td>
                            <td width="19%" align=right style="padding-right: 22px;" class="<?php echo ($tot_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["amount_totals"])?></b></td>
                          </tr>
                        </table>
                      </td>
                    </tr>

            <?php
                    if ($yymm < $previous_yymm)
                      {
            ?>
                            <!--- PRIOR MONTH --- TOTALS --->
                            <tr ID="Month-<?php echo $COUNT?>" style="display:<?php echo $display?>">
                              <td>
                                <table  align=right width=596 border=0 cellspacing=0 cellpadding=0" style="padding-right: 17px;">
                                  <tr>
                                    <td width=596>
                                      <table width=596 class="smalltext black" border=0 cellspacing=0 cellpadding=3 style="border-left: 1px solid #FFCC00; border-right: 1px solid #FFCC00;">
                                        <tr height=28 valign="middle" bgcolor="#DEE2E7">
                                          <td width="11%" class=aff_rpts2><b>Day</b></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="PRO Membership Signups"><b>PRO</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="ELITE Membership Signups"><b>ELITE</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="ELITE Infinity Bonus"><b>INFINITY</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="PUSHY Network Ad Sales"><b>PUSHY</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Adjustments, chargebacks, returns"><b>RETURNS</b></a></td>
                                          <td width="19%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="TOTAL Sales"><b>TOTALS</b></a></td>
                                        </tr>

                                        <tr valign="middle"  bgcolor=#FDFFFC>
                                          <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>
                                            <a onClick="return false" style="cursor:help; text-decoration:none" title="Totals from 1st to 15th of month">1st<span class=size14> &frac12;</span></a></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["sales_pro"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["sales_elite"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["bonus_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b>~</b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["returns_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["count_totals"]?></b></td>
                                        </tr>
                                        <tr valign="middle" bgcolor=#F6FCF5>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["earnings_pro"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["earnings_elite"] )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["bonus_amount"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b>$0.00</b></td>
                                          <td class="aff_rpts3 <?php echo ($p1_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($p1_stub["returns_amount"] )?></b></td>
                                          <td class="aff_rpts3 <?php echo ($p1_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($p1_stub["amount_totals"]   )?></b></td>
                                        </tr>

                                        <tr>
                                          <td colspan=8 height=10 bgcolor=#E4E8EB></td>
                                        </tr>

                                        <tr valign="middle"  bgcolor=#FDFFFC>
                                          <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>
                                            <a onClick="return false" style="cursor:help; text-decoration:none" title="Totals from 16th to last day of month">2nd<span class=size14> &frac12;</span></a></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["sales_pro"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["sales_elite"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["bonus_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b>~</b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["returns_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["count_totals"]?></b></td>
                                        </tr>
                                        <tr valign="middle" bgcolor=#F6FCF5>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["earnings_pro"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["earnings_elite"] )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["bonus_amount"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b>$0.00</b></td>
                                          <td class="aff_rpts3 <?php echo ($p2_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($p2_stub["returns_amount"] )?></b></td>
                                          <td class="aff_rpts3 <?php echo ($p2_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($p2_stub["amount_totals"]   )?></b></td>
                                        </tr>

                                        <tr>
                                          <td colspan=8 bgcolor=#E4E8EB style="border-bottom: 1px solid #BFC4D0;">&nbsp;</td>
                                        </tr>

                                        <tr valign="middle" bgcolor=#FDFFFC>
                                          <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>TOT</td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["sales_pro"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["sales_elite"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["bonus_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b>~</b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["returns_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["count_totals"]?></b></td>
                                        </tr>
                                        <tr valign="middle" bgcolor=#F6FCF5>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["earnings_pro"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["earnings_elite"] )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["bonus_amount"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b>$0.00</b></td>
                                          <td class="aff_rpts3 <?php echo ($tot_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["returns_amount"] )?></b></td>
                                          <td class="aff_rpts3 <?php echo ($tot_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["amount_totals"]   )?></b></td>
                                        </tr>
                                      </table>
                                      <div align=right><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=575 height=31></div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <!---------- END PRIOR MONTH --- TOTALS --->

            <?php
                         }
                      else
                      if ($yymm == $previous_yymm)
                         {
            ?>
                            <!---------- BEGIN "DAILY" SALES TAB ---- PREVIOUS MONTH ------>
                            <tr ID="Month-<?php echo $COUNT?>" style="display:<?php echo $display?>">
                              <td>
                                <div style="float: right; width: 613px; height: 28px;">
                                <table width=613 border=0 cellspacing=0 cellpadding=0">
                                  <tr>
                                    <td width=596>
                                      <table width=596 class="smalltext black" cellspacing=0 cellpadding=6 style="border-left: 1px solid #FFCC00; border-right: 1px solid #FFCC00; border-collapse: collapse;">
                                        <tr height=28 valign="middle" bgcolor="#DEE2E7">
                                          <td width="11%" class=aff_rpts2><b>Day</b></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="PRO Membership Signups"><b>PRO</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="ELITE Membership Signups"><b>ELITE</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="ELITE Infinity Bonus"><b>INFINITY</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="PUSHY Network Ad Sales"><b>PUSHY</b></a></td>
                                          <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Adjustments, chargebacks, returns"><b>RETURNS</b></a></td>
                                          <td width="19%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="TOTAL Sales"><b>TOTALS</b></a></td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td width=17><img src="http://pds1106.s3.amazonaws.com/images/bar.png" width="17" height="28"></td>
                                  </tr>
                                </table>
                                </div>

                                <div style="float: right; width: 612px; max-height: 302px; height: auto; height: 302px; overflow-y: scroll; border-bottom: 1px solid #FFCC00; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

                                <table width=595 align=right border=0 cellspacing=0 cellpadding=0  style="border-right: 1px solid #FFCC00;">
                                  <tr>
                                    <td>
                                      <table width=595 class="smalltext black" border=0 cellspacing=0 cellpadding=03>
                                         <?php

                                            $dailyTargetMonthAsArray = $previousMonthAsArray;

                                            $monthStart = sprintf("%04d-%02d-%02d",$dailyTargetMonthAsArray["year"],$dailyTargetMonthAsArray["month"],  1);
                                            $monthEnd   = sprintf("%04d-%02d-%02d",$dailyTargetMonthAsArray["year"],$dailyTargetMonthAsArray["month"], 31);

                                            $earnings=array();
                                            $sql  = "SELECT * from earnings_daily ";
                                            $sql .= " WHERE yymmdd>='$monthStart'";
                                            $sql .= " AND   yymmdd<='$monthEnd'";
                                            $sql .= " AND member_id='$member_id'";
                                            $sql .= " ORDER by 'yymmdd'";
                                            $result = mysql_query($sql,$db);

                                               // printf("SQL: %s<br>\n",$sql);
                                               // printf("ERR: %s<br>\n",mysql_error());
                                               // printf("ROWS: %s<br>\n",mysql_num_rows($result));
                                            if (($result) && mysql_num_rows($result) > 0)
                                              {
                                                while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
                                                  {
                                                    $yymmdd=$myrow["yymmdd"];
                                                    $earnings[$yymmdd] = $myrow;
                                                  }
                                              }

                                            for ($day=1; $day<=$days_previousMonth; $day++)
                                              {
                                                $date=sprintf("%04d-%02d-%02d", $dailyTargetMonthAsArray["year"],$dailyTargetMonthAsArray["month"],$day);
                                                if (isset($earnings[$date]))
                                                  {
                                                     $myrow = $earnings[$date];
                                                     $sales_pro         = $myrow["sales_pro"];
                                                     $earnings_pro      = $myrow["earnings_pro"];
                                                     $sales_elite       = $myrow["sales_elite"];
                                                     $earnings_elite    = $myrow["earnings_elite"];
                                                     $bonus_count       = $myrow["bonus_count"];
                                                     $bonus_amount      = $myrow["bonus_amount"];
                                                     $returns_count     = $myrow["returns_count"];
                                                     $returns_amount    = $myrow["returns_amount"];
                                                     $count_totals      = $sales_pro    + $sales_elite    + $bonus_count   - $returns_count;
                                                     $amount_totals     = $earnings_pro + $earnings_elite + $bonus_amount  + $returns_amount; //-- (Note: returns_amount is already Negative so ADD
                                                  }
                                                else
                                                  {
                                                     $sales_pro         = 0;
                                                     $earnings_pro      = 0;
                                                     $sales_elite       = 0;
                                                     $earnings_elite    = 0;
                                                     $bonus_count       = 0;
                                                     $bonus_amount      = 0;
                                                     $returns_count     = 0;
                                                     $returns_amount    = 0;
                                                     $count_totals      = 0;
                                                     $amount_totals     = 0;
                                                  }
                                         ?>
                                                <tr valign="middle" bgcolor=#FDFFFC>
                                                  <td width="11%" rowspan="2" class=aff_rpts2 bgcolor=#EFF5ED><?php echo $day?></td>
                                                  <td width="14%" class="aff_rpts darkred"><?php echo $sales_pro?></td>
                                                  <td width="14%" class="aff_rpts darkred"><?php echo $sales_elite?></td>
                                                  <td width="14%" class="aff_rpts darkred"><?php echo $bonus_count?></td>
                                                  <td width="14%" class="aff_rpts darkred">~</td>
                                                  <td width="14%" class="aff_rpts darkred"><?php echo $returns_count?></td>
                                                  <td width="19%" class="aff_rpts darkred"><?php echo $count_totals?></td>
                                                </tr>
                                                <tr valign="middle" bgcolor=#F6FCF5>
                                                  <td class="aff_rpts3 black"><?php echo dollar_format($earnings_pro )?></td>
                                                  <td class="aff_rpts3 black"><?php echo dollar_format($earnings_elite )?></td>
                                                  <td class="aff_rpts3 black"><?php echo dollar_format($bonus_amount )?></td>
                                                  <td class="aff_rpts3 black">$0.00</td>
                                                  <td class="aff_rpts3 <?php echo ($returns_amount<0)?"red":"black"?>"><?php echo dollar_format($returns_amount )?></td>
                                                  <td class="aff_rpts3 <?php echo ($amount_totals<0)?"red":"black"?>"><b><?php echo dollar_format($amount_totals )?></b></td>
                                                </tr>
                                         <?php
                                              }
                                         ?>

                                        <tr bgcolor=#FFFBF2 height=48>
                                          <td colspan=8 align=center style="border-bottom: 1px solid #BFC4D0; color:#299900" class="size16 bold">
                                            <img src="http://pds1106.s3.amazonaws.com/images/calculator_add.png" style="vertical-align: middle; margin-right: 20px;">
                                            <i>OK <?php echo $firstname?>, add it all up now!</i></td>
                                        </tr>

                                        <tr valign="middle"  bgcolor=#FDFFFC>
                                          <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>
                                            <a onClick="return false" style="cursor:help; text-decoration:none" title="Totals from 1st to 15th of month">1st<span class=size14> &frac12;</span></a></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["sales_pro"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["sales_elite"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["bonus_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b>~</b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["returns_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p1_stub["count_totals"]?></b></td>
                                        </tr>
                                        <tr valign="middle" bgcolor=#F6FCF5>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["earnings_pro"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["earnings_elite"] )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["bonus_amount"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b>$0.00</b></td>
                                          <td class="aff_rpts3 <?php echo ($p1_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($p1_stub["returns_amount"] )?></b></td>
                                          <td class="aff_rpts3 <?php echo ($p1_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($p1_stub["amount_totals"]   )?></b></td>
                                        </tr>

                                        <tr>
                                          <td colspan=8 height=10 bgcolor=#E4E8EB></td>
                                        </tr>

                                        <tr valign="middle"  bgcolor=#FDFFFC>
                                          <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>
                                            <a onClick="return false" style="cursor:help; text-decoration:none" title="Totals from 16th to last day of month">2nd<span class=size14> &frac12;</span></a></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["sales_pro"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["sales_elite"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["bonus_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b>~</b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["returns_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $p2_stub["count_totals"]?></b></td>
                                        </tr>
                                        <tr valign="middle" bgcolor=#F6FCF5>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["earnings_pro"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["earnings_elite"] )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["bonus_amount"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b>$0.00</b></td>
                                          <td class="aff_rpts3 <?php echo ($p2_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($p2_stub["returns_amount"] )?></b></td>
                                          <td class="aff_rpts3 <?php echo ($p2_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($p2_stub["amount_totals"]   )?></b></td>
                                        </tr>

                                        <tr>
                                          <td colspan=8 bgcolor=#E4E8EB style="border-bottom: 1px solid #BFC4D0;">&nbsp;</td>
                                        </tr>

                                        <tr valign="middle" bgcolor=#FDFFFC>
                                          <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>TOT</td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["sales_pro"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["sales_elite"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["bonus_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b>~</b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["returns_count"]?></b></td>
                                          <td class="aff_rpts darkred"><b><?php echo $tot_stub["count_totals"]?></b></td>
                                        </tr>
                                        <tr valign="middle" bgcolor=#F6FCF5>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["earnings_pro"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["earnings_elite"] )?></b></td>
                                          <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["bonus_amount"]   )?></b></td>
                                          <td class="aff_rpts3 black"><b>$0.00</b></td>
                                          <td class="aff_rpts3 <?php echo ($tot_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["returns_amount"] )?></b></td>
                                          <td class="aff_rpts3 <?php echo ($tot_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["amount_totals"]   )?></b></td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                                </div>

                                <div align=right><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=575 height=31></div>

                              </td>
                            </tr>
                            <!---------- END "DAILY" SALES - PREVIOUS MONTH --->
            <?php
                         }
                      else
                      if ($yymm == $current_yymm)
                         {
            ?>
                            <!---------- BEGIN "DAILY" SALES TAB - CURRENT MONTH --------->
                            <tr ID="Month-<?php echo $COUNT?>" style="display:<?php echo $display?>">
                              <td>

                                <div style="position: absolute;">
                                  <table bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0 style="width: 33px; height:166px;">
                                    <tr>
                                      <td width=10></td>
                                      <td width=23 valign=top>
                                         <img ID="Image-Daily-<?php echo $COUNT?>" src="http://pds1106.s3.amazonaws.com/images/sales_daily_active.png" onClick=javascript:sales_report_dailyClicked(<?php echo $COUNT?>,'<?php echo $yymm?>','<?php echo $previous_yymm?>','<?php echo $current_yymm?>')><img ID="Image-Summary-<?php echo $COUNT?>" src="http://pds1106.s3.amazonaws.com/images/sales_summary_inactive.png" onClick=javascript:sales_report_summaryClicked(<?php echo $COUNT?>,'<?php echo $yymm?>','<?php echo $previous_yymm?>','<?php echo $current_yymm?>')><div align=right><img src="http://pds1106.s3.amazonaws.com/images/shadow_16.gif"></div>
                                      </td>
                                    </tr>
                                  </table>
                                </div>

                                <div ID="Daily-<?php echo $COUNT?>">
                                     <div style="float: right; width: 613px; height: 28px;">
                                     <table width=613 border=0 cellspacing=0 cellpadding=0">
                                       <tr>
                                         <td width=596>
                                           <table width=596 class="smalltext black" cellspacing=0 cellpadding=6 style="border-left: 1px solid #FFCC00; border-right: 1px solid #FFCC00; border-collapse: collapse;">
                                             <tr height=28 valign="middle" bgcolor="#DEE2E7">
                                               <td width="11%" class=aff_rpts2><b>Day</b></td>
                                               <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="PRO Membership Signups"><b>PRO</b></a></td>
                                               <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="ELITE Membership Signups"><b>ELITE</b></a></td>
                                               <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="ELITE Infinity Bonus"><b>INFINITY</b></a></td>
                                               <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="PUSHY Network Ad Sales"><b>PUSHY</b></a></td>
                                               <td width="14%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Adjustments, chargebacks, returns"><b>RETURNS</b></a></td>
                                               <td width="19%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="TOTAL Sales"><b>TOTALS</b></a></td>
                                             </tr>
                                           </table>
                                         </td>
                                         <td width=17><img src="http://pds1106.s3.amazonaws.com/images/bar.png"></td>
                                       </tr>
                                     </table>
                                     </div>

                                     <div style="float: right; width: 612px; max-height: 302px; height: auto; height: 302px; overflow-y: scroll; border-bottom: 1px solid #FFCC00; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">
                                     <table width=595 align=right border=0 cellspacing=0 cellpadding=0  style="border-right: 1px solid #FFCC00;">
                                       <tr>
                                         <td>
                                           <table width=595 class="smalltext black" border=0 cellspacing=0 cellpadding=03>
                                              <?php

                                                 $dailyTargetMonthAsArray = $currentMonthAsArray;

                                                 $monthStart = sprintf("%04d-%02d-%02d",$dailyTargetMonthAsArray["year"],$dailyTargetMonthAsArray["month"],  1);
                                                 $monthEnd   = sprintf("%04d-%02d-%02d",$dailyTargetMonthAsArray["year"],$dailyTargetMonthAsArray["month"], 31);

                                                 $earnings=array();
                                                 $sql  = "SELECT * from earnings_daily ";
                                                 $sql .= " WHERE yymmdd>='$monthStart'";
                                                 $sql .= " AND   yymmdd<='$monthEnd'";
                                                 $sql .= " AND member_id='$member_id'";
                                                 $sql .= " ORDER by 'yymmdd'";
                                                 $result = mysql_query($sql,$db);

                                                    // printf("SQL: %s<br>\n",$sql);
                                                    // printf("ERR: %s<br>\n",mysql_error());
                                                    // printf("ROWS: %s<br>\n",mysql_num_rows($result));
                                                 if (($result) && mysql_num_rows($result) > 0)
                                                   {
                                                     while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
                                                       {
                                                         $yymmdd=$myrow["yymmdd"];
                                                         $earnings[$yymmdd] = $myrow;
                                                       }
                                                   }

                                                 for ($day=1; $day<=$dayToday; $day++)
                                                   {
                                                     $date=sprintf("%04d-%02d-%02d", $dailyTargetMonthAsArray["year"],$dailyTargetMonthAsArray["month"],$day);
                                                     if (isset($earnings[$date]))
                                                       {
                                                          $myrow = $earnings[$date];
                                                          $sales_pro         = $myrow["sales_pro"];
                                                          $earnings_pro      = $myrow["earnings_pro"];
                                                          $sales_elite       = $myrow["sales_elite"];
                                                          $earnings_elite    = $myrow["earnings_elite"];
                                                          $bonus_count       = $myrow["bonus_count"];
                                                          $bonus_amount      = $myrow["bonus_amount"];
                                                          $returns_count     = $myrow["returns_count"];
                                                          $returns_amount    = $myrow["returns_amount"];
                                                          $count_totals      = $sales_pro    + $sales_elite    + $bonus_count   - $returns_count;
                                                          $amount_totals     = $earnings_pro + $earnings_elite + $bonus_amount  + $returns_amount; //-- (Note: returns_amount is already Negative so ADD
                                                       }
                                                     else
                                                       {
                                                          $sales_pro         = 0;
                                                          $earnings_pro      = 0;
                                                          $sales_elite       = 0;
                                                          $earnings_elite    = 0;
                                                          $bonus_count       = 0;
                                                          $bonus_amount      = 0;
                                                          $returns_count     = 0;
                                                          $returns_amount    = 0;
                                                          $count_totals      = 0;
                                                          $amount_totals     = 0;
                                                       }
                                              ?>
                                                     <tr valign="middle" bgcolor=#FDFFFC>
                                                       <td width="11%" rowspan="2" class=aff_rpts2 bgcolor=#EFF5ED><?php echo $day?></td>
                                                       <td width="14%" class="aff_rpts darkred"><?php echo $sales_pro?></td>
                                                       <td width="14%" class="aff_rpts darkred"><?php echo $sales_elite?></td>
                                                       <td width="14%" class="aff_rpts darkred"><?php echo $bonus_count?></td>
                                                       <td width="14%" class="aff_rpts darkred">~</td>
                                                       <td width="14%" class="aff_rpts darkred"><?php echo $returns_count?></td>
                                                       <td width="19%" class="aff_rpts darkred"><?php echo $count_totals?></td>
                                                     </tr>
                                                     <tr valign="middle" bgcolor=#F6FCF5>
                                                       <td class="aff_rpts3 black"><?php echo dollar_format($earnings_pro )?></td>
                                                       <td class="aff_rpts3 black"><?php echo dollar_format($earnings_elite )?></td>
                                                       <td class="aff_rpts3 black"><?php echo dollar_format($bonus_amount )?></td>
                                                       <td class="aff_rpts3 black">$0.00</td>
                                                       <td class="aff_rpts3 <?php echo ($returns_amount<0)?"red":"black"?>"><?php echo dollar_format($returns_amount )?></td>
                                                       <td class="aff_rpts3 <?php echo ($amount_totals<0)?"red":"black"?>"><b><?php echo dollar_format($amount_totals )?></b></td>
                                                     </tr>
                                              <?php
                                                   }
                                              ?>

                                             <tr bgcolor=#FFFBF2 height=48>
                                               <td colspan=8 align=center style="border-bottom: 1px solid #BFC4D0; color:#299900" class="size16 bold">
                                                 <img src="http://pds1106.s3.amazonaws.com/images/calculator_add.png" style="vertical-align: middle; margin-right: 20px;">
                                                 <i>OK <?php echo $firstname?>, add it all up now!</i></td>
                                             </tr>

                                             <tr valign="middle"  bgcolor=#FDFFFC>
                                               <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>
                                                 <a onClick="return false" style="cursor:help; text-decoration:none" title="Totals from 1st to 15th of month">1st<span class=size14> &frac12;</span></a></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p1_stub["sales_pro"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p1_stub["sales_elite"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p1_stub["bonus_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b>~</b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p1_stub["returns_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p1_stub["count_totals"]?></b></td>
                                             </tr>
                                             <tr valign="middle" bgcolor=#F6FCF5>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["earnings_pro"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["earnings_elite"] )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($p1_stub["bonus_amount"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b>$0.00</b></td>
                                               <td class="aff_rpts3 <?php echo ($p1_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($p1_stub["returns_amount"] )?></b></td>
                                               <td class="aff_rpts3 <?php echo ($p1_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($p1_stub["amount_totals"]   )?></b></td>
                                             </tr>

                                             <tr>
                                               <td colspan=8 height=10 bgcolor=#E4E8EB></td>
                                             </tr>

                                             <tr valign="middle"  bgcolor=#FDFFFC>
                                               <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>
                                                 <a onClick="return false" style="cursor:help; text-decoration:none" title="Totals from 16th to last day of month">2nd<span class=size14> &frac12;</span></a></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p2_stub["sales_pro"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p2_stub["sales_elite"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p2_stub["bonus_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b>~</b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p2_stub["returns_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $p2_stub["count_totals"]?></b></td>
                                             </tr>
                                             <tr valign="middle" bgcolor=#F6FCF5>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["earnings_pro"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["earnings_elite"] )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($p2_stub["bonus_amount"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b>$0.00</b></td>
                                               <td class="aff_rpts3 <?php echo ($p2_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($p2_stub["returns_amount"] )?></b></td>
                                               <td class="aff_rpts3 <?php echo ($p2_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($p2_stub["amount_totals"]   )?></b></td>
                                             </tr>

                                             <tr>
                                               <td colspan=8 bgcolor=#E4E8EB style="border-bottom: 1px solid #BFC4D0;">&nbsp;</td>
                                             </tr>

                                             <tr valign="middle" bgcolor=#FDFFFC>
                                               <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>TOT</td>
                                               <td class="aff_rpts darkred"><b><?php echo $tot_stub["sales_pro"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $tot_stub["sales_elite"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $tot_stub["bonus_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b>~</b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $tot_stub["returns_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $tot_stub["count_totals"]?></b></td>
                                             </tr>
                                             <tr valign="middle" bgcolor=#F6FCF5>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["earnings_pro"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["earnings_elite"] )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($tot_stub["bonus_amount"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b>$0.00</b></td>
                                               <td class="aff_rpts3 <?php echo ($tot_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["returns_amount"] )?></b></td>
                                               <td class="aff_rpts3 <?php echo ($tot_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($tot_stub["amount_totals"]   )?></b></td>
                                             </tr>

                                             <tr>
                                               <td colspan=8 bgcolor=#E4E8EB style="border-bottom: 1px solid #BFC4D0;">&nbsp;</td>
                                             </tr>

                                             <tr valign="middle" bgcolor=#FDFFFC>
                                               <td rowspan="2" class="aff_rpts2 red" bgcolor=#EFF5ED>YTD</td>
                                               <td class="aff_rpts darkred"><b><?php echo $ytd_stub["sales_pro"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $ytd_stub["sales_elite"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $ytd_stub["bonus_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b>~</b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $ytd_stub["returns_count"]?></b></td>
                                               <td class="aff_rpts darkred"><b><?php echo $ytd_stub["count_totals"]?></b></td>
                                             </tr>
                                             <tr valign="middle" bgcolor=#F6FCF5>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($ytd_stub["earnings_pro"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($ytd_stub["earnings_elite"] )?></b></td>
                                               <td class="aff_rpts3 black"><b><?php echo dollar_format($ytd_stub["bonus_amount"]   )?></b></td>
                                               <td class="aff_rpts3 black"><b>$0.00</b></td>
                                               <td class="aff_rpts3 <?php echo ($ytd_stub["returns_amount"]<0)?"red":"black"?>"><b><?php echo dollar_format($ytd_stub["returns_amount"] )?></b></td>
                                               <td class="aff_rpts3 <?php echo ($ytd_stub["amount_totals"]<0)?"red":"black"?>"><b><?php echo dollar_format($ytd_stub["amount_totals"]   )?></b></td>
                                             </tr>
                                           </table>
                                         </td>
                                       </tr>
                                     </table>
                                     </div>
                                </div>

                                <!---------- BEGIN "SUMMARY" SALES TAB - CURRENT MONTH --->
                                <div ID="Summary-<?php echo $COUNT?>" style="display:none">
                                     <div style="float: right; width: 613px; height: 28px;">
                                     <table width=613 align=right border=0 cellspacing=0 cellpadding=0>
                                       <tr>
                                         <td width=596>
                                           <table width=596 align=right class="smalltext black" border=0 cellspacing=0 cellpadding=3 style="border-right: 1px solid #FFCC00; border-left: 1px solid #FFCC00; border-collapse: collapse;">
                                             <tr height=28 valign="middle" bgcolor="#DEE2E7">
                                               <td width="12%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Pay period ending on 15th or last day of month"><b>PERIOD</b></a></td>
                                               <td width="12%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Total PRO membership sales"><b>PRO</b></a></td>
                                               <td width="12%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Total ELITE membership sales"><b>ELITE</b></a></td>
                                               <td width="12%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Total INFINITY Bonus sales"><b>INFINITY</b></a></td>
                                               <td width="12%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Total PUSHY Network Ad sales"><b>PUSHY</b></a></td>
                                               <td width="13%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Total refunds, chargebacks, or adjustments"><b>RETURNS</b></a></td>
                                               <td width="15%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Total Earnings for this week"><b>TOTAL</b></a></td>
                                               <td width="12%" class=aff_rpts3><a onClick="return false" style="cursor:help; text-decoration:none" title="Date payment WILL BE or HAS BEEN paid"><b>PAID</b></a></td>
                                             </tr>
                                           </table>
                                         </td>
                                         <td width=17><img src="http://pds1106.s3.amazonaws.com/images/bar.png"></td>
                                       </tr>
                                     </table>
                                     </div>

                                     <div style="float: right; width: 612px; max-height: 304px; height: auto; height: 304px; overflow-y: scroll; border-bottom: 1px solid #FFCC00; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

                                     <table width=595 align=right border=0 cellspacing=0 cellpadding=0>
                                       <tr>
                                         <td>
                                           <table width=595 align=right border=0 cellspacing=0 cellpadding=0 class=smalltext style="border-right: 1px solid #FFCC00; border-bottom: 1px solid #FFCC00; border-collapse: collapse;">

                                             <?php

                                                $dates=array();
                                                for ($year=$Index_FirstYear, $firstMonth=$Index_FirstMonth;  $year<=$Index_LastYear;  $year++,$firstMonth=1)
                                                  {
                                                    for ($month=$firstMonth; $month<=12; $month++)
                                                      {
                                                         $p1_yymmdd    = sprintf("%04d-%02d-%02d", $year, $month, 15);
                                                         if ($p1_yymmdd >= $current_yymmdd)
                                                            break;
                                                         $dates[]=$p1_yymmdd;

                                                         $calData      = calendar($p1_dateArray);
                                                         $p2_yymmdd    = sprintf("%04d-%02d-%02d", $year, $month, $calData["DaysInMonth"]);
                                                         if ($p2_yymmdd >= $current_yymmdd)
                                                            break;
                                                         $dates[]=$p2_yymmdd;
                                                      }
                                                   }

                                                //print_r($dates);

                                                for ($j=count($dates)-1; $j>=0; $j--)
                                                  {
                                                    $yymmdd=$dates[$j];
                                                    $dateArray=dateToArray($yymmdd);
                                                    $yymm=substr($yymmdd,0,7);

                                                    $date_mmddyy = sprintf("%02d/%02d/%02d", $dateArray["month"],$dateArray["day"],$dateArray["year"]%100);

                                                    if ($dateArray["day"]==15)
                                                      $stub  = $paystubs[$yymm]["P1"];
                                                    else
                                                      $stub  = $paystubs[$yymm]["P2"];

                                                    if ($stub["amount_totals"]==0)
                                                       $date_paid = "~";
                                                    else
                                                      {
                                                         $dateArray=calStepDays(5,$dateArray);
                                                         $date_paid = sprintf("%02d/%02d/%02d", $dateArray["month"],$dateArray["day"],$dateArray["year"]%100);
                                                      }

                                             ?>
                                                    <tr height=24 valign="middle" bgcolor=#FDFFFC>
                                                      <td width="12%" class="aff_rpts5"><?php echo $date_mmddyy?></td>
                                                      <td width="12%" class="aff_rpts"><?php echo dollar_format($stub["earnings_pro"])?></td>
                                                      <td width="12%" class="aff_rpts"><?php echo dollar_format($stub["earnings_elite"])?></td>
                                                      <td width="12%" class="aff_rpts"><?php echo dollar_format($stub["bonus_amount"])?></td>
                                                      <td width="12%" class="aff_rpts">~</td>
                                                      <td width="13%" class="aff_rpts <?php echo ($stub["returns_amount"]<0)?"red":"black"?>"><?php echo dollar_format($stub["returns_amount"])?></td>
                                                      <td width="15%" class="aff_rpts darkgreen"><?php echo dollar_format($stub["amount_totals"])?></td>
                                                      <td width="12%" class="aff_rpts"><?php echo $date_paid?></td>
                                                    </tr>
                                             <?php
                                                  }
                                             ?>

                                           </table>
                                         </td>
                                       </tr>

                                     </table>
                                     </div>
                                </div>

                                <div align=right><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=575 height=31></div>

                              </td>
                            </tr>
            <?php
                           $yearly_amount_totals = $ytd_amount_totals;
                         }

                }
            ?>




              <tr>   <!---------- YTD EARNINGS TOTAL ------------->
                <td>
                  &nbsp;<br>
                  <table width=100% class="size18 green" cellspacing=3 cellpadding=0 border=0>
                    <tr>
                      <td width="78%" align=right colspan=3 class=black><i><b>Total YTD Earnings</b></i>&nbsp;:</td>
                      <td width="22%" align=right style="padding-right:24px">&nbsp;<b><u><?php echo dollar_format($yearly_amount_totals)?></u></b> </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>

            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
