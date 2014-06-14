<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

require_once "Spreadsheet/Excel/Writer.php";

$db = getPushyDatabaseConnection();

$week_start_dates = tracker_dates();

function tracker_widgets($db)
  {
    $unique_widgets=0;
    $sql  = "SELECT COUNT(*) FROM tracker_pushy_category";
    $sql .= "  GROUP BY member_id,tracker_id";
    $result=mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result,MYSQL_NUM))
      $unique_widgets=(int)$myrow[0];

    return $unique_widgets;
  }


function tracker_sum_category($db)
  {
    $categories = array();

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  userkey ";
    $sql .= "  FROM tracker_pushy_category";
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

            $categories[$category] = array("hits"=>$hits, "clicks"=>$clicks, "fiveweektotalhits"=>$totalhits, "fiveweektotalclicks"=>$totalclicks);

          }
      }

    $unique_widgets=0;
    $sql  = "SELECT COUNT(*) FROM tracker_pushy_category";
    $sql .= "  GROUP BY member_id,tracker_id";
    $result=mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result,MYSQL_NUM))
      $unique_widgets=(int)$myrow[0];

    return $categories;
  }


$category_summary = tracker_sum_category($db);
$total_catagories = count($category_summary);



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

// Add the format. This could be done all together.
// I keep it separated for ease of use
// $format =& $xls->addFormat($format_a);

// Now apply the format to the cells you want to merge
// This is the same as:
//    $sheet->write(0,0,'',$format);
//    $sheet->write(0,1,'',$format);
//    $sheet->write(0,2,'',$format);
//    $sheet->write(0,3,'',$format);
//    $sheet->write(0,4,'',$format);
// I just think it's cleaner

$sheet->setColumn( 0,   0,  25);
for ($n=1; $n<=35; $n++)
  $sheet->setColumn( $n, $n, 4);  // Col Width

// Write in the title or whatever you want in the merged cells

$sheet->writeString(0, 0, "", $format_header_even);
$sheet->writeString(0, 1,  $week_start_dates[1], $format_header_odd );     // ROW. COL, DATA, FORMAT
$sheet->writeString(0, 8,  $week_start_dates[2], $format_header_even);
$sheet->writeString(0, 15, $week_start_dates[3], $format_header_odd );
$sheet->writeString(0, 22, $week_start_dates[4], $format_header_even);
$sheet->writeString(0, 29, $week_start_dates[5], $format_header_odd );
$sheet->writeString(0, 36, "", $format_label_totals);

$sheet->mergeCells(0,  1, 0,  7);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0,  8, 0, 14);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0, 15, 0, 21);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0, 22, 0, 28);                  // ROW, COL, LASTROW, LASTCOL
$sheet->mergeCells(0, 29, 0, 35);                  // ROW, COL, LASTROW, LASTCOL

$sheet->setColumn( 0,   0,  25);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn( 1,   7,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn( 8,  14,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(15,  21,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(22,  28,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(29,  35,  15);                   // FIRSTCOL, LASTCOL, WIDTH
$sheet->setColumn(26,  36,  20);                   // FIRSTCOL, LASTCOL, WIDTH


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

$format_data_bold  =& $xls->addFormat();
$format_data_bold->setAlign('center');
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
for ($i=0; $i<5; $i++)
  {
    for ($j=0; $j<7; $j++)
      {
        $dow=substr($day_names[$j],0,1);
        $col=$inx+$j+1;
        if ($z%2==0)
           $sheet->writeString(1, $col, $dow, $format_dow_even);
        else
           $sheet->writeString(1, $col, $dow, $format_dow_odd);
        $z++;
      }
    $inx+=7;
  }
$sheet->writeString(1, 36, "TOTALS", $format_label_totals);


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

    $sheet->write($row,0,$ctitle."  ($cat)");

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

            $col=$inx+$j+1;
            $sheet->writeString($row, $col, $data, $format_data);
          }
        $inx+=7;
      }

    if ($totalhits==0 && $totalclicks==0)
      $data=$no_data;
    else
      $data=$totalhits."/".$totalclicks;

    $sheet->writeString($row, 36, $data, $format_data_bold);
    $row++;
  }



//--- Totals -------------
$sheet->writeString($row, 0, "TOTALS", $format_label_totals);


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

        if ($h==0 && $c==0)
          {
            $data=$no_data;
            $col=$inx+$j+1;
            $sheet->writeString($row, $col, $data, $format_data_bold);
          }
        else
          {
            $data=$h."/".$c;
            $col=$inx+$j+1;
            $sheet->writeString($row, $col, $data, $format_data_totals);
          }
      }
    $inx+=7;
  }

$data=$grandTotalHits."/".$grandTotalClicks;
$sheet->writeString($row, 36, $data, $format_data_grand_total);

$xls->close();
?>
