<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

require_once "Spreadsheet/Excel/Writer.php";

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
            if (isset($adCategories[$ad_id]) && ($adCategories[$ad_id] > 1))
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





// printf("\n********************** AAA ***********************\n");

$adInfo = getAdCounts($db);
list($adCounts, $total_ads, $adCategories) = $adInfo;

// print_r($adInfo);
// for ($i=1; $i<=7; $i++)
//  {
//    printf("Ads - %d Categories = %d\n", $i, $adCounts[$i]);
//  }
// printf("Total Unique Ads       = %d\n", $total_ads);
// printf("Number Ad Categories (1/7) on a Per Ad basis\n");
// print_r($adCategories);





// printf("\n********************** BBB ***********************\n");

$categoryInfo = getAdCountByCategory($db);
list($activeAds, $categories) = $categoryInfo;

//
// print_r($categoryInfo);
//
// printf("Active Ads  = %d\n", $activeAds);
// printf("Total Unique Ad Hits Per Category\n");
// print_r($categories);






// printf("\n********************** CCC ***********************\n");

$daily_activity =  daily_activity($db);

// print_r($daily_activity);
//
// foreach($daily_activity AS $category => $activity)
//   {
//     printf("Category: %-12s   HITS=%4d  CLICKS=%4d\n",$category, $activity["total_hits"], $activity["total_clicks"]);
//   }





// printf("\n****** DDD ****************** \n");

$daily_totals =  daily_totals($db,$adCategories);
list($ad_activity, $activity_totals)  =  $daily_totals;


// foreach($ad_activity AS $ad_id => $activity)
//   {
//     printf("ad_id: %-32s   HITS=%4d  CLICKS=%4d\n",$ad_id, $activity["total_hits"], $activity["total_clicks"]);
//   }
// printf("Day 33        :  %d / %d\n", $activity_totals["hitSum"][33],   $activity_totals["clickSum"][33]);
// printf("Day 34        :  %d / %d\n", $activity_totals["hitSum"][34],   $activity_totals["clickSum"][34]);
// printf("Totals        :  %d / %d\n", $activity_totals["hitSumTotal"],  $activity_totals["clickSumTotal"]);
//
//

//
// exit;
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

$format_header_ads =& $xls->addFormat();
$format_header_ads->setBold();
$format_header_ads->setSize(9);
$format_header_ads->setAlign('center');
$format_header_ads->setColor('white');     // ForeGround
$format_header_ads->setFgColor('black');   // Background

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
$sheet->writeString(0,  1, "", $format_header_ads);
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
$sheet->writeString(1, 1, "Ads",  $format_header_ads);
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

    $acount=0;
    if (isset($categories[$cat]))
       $acount = $categories[$cat];

    if ($acount==0)
      $data = $no_data;
    else
      $data = "($acount)";

    $sheet->writeString($row,1,$data,$format_data);

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

$data="($activeAds / $total_ads)";

$sheet->writeString($row, 1, $data, $format_header_ads);

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


$row+=2;

for ($j=1; $j<=7; $j++)
  {
    $adCategoryCount = $adCounts[$j];
    if ($j==1)
       $sheet->writeString($row, 0, "Ads - 1 Category");
    else
       $sheet->writeString($row, 0, "Ads - $j Categories");
    $sheet->writeString($row, 1, "$adCategoryCount",   $format_data_bold);
    $row++;
  }
$row++;
$row++;
$sheet->writeString($row, 0, "Ad Total");
$sheet->writeString($row, 1, "$total_ads",  $format_data_bold);

$xls->close();
?>
