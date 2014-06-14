<?php
include_once("pushy_tracker.inc");

$current_week_personal_domains  = 0;
$current_week_referral_domains  = 0;
$current_week_personal_traffic  = 0;
$current_week_referral_traffic  = 0;
$current_week_memberships_vip   = 0;
$current_week_memberships_pro   = 0;
$current_week_memberships_elite = 0;

$current_year_personal_domains  = 0;
$current_year_referral_domains  = 0;
$current_year_personal_traffic  = 0;
$current_year_referral_traffic  = 0;
$current_year_memberships_vip   = 0;
$current_year_memberships_pro   = 0;
$current_year_memberships_elite = 0;

$currentWeekStart=tracker_current_week();  // Current Week Start: Sunday
$dateArray=dateToArray($currentWeekStart);
$currentWeekEndArray=calStepDays(6,$dateArray);
$currentWeekEnd=dateArrayToString($currentWeekEndArray);  // Current Week Start: Sunday
$weekEnd = sprintf("%02d/%02d/%04d",$currentWeekEndArray["month"], $currentWeekEndArray["day"], $currentWeekEndArray["year"]);


$VIP_REPORT=FALSE;
if (is_array($memberRecord) && isset($memberRecord["user_level"]) && $memberRecord["user_level"]==$PUSHY_LEVEL_VIP)
  {
    $VIP_REPORT=TRUE;
  }
else
  {
    $current_year_personal_domains  = $memberRecord["current_year_personal_domains"];
    $current_year_referral_domains  = $memberRecord["current_year_referral_domains"];
    $current_year_personal_traffic  = $memberRecord["current_year_personal_traffic"];
    $current_year_referral_traffic  = $memberRecord["current_year_referral_traffic"];
    $current_year_memberships_vip   = $memberRecord["current_year_memberships_vip"];
    $current_year_memberships_pro   = $memberRecord["current_year_memberships_pro"];
    $current_year_memberships_elite = $memberRecord["current_year_memberships_elite"];


    $sql  = "SELECT COUNT(*) from widget ";
    $sql .= " WHERE member_id='$mid'";
    $sql .= " AND date_first_access>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_NUM)))
      {
        $current_week_personal_domains=$myrow[0];
      }

    $sql  = "SELECT COUNT(*) from widget ";
    $sql .= " WHERE refid='$mid'";
    $sql .= " AND date_first_access>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_NUM)))
      {
        $current_week_referral_domains=$myrow[0];
      }

    $sql  = "SELECT SUM(weekly_access_count) from widget ";
    $sql .= " WHERE member_id='$mid'";
    $sql .= " AND date_first_access>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_NUM)))
      {
        $current_week_personal_traffic=(int)$myrow[0];
      }

    $sql  = "SELECT SUM(weekly_access_count) from widget ";
    $sql .= " WHERE refid='$mid'";
    $sql .= " AND date_first_access>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_NUM)))
      {
        $current_week_referral_traffic=(int)$myrow[0];
      }


    $sql  = "SELECT COUNT(*) from member ";
    $sql .= " WHERE refid='$mid'";
    $sql .= " AND   user_level='$PUSHY_LEVEL_VIP'";
    $sql .= " AND date_registered>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result)))
      {
        $current_week_memberships_vip=$myrow[0];
      }

    $sql  = "SELECT COUNT(*) from member ";
    $sql .= " WHERE refid='$mid'";
    $sql .= " AND   user_level='$PUSHY_LEVEL_PRO'";
    $sql .= " AND date_registered>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result)))
      {
        $current_week_memberships_pro=$myrow[0];
      }

    $sql  = "SELECT COUNT(*) from member ";
    $sql .= " WHERE refid='$mid'";
    $sql .= " AND   user_level='$PUSHY_LEVEL_ELITE'";
    $sql .= " AND date_registered>='$currentWeekStart'";
    $result=mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result)))
      {
        $current_week_memberships_elite=$myrow[0];
      }
  }


