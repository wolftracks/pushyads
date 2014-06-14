<?php
// include_once("pushy_common.inc");
// include_once("pushy_commonsql.inc");
// include_once("pushy.inc");
// include_once("pushy_tracker.inc");
//
// $db = getPushyDatabaseConnection();
//
//
//---------------------------------------------------------------------------------

include_once("pushy_tracker.inc");


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



function ad_tracker_summary($db)
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


// $no_data = "0/0";
$no_data = "-";

$summary = ad_tracker_summary($db);
$category_summary     = $summary["categories"];
$adCategoriesHit      = $summary["adCategoriesHit"];
$adCategoriesClicked  = $summary["adCategoriesClicked"];
$adsHit               = $summary["adsHit"];
$adsClicked           = $summary["adsClicked"];
$adList               = $summary["adList"];
$adCategoryCounts     = $summary["adCategoryCounts"];
$adCategorySummary    = $summary["adCategorySummary"];
$adTotal              = $summary["adTotal"];

//printf("<PRE>\n");
//print_r($summary);
//printf("</PRE>\n");
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
?>
<table id="ACAT<?php echo $WEEK?>" width=100% border=0 cellspacing=0 cellpadding=0 bgcolor=#FFFFFF style="<?php echo $DISP?> border-collapse: collapse; margin-top: -2px;" class="smalltext">
  <tr height=40>
    <td width=21% bgcolor=#FFFFFF> &nbsp;</td>
    <td width=67% align=center valign=middle class="bdr_crnr1 largetext bold" bgcolor=#F1FEF1 colspan=8 style="border-top: 3px double #000000; border-right: 3px double #000000;">
      <table width=450 cellpadding=0 cellspacing=0 border=0 align=center>
        <tr>
           <td width=40  align=right><a href=javascript:acat_week(1)><img src="http://pds1106.s3.amazonaws.com/images/arrow2-lt.png" style="vertical-align:middle;"></a></td>
           <td width=40  align=right><a href=javascript:acat_week(<?php echo $WEEK-1?>)><img src="http://pds1106.s3.amazonaws.com/images/arrow-lt.png"   style="vertical-align:middle;"></a></td>
           <td width=290 align=center><span id="MonthName"><b><?php echo $MonthName?></b></span></td>
           <td width=40  align=left><a href=javascript:acat_week(<?php echo $WEEK+1?>)><img src="http://pds1106.s3.amazonaws.com/images/arrow-rt.png"   style="vertical-align:middle;"></a></td>
           <td width=40  align=left><a href=javascript:acat_week(5)><img src="http://pds1106.s3.amazonaws.com/images/arrow2-rt.png"  style="vertical-align:middle;"></a></td>
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
      if (isset($category_summary[$cat]))
        $category_data = $category_summary[$cat];
      else
        $category_data = $category_none;

      $hits        = $category_data["hits"];
      $clicks      = $category_data["clicks"];
      $totalhits   = $category_data["fiveweektotalhits"];
      $totalclicks = $category_data["fiveweektotalclicks"];

      $h=0; $c=0;

      if (isset($adCategoriesHit[$cat]))
         $h = count($adCategoriesHit[$cat]);
      if (isset($adCategoriesClicked[$cat]))
         $c = count($adCategoriesClicked[$cat]);
      if ($h==0 && $c==0)
        $data = $no_data;
      else
        $data = "( $h / $c )";

?>

      <tr height=24 bgcolor=#FFFFFF>
        <td class="smalltext" bgcolor=#F1FEF1 style="font-size:11px; padding-left: 2px;">
            <?php
                 echo $ctitle;
              // echo $ctitle."  ($cat)";
            ?>
        </td>
        <td align=center class="bdr_crnr3 border_rt2" style="font-size:11px;" bgcolor=#FFF8EB><?php echo $data?></td>

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

            echo "<td align=center class=\"bdr_crnr$cls\" style=\"font-size:11px;\">".$data."</td>\n";
            $cls=4;
          }


        if ($totalhits==0 && $totalclicks==0)
          $data=$no_data;
        else
          $data=$totalhits."/".$totalclicks;
        echo "<td align=center class=\"bdr_crnr3\" style=\"font-size:11px;\" bgcolor=\"#F1FEF1\">".$data."</td>\n";
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
     $h=count($adsHit);
     $c=count($adsClicked);
     if ($h==0 && $c==0)
       $data=$no_data;
     else
       $data="($h/$c)";
?>

     <td align=center class="bdr_crnr3 border_rt2" style="font-size:11px;" bgcolor=#FFF8EB><?php echo $data?></td>

<?
     $grandTotalHits=0;
     $grandTotalClicks=0;

     $cls=3;
     $inx = ($WEEK-1) * 7;
     for ($j=0; $j<7; $j++)
       {
         $h = $ctotalhits[$inx+$j];
         $c = $ctotalclicks[$inx+$j];
         $grandTotalHits   += $h;
         $grandTotalClicks += $c;

         if ($h==0 && $c==0)
           $data=$no_data;
         else
           $data=$h."/".$c;

         echo "<td align=center class=\"bdr_crnr$cls\" style=\"font-size:11px;\">".$data."</td>\n";
         $cls=4;
       }


     $data=$grandTotalHits."/".$grandTotalClicks;
     echo "<td align=center class=\"bdr_crnr3\" style=\"font-size:11px;\" bgcolor=\"#F1FEF1\">".$data."</td>\n";
?>

  </tr>


  <tr><td colspan=10>&nbsp;</td></tr>
  <tr height=24>
    <td bgcolor="#FFFFFF">Widgets - 1 Category:</td>
    <td bgcolor="#FFFFFF" align=right>6 &nbsp;&nbsp;</td>
    <td bgcolor="#FFFFFF" colspan=8 align=left>&nbsp;</td>
  </tr>
  <tr height=24>
    <td bgcolor="#FFFFFF">Widgets - 2 Categories:</td>
    <td bgcolor="#FFFFFF" align=right>26 &nbsp;&nbsp;</td>
    <td bgcolor="#FFFFFF" colspan=8 align=left>&nbsp;</td>
  </tr>
  <tr height=30>
    <td bgcolor="#FFFFFF"><b>Total Widgets:</b></td>
    <td bgcolor="#FFFFFF" align=right><b>32 &nbsp;&nbsp;</b></td>
    <td bgcolor="#FFFFFF" colspan=8 align=left>&nbsp;</td>
  </tr>
  <tr><td colspan=10>&nbsp;</td></tr>
</table>
<?php
    }
?>
