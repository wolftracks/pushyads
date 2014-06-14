<?php
include_once("pushy_tracker.inc");

$DEBUG=FALSE;

if ($DEBUG)
  {
    include_once("pushy_common.inc");
    include_once("pushy_commonsql.inc");
    include_once("pushy.inc");
    include_once("pushy_sendmail.inc");
    include_once("pushy_jsontools.inc");
    include_once("pushy_tree.inc");
    include_once("pushy_imagestore.inc");

    $mid="paw1200";
    $mid="awm1316e";

    $db = getPushyDatabaseConnection();

    $DEBUG=TRUE;
  }


if (!is_array($memberRecord))
  $memberRecord=getMemberInfo($db,$mid);
$affiliate_id = $memberRecord["affiliate_id"];


$week=5;
if (isset($_REQUEST["week"]))
  $week=(int) $_REQUEST["week"];
if ($week < 1 || $week > 5)
  $week=5;


function weekTotals($dayTotals)
  {
    $wtotal=0;
    for ($j=0; $j<=6; $j++)
      {
        $wtotal += $dayTotals[$j];
      }
    return $wtotal;
  }

function fiveWeekTotalHitsFromRow($row)
  {
    $hits = 0;
    for ($i=1; $i<=5; $i++)
      {
        for ($j=0; $j<=6; $j++)
          {
            $hits += $row["w".$i."_h".$j];
          }
      }
    return $hits;
  }


function fiveWeekTotalClicksFromRow($row)
  {
    $clicks = 0;
    for ($i=1; $i<=5; $i++)
      {
        for ($j=0; $j<=6; $j++)
          {
            $clicks += $row["w".$i."_c".$j];
          }
      }
    return $clicks;
  }



function hitsFromRow($row,$week)
  {
    $hits = array(0,0,0,0,0,0,0);
    for ($j=0; $j<=6; $j++)
      {
        $hits[$j] = $row["w".$week."_h".$j];
      }
    return $hits;
  }

function clicksFromRow($row,$week)
  {
    $clicks = array(0,0,0,0,0,0,0);
    for ($j=0; $j<=6; $j++)
      {
        $clicks[$j] = $row["w".$week."_c".$j];
      }
    return $clicks;
  }


$today=getDateToday();
$dates = tracker_dates();
$weekStart = $dates[$week];





$weekStartArray = dateToArray($weekStart);
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


//-------------------------  How many of My Referrals Have 1 OR MORE Widgets Installed ? ---------------------
//-------------------------  How many Widgets Does That Represent                      ? ---------------------
//-------------------------  ACTIVE WIDGETS ONLY (defined to be ACTIVITY IN LAST 5 WEEKS) --------------------
$direct_referral_websites_with_1_widget = 0;
$direct_referral_websites_total_widget_count = 0;

$sql  = "SELECT member_id,COUNT(*) from member JOIN tracker_pushy_widget USING(member_id)";
$sql .= " WHERE member.refid='$mid'";
$sql .= " GROUP BY member_id";
$result=mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
      {
        $referral_member_id = $myrow[0];
        $widget_count = (int) $myrow[1];
        if ($widget_count > 0)
          {
            $direct_referral_websites_with_1_widget++;
            $direct_referral_websites_total_widget_count += $widget_count;
          }
      }
  }
//-- printf("direct_referral_websites_with_1_widget = %d<br>\n",$direct_referral_websites_with_1_widget);
//-- printf("direct_referral_websites_total_widget_count = %d<br>\n",$direct_referral_websites_total_widget_count);



//-------------------------  How many resellers are marketing My Ads ? ---------------------------------------
$reseller_count=0;
$sql  = "SELECT COUNT(*) from ads LEFT JOIN MEMBER USING(member_id) LEFT JOIN PRODUCT USING(product_id)";
$sql .= " WHERE ads.reseller_listing>0";
$sql .= " AND   (member.user_level!='$PUSHY_LEVEL_ELITE' OR ads.product_list > 0 OR ads.pushy_network > 0 OR ads.elite_bar > 0 OR ads.elite_pool > 0)";
$sql .= " AND   product.product_owner='$mid'";
$result=mysql_query($sql,$db);

