<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();

//
//---------------------------------------------------------------------------------

include_once("pushy_tracker.inc");


$db = getPushyDatabaseConnection();


$SYSTEM_WIDGET_KEY = getSystemWidgetKey($db);


$week_start_dates = tracker_dates();

//=================================================================================
// SELECT widget_key, count(*) from tracker_widget_category  GROUP BY widget_key;
// +-----------------+----------+
// | widget_key      | count(*) |
// +-----------------+----------+
// | aaaaaaaa@bob-1  |        2 |
// | bbbbbbbb@bob-2  |        1 |
// | iiiiiiii@mary-1 |        2 |
// | jjjjjjjj@mary-2 |        1 |
// | kkkkkkkk@mary-3 |        2 |
// | xxxxxxxx@jim-1  |        2 |
// | yyyyyyyy@jim-2  |        1 |
// | zzzzzzzz@jim-3  |        2 |
// +-----------------+----------+
//  N = 8   ==> 8 rows in set (0.00 sec)
//
//  W/1 Category =  3
//  W/2 Category =  5
//  Total        =  8
//=================================================================================

function per_getWidgetCounts($db, $member_id)
 {
    global $SYSTEM_WIDGET_KEY;

    $widgets_1_cat=0;
    $widgets_2_cat=0;

    $widgetCategories=array();

    $sql = " SELECT widget_key, COUNT(*) from tracker_widget_category WHERE member_id='$member_id' AND widget_key NOT LIKE '".$SYSTEM_WIDGET_KEY."%' GROUP BY widget_key";
    $result=mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $widget_key     = $myrow[0];
            $category_count = $myrow[1];
            $widgetCategories[$widget_key]=$category_count;

            if ($category_count==1)
              $widgets_1_cat++;
            else
              $widgets_2_cat++;
          }
      }
    $total_widgets = $widgets_1_cat + $widgets_2_cat;
    return array($widgets_1_cat, $widgets_2_cat, $total_widgets, $widgetCategories);
 }




function per_getWidgetCountByCategory($db,$member_id)
 {
    global $SYSTEM_WIDGET_KEY;
    $activeWidgets=0;
    $categories=array();

    $sql = " SELECT userkey, COUNT(*) from tracker_widget_category WHERE member_id='$member_id' AND widget_key NOT LIKE '".$SYSTEM_WIDGET_KEY."%'  GROUP by userkey";
    $result=mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $category       = $myrow[0];
            $widget_count   = $myrow[1];
            $categories[$category]=$widget_count;

            $activeWidgets += $widget_count;
          }
      }
    return array($activeWidgets, $categories);
 }



// CATEGORY        DAY 1 (H / C)     DAY 2 (H/C)     -->     TOTAL (H / C)
//  101   =>       202 /    27       202 /    27     -->     404 /    54
//  108   =>       152 /    20       152 /    20     -->     304 /    40
//  112   =>        12 /     1        12 /     1     -->      24 /     2
//  117   =>        12 /     1        12 /     1     -->      24 /     2
//  120   =>       204 /    25       204 /    25     -->     408 /    50
//  130   =>        87 /    14        87 /    14     -->     174 /    28


function per_daily_activity($db,$member_id)
  {
    global $SYSTEM_WIDGET_KEY;
    $category_activity=array();
    $hits=array();
    $clicks=array();
    $totalhits=0;
    $totalclicks=0;

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  userkey";
    $sql .= "  FROM tracker_widget_category";
    $sql .= "  WHERE member_id='$member_id' ";
    $sql .= "  AND   widget_key NOT LIKE '".$SYSTEM_WIDGET_KEY."%'  ";
    $sql .= "  GROUP BY userkey";
    $result=mysql_query($sql,$db);

    if (FALSE)
     {
       printf("SQL: %s<br>\n",$sql);
       printf("ERR: %s<br>\n",mysql_error());
     }

    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {

            $hits=array();
            $clicks=array();
            $totalhits=0;
            $totalclicks=0;

            $j=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $hits[$i]   = $myrow[$j];
                $totalhits+=$hits[$i];
              }
            for ($i=0; $i<35; $i++, $j++)
              {
                $clicks[$i] = $myrow[$j];
                $totalclicks+=$clicks[$i];
              }

            $category    = $myrow[70];

         // printf("%5s   =>     %5d / %5d     %5d / %5d     -->   %5d / %5d\n",
         //       $category,
         //       $hits[33],  $clicks[33],
         //       $hits[34],  $clicks[34],
         //       $totalhits, $totalclicks);

            $category_activity[$category] = array ("daily_hits"   => $hits,
                                                   "total_hits"   => $totalhits,
                                                   "daily_clicks" => $clicks,
                                                   "total_clicks" => $totalclicks);
          }
      }
    return $category_activity;
  }



