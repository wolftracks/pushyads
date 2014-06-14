<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();

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
//

function getWidgetCounts($db)
 {
    $widgets_1_cat=0;
    $widgets_2_cat=0;

    $widgetCategories=array();

    $sql = " SELECT widget_key, COUNT(*) from tracker_widget_category GROUP BY widget_key";
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



$widgetInfo = getWidgetCounts($db);
list($widgets_1_cat,$widgets_2_cat,$total_widgets,$widgetCategories) = $widgetInfo;
print_r($widgetInfo);

printf("\n--------------------------------\n\n");
printf("Widgets - 1 Category   = %d\n", $widgets_1_cat);
printf("Widgets - 2 Categories = %d\n", $widgets_2_cat);
printf("Total Unique Widgets   = %d\n", $total_widgets);
printf("Number Widget Categories (1/2) on a Per Widget basis\n");
print_r($widgetCategories);
printf("\n--------------------------------\n");




function getWidgetCountByCategory($db)
 {
    $activeWidgets=0;
    $categories=array();

    $sql = " SELECT userkey, COUNT(*) from tracker_widget_category GROUP by userkey";
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

$categoryInfo = getWidgetCountByCategory($db);
list($activeWidgets, $categories) = $categoryInfo;
print_r($categoryInfo);

printf("\n--------------------------------\n\n");
printf("Active Widgets         = %d\n", $activeWidgets);
printf("Total Unique Widget Hits Per Category\n");
print_r($categories);
printf("\n--------------------------------\n");





// CATEGORY        DAY 1 (H / C)     DAY 2 (H/C)     -->     TOTAL (H / C)
//  101   =>       202 /    27       202 /    27     -->     404 /    54
//  108   =>       152 /    20       152 /    20     -->     304 /    40
//  112   =>        12 /     1        12 /     1     -->      24 /     2
//  117   =>        12 /     1        12 /     1     -->      24 /     2
//  120   =>       204 /    25       204 /    25     -->     408 /    50
//  130   =>        87 /    14        87 /    14     -->     174 /    28


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
    $sql .= "  FROM tracker_widget_category";
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


$category_activity =  daily_activity($db);
// print_r($category_activity);

foreach($category_activity AS $category=>$activity)
  {
     printf("Category: %s\n",$category);


         // printf("%5s   =>     %5d / %5d     %5d / %5d     -->   %5d / %5d\n",
         //       $category,
         //       $hits[33],  $clicks[33],
         //       $hits[34],  $clicks[34],
         //       $totalhits, $totalclicks);

  }



function daily_totals($db, $widgetCategories)
  {
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

            printf("%5s   =>     %5d / %5d     %5d / %5d     -->   %5d / %5d\n",
                  $widget_key,
                  $hits[33],  $clicks[33],
                  $hits[34],  $clicks[34],
                  $totalhits, $totalclicks);

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


$category_activity =  daily_activity($db);
// print_r($category_activity);

foreach($category_activity AS $category=>$activity)
  {
     printf("Category: %s\n",$category);


         // printf("%5s   =>     %5d / %5d     %5d / %5d     -->   %5d / %5d\n",
         //       $category,
         //       $hits[33],  $clicks[33],
         //       $hits[34],  $clicks[34],
         //       $totalhits, $totalclicks);

  }


printf("\n\n------------------\n\n");

list($widget_activity, $totals)  =  daily_totals($db,$widgetCategories);
print_r($widget_activity);
print_r($totals);

foreach($widget_activity AS $widget_key=>$activity)
  {
     printf("Widget: %s\n",$widget_key);


         // printf("%5s   =>     %5d / %5d     %5d / %5d     -->   %5d / %5d\n",
         //       $category,
         //       $hits[33],  $clicks[33],
         //       $hits[34],  $clicks[34],
         //       $totalhits, $totalclicks);

  }
?>
