<?php
$DEBUG=FALSE;

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

set_time_limit(0);

$dates = tracker_dates();
$currentWeekStartDate=$dates[5];    // Current Week Start: Sunday
$priorWeekStartDate  =$dates[4];    // Prior   Week Start: Sunday

// -- print_r($dates);

printf("    Current Week Start: %s\n",   $currentWeekStartDate);
printf("==> Prior   Week Start: %s\n\n", $priorWeekStartDate);

$db = getPushyDatabaseConnection();

$processed=0;

$tmStart=time();

$sql  = "SELECT member_id from member";
$sql .= " WHERE prior_week_start_date != '$priorWeekStartDate'";
$sql .= " AND (user_level='$PUSHY_LEVEL_PRO' OR user_level='$PUSHY_LEVEL_ELITE')";
$result=mysql_query($sql,$db);

// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());

if ($result)
  {
    while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        $mid = $myrow["member_id"];

        $week_personal_domains  = 0;
        $week_referral_domains  = 0;
        $week_personal_traffic  = 0;
        $week_referral_traffic  = 0;
        $week_memberships_vip   = 0;
        $week_memberships_pro   = 0;
        $week_memberships_elite = 0;

        $sql  = "SELECT COUNT(*) from widget ";
        $sql .= " WHERE member_id='$mid'";
        $sql .= " AND date_first_access>='$priorWeekStartDate'";
        $sql .= " AND date_first_access< '$currentWeekStartDate'";
        $res=mysql_query($sql,$db);
        if (($res) && ($myrow = mysql_fetch_array($res, MYSQL_NUM)))
          {
            $week_personal_domains=$myrow[0];
          }

        $sql  = "SELECT COUNT(*) from widget ";
        $sql .= " WHERE refid='$mid'";
        $sql .= " AND date_first_access>='$priorWeekStartDate'";
        $sql .= " AND date_first_access< '$currentWeekStartDate'";
        $res=mysql_query($sql,$db);
        if (($res) && ($myrow = mysql_fetch_array($res, MYSQL_NUM)))
          {
            $week_referral_domains=$myrow[0];
          }

        $sql  = "SELECT SUM(weekly_access_count) from widget ";
        $sql .= " WHERE member_id='$mid'";
        $sql .= " AND date_first_access>='$priorWeekStartDate'";
        $sql .= " AND date_first_access< '$currentWeekStartDate'";
        $res=mysql_query($sql,$db);
        if (($res) && ($myrow = mysql_fetch_array($res, MYSQL_NUM)))
          {
            $week_personal_traffic=(int)$myrow[0];
          }

        $sql  = "SELECT SUM(weekly_access_count) from widget ";
        $sql .= " WHERE refid='$mid'";
        $sql .= " AND date_first_access>='$priorWeekStartDate'";
        $sql .= " AND date_first_access< '$currentWeekStartDate'";
        $res=mysql_query($sql,$db);
        if (($res) && ($myrow = mysql_fetch_array($res, MYSQL_NUM)))
          {
            $week_referral_traffic=(int)$myrow[0];
          }

        $sql  = "SELECT user_level, COUNT(*) from member ";
        $sql .= " WHERE refid='$mid'";
        $sql .= " AND date_registered>='$priorWeekStartDate'";
        $sql .= " AND date_registered< '$currentWeekStartDate'";
        $sql .= " GROUP BY user_level";
        $res=mysql_query($sql,$db);

        // printf("SQL:%s\n",$sql);
        // printf("ERR:%s\n",mysql_error());

        if ($res)
          {
            while ($myrow = mysql_fetch_array($res, MYSQL_NUM))
              {
                if ($myrow[0]==$PUSHY_LEVEL_VIP)
                  $week_memberships_vip   = (int)$myrow[1];
                else
                if ($myrow[0]==$PUSHY_LEVEL_PRO)
                  $week_memberships_pro   = (int)$myrow[1];
                else
                if ($myrow[0]==$PUSHY_LEVEL_ELITE)
                  $week_memberships_elite = (int)$myrow[1];
              }
          }


        $week_total_credits    =    $week_personal_domains  * $PUSHY_CREDITS_PERSONAL_DOMAINS;
        $week_total_credits   +=  ( $week_referral_domains  * $PUSHY_CREDITS_REFERRAL_DOMAINS  );
        $week_total_credits   +=  ( $week_personal_traffic  * $PUSHY_CREDITS_PERSONAL_TRAFFIC  );
        $week_total_credits   +=  ( $week_referral_traffic  * $PUSHY_CREDITS_REFERRAL_TRAFFIC  );
        $week_total_credits   +=  ( $week_memberships_vip   * $PUSHY_CREDITS_MEMBERSHIPS_VIP   );
        $week_total_credits   +=  ( $week_memberships_pro   * $PUSHY_CREDITS_MEMBERSHIPS_PRO   );
        $week_total_credits   +=  ( $week_memberships_elite * $PUSHY_CREDITS_MEMBERSHIPS_ELITE );

        if ($week_total_credits > 0)
           printf("MEMBER=%-15s   TOTAL=%d\n",$mid,$week_total_credits);

        $sql  = "UPDATE member set ";
        $sql .= " current_year_personal_domains  = current_year_personal_domains  + $week_personal_domains, ";
        $sql .= " current_year_referral_domains  = current_year_referral_domains  + $week_referral_domains, ";
        $sql .= " current_year_personal_traffic  = current_year_personal_traffic  + $week_personal_traffic, ";
        $sql .= " current_year_referral_traffic  = current_year_referral_traffic  + $week_referral_traffic, ";
        $sql .= " current_year_memberships_vip   = current_year_memberships_vip   + $week_memberships_vip,  ";
        $sql .= " current_year_memberships_pro   = current_year_memberships_pro   + $week_memberships_pro,  ";
        $sql .= " current_year_memberships_elite = current_year_memberships_elite + $week_memberships_elite,";
        $sql .= " prior_week_total_credits = $week_total_credits, ";
        $sql .= " prior_week_start_date    = '$priorWeekStartDate' ";
        $sql .= " WHERE member_id='$mid'";
        $res=mysql_query($sql,$db);

        // printf("SQL: %s\n",$sql);
        // printf("ERR: %s\n",mysql_error());

        if (!$res)
          {
            printf("---- Error After Processing %d Member Records -----\n",$processed);
            printf("SQL: %s\n",$sql);
            printf("ERR: %s\n",mysql_error());
            printf("ERR2: %d\n",mysql_errno($db));
            exit;
          }

        $processed++;
      }
  }




