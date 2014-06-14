<?php
//----------------------- Direct Referrals --------------------
$referrals=array(0,0,0,0,0);
$total_referrals=0;

$referrals_vip=0;
$referrals_pro=0;
$referrals_elite=0;

$sql    = "SELECT user_level,COUNT(*) FROM member";
$sql   .= " WHERE refid='$mid'";
$sql   .= " AND   registered>0";
$sql   .= " AND   member_disabled=0";
$sql   .= " GROUP BY user_level";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
      {
        $user_level = $myrow[0];
        $count      = $myrow[1];
        $referrals[$user_level]=$count;
      }
  }


$result = tree_getDownlineInfo($db, $mid);

$total_descendants = $result["TOTAL_DESCENDANTS"];
$user_level_counts = $result["USER_LEVEL_COUNTS"];
$tree_level_counts = $result["TREE_LEVEL_COUNTS"];
$user_level        = $memberRecord["user_level"];
$user_level_name   = $UserLevels[$user_level];

for ($j=count($tree_level_counts); $j<6; $j++)  //  Reporting a Minimum of 6 levels - even if Zeroes
  {
    $tree_level_counts[$j]=0;
  }

$vip_exposure   = 0;
$pro_exposure   = 0;
$elite_exposure = 0;

for ($i=0; $i<count($tree_level_counts); $i++)
  {
    if ($i < 2)
      $vip_exposure   +=  $tree_level_counts[$i];
    else
    if ($i < 5)
      $pro_exposure   +=  $tree_level_counts[$i];
    else
      $elite_exposure +=  $tree_level_counts[$i];
  }

$total_exposure = $vip_exposure + $pro_exposure + $elite_exposure;

// $temp=0;
// for ($m=0; $m<count($tree_level_counts); $m++)
//   $temp+=$tree_level_counts[$m];
//



//---------------------- DEBUG - LEAVE EARLY
//  echo "<PRE>\n";
//  print_r($result);
//  printf("\ntemp=%d\n",$temp);
//  printf("\nvip_exposure=%d\n",  $vip_exposure);
//  printf("\npro_exposure=%d\n",  $pro_exposure);
//  printf("\nelite_exposure=%d\n",$elite_exposure);
//  echo "</PRE>\n";
//
//  $contents = ob_get_contents();
//  ob_end_clean();
//  $response= new stdClass();
//  $response->success     = "TRUE";
//  $response->html  = $contents;
//  sendJSONResponse(0, $response, null);
//  exit;
//------------------------------------------


/*---------------- tree_getDownlineInfo --
Array
(
    [TOTAL_DESCENDANTS] => 54
    [USER_LEVEL_COUNTS] => Array
        (
            [0] => 22
            [1] => 13
            [2] => 19
            [3] => 0
            [4] => 0
            [5] => 0
            [6] => 0
            [7] => 0
        )

    [TREE_LEVEL_COUNTS] => Array
        (
            [0] => 4
            [1] => 5
            [2] => 2
            [3] => 2
            [4] => 3
            [5] => 4
            [6] => 4
            [7] => 3
            [8] => 4
            [9] => 3
            [10] => 1
            [11] => 1
            [12] => 1
            [13] => 1
            [14] => 1
            [15] => 1
            [16] => 2
            [17] => 2
            [18] => 3
            [19] => 1
            [20] => 1
            [21] => 1
            [22] => 2
            [23] => 2
        )

)

temp=54
-------------- tree_getDownlineInfo --*/

?>


<div align=right style="margin: -41px 0 0 640px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/reports-network.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
</div>