if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }

if ($result && ($myrow = mysql_fetch_array($result,MYSQL_NUM)))
  {
    $reseller_count = (int) $myrow[0];
  }
// printf("%5d\n",$reseller_count);


//----- ALL DEBUG ONLY 0 - ANSWERS THE QUESTION"  WHICH RESELLERS are displaying My "Affiliate Offer" (Existing Produt List)
// $sql  = "SELECT * from ads LEFT JOIN MEMBER USING(member_id) LEFT JOIN PRODUCT USING(product_id)";
// $sql .= " WHERE ads.reseller_listing>0";
// $sql .= " AND   (member.user_level!='$PUSHY_LEVEL_ELITE' OR ads.product_list > 0 OR ads.pushy_network > 0 OR ads.elite_bar > 0 OR ads.elite_pool > 0)";
// $sql .= " AND   product.product_owner='$mid'";
// $result=mysql_query($sql,$db);
//
// if ($DEBUG)
//  {
//    printf("SQL: %s<br>\n",$sql);
//    printf("ERR: %s<br>\n",mysql_error());
//  }
//
// if ($result)
//   {
//     while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
//       {
//         printf("%s  %s %s<br>\n",$myrow["member_id"],$myrow["firstname"],$myrow["lastname"]);
//       }
//   }
//-----------------------------------------------------------------------------------------------------------




$myads = array();
$sql  = "SELECT ads.ad_id, product.product_name from ads LEFT JOIN product USING(product_id) ";
$sql .= " WHERE ads.member_id='$mid' ";
$sql .= " ORDER BY product.product_name";
$result = mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    $count = mysql_num_rows($result);
    while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        $ad_id         = $myrow["ad_id"];
        $myads[$ad_id] = stripslashes($myrow["product_name"]);
      }
  }

$pushy_ads      = array();
$pushy_widget   = array();
$elite_bar      = array();
$affiliate_page = array();



//------------ Pushy Widget -------------------------
$sql  = "SELECT * from ".TRACKER_PUSHY_WIDGET;
$sql .= " WHERE member_id='$mid'";
$sql .= " ORDER BY widget_key";
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {

        // print_r($myrow);

        $widgetInfo = splitWidgetKey($myrow["widget_key"]);
        $widget_key  = $widgetInfo["WidgetConfigurationKey"];
        $tracking_id = $widgetInfo["TrackingId"];
        $widget=getWidget($db,$widget_key);

        $widget_name = $widget["widget_name"];
        $hits   = hitsFromRow($myrow,$week);
        $clicks = clicksFromRow($myrow,$week);
        $pushy_widget[$widget_name][$tracking_id] = array("hits"           => $hits,
                                                          "clicks"         => $clicks,
                                                          "weekHits"       => weekTotals($hits),
                                                          "weekClicks"     => weekTotals($clicks),
                                                          "fiveWeekHits"   => fiveWeekTotalHitsFromRow($myrow),
                                                          "fiveWeekClicks" => fiveWeekTotalClicksFromRow($myrow)
                                                         );
      }
    if ($DEBUG)
      {
        dump_var($pushy_widget);
      }
  }




//------------ Pushy Ads MYSITES -------------------------
$sql  = "SELECT * from ".TRACKER_PUSHY_ADS_MYSITES;
$i=0;
foreach($myads AS $ad_id => $data)
 {
   if ($i==0)
     $sql  .= " WHERE ";
   else
     $sql  .= " OR ";
   $sql  .= "ad_id='$ad_id'";
   $i++;
 }
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {

        // print_r($myrow);

        $ad_id = $myrow["ad_id"];
        $hits   = hitsFromRow($myrow,$week);
        $clicks = clicksFromRow($myrow,$week);
        $pushy_ads_mysites[$ad_id] = array("hits"           => $hits,
                                   "clicks"         => $clicks,
                                   "weekHits"       => weekTotals($hits),
                                   "weekClicks"     => weekTotals($clicks),
                                   "fiveWeekHits"   => fiveWeekTotalHitsFromRow($myrow),
                                   "fiveWeekClicks" => fiveWeekTotalClicksFromRow($myrow)
                                  );
      }
    if ($DEBUG)
      {
        dump_var($pushy_ads_mysites);
      }
  }