//-------------------------------- We have Processed the Counts - RESET - New Week Starting -----------
$sql = "UPDATE widget set weekly_access_count=0";
$result = mysql_query($sql,$db);
//-------------------------------- We have Processed the Counts - RESET - New Week Starting -----------





printf("\nBuilding Credit Map ...\n");
//-------------------------------- BUILD CREDIT MAP ------
$sql  = "DELETE from credit_map";
$result = mysql_query($sql,$db);
//-----

$sql  = "SELECT member_id,prior_week_total_credits from member";
$sql .= " WHERE    prior_week_start_date='$priorWeekStartDate'";
$sql .= " AND      prior_week_total_credits>0";
$sql .= " AND      member_id != '$PUSHY_ROOT'";
$sql .= " AND      (user_level='$PUSHY_LEVEL_PRO' OR user_level='$PUSHY_LEVEL_ELITE')";
$sql .= " AND      system   = 0";
$sql .= " AND      member_disabled = 0";
$sql .= " ORDER BY prior_week_total_credits DESC";

$result = mysql_query($sql,$db);

// printf("SQL:%s\n",$sql);
// printf("ERR:%s\n",mysql_error());
// exit;

$total_credits=0;
$creditList=array();
if ($result && (($count=mysql_num_rows($result))>0))
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id  = $myrow["member_id"];
        $credits    = $myrow["prior_week_total_credits"];

        $sql  = "INSERT into credit_map set";
        $sql .= " member_id  = '$member_id', ";
        $sql .= " credits_allocated = '$credits' ";
        $res  = mysql_query($sql,$db);
      }
  }
//-------------------------------- BUILD CREDIT MAP ------



$tmEnd=time();

printf("\nRecords Processed for Week Starting: %s\n",$priorWeekStartDate);
printf("Start Time: %s\n",formatDateTime($tmStart,TRUE));
printf("Member Records Processed (%d)\n",$processed);
printf("End   Time: %s\n",formatDateTime($tmEnd,TRUE));
printf("Elapsed: %d seconds\n",($tmEnd-$tmStart));

exit;
?>