function per_daily_totals($db, $member_id, $widgetCategories)
  {
    global $SYSTEM_WIDGET_KEY;
    $widget_activity=array();
    $hits=array();
    $clicks=array();
    $totalhits=0;
    $totalclicks=0;

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  widget_key";
    $sql .= "  FROM tracker_widget_category";
    $sql .= "  WHERE member_id='$member_id' ";
    $sql .= "  AND   widget_key NOT LIKE '".$SYSTEM_WIDGET_KEY."%'  ";
    $sql .= "  GROUP BY widget_key";
    $result=mysql_query($sql,$db);

    if (FALSE)
     {
       printf("SQL: %s<br>\n",$sql);
       printf("ERR: %s<br>\n",mysql_error());
     }

    if ($result)
      {
        $hitSum  =array();  // Column Totals
        $clickSum=array();
        for ($i=0; $i<35; $i++)
          {
             $hitSum[$i]   = 0;
             $clickSum[$i] = 0;
          }
        $hitSumTotal   = 0;
        $clickSumTotal = 0;
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {

            $hits=array();
            $clicks=array();
            $totalhits=0;
            $totalclicks=0;

            $widget_key = $myrow[70];
            $divisor=1;
            if (isset($widgetCategories[$widget_key]) && ($widgetCategories[$widget_key] == 2))
              $divisor=2;

            $j=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $hits[$i] = $myrow[$j];
                if ($divisor==2)
                  $hits[$i] = round($hits[$i] / 2);

                $totalhits+=$hits[$i];               // Row Totals (For Category)

                $hitSum[$i] += $hits[$i];            // Col Totals (For Day)

              }
            for ($i=0; $i<35; $i++, $j++)
              {
                $clicks[$i] = $myrow[$j];
                if ($divisor==2)
                  $clicks[$i] = round($clicks[$i] / 2);

                $totalclicks+=$clicks[$i];           // Row Totals (For Category)

                $clickSum[$i] += $clicks[$i];        // Col Totals (For Day)
              }

            $category    = $myrow[70];

            $widget_activity[$widget_key] = array ("daily_hits"   => $hits,
                                                   "total_hits"   => $totalhits,
                                                   "daily_clicks" => $clicks,
                                                   "total_clicks" => $totalclicks);
          }

        for ($i=0; $i<35; $i++)
          {
            $hitSumTotal   += $hitSum[$i];
            $clickSumTotal += $clickSum[$i];
          }
      }

    $totals =  array(
       "hitSum"        => $hitSum,
       "clickSum"      => $clickSum,
       "hitSumTotal"   => $hitSumTotal,
       "clickSumTotal" => $clickSumTotal
    );

    return array($widget_activity, $totals);
  }



$widgetInfo = per_getWidgetCounts($db,$mid);
list($widgets_1_cat,$widgets_2_cat,$total_widgets,$widgetCategories) = $widgetInfo;



// print_r($widgetInfo);
//
// printf("\n****** AAA ****************** \n");
// printf("Widgets - 1 Category   = %d\n", $widgets_1_cat);
// printf("Widgets - 2 Categories = %d\n", $widgets_2_cat);
// printf("Total Unique Widgets   = %d\n", $total_widgets);
// printf("Number Widget Categories (1/2) on a Per Widget basis\n");
// print_r($widgetCategories);
//


$categoryInfo = per_getWidgetCountByCategory($db,$mid);
list($activeWidgets, $categories) = $categoryInfo;


// print_r($categoryInfo);
//
// printf("\n****** BBB ****************** \n");
// printf("Active Widgets         = %d\n", $activeWidgets);
// printf("Total Unique Widget Hits Per Category\n");
// print_r($categories);
//




// printf("\n****** CCC ****************** \n");
$daily_activity =  per_daily_activity($db,$mid);
// print_r($daily_activity);

// foreach($daily_activity AS $category => $activity)
//   {
//     printf("Category: %-12s   HITS=%4d  CLICKS=%4d\n",$category, $activity["total_hits"], $activity["total_clicks"]);
//   }




// printf("\n****** DDD ****************** \n");
$daily_totals =  per_daily_totals($db,$mid,$widgetCategories);
list($widget_activity, $activity_totals)  =  $daily_totals;

// foreach($widget_activity AS $widget_key => $activity)
//   {
//     printf("Widget_Key: %-32s   HITS=%4d  CLICKS=%4d\n",$widget_key, $activity["total_hits"], $activity["total_clicks"]);
//   }
// printf("Day 33        :  %d / %d\n", $activity_totals["hitSum"][33],   $activity_totals["clickSum"][33]);
// printf("Day 34        :  %d / %d\n", $activity_totals["hitSum"][34],   $activity_totals["clickSum"][34]);
// printf("Totals        :  %d / %d\n", $activity_totals["hitSumTotal"],  $activity_totals["clickSumTotal"]);
//