$current_year_personal_domains  +=   $current_week_personal_domains;
$current_year_referral_domains  +=   $current_week_referral_domains;

$current_year_memberships_vip   +=   $current_week_memberships_vip;
$current_year_memberships_pro   +=   $current_week_memberships_pro;
$current_year_memberships_elite +=   $current_week_memberships_elite;

$current_year_personal_traffic  +=   $current_week_personal_traffic;
$current_year_referral_traffic  +=   $current_week_referral_traffic;

//----------

$weekly_total_activities   =  $current_week_personal_domains;
$weekly_total_activities  +=  $current_week_referral_domains;

$weekly_total_activities  +=  $current_week_memberships_vip;
$weekly_total_activities  +=  $current_week_memberships_pro;
$weekly_total_activities  +=  $current_week_memberships_elite;

$weekly_total_activities  +=  $current_week_personal_traffic;
$weekly_total_activities  +=  $current_week_referral_traffic;

//---------

$weekly_total_credits      =    $current_week_personal_domains  * $PUSHY_CREDITS_PERSONAL_DOMAINS;
$weekly_total_credits     +=  ( $current_week_referral_domains  * $PUSHY_CREDITS_REFERRAL_DOMAINS  );
$weekly_total_credits     +=  ( $current_week_memberships_vip   * $PUSHY_CREDITS_MEMBERSHIPS_VIP   );
$weekly_total_credits     +=  ( $current_week_memberships_pro   * $PUSHY_CREDITS_MEMBERSHIPS_PRO   );
$weekly_total_credits     +=  ( $current_week_memberships_elite * $PUSHY_CREDITS_MEMBERSHIPS_ELITE );
$weekly_total_credits     +=  ( $current_week_personal_traffic  * $PUSHY_CREDITS_PERSONAL_TRAFFIC  );
$weekly_total_credits     +=  ( $current_week_referral_traffic  * $PUSHY_CREDITS_REFERRAL_TRAFFIC  );

//---------

$yearly_total_activities   =  $current_year_personal_domains;
$yearly_total_activities  +=  $current_year_referral_domains;

$yearly_total_activities  +=  $current_year_memberships_vip;
$yearly_total_activities  +=  $current_year_memberships_pro;
$yearly_total_activities  +=  $current_year_memberships_elite;

$yearly_total_activities  +=  $current_year_personal_traffic;
$yearly_total_activities  +=  $current_year_referral_traffic;

//---------
$yearly_total_credits      =    $current_year_personal_domains  * $PUSHY_CREDITS_PERSONAL_DOMAINS;
$yearly_total_credits     +=  ( $current_year_referral_domains  * $PUSHY_CREDITS_REFERRAL_DOMAINS  );
$yearly_total_credits     +=  ( $current_year_memberships_vip   * $PUSHY_CREDITS_MEMBERSHIPS_VIP   );
$yearly_total_credits     +=  ( $current_year_memberships_pro   * $PUSHY_CREDITS_MEMBERSHIPS_PRO   );
$yearly_total_credits     +=  ( $current_year_memberships_elite * $PUSHY_CREDITS_MEMBERSHIPS_ELITE );
$yearly_total_credits     +=  ( $current_year_personal_traffic  * $PUSHY_CREDITS_PERSONAL_TRAFFIC  );
$yearly_total_credits     +=  ( $current_year_referral_traffic  * $PUSHY_CREDITS_REFERRAL_TRAFFIC  );
//---------
?>

<div align=right style="margin: -41px 0 0 640px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/reports-credit.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
</div>

