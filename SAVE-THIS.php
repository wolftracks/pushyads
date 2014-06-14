<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

require_once "Spreadsheet/Excel/Writer.php";

$db = getPushyDatabaseConnection();

$week_start_dates = tracker_dates();

function tracker_widget_category_counts($db)
  {
    // How Many Widgets (Widget=WIDGET_KEY-TRACKING_ID) contributed to the Hits/Clicks
    // Counts IN EACH CATEGORY

    $widgetsByCategory=array();
    $total_unique_widgets=0;
    $total_categories    =0;

    $sql  = "SELECT userkey,COUNT(*) FROM tracker_widget_category";
    $sql .= " GROUP BY userkey";
    $result=mysql_query($sql,$db);

    if (FALSE)
     {
       printf("SQL: %s<br>\n",$sql);
       printf("ERR: %s<br>\n",mysql_error());
     }

    if ($result)
      {
        while ($myrow = mysql_fetch_array($result))
          {
            $widget_category = $myrow[0];
            $widget_count    = $myrow[1];
            $widgetsByCategory[$widget_category] = $widget_count;

            $total_categories++;
            $total_unique_widgets += $widget_count;
          }
      }


    $widgetCountInfo = array (
                               "total_categories"      => $total_categories,
                               "total_unique_widgets"  => $total_unique_widgets,
                               "widgets_by_category"   => $widgetsByCategory
                             );

    return $widgetCountInfo;
  }



function tracker_summary($db)
  {
    $categories  = array();
    $widgetCategoriesHit     = array();
    $widgetCategoriesClicked = array();
    $activeWidgetsHit        = array();
    $activeWidgetsClicked    = array();

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  userkey,widget_key";
    $sql .= "  FROM tracker_widget_category";
    $sql .= "  GROUP BY userkey,widget_key";
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
            $j=0;
            $totalhits=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $hits[$i]   = $myrow[$j];
                $totalhits+=$hits[$i];
              }
            $totalclicks=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $clicks[$i] = $myrow[$j];
                $totalclicks+=$clicks[$i];
              }

            $category    = $myrow[70];
            $widget_key  = $myrow[71];

            if ($totalhits > 0 || $totalclicks > 0)
              {
                $categories[$category] = array("hits"=>$hits, "clicks"=>$clicks, "fiveweektotalhits"=>$totalhits, "fiveweektotalclicks"=>$totalclicks);

                $key=$category."@".$widget_key;
                if ($totalhits > 0)
                  {
                    $widgetCategoriesHit[$category][$widget_key]=true;
                    $activeWidgetsHit[$widget_key]=true;
                  }
                if ($totalclicks > 0)
                  {
                    $widgetCategoriesClicked[$category][$widget_key]=true;
                    $activeWidgetsClicked[$widget_key]=true;
                  }
              }
          }
      }

    $summary = array(
                      "categories"              => $cateories,
                      "widgetCategoriesHit"     => $widgetCategoriesHit,
                      "widgetCategoriesClicked" => $widgetCategoriesClicked,
                      "activeWidgetsHit"        => $activeWidgetsHit,
                      "activeWidgetsClicked"    => $activeWidgetsClicked,
                    );
    return $summary;
  }


$summary = tracker_summary($db);
$category_summary        = $summary["categories"];
$widgetCategoriesHit     = $summary["widgetCategoriesHit"];
$widgetCategoriesClicked = $summary["widgetCategoriesClicked"];
$activeWidgetsHit        = $summary["activeWidgetsHit"];
$activeWidgetsClicked    = $summary["activeWidgetsClicked"];

printf("\n--- WidgetCategoriesHit: ---\n");
foreach($widgetCategoriesHit AS $cat=>$widget_keys)
  {
    printf("      WidgetCategories   Category=%d   Hit=%d\n",$cat,count($widget_keys));
  }

printf("\n--- WidgetCategoriesClicked: ---\n");
foreach($widgetCategoriesClicked AS $cat=>$widget_keys)
  {
    printf("      WidgetCategories   Category=%d   Clicked=%d\n",$cat,count($widget_keys));
  }


printf("\n\n\n----- ActiveWidgetsHit=%d  ActiveWidgetsClicked=%d  -----\n",count($activeWidgetsHit),count($activeWidgetsClicked));

