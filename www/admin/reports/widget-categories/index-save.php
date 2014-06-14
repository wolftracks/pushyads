<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

require_once "Spreadsheet/Excel/Writer.php";

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
//=================================================================================

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



$widgetInfo = getWidgetCounts($db);
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


$categoryInfo = getWidgetCountByCategory($db);
list($activeWidgets, $categories) = $categoryInfo;


// print_r($categoryInfo);
//
// printf("\n****** BBB ****************** \n");
// printf("Active Widgets         = %d\n", $activeWidgets);
// printf("Total Unique Widget Hits Per Category\n");
// print_r($categories);
//




// printf("\n****** CCC ****************** \n");
$daily_activity =  daily_activity($db);
// print_r($daily_activity);

// foreach($daily_activity AS $category => $activity)
//   {
//     printf("Category: %-12s   HITS=%4d  CLICKS=%4d\n",$category, $activity["total_hits"], $activity["total_clicks"]);
//   }




// printf("\n****** DDD ****************** \n");
$daily_totals =  daily_totals($db,$widgetCategories);
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



//===========================================================================================================================

// Create an instance
$xls =& new Spreadsheet_Excel_Writer();

// Send HTTP headers to tell the browser what's coming
$xls->send("categories.xls");
$xls->setVersion(8);
// Add a worksheet to the file, returning an object to add data to
$sheet =& $xls->addWorksheet('Categories');


$format_header_odd  =& $xls->addFormat();
$format_header_odd->setBold();
$format_header_odd->setSize(9);
$format_header_odd->setAlign('center');
$format_header_odd->setColor('yellow');  // ForeGround
$format_header_odd->setFgColor('green'); // BackGround

$format_header_even =& $xls->addFormat();
$format_header_even->setBold();
$format_header_even->setSize(9);
$format_header_even->setAlign('center');
$format_header_even->setColor('yellow');  // ForeGround
$format_header_even->setFgColor('blue');  // BackGround

$format_header_widgets =& $xls->addFormat();
$format_header_widgets->setBold();
$format_header_widgets->setSize(9);
$format_header_widgets->setAlign('center');
$format_header_widgets->setColor('white');     // ForeGround
$format_header_widgets->setFgColor('black');   // Background

$format_header_odd->setFgColor('green'); // BackGround
$format_label_totals =& $xls->addFormat();
$format_label_totals->setBold();
$format_label_totals->setSize(10);
$format_label_totals->setAlign('center');
$format_label_totals->setColor('white');  // ForeGround
$format_label_totals->setFgColor('red');   // BackGround

// Think this needs to happen for the column merging ??
$sheet->setColumn( 0,   0,  25);
$sheet->setColumn( 1,   1,  15);
for ($n=2; $n<=36; $n++)
  $sheet->setColumn( $n, $n, 4);  // Col Width

// Write in the title or whatever you want in the merged cells

$sheet->writeString(0,  0, "", $format_header_even);
$sheet->writeString(0,  1, "", $format_header_widgets);
$sheet->writeString(0,  2, $week_start_dates[1], $format_header_odd );     // ROW. COL, DATA, FORMAT
$sheet->writeString(0,  9, $week_start_dates[2], $format_header_even);
$sheet->writeString(0, 16, $week_start_dates[3], $format_header_odd );
$sheet->writeString(0, 23, $week_start_dates[4], $format_header_even);
$sheet->writeString(0, 30, $week_start_dates[5], $format_header_odd );
$sheet->writeString(0, 37, "", $format_label_totals);

$sheet->mergeCells(0,  2, 0,  8);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0,  9, 0, 15);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0, 16, 0, 22);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0, 23, 0, 29);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0, 30, 0, 36);                  // ROW, COL, LASTROW, LASTCOL