<?php
if ($VIP_REPORT)
  {
?>
<table width=680 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF">
      <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
        <tr>
          <td class="text">

            <center><img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">
            <span class="size18 darkred tahoma bold">is really sorry, <?php echo $firstname?>!</span></center>

            <p>But as a <b>VIP</b>, you won't receive Credits for any of your <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482
              activity. Oh, you'll get paid for referrals, but you'll only see big <b>0</b>'s, in all the columns of your Credit Report, like you see in the screenshot below.

            <p>Worse than that, you don't get any of <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">'s world renouned
              <b>Viral Traffic</b> that acts like a snow ball, or a speeding locomotive. Once it gains speed, hardly nothing can stop it, and it only increases in size, speed,
              and velocity as it continues down the track.

            <p>For only $47 a month, you could <a href=javascript:membership_plan()>upgrade</a> your membership to a <b>PRO</b> member, and not only get credit
              for all your activity, but get paid higher commissions for any <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482
              referrals who signup, and a ton of other benefits too (see all the
              <a href="javascript:openPopup('/members/popup.php?tp=pop-compare&sid=<?php echo $sid?>&mid=<?php echo $mid?>',630,700,true)">features and benefits</a> here).

            <p>To sum it all up, as a <b>PRO</b> or <b>ELITE</b> member, you can participate in the Credit Ad Pool, which generates <b>Viral Traffic</b> for your ads, whenever

              <ul>
                 <li>You install <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on a website</li>
                 <li>One of your referrals installs <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on their website(s)</li>
                 <li>Someone you refer to <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 signs up</li>
                 <li>There is traffic on your website that has <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on it.</li>
                 <li>There is traffic on your referral's websites which have <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px">&#8482 on them.</li>
              </ul>

            Come on <?php echo $firstname?>! What do ya say? <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> wants to reward you
            <b>BIG TIME</b> with lotsa traffic and green stuff for all your simple efforts. Ready? OK, let's do it! <a href=javascript:membership_plan()>Upgrade Now!</a> Your credits will
            start accumulating the day you upgrade.
            <br>&nbsp;
<?php
  }