printf("\nActiveWidgetsHit:\n");
print_r($activeWidgetsHit);

printf("\nActiveWidgetsClicked:\n");
print_r($activeWidgetsClicked);

exit;




echo "----- 1 -----\n";
print_r($widgetHits);

$hitsByCategory = array();
$hitsByWidget   = array();
foreach($widgetHits AS $cat=>$whits)
  {
    foreach($whits AS $wkey=>$hcount)
     {
       $hitsByCategory[$cat]+=$hcount;
       $hitsByWidget[$wkey]+=$hcount;
     }
  }
echo "----- 2 -----\n";
print_r($hitsByCategory);
echo "----- 3 -----\n";
print_r($hitsByWidget);




echo "----- 4 -----\n";
print_r($widgetClicks);

$clicksByCategory = array();
$clicksByWidget   = array();
foreach($widgetClicks AS $cat=>$wclicks)
  {
    foreach($wclicks AS $wkey=>$ccount)
     {
       $clicksByCategory[$cat]+=$ccount;
       $clicksByWidget[$wkey]+=$ccount;
     }
  }
echo "----- 5 -----\n";
print_r($clicksByCategory);
echo "----- 6 -----\n";
print_r($clicksByWidget);

exit;


$clicksByCategory = array();
foreach($widgetClicks AS $cat)
  {
    foreach($cat AS $clicks)
     {
       $categoryClickTotals[$cat]++;
     }
  }






// $widgetCountInfo  = tracker_widget_category_counts($db);
// $widgetsByCategory=$widgetCountInfo["widgets_by_category"];

print_r($category_summary);
print_r($widgetHits);
print_r($widgetClicks);
print_r($hitsByCategory);
print_r($clicksByCategory);
exit;

// $no_data = "0/0";
$no_data = "-";

//--- Categories with No Activity  ------- No Hits, No Clicks --------
$no_hits   = array();
$no_clicks = array();
$j=0;
for ($i=0; $i<35; $i++, $j++)   $no_hits[$i]   = 0;
for ($i=0; $i<35; $i++, $j++)   $no_clicks[$i] = 0;
$category_none = array("hits"=>$no_hits, "clicks"=>$no_clicks, "fiveweektotalhits"=>0, "fiveweektotalclicks"=>0);
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
$sheet->writeString(0,  1, "", $format_header_even);
$sheet->writeString(0,  2,  $week_start_dates[1], $format_header_odd );     // ROW. COL, DATA, FORMAT
$sheet->writeString(0,  9,  $week_start_dates[2], $format_header_even);
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
$sheet->writeString(1, 1, "Widgets",  $format_header_even);
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


$ctotalhits   = array();
$ctotalclicks = array();
for ($i=0; $i<35; $i++)   $ctotalhits[$i]   = 0;
for ($i=0; $i<35; $i++)   $ctotalclicks[$i] = 0;

$row=2;
$col=0;
asort($ProductCategories);
foreach ($ProductCategories AS $cat => $ctitle)
  {
    if (isset($category_summary[$cat]))
      $category_data = $category_summary[$cat];
    else
      $category_data = $category_none;

    $hits        = $category_data["hits"];
    $clicks      = $category_data["clicks"];
    $totalhits   = $category_data["fiveweektotalhits"];
    $totalclicks = $category_data["fiveweektotalclicks"];

    $sheet->writeString($row,0,$ctitle."  ($cat)");

    $m1=8;
    $m2=4;
    if (isset($widgetsByCategory[$cat]))
       $widgets = "(". $m1 ."/". $m2 .")";
    else
       $widgets = "(0/0)";

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

$sheet->writeString($row, 1, "(XX/XX)", $format_label_totals);


$grandTotalHits=0;
$grandTotalClicks=0;

$inx=0;
for ($i=0; $i<5; $i++)
  {
    for ($j=0; $j<7; $j++)
      {
        $h = $ctotalhits[$inx+$j];
        $c = $ctotalclicks[$inx+$j];
        $grandTotalHits+=$h;
        $grandTotalClicks+=$c;

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

$data=$grandTotalHits."/".$grandTotalClicks;
$sheet->writeString($row, 37, $data, $format_data_grand_total);

$xls->close();
?>