//------------ Pushy Ads REFERRALS -----------------------
$sql  = "SELECT * from ".TRACKER_PUSHY_ADS_REFERRALS;
$i=0;
foreach($myads AS $ad_id => $data)
 {
   if ($i==0)
     $sql  .= " WHERE ";
   else
     $sql  .= " OR ";
   $sql  .= "ad_id='$ad_id'";
   $i++;
 }
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {

        // print_r($myrow);

        $ad_id = $myrow["ad_id"];
        $hits   = hitsFromRow($myrow,$week);
        $clicks = clicksFromRow($myrow,$week);
        $pushy_ads_referrals[$ad_id] = array("hits"           => $hits,
                                   "clicks"         => $clicks,
                                   "weekHits"       => weekTotals($hits),
                                   "weekClicks"     => weekTotals($clicks),
                                   "fiveWeekHits"   => fiveWeekTotalHitsFromRow($myrow),
                                   "fiveWeekClicks" => fiveWeekTotalClicksFromRow($myrow)
                                  );
      }
    if ($DEBUG)
      {
        dump_var($pushy_ads_referrals);
      }
  }





//------------ Pushy Ads ------( ALL - My Pushy Network ) ------
$sql  = "SELECT * from ".TRACKER_PUSHY_ADS;
$i=0;
foreach($myads AS $ad_id => $data)
 {
   if ($i==0)
     $sql  .= " WHERE ";
   else
     $sql  .= " OR ";
   $sql  .= "ad_id='$ad_id'";
   $i++;
 }
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {

        // print_r($myrow);

        $ad_id = $myrow["ad_id"];
        $hits   = hitsFromRow($myrow,$week);
        $clicks = clicksFromRow($myrow,$week);
        $pushy_ads[$ad_id] = array("hits"           => $hits,
                                   "clicks"         => $clicks,
                                   "weekHits"       => weekTotals($hits),
                                   "weekClicks"     => weekTotals($clicks),
                                   "fiveWeekHits"   => fiveWeekTotalHitsFromRow($myrow),
                                   "fiveWeekClicks" => fiveWeekTotalClicksFromRow($myrow)
                                  );
      }
    if ($DEBUG)
      {
        dump_var($pushy_ads);
      }
  }




//------------ Elite Bar -------------------------
$sql  = "SELECT * from ".TRACKER_ELITE_BAR;
$i=0;
foreach($myads AS $ad_id => $data)
 {
   if ($i==0)
     $sql  .= " WHERE ";
   else
     $sql  .= " OR ";
   $sql  .= "ad_id='$ad_id'";
   $i++;
 }
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {

        // print_r($myrow);

        $ad_id = $myrow["ad_id"];
        $hits   = hitsFromRow($myrow,$week);
        $clicks = clicksFromRow($myrow,$week);
        $elite_bar[$ad_id] = array("hits"           => $hits,
                                   "clicks"         => $clicks,
                                   "weekHits"       => weekTotals($hits),
                                   "weekClicks"     => weekTotals($clicks),
                                   "fiveWeekHits"   => fiveWeekTotalHitsFromRow($myrow),
                                   "fiveWeekClicks" => fiveWeekTotalClicksFromRow($myrow)
                                  );
      }
    if ($DEBUG)
      {
        dump_var($elite_bar);
      }
  }