else
  {
?>

<table width=680 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF">
      <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
        <tr>
          <td class="text">

            This is your credit report. Only this one actually rewards you with lots of traffic (that is, if you've done anything to deserve it).
            What do you need to do in order to deserve it?

            <p style="margin-bottom: 20px;">Well, just tell other people about
            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> (2-8 credits each) Put him on your website (40 credits each). Get your referrals
            to put him on their website  (20 credits each). In other words, tell the world to <b>Get</b>
            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> and we'll reward you <b>BIG TIME</b>! Now how easy is that?</p>

            <p style="margin-bottom: 20px;">Your total credits earned each week become a percentage of the Credit Ad Pool, which will be converted into traffic to your
            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> ad the following week.

<?php
  }
?>

            <div align=center>
            <table width=95% valign=top border=0 cellspacing=0 cellpadding=0 class="text gridb1">
              <tr bgcolor=#D0D6DF height=35>
                <td width=36%><b>&nbsp; Week Ending <?php echo $weekEnd?></b></td>
                <td width=32% align=center colspan=2><b>THIS WEEK</b></td>
                <td width=32% align=center colspan=2><b>&nbsp; YEAR TO DATE</b></td>
              </tr>

              <tr bgcolor=#F1FEF1 height=30>
                <td width=36% bgcolor=#F0F6FF style="border-bottom: 3px double #899CB0;"><b>&nbsp; Type of Activity</b>
                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-REPORTS-CREDIT')"></a></td>
                <td width=16% align=center bgcolor=#FFF4DE style="border-bottom: 3px double #899CB0;"><b>Activity</b></td>
                <td width=16% align=center style="border-bottom: 3px double #899CB0;"><b>Credit</b></td>
                <td width=16% align=center bgcolor=#FFF4DE style="border-bottom: 3px double #899CB0;"><b>Activity</b></td>
                <td width=16% align=center style="border-bottom: 3px double #899CB0;"><b>Credit</b></td>
              </tr>

              <tr height=40 valign=bottom>
                <td colspan=1 style="padding-left: 20px;">&nbsp; <b>PUSHY Installations</b></td>
                <td colspan=2>&nbsp;</td>
                <td colspan=2>&nbsp;</td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">Personal Domains</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_personal_domains)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_personal_domains * $PUSHY_CREDITS_PERSONAL_DOMAINS)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_personal_domains)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_personal_domains * $PUSHY_CREDITS_PERSONAL_DOMAINS)?></td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">Referral Domains</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_referral_domains)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_referral_domains * $PUSHY_CREDITS_REFERRAL_DOMAINS)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_referral_domains)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_referral_domains * $PUSHY_CREDITS_REFERRAL_DOMAINS)?></td>
              </tr>

              <tr height=40 valign=bottom>
                <td style="padding-left: 20px;" class=bold>&nbsp; <b>Membership Referrals</b></td>
                <td colspan=2>&nbsp;</td>
                <td colspan=2>&nbsp;</td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">VIP</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_memberships_vip)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_memberships_vip * $PUSHY_CREDITS_MEMBERSHIPS_VIP)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_memberships_vip)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_memberships_vip * $PUSHY_CREDITS_MEMBERSHIPS_VIP)?></td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">PRO</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_memberships_pro)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_memberships_pro * $PUSHY_CREDITS_MEMBERSHIPS_PRO)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_memberships_pro)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_memberships_pro * $PUSHY_CREDITS_MEMBERSHIPS_PRO)?></td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">ELITE</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_memberships_elite)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_memberships_elite * $PUSHY_CREDITS_MEMBERSHIPS_ELITE)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_memberships_elite)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_memberships_elite * $PUSHY_CREDITS_MEMBERSHIPS_ELITE)?></td>
              </tr>

              <tr height=40 valign=bottom>
                <td style="padding-left: 20px;">&nbsp; <b>PUSHY Traffic Volume</b></td>
                <td colspan=2>&nbsp;</td>
                <td colspan=2>&nbsp;</td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">Personal Websites</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_personal_traffic)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_personal_traffic * $PUSHY_CREDITS_PERSONAL_TRAFFIC)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_personal_traffic)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_personal_traffic * $PUSHY_CREDITS_PERSONAL_TRAFFIC)?></td>
              </tr>

              <tr>
                <td><span style="padding-left: 35px;">Referral Websites</span></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_week_referral_traffic)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_week_referral_traffic * $PUSHY_CREDITS_REFERRAL_TRAFFIC)?></td>
                <td align=center bgcolor=#FFFAF0><?php echo number_format($current_year_referral_traffic)?></td>
                <td align=center bgcolor=#F7FCF7><?php echo number_format($current_year_referral_traffic * $PUSHY_CREDITS_REFERRAL_TRAFFIC)?></td>
              </tr>

              <tr height=45 valign=bottom>
                <td style="padding: 0 0 7px 20px;">&nbsp; <b>TOTALS</b> .............</td>
                <td align=center style="border-right: 0px solid #FFFFFF; font-size: 14px; padding-bottom: 7px;">
                  <b style="border-bottom: 3px double #999999;"><?php echo number_format($weekly_total_activities)?></td>
                <td align=center style="border-left: 0px solid #FFFFFF; font-size: 14px; padding-bottom: 7px;">
                  <b style="border-bottom: 3px double #999999;"><?php echo number_format($weekly_total_credits)?></b></td>
                <td align=center style="border-right: 0px solid #FFFFFF; font-size: 14px; padding-bottom: 7px;">
                  <b style="border-bottom: 3px double #999999;"><?php echo number_format($yearly_total_activities)?></b></td>
                <td align=center style="border-left: 0px solid #FFFFFF; font-size: 14px; padding-bottom: 7px;">
                  <b style="border-bottom: 3px double #999999;"><?php echo number_format($yearly_total_credits)?></b></td>
              </tr>

            </table>
            </div>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