<table width=680 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF">
      <table width=100% align=center valign=top cellspacing=0 cellpadding=0 style="padding:15px">
        <tr valign=top>
          <td class="text" style="padding-bottom:12px">
              This report shows you the extent of your actual & potential ad exposure. Depending on your membership level (which is <b><?php echo $user_level_name?></b>). You can have two
              levels, on up to <span class=darkred><i>infinite levels of exposure for your ads</i></span>. Nope, this isn't network marketing in the traditional sense. However, it's <b>a powerful algorithm</b> for
              marketing your products, services, blog, or opportunity to virtually endless networks. <a href=javascript:reports_network_expand('NETWORK_LEARN_MORE')>[ learn more ]</a></td>
        </tr>
        <tr ID="NETWORK_LEARN_MORE" style="display:none">
          <td class="text" style="padding-top:0px">
              OK, so "endless" is a bit of a stretch, since there is a limit to how many eyeballs there are on the planet (almost 14 billion). So let's
              be realistic! How many of those eyeballs could potentially see your ad if you play your cards right?

            <p>As a <b>VIP</b> member, you only get two levels of exposure in the <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482
              algorithm. As a <b>PRO</b> member, you get 5 levels of exposure. And as an <b>ELITE</b>, you get <b>UNLIMITED</b> levels of exposure.</p>

            <p>So what's a "level of exposure"? A level of ad exposure is created by members who are referred to the
              <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 Ad Network by another member. When you refer anyone
              who signs up as a member, they are on level <b>1</b> of your ad network. Ads you submit on the network will be seen by them, according to the algorithm. When
              they place <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on their website, your ad will be shown to all visitors
              who come to their website. Everyone you refer, and every visitor who comes to their web site with
              <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on it represents one level of ad exposure.

            <p>The colored sections below depict the number of actual members who will positively see your ad scrolling inside any & all
              <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">'s&#8482 they install on their website. Bigger than this, every single
              visitor to their website will see your ad scrolling as well. Do you see the <a href="#">exponential potential</a>?</p>

            <p>OK, so there's more. Yep, the picture gets even bigger! Consider that as an <b>ELITE</b> member, you would have <b>INFINITE</b> levels of exposure and
              commissions, based on the powerful <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482
              <a href="javascript:void(0)">algorithm</a> pushing ads through hundreds and thousands of widgets on websites where thousands upon thousands of
              <b>targeted eyeballs</b> are seeing your  ad<a href="javascript:void(0)">(s)</a>. Remember how your product categories are matched with website visitor's interest?

            <p>Listen, we don't expect everyone to understand the size and magnitue of this algorithm. But if you can understand how referring just one member, who has a
              website with traffic on it could expose your product to hundreds or thousands a day, just multiply that by the number of members you can refer, who in turn refer
              others, who in return.... well, you get the point! This is your  <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482
              <b>Referral Network</b>, that could go INFINITE levels deeap, depending on how many eye balls you want looking at your product.

            <p>Oh, and then there's the entire <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 Network, which is
              comprised of every member in the database, having websites with scrolling ads, which your ad could be seen in, based on the
              <a href="javascript:void(0)"><b>Credit Ad Pool</b></a> and <a href="javascript:void(0)"><b>ELITE Ad Pool</b></a>, which grow exponentially, depending upon
              how much activity is within your own network.

            <p>Ya, I know.... pretty mind boggling isn't it? I think it's time to
              <b>GET</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482, don't you? &nbsp;&nbsp;&nbsp;

            <a href="javascript:reports_network_collapse('NETWORK_LEARN_MORE')">[ hide ]</a></p>

          </td>
        </tr>

        <tr>
          <td class="text">

            <!----------------------------------- FUTURE PR #1130 ----------------------------------->
            <!-- table width=100% class=text border=0 cellpadding=0 cellspacing=0>
              <tr height=55 valign=middle>
                <td width="15%"><b>Personal:</b> &nbsp; <span class=darkred>0</span></td>
                <td width="25%" align=right><b>Actual:</b>&nbsp; <span class=darkred>0</span></td>
                <td width="30%" align=right><b>Potential:</b>&nbsp; <span class=darkred>0</span></td>
                <td width="30%" align=right><b>PUSHY!:</b>&nbsp; <span class=darkred>0</span></td>
              </tr>
            </table -->

            <table width=100% class="smallgridb1 black" border=0 cellspacing=0 cellpadding=0>
              <tr valign="bottom" bgcolor="#DEE2E7">
                <td width="30%" align=center style="padding-bottom: 4px;"><b>Network Level</b></td>
                <td width="30%" align=center style="padding-bottom: 4px;"><b><img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> Members</b></td>
                <td width="40%" align=left><b>Total Ad Exposure</b>
                  <a href="#" onClick="return false" style="cursor:help">
                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-REPORTS-POTENTIAL')"></a>
                  <div valign=top align=right class=darkred style="position: relative; width:150px; margin: -13px 0 0 150px"><b><?php echo $total_exposure?>  Referrals</b></div>
                </td>
              </tr>

              <tr valign="middle" bgcolor=#FFFDF4>
                <td align=center>1</td>
                <td align=center><?php echo $tree_level_counts[0]?></td>
                <td rowspan="2" align=left valign=bottom><b>Total VIP Exposure: </b>
                  <div valign=top align=right class=darkred style="position: relative; width:150px; margin: -13px 0 0 150px"><b><?php echo $vip_exposure?>  Referrals</b></div></td>
              </tr>
              <tr valign="middle" bgcolor=#FFFDF4>
                <td align=center>2</td>
                <td align=center><?php echo $tree_level_counts[1]?></td>
              </tr>


              <tr valign="middle" bgcolor=#F1FEF1>
                <td align=center>3</td>
                <td align=center><?php echo $tree_level_counts[2]?></td>
                <td rowspan="3" align=left valign=bottom><b>Total PRO Exposure: </b>
                  <div valign=top align=right class=darkred style="position: relative; width:150px; margin: -13px 0 0 150px"><b><?php echo $pro_exposure?>  Referrals</b></div></td>
              </tr>
              <tr valign="middle" bgcolor=#F1FEF1>
                <td align=center>4</td>
                <td align=center><?php echo $tree_level_counts[3]?></td>
              </tr>
              <tr valign="middle" bgcolor=#F1FEF1>
                <td align=center>5</td>
                <td align=center><?php echo $tree_level_counts[4]?></td>
              </tr>


              <tr valign="middle" bgcolor=#F2FDFF>
                <td align=center>6</td>
                <td align=center><?php echo $tree_level_counts[5]?></td>
                <td rowspan="<?php echo (count($tree_level_counts)-5)?>" align=left valign=bottom><b>Total ELITE Exposure: </b>
                  <div valign=top align=right class=darkred style="position: relative; width:150px; margin: -13px 0 0 150px"><b><?php echo $elite_exposure?> Referrals</b></div></td>
              </tr>

              <?php
                for ($j=6; $j<count($tree_level_counts); $j++)
                  {
              ?>
                    <tr valign="middle" bgcolor=#F2FDFF>
                      <td align=center><?php $k=$j+1; echo $k?></td>
                      <td align=center><?php echo $tree_level_counts[$j]?></td>
                    </tr>
              <?php
                  }
              ?>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