$sheet->setColumn( 0,   0,  25);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn( 1,   1,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn( 2,   8,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn( 9,  15,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(16,  22,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(23,  29,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(30,  36,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(37,  37,  10);                   // FIRSTCOL, LASTCOL, WIDTH


// Now apply the merge to the cells

/*
******************************************
* The rest of this just populates the sheet
* with some data and totals it up.
******************************************
*/

$format_dow_odd  =& $xls->addFormat();
$format_dow_odd->setBold();
$format_dow_odd->setSize(9);
$format_dow_odd->setAlign('center');
$format_dow_odd->setFgColor('white');  // BackGround

$format_dow_even  =& $xls->addFormat();
$format_dow_even->setBold();
$format_dow_even->setSize(9);
$format_dow_even->setAlign('center');
$format_dow_even->setFgColor(23);  // BackGround

$format_data  =& $xls->addFormat();
$format_data->setAlign('center');
$format_data->setSize(9);

$format_data_bold  =& $xls->addFormat();
$format_data_bold->setAlign('center');
$format_data->setSize(9);
$format_data_bold->setBold();

$format_data_totals =& $xls->addFormat();
$format_data_totals->setAlign('center');
$format_data_totals->setBold();
$format_data_totals->setSize(10);
$format_data_totals->setFgColor(7);  // BackGround

$format_data_grand_total =& $xls->addFormat();
$format_data_grand_total->setAlign('center');
$format_data_grand_total->setBold();
$format_data_grand_total->setSize(10);
$format_data_grand_total->setFgColor(5);  // BackGround

$inx=0;
$z=0;
$sheet->writeString(1, 0, "CATEGORY", $format_header_even);
$sheet->writeString(1, 1, "Widgets",  $format_header_widgets);
for ($i=0; $i<5; $i++)
  {
    for ($j=0; $j<7; $j++)
      {
        $dow=substr($day_names[$j],0,1);
        $col=$inx+$j+2;
        if ($z%2==0)
           $sheet->writeString(1, $col, $dow, $format_dow_even);
        else
           $sheet->writeString(1, $col, $dow, $format_dow_odd);
        $z++;
      }
    $inx+=7;
  }
$sheet->writeString(1, 37, "TOTALS", $format_label_totals);


$row=2;
$col=0;
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

    $sheet->writeString($row,0,$ctitle."  ($cat)");

    $wcount=0;

    if (isset($categories[$cat]))
       $wcount = $categories[$cat];

    if ($wcount==0)
      $widgets = $no_data;
    else
      $widgets = "($wcount)";

    $sheet->writeString($row,1,$widgets,$format_data);

    $inx=0;
    for ($i=0; $i<5; $i++)
      {
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

            $col=$inx+$j+2;
            $sheet->writeString($row, $col, $data, $format_data);
          }
        $inx+=7;
      }

    if ($totalhits==0 && $totalclicks==0)
      $data=$no_data;
    else
      $data=$totalhits."/".$totalclicks;

    $sheet->writeString($row, 37, $data, $format_data_bold);
    $row++;
  }



//--- Totals -------------
$sheet->writeString($row, 0, "TOTALS",  $format_label_totals);

$data="($activeWidgets / $total_widgets)";

$sheet->writeString($row, 1, $data, $format_header_widgets);

$inx=0;
for ($i=0; $i<5; $i++)
  {
    for ($j=0; $j<7; $j++)
      {
        $h = $activity_totals["hitSum"][$inx+$j];
        $c = $activity_totals["clickSum"][$inx+$j];

        $col=$inx+$j+2;
        if ($h==0 && $c==0)
          {
            $data=$no_data;
            $sheet->writeString($row, $col, $data, $format_data_bold);
          }
        else
          {
            $data=$h."/".$c;
            $sheet->writeString($row, $col, $data, $format_data_totals);
          }
      }
    $inx+=7;
  }

$data=$activity_totals["hitSumTotal"]."/".$activity_totals["clickSumTotal"];
$sheet->writeString($row, 37, $data, $format_data_grand_total);

//-------------

$sheet->writeString($row+2, 0, "Widgets - 1 Category");
$sheet->writeString($row+2, 1, "$widgets_1_cat",   $format_data_bold);

$sheet->writeString($row+3, 0, "Widgets - 2 Categories");
$sheet->writeString($row+3, 1, "$widgets_2_cat",   $format_data_bold);

$sheet->writeString($row+5, 0, "Widget Total");
$sheet->writeString($row+5, 1, "$total_widgets",   $format_data_bold);

$xls->close();
?>