//------------ Affiliate Page --------------------
$hits           = array(0,0,0,0,0,0,0);
$weekHits       = 0;
$fiveWeekHits   = 0;
$clicks         = array(0,0,0,0,0,0,0);
$weekClicks     = 0;
$fiveWeekClicks = 0;

$trackerDates = tracker_dates();
$trackerWeeks = tracker_weeks();

$fiveWeekStartDate=$trackerDates[1];
$weekStartDate=$trackerDates[$week];
$weekEndDate  =$trackerWeeks[$weekStartDate];

// print_r($trackerDates);
// print_r($trackerWeeks);
//
// printf(" --- FIVEWEEKSTART=%s\n",$fiveWeekStartDate);
// printf(" --- WEEK=%d\n",$week);
// printf(" --- WEEKSTART=%s\n",$weekStartDate);
// printf(" --- WEEKEND=%s\n",$weekEndDate);
//


//---- HITS ---------------
$sql  = "SELECT * from ".TRACKER_AFFILIATE_PAGE;
$sql .= " WHERE member_id='$mid'";
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_ASSOC)))
  {
    $hits         = hitsFromRow($myrow,$week);
    $weekHits     = weekTotals($hits);
    $fiveWeekHits = fiveWeekTotalHitsFromRow($myrow);
  }


//---- CLICKS -----------
$sql  = "SELECT registered from member";
$sql .= " WHERE registered>0";
$sql .= " AND   refid='$mid'";
$sql .= " AND   record_created>='".$fiveWeekStartDate."'";
$result=mysql_query($sql,$db);
if ($DEBUG)
 {
   printf("SQL: %s<br>\n",$sql);
   printf("ERR: %s<br>\n",mysql_error());
 }
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $reg=$myrow["registered"];
        $dt =formatDate($reg);
        // printf(" --- %s ---\n",$dt);

        if ($dt >= $weekStartDate && $dt <= $weekEndDate)
          {
            $dow=strftime("%w",$reg);
            $clicks[$dow]++;
            $weekClicks++;
          }
        $fiveWeekClicks++;
      }
  }

$affiliate_page  =  array("hits"           => $hits,
                          "clicks"         => $clicks,
                          "weekHits"       => $weekHits,
                          "weekClicks"     => $weekClicks,
                          "fiveWeekHits"   => $fiveWeekHits,
                          "fiveWeekClicks" => $fiveWeekClicks
                         );

if ($DEBUG)
  {
    dump_var($affiliate_page);
  }



if ($DEBUG)
  {
     exit;
  }

//    $contents = ob_get_contents();
//    ob_end_clean();
//    $response= new stdClass();
//    $response->success      = "TRUE";
//    $response->html  = $contents;
//    sendJSONResponse(0, $response, null);
//exit;
?>


<table width=100% bgcolor=#FFFFFF valign=top border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td >
      <table width=100% align=center valign=top cellspacing=0 cellpadding=15">
        <tr>
          <td class="text">

            This report will help you understand where the best sources of traffic are coming from, directly related to your ad placements, membership referral activity,
            and <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">&#8482 widget installations by you and your referral network.</p>

            <p>In order to increase your ROI, you will need to know where to increase your
            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">&#8482 activity. These reports reveal that information to you.</p>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!------------------- BEGIN TRAFFIC REPORT -------------------->

