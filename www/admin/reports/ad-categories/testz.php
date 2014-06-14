<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();

$week_start_dates = tracker_dates();

function getAdCounts($db)
 {
    $adCounts = array(0,0,0,0,0,0,0,0);
    $adCategories=array();

    $sql = " SELECT ad_id, COUNT(*) from tracker_ad_category GROUP BY ad_id";
    $result=mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $ad_id          = $myrow[0];
            $category_count = $myrow[1];
            $adCategories[$ad_id]=$category_count;

            if ($category_count>7) $category_count=7;
            $adCounts[$category_count]++;
          }
      }

    $total_ads=0;
    for ($i=1; $i<=7; $i++)
       $total_ads += $adCounts[$i];
    return array($adCounts, $total_ads, $adCategories);
 }


printf("\n********************** AAA ***********************\n");
$adInfo = getAdCounts($db);
list($adCounts, $total_ads, $adCategories) = $adInfo;
// print_r($adInfo);
for ($i=1; $i<=7; $i++)
 {
   printf("Ads - %d Categories = %d\n", $i, $adCounts[$i]);
 }
printf("Total Unique Ads       = %d\n", $total_ads);
printf("Number Ad Categories (1/7) on a Per Ad basis\n");
print_r($adCategories);



function getAdCountByCategory($db)
 {
    $activeAds=0;
    $categories=array();

    $sql = " SELECT userkey, COUNT(*) from tracker_ad_category GROUP by userkey";
    $result=mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $category   = $myrow[0];
            $ad_count   = $myrow[1];
            $categories[$category]=$ad_count;

            $activeAds += $ad_count;
          }
      }
    return array($activeAds, $categories);
 }

printf("\n********************** BBB ***********************\n");
$categoryInfo = getAdCountByCategory($db);
list($activeAds, $categories) = $categoryInfo;

// print_r($categoryInfo);

printf("Active Ads  = %d\n", $activeAds);
printf("Total Unique Ad Hits Per Category\n");
print_r($categories);





function daily_activity($db)
  {
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
    $sql .= "  FROM tracker_ad_category";
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

            $category_activity[$category] = array ("daily_hits"   => $hits,
                                                   "total_hits"   => $totalhits,
                                                   "daily_clicks" => $clicks,
                                                   "total_clicks" => $totalclicks);
          }
      }
    return $category_activity;
  }


printf("\n********************** CCC ***********************\n");
$daily_activity =  daily_activity($db);

// print_r($daily_activity);

foreach($daily_activity AS $category => $activity)
  {
    printf("Category: %-12s   HITS=%4d  CLICKS=%4d\n",$category, $activity["total_hits"], $activity["total_clicks"]);
  }





function daily_totals($db, $adCategories)
  {
    $ad_activity=array();
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
    $sql .= "  ad_id";
    $sql .= "  FROM tracker_ad_category";
    $sql .= "  GROUP BY ad_id";
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

            $ad_id = $myrow[70];
            $divisor=1;
            if (isset($adCategories[$ad_id]))
              $divisor=$adCategories[$ad_id];

            $j=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $hits[$i] = $myrow[$j];
                if ($divisor>1)
                  $hits[$i] = round($hits[$i] / $divisor);

                $totalhits+=$hits[$i];               // Row Totals (For Category)

                $hitSum[$i] += $hits[$i];            // Col Totals (For Day)

              }
            for ($i=0; $i<35; $i++, $j++)
              {
                $clicks[$i] = $myrow[$j];
                if ($divisor>1)
                  $clicks[$i] = round($clicks[$i] / $divisor);

                $totalclicks+=$clicks[$i];           // Row Totals (For Category)

                $clickSum[$i] += $clicks[$i];        // Col Totals (For Day)
              }

            $category    = $myrow[70];

            $ad_activity[$ad_id] = array ("daily_hits"   => $hits,
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

    return array($ad_activity, $totals);
  }



printf("\n****** DDD ****************** \n");
$daily_totals =  daily_totals($db,$adCategories);
list($ad_activity, $activity_totals)  =  $daily_totals;

foreach($ad_activity AS $ad_id => $activity)
  {
    printf("ad_id: %-32s   HITS=%4d  CLICKS=%4d\n",$ad_id, $activity["total_hits"], $activity["total_clicks"]);
  }
printf("Day 33        :  %d / %d\n", $activity_totals["hitSum"][33],   $activity_totals["clickSum"][33]);
printf("Day 34        :  %d / %d\n", $activity_totals["hitSum"][34],   $activity_totals["clickSum"][34]);
printf("Totals        :  %d / %d\n", $activity_totals["hitSumTotal"],  $activity_totals["clickSumTotal"]);

exit;

?>