// $no_data = "0/0";
$no_data = "-";

//--- Categories with No Activity  ------- No Hits, No Clicks --------
$no_hits   = array();
$no_clicks = array();
$j=0;
for ($i=0; $i<35; $i++, $j++)   $no_hits[$i]   = 0;
for ($i=0; $i<35; $i++, $j++)   $no_clicks[$i] = 0;
$category_none = array("daily_hits"=>$no_hits, "daily_clicks"=>$no_clicks, "total_hits"=>0, "total_clicks"=>0);
//--------------------------------------------------------------------


//echo "<PRE>";
//print_r($category_none);
//print_r($category_summary);
//echo "</PRE>";
// exit;
?>

<table width=100% bgcolor=#FFFFFF valign=top border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td >
      <table width=100% align=center valign=top cellspacing=0 cellpadding=15">
        <tr>
          <td class="text">
            This report will help you understand where the best sources of traffic are coming from, directly related to your ad placements, membership referral activity,
            blah, blah.
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>




<?php
  for ($WEEK=1; $WEEK<=5; $WEEK++)
    {
      if ($WEEK == 5)
        $DISP="";
      else
        $DISP=" display:none; ";


      $weekStartArray = dateToArray($week_start_dates[$WEEK]);
      $weekEndArray   = calStepDays(6,$weekStartArray);
      $MonthName = $month_names[$weekStartArray["month"]-1];
      if ($weekStartArray["month"] != $weekEndArray["month"])
         $MonthName .= "/".$month_names[$weekEndArray["month"]-1];

      $dateArray=$weekStartArray;
      $daysArray=array();
      $daysArray[]=$dateArray["day"];
      for ($i=0; $i<6; $i++)
        {
          $dateArray=calStepDays(1,$dateArray);
          $daysArray[]=$dateArray["day"];
        }
?>





<table id="PER_WCAT_<?php echo $WEEK?>" width=100% border=0 cellspacing=0 cellpadding=0 bgcolor=#FFFFFF style="<?php echo $DISP?> border-collapse: collapse; margin-top: -2px;" class="smalltext">
  <tr height=40>
    <td width=21% bgcolor=#FFFFFF> &nbsp;</td>
    <td width=67% align=center valign=middle class="bdr_crnr1 largetext bold" bgcolor=#F1FEF1 colspan=8 style="border-top: 3px double #000000; border-right: 3px double #000000;">
      <table width=450 cellpadding=0 cellspacing=0 border=0 align=center>
        <tr>
           <td width=40  align=right><a href=javascript:per_wcat_week(1)><img src="http://pds1106.s3.amazonaws.com/images/arrow2-lt.png" style="vertical-align:middle;"></a></td>
           <td width=40  align=right><a href=javascript:per_wcat_week(<?php echo $WEEK-1?>)><img src="http://pds1106.s3.amazonaws.com/images/arrow-lt.png"   style="vertical-align:middle;"></a></td>
           <td width=290 align=center><span id="MonthName"><b><?php echo $MonthName?></b></span></td>
           <td width=40  align=left><a href=javascript:per_wcat_week(<?php echo $WEEK+1?>)><img src="http://pds1106.s3.amazonaws.com/images/arrow-rt.png"   style="vertical-align:middle;"></a></td>
           <td width=40  align=left><a href=javascript:per_wcat_week(5)><img src="http://pds1106.s3.amazonaws.com/images/arrow2-rt.png"  style="vertical-align:middle;"></a></td>
        </tr>
      </table>
    </td>
    <td width=12% class="bdr_crnr1" bgcolor=#FFFFFF>&nbsp;</td>
  </tr>

  <tr height=37 bgcolor=#FFF8EB>
    <td width=21% valign=top class="bold" bgcolor=#F1FEF1 style="color:#0E6600; border-top:2px solid #999999; padding:3px 2px;">
      <br>Ad Categories</td>
    <td width=11% align=center class="bdr_crnr1 bold" bgcolor=#FFF8EB> <br>Widgets</td>
    <td width=8%  align=center class="bdr_crnr1 bold">SUN<br><?php echo $daysArray[0]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">MON<br><?php echo $daysArray[1]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">TUE<br><?php echo $daysArray[2]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">WED<br><?php echo $daysArray[3]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">THU<br><?php echo $daysArray[4]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">FRI<br><?php echo $daysArray[5]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">SAT<br><?php echo $daysArray[6]?></td>
    <td width=12% align=center class="bdr_crnr1 bold" bgcolor=#F1FEF1>TOTALS<br>5 Weeks</td>
  </tr>

<?php
  asort($ProductCategories);
  foreach ($ProductCategories AS $cat => $ctitle)
    {
      if (isset($daily_activity[$cat]))
        $category_data = $daily_activity[$cat];
      else
        $category_data = $category_none;

      $hits        = $category_data["daily_hits"];
      $clicks      = $category_data["daily_clicks"];
      $totalhits   = $category_data["total_hits"];
      $totalclicks = $category_data["total_clicks"];

      $wcount=0;
      if (isset($categories[$cat]))
         $wcount = $categories[$cat];

      if ($wcount==0)
        $widgets = $no_data;
      else
        $widgets = "($wcount)";
?>

      <tr height=24 bgcolor=#FFFFFF>
        <td class="smalltext" bgcolor=#F1FEF1 style="font-size:10px; padding-left: 2px;">
            <?php
                 echo $ctitle;
              // echo $ctitle."  ($cat)";
            ?>
        </td>
        <td align=center class="bdr_crnr3 border_rt2" style="font-size:10px;" bgcolor=#FFF8EB><?php echo $widgets?></td>

<?php
        $cls=3;
        $inx = ($WEEK-1) * 7;
        for ($j=0; $j<7; $j++)
          {
            $h = $hits[$inx+$j];
            $c = $clicks[$inx+$j];
            $ctotalhits[$inx+$j]   += $h;
            $ctotalclicks[$inx+$j] += $c;

            if ($h==0 && $c==0)
              $data=$no_data;
            else
              $data=$h."/".$c;

            echo "<td align=center class=\"bdr_crnr$cls\" style=\"font-size:10px;\">".$data."</td>\n";
            $cls=4;
          }


        if ($totalhits==0 && $totalclicks==0)
          $data=$no_data;
        else
          $data=$totalhits."/".$totalclicks;
        echo "<td align=center class=\"bdr_crnr3\" style=\"font-size:10px;\" bgcolor=\"#F1FEF1\">".$data."</td>\n";
?>
      </tr>

<?php
    }
?>


  <tr height=24 bgcolor=#FFFFFF>

    <td class="smalltext" bgcolor="#F1FEF1" style="padding-left: 2px;">
        <b>~ TOTALS ~</b>
    </td>

<?php
    $data="($activeWidgets / $total_widgets)";
?>

     <td align=center class="bdr_crnr3 border_rt2" style="font-size:10px; font-weight:bold;" bgcolor=#FFF8EB><?php echo $data?></td>

<?php
     $cls=3;
     $inx = ($WEEK-1) * 7;
     for ($j=0; $j<7; $j++)
       {
         $h = $activity_totals["hitSum"][$inx+$j];
         $c = $activity_totals["clickSum"][$inx+$j];
         if ($h==0 && $c==0)
           $data=$no_data;
         else
           $data=$h."/".$c;

         echo "<td align=center class=\"bdr_crnr$cls\" style=\"font-size:10px; font-weight:bold;\" bgcolor=\"#FFF8EB\">".$data."</td>\n";
         $cls=4;
       }

     $data=$activity_totals["hitSumTotal"]."/".$activity_totals["clickSumTotal"];
     echo "<td align=center class=\"bdr_crnr3\" style=\"font-size:10px; font-weight:bold;\" bgcolor=\"#FFF8EB\">".$data."</td>\n";
?>

  </tr>


  <tr><td colspan=10>&nbsp;</td></tr>
  <tr height=24>
    <td bgcolor="#FFFFFF">Widgets - 1 Category:</td>
    <td bgcolor="#FFFFFF" align=right><?php echo $widgets_1_cat?>&nbsp;&nbsp;</td>
    <td bgcolor="#FFFFFF" colspan=8 align=left>&nbsp;</td>
  </tr>
  <tr height=24>
    <td bgcolor="#FFFFFF">Widgets - 2 Categories:</td>
    <td bgcolor="#FFFFFF" align=right><?php echo $widgets_2_cat?>&nbsp;&nbsp;</td>
    <td bgcolor="#FFFFFF" colspan=8 align=left>&nbsp;</td>
  </tr>
  <tr height=30>
    <td bgcolor="#FFFFFF"><b>Total Widgets:</b></td>
    <td bgcolor="#FFFFFF" align=right><b><?php echo $total_widgets?>&nbsp;&nbsp;</b></td>
    <td bgcolor="#FFFFFF" colspan=8 align=left>&nbsp;</td>
  </tr>
  <tr><td colspan=10>&nbsp;</td></tr>
</table>




<?php
    }
?>