<table width=100% border=0 cellspacing=0 cellpadding=0 bgcolor=#FFFFFF style="border-collapse: collapse; margin-top: -2px;" class="smalltext">
  <tr height=40>
    <td width=21% bgcolor=#FFFFFF> &nbsp;</td>
    <td width=67% align=center valign=middle class="bdr_crnr1 largetext bold" bgcolor=#F1FEF1 colspan=8 style="border-top: 3px double #000000; border-right: 3px double #000000;">
      <table width=450 cellpadding=0 cellspacing=0 border=0 align=center>
        <tr>
           <td width=40  align=right><a href=javascript:traffic_week('first')><img src="http://pds1106.s3.amazonaws.com/images/arrow2-lt.png" style="vertical-align:middle;"></a></td>
           <td width=40  align=right><a href=javascript:traffic_week('prev')><img src="http://pds1106.s3.amazonaws.com/images/arrow-lt.png"   style="vertical-align:middle;"></a></td>
           <td width=290 align=center><span id="MonthName"><b><?php echo $MonthName?></b></span></td>
           <td width=40  align=left><a href=javascript:traffic_week('next')><img src="http://pds1106.s3.amazonaws.com/images/arrow-rt.png"   style="vertical-align:middle;"></a></td>
           <td width=40  align=left><a href=javascript:traffic_week('last')><img src="http://pds1106.s3.amazonaws.com/images/arrow2-rt.png"  style="vertical-align:middle;"></a></td>
        </tr>
      </table>
    </td>
    <td width=12% class="bdr_crnr1" bgcolor=#FFFFFF>&nbsp;</td>
  </tr>

  <tr height=37 bgcolor=#FFF8EB>
    <td width=21% valign=top class="bold" bgcolor=#F1FEF1 style="border-top: 2px solid #999999; padding: 3px 2px;">
      TRAFFIC SOURCE</td>
    <td width=8%  align=center class="bdr_crnr1 bold">SUN<br><?php echo $daysArray[0]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">MON<br><?php echo $daysArray[1]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">TUE<br><?php echo $daysArray[2]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">WED<br><?php echo $daysArray[3]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">THU<br><?php echo $daysArray[4]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">FRI<br><?php echo $daysArray[5]?></td>
    <td width=8%  align=center class="bdr_crnr2 bold">SAT<br><?php echo $daysArray[6]?></td>
    <td width=11% align=center class="bdr_crnr1 bold" bgcolor=#FFF8EB>TOTALS<br>Week</td>
    <td width=12% align=center class="bdr_crnr1 bold" bgcolor=#F1FEF1>TOTALS<br>5 Weeks</td>
  </tr>


  <tr height=24 bgcolor=#FFFFFF>
    <td class="bold" bgcolor=#F1FEF1 style="padding-left: 2px; color:#0E6600; font-weight:bold;">
       PushyAds.com
       &nbsp; <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-REPORTS-MYPUSHY-AFFILIATE-PAGE')"></a>
    </td>
    <td align=center class="bdr_crnr3">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
    <td align=center class="bdr_crnr3" bgcolor=#F1FEF1>&nbsp;</td>
  </tr>


       <!--------------------- AFFILIATE PAGE STATS --------------------->
  <tr height=24 bgcolor=#FFFFFF class="tinytext">
    <td class="smalltext" bgcolor=#F1FEF1 style="padding-left: 10px; color:#0E6600"><?php echo $affiliate_id?></td>
    <?php

       if (is_array($affiliate_page) && is_array($affiliate_page["hits"]))
         $statsArray = $affiliate_page;
       else
         {
           $statsArray=array();
           $statsArray["hits"]            = array(0,0,0,0,0,0,0);
           $statsArray["clicks"]          = array(0,0,0,0,0,0,0);
           $statsArray["weekHits"]        = 0;
           $statsArray["weekClicks"]      = 0;
           $statsArray["fiveWeekHits"]    = 0;
           $statsArray["fiveWeekClicks"]  = 0;
         }

       $hits           = $statsArray["hits"];
       $clicks         = $statsArray["clicks"];
       $weekHits       = $statsArray["weekHits"];
       $weekClicks     = $statsArray["weekClicks"];
       $fiveWeekHits   = $statsArray["fiveWeekHits"];
       $fiveWeekClicks = $statsArray["fiveWeekClicks"];
       $j=3;
       for ($i=0; $i<7; $i++)
         {
           echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
           // echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]."</td>\n";
           $j=4;
         }
       echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
       // echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits."</td>\n";
       echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
       // echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits."</td>\n";
    ?>
  </tr>

  <tr height=24 bgcolor=#FFFFFF class="tinytext">
    <td bgcolor=#F1FEF1 style="border-bottom: 2px solid #999999;">&nbsp;</td>
    <td align=center class="bdr_crnr1">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr1" bgcolor=#FFF8EB>&nbsp;</td>
    <td align=center class="bdr_crnr1" bgcolor=#F1FEF1>&nbsp;</td>
  </tr>



       <!--------------------- PUSHY WIDGET STATS --------------------->

  <tr height=24 bgcolor=#FFFFFF>
    <td class="bold" bgcolor=#F1FEF1 style="padding-left: 2px; color:#0E6600; font-weight:bold;">
       My <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -2px">'s
       &nbsp; <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-REPORTS-MYPUSHY')"></a>
    </td>
    <td align=center class="bdr_crnr3">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr4">&nbsp;</td>
    <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
    <td align=center class="bdr_crnr3" bgcolor=#F1FEF1>&nbsp;</td>
  </tr>


  <?php
   foreach($pushy_widget AS $widget_name => $widget_data)
     {
       if ($widget_name=="/system/") continue;
  ?>
      <tr  height=24>
        <td class="bold" bgcolor=#F1FEF1 style="padding-left: 10px; color:#0E6600; font-weight:bold;">
          <div style="width: 130px; white-space: nowrap; overflow: hidden;">
            <?php echo $widget_name?> *
          </div>
        </td>
        <td align=center class="bdr_crnr3">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
        <td align=center class="bdr_crnr3" bgcolor=#F1FEF1>&nbsp;</td>
      </tr>

      <?php
       foreach($widget_data AS $tracking_id => $data)
         {
           if ($tracking_id != "/default/") continue;
           // SYSTEM DEFAULT - User TRACKER ID
      ?>
           <tr height=24 bgcolor=#FFFFFF class="tinytext">
             <td class="smalltext" bgcolor=#F1FEF1 style="padding-left: 15px; color:#0E6600">(Default)</td>
             <?php
                $statsArray = $widget_data[$tracking_id];
                $hits           = $statsArray["hits"];
                $clicks         = $statsArray["clicks"];
                $weekHits       = $statsArray["weekHits"];
                $weekClicks     = $statsArray["weekClicks"];
                $fiveWeekHits   = $statsArray["fiveWeekHits"];
                $fiveWeekClicks = $statsArray["fiveWeekClicks"];
                $j=3;
                for ($i=0; $i<7; $i++)
                  {
                    echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
                    $j=4;
                  }
                echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
                echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
             ?>
           </tr>
      <?php
         }

       foreach($widget_data AS $tracking_id => $data)
         {
           if ($tracking_id == "/default/") continue;
           // SYSTEM DEFAULT - User TRACKER ID
      ?>
           <tr height=24 bgcolor=#FFFFFF class="tinytext">
             <td class="smalltext" bgcolor=#F1FEF1 style="padding-left: 15px; color:#0E6600"><?php echo $tracking_id?></td>
             <?php
                $statsArray = $widget_data[$tracking_id];
                $hits           = $statsArray["hits"];
                $clicks         = $statsArray["clicks"];
                $weekHits       = $statsArray["weekHits"];
                $weekClicks     = $statsArray["weekClicks"];
                $fiveWeekHits   = $statsArray["fiveWeekHits"];
                $fiveWeekClicks = $statsArray["fiveWeekClicks"];
                $j=3;
                for ($i=0; $i<7; $i++)
                  {
                    echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
                    $j=4;
                  }
                echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
                echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
             ?>
           </tr>
      <?php
         }
     }
   ?>



  <tr height=24 bgcolor=#FFFFFF class="tinytext">
    <td bgcolor=#F1FEF1 style="border-bottom: 2px solid #999999;">&nbsp;</td>
    <td align=center class="bdr_crnr1">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr2">&nbsp;</td>
    <td align=center class="bdr_crnr1" bgcolor=#FFF8EB>&nbsp;</td>
    <td align=center class="bdr_crnr1" bgcolor=#F1FEF1>&nbsp;</td>
  </tr>

  <?php
  foreach($myads AS $ad_id => $data)
    {

      $product_name = $data;
  //  $product_name = $ad_id;

  ?>
      <tr height=24 bgcolor=#FFFFFF>
        <td class="bold" bgcolor=#F1FEF1 style="padding-left: 5px; color:#0E6600; font-size:12px;">
          <div style="width: 130px; white-space: nowrap; overflow: hidden;">
            <?php echo $product_name?>
          </div>
        </td>
        <td align=center class="bdr_crnr3">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr4">&nbsp;</td>
        <td align=center class="bdr_crnr3 border_rt2" bgcolor=#FFF8EB>&nbsp;</td>
        <td align=center class="bdr_crnr3" bgcolor=#F1FEF1>&nbsp;</td>
      </tr>


      <tr height=24 bgcolor=#FFFFFF class="tinytext">
        <td class="smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
            Websites I Own
        </td>
        <?php
           if (isset($pushy_ads_mysites[$ad_id]))
             {
               $statsArray = $pushy_ads_mysites[$ad_id];
               $hits           = $statsArray["hits"];
               $clicks         = $statsArray["clicks"];
               $weekHits       = $statsArray["weekHits"];
               $weekClicks     = $statsArray["weekClicks"];
               $fiveWeekHits   = $statsArray["fiveWeekHits"];
               $fiveWeekClicks = $statsArray["fiveWeekClicks"];
             }
           else
             {
               $hits           = array(0,0,0,0,0,0,0);
               $clicks         = array(0,0,0,0,0,0,0);
               $weekHits       = 0;
               $weekClicks     = 0;
               $fiveWeekHits   = 0;
               $fiveWeekClicks = 0;
             }
           $j=3;
           for ($i=0; $i<7; $i++)
             {
               echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
               $j=4;
             }
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
        ?>
      </tr>


      <tr height=24 bgcolor=#FFFFFF class="tinytext">
        <td class="smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
            Referral Websites
        </td>
        <?php
           if (isset($pushy_ads_referrals[$ad_id]))
             {
               $statsArray = $pushy_ads_referrals[$ad_id];
               $hits           = $statsArray["hits"];
               $clicks         = $statsArray["clicks"];
               $weekHits       = $statsArray["weekHits"];
               $weekClicks     = $statsArray["weekClicks"];
               $fiveWeekHits   = $statsArray["fiveWeekHits"];
               $fiveWeekClicks = $statsArray["fiveWeekClicks"];
             }
           else
             {
               $hits           = array(0,0,0,0,0,0,0);
               $clicks         = array(0,0,0,0,0,0,0);
               $weekHits       = 0;
               $weekClicks     = 0;
               $fiveWeekHits   = 0;
               $fiveWeekClicks = 0;
             }
           $j=3;
           for ($i=0; $i<7; $i++)
             {
               echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
               $j=4;
             }
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
        ?>
      </tr>



      <tr height=24 bgcolor=#FFFFFF class="tinytext">
        <td class="smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
            Pushy Network
        </td>
        <?php
           if (isset($pushy_ads[$ad_id]))
             {
               $statsArray = $pushy_ads[$ad_id];
               $hits           = $statsArray["hits"];
               $clicks         = $statsArray["clicks"];
               $weekHits       = $statsArray["weekHits"];
               $weekClicks     = $statsArray["weekClicks"];
               $fiveWeekHits   = $statsArray["fiveWeekHits"];
               $fiveWeekClicks = $statsArray["fiveWeekClicks"];
             }
           else
             {
               $hits           = array(0,0,0,0,0,0,0);
               $clicks         = array(0,0,0,0,0,0,0);
               $weekHits       = 0;
               $weekClicks     = 0;
               $fiveWeekHits   = 0;
               $fiveWeekClicks = 0;
             }
           $j=3;
           for ($i=0; $i<7; $i++)
             {
               echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
               $j=4;
             }
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
        ?>
      </tr>

      <tr height=24 bgcolor=#FFFFFF class="tinytext">
        <td class="smalltext bold" bgcolor=#F1FEF1 style="padding-left: 10px;">
            Elite Bar
        </td>
        <?php
           if (isset($elite_bar[$ad_id]))
             {
               $statsArray = $elite_bar[$ad_id];
               $hits           = $statsArray["hits"];
               $clicks         = $statsArray["clicks"];
               $weekHits       = $statsArray["weekHits"];
               $weekClicks     = $statsArray["weekClicks"];
               $fiveWeekHits   = $statsArray["fiveWeekHits"];
               $fiveWeekClicks = $statsArray["fiveWeekClicks"];
             }
           else
             {
               $hits           = array(0,0,0,0,0,0,0);
               $clicks         = array(0,0,0,0,0,0,0);
               $weekHits       = 0;
               $weekClicks     = 0;
               $fiveWeekHits   = 0;
               $fiveWeekClicks = 0;
             }
           $j=3;
           for ($i=0; $i<7; $i++)
             {
               echo "<td align=center class=\"bdr_crnr$j\">".$hits[$i]." / ".$clicks[$i]."</td>\n";
               $j=4;
             }
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#FFF8EB\">".$weekHits." / ".$weekClicks."</td>\n";
           echo "<td align=center class=\"bdr_crnr3\" bgcolor=\"#F1FEF1\">".$fiveWeekHits." / ".$fiveWeekClicks."</td>\n";
        ?>
      </tr>

      <tr height=24 bgcolor=#FFFFFF class="tinytext">
        <td bgcolor=#F1FEF1 style="border-bottom: 2px solid #999999;">&nbsp;</td>
        <td align=center class="bdr_crnr1">&nbsp;</td>
        <td align=center class="bdr_crnr2">&nbsp;</td>
        <td align=center class="bdr_crnr2">&nbsp;</td>
        <td align=center class="bdr_crnr2">&nbsp;</td>
        <td align=center class="bdr_crnr2">&nbsp;</td>
        <td align=center class="bdr_crnr2">&nbsp;</td>
        <td align=center class="bdr_crnr2">&nbsp;</td>
        <td align=center class="bdr_crnr1" bgcolor=#FFF8EB>&nbsp;</td>
        <td align=center class="bdr_crnr1" bgcolor=#F1FEF1>&nbsp;</td>
      </tr>

  <?php
    }
  ?>

      <!-------------------- BOTTOM GREEN ROW --------------------->

  <tr height=75>
    <td width=21% bgcolor=#FFFFFF></td>
    <td width=67% align=right valign=middle class="bdr_crnr1 text bold" bgcolor=#F1FEF1 colspan=8 style="border-bottom: 3px double #000000; border-right: 3px double #000000; line-height:25px;">

<!----------- <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-REPORTS-OFFERS')"></a>
      &nbsp; <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> members displaying your "Affiliate Offer"&nbsp;
      <br>
------------->
      Direct referrals displaying <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on 1 or more websites:&nbsp;
    </td>
    <td width=12% align=center valign=middle class="text bold" bgcolor=#FFFFFF style="line-height:25px;">
<!-----------        <?php echo $reseller_count?>
      <br>
------------->
      <?php echo $direct_referral_websites_with_1_widget?>
    </td>
  </tr>

  <tr>
    <td width=21% bgcolor=#FFFFFF border=#FFEECC>&nbsp;</td>
    <td width=67% bgcolor=#FFFFFF colspan=8 align=right height=50>
      * Hits on Pushy widget / Clicks on "Get Pushy" link (on your website) &nbsp;</td>
    <td width=12% bgcolor=#FFFFFF>&nbsp;</td>
  </tr>

</table>
