<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

require_once "Spreadsheet/Excel/Writer.php";

$db = getPushyDatabaseConnection();

$week_start_dates = tracker_dates();

function tracker_getConfiguredAdCategoryCounts($db,$adList)
  {
    if (count($adList)==0) return $adList;

    $sql  = "SELECT ad_id, product.product_categories from ads JOIN product USING(product_id)";

    $j=0;
    foreach($adList AS $ad_id => $c)
      {
        if ($j==0)
          $sql .= " WHERE ";
        else
          $sql .= " OR ";
        $sql .= "ad_id='$ad_id'";
        $j++;
      }
    $result=mysql_query($sql,$db);

    if (FALSE)
     {
       printf("SQL: %s<br>\n",$sql);
       printf("ERR: %s<br>\n",mysql_error());
     }

    $configuredAdCategoryCounts=array();
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
          {
            $ad_id         = $myrow['ad_id'];
            $ad_categories = $myrow['product_categories'];

            $ac_count=0;
            $tarray = explode("~",$ad_categories);
            for ($i=0; $i<count($tarray); $i++)
             {
               if (strlen($tarray[$i])>0) $ac_count++;
             }
            $configuredAdCategoryCounts[$ad_id]=$ac_count;
          }
      }
    return $configuredAdCategoryCounts;
  }



function tracker_summary($db)
  {
    $categories  = array();
    $adCategoriesHit     = array();
    $adCategoriesClicked = array();
    $adsHit              = array();
    $adsClicked          = array();
    $adList              = array();

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  userkey,ad_id";
    $sql .= "  FROM tracker_ad_category";
    $sql .= "  GROUP BY userkey,ad_id";
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
            $ad_id       = $myrow[71];

            if ($totalhits > 0 || $totalclicks > 0)
              {
                $adList[$ad_id]=0;

                $categories[$category] = array("hits"=>$hits, "clicks"=>$clicks, "fiveweektotalhits"=>$totalhits, "fiveweektotalclicks"=>$totalclicks);

                if ($totalhits > 0)
                  {
                    $adCategoriesHit[$category][$ad_id]=true;
                    $adsHit[$ad_id]=true;
                  }
                if ($totalclicks > 0)
                  {
                    $adCategoriesClicked[$category][$ad_id]=true;
                    $adsClicked[$ad_id]=true;
                  }
              }
          }
      }

    $adCategoryCounts = tracker_getConfiguredAdCategoryCounts($db,$adList);

    $adCategorySummary= array(0,0,0,0,0,0,0,0);

    $adTotal=0;
    foreach($adList AS $ad_id => $nan)
      {
        $categoryCount=0;
        if (isset($adCategoryCounts[$ad_id]))
          $categoryCount=$adCategoryCounts[$ad_id];
        $adList[$ad_id]=$categoryCount;
        if ($categoryCount>0)
          {
            $adCategorySummary[$categoryCount]++;
            $adTotal++;
          }
      }

    $summary = array(
                      "categories"          => $categories,
                      "adCategoriesHit"     => $adCategoriesHit,
                      "adCategoriesClicked" => $adCategoriesClicked,
                      "adsHit"              => $adsHit,
                      "adsClicked"          => $adsClicked,
                      "adList"              => $adList,
                      "adCategoryCounts"    => $adCategoryCounts,
                      "adCategorySummary"   => $adCategorySummary,
                      "adTotal"             => $adTotal,
                    );
    return $summary;
  }


$summary = tracker_summary($db);
$category_summary     = $summary["categories"];
$adCategoriesHit      = $summary["adCategoriesHit"];
$adCategoriesClicked  = $summary["adCategoriesClicked"];
$adsHit               = $summary["adsHit"];
$adsClicked           = $summary["adsClicked"];
$adList               = $summary["adList"];
$adCategoryCounts     = $summary["adCategoryCounts"];
$adCategorySummary    = $summary["adCategorySummary"];
$adTotal              = $summary["adTotal"];

// print_r($summary);
// exit;

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

    $h=0; $c=0;

    if (isset($adCategoriesHit[$cat]))
       $h = count($adCategoriesHit[$cat]);
    if (isset($adCategoriesClicked[$cat]))
       $c = count($adCategoriesClicked[$cat]);

    if ($h==0)  //  && $c==0)
      $data = $no_data;
    else
      {
        // $data = "($h/$c)";
        $data = "($h)";
      }

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



$h=count($adsHit);
$c=count($adsClicked);
if ($h==0 && $c==0)
  $data=$no_data;
else
  $data="($h/$c)";

$sheet->writeString($row, 1, $data, $format_header_ads);


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

//-------------

$row+=2;

for ($j=1; $j<=7; $j++)
  {
    $adCategoryCount = $adCategorySummary[$j];
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
$sheet->writeString($row, 1, "$adTotal",  $format_data_bold);

$xls->close();
?>
