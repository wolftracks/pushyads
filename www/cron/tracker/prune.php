<?php
$DEBUG=FALSE;

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();


$period="";
if ($argc > 1)
  $period=$argv[1];   //     "daily", "weekly" or "monthly"  - Not Currently Used

printf("---- Today is  %s %s   ----- period(%s) ----\n\n",dateAsText(), timeAsText(), $period);




//=====================================================================================================================================================================

//---

$dateTodayAsArray = getDateTodayAsArray();
$today            = dateArrayToString($dateTodayAsArray);
$yesterdayAsArray = calStepDays(-1,$dateTodayAsArray);
$yesterday        = dateArrayToString($yesterdayAsArray);

//---

$targetDate       = $yesterdayAsArray;
$target_date      = dateArrayToString($targetDate);
$calData=calendar($targetDate);
$dow = $calData["DayOfWeek"];

//---




//------------- Update Trackers to Reflect SIGNUPS For Target Date  (Recorded As AFFILIATE_PAGE "clicks") ------

$sql  = "SELECT refid,COUNT(*) from member";
$sql .= " WHERE registered>0";
$sql .= " AND   date_registered='$target_date'";
// $sql .= " AND   user_level='$PUSHY_LEVEL_VIP'";
$sql .= " GROUP BY refid";
$result = mysql_query($sql,$db);

// printf("SQL:%s\n",$sql);
// printf("ERR:%s\n",mysql_error());

if ($result)
  {
    while($myrow = mysql_fetch_array($result))
      {
        $refid   = $myrow[0];
        $signups = (int) $myrow[1];
        if ($signups > 0)
          {

            printf("Set Signups:  Refid(%s)  Signups:%d\n",$refid,$signups);

            setSignups($db,$refid,$dow,$signups);
          }
      }
  }


function setSignups($db,$member_id,$dow,$signups)
  {
    $table   = TRACKER_AFFILIATE_PAGE;
    $userkey = "";

    if (!tracker_item_exists($db,$table,$member_id,'','',$userkey))
       tracker_create_item($db,$table,$member_id,'','',$userkey);

    // $sql = "UPDATE $table set w5_c$dow = w5_c$dow + $signups WHERE member_id='$member_id' AND widget_key='' AND ad_id='' AND userkey='$userkey'";
    $sql = "UPDATE $table set w5_c$dow = $signups WHERE member_id='$member_id' AND widget_key='' AND ad_id='' AND userkey='$userkey'";
    mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error());
  }

//------------------------------------------------------------------------------------------------------------







function getUniqueVisits($db,$week,$dow)
  {
    // week => 1 .. 5
    // dow  => 0 .. 6

    $visits=0;
    $sql = "SELECT sum(w".$week."_h".$dow.") from ".TRACKER_AFFILIATE_PAGE;
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error);

    if ($result && ($myrow = mysql_fetch_array($result)))
       $visits = (int) $myrow[0];

    // printf("Visits=%d\n",$visits);
    return $visits;
  }


function getSignups($db,$dt)
  {
    $signups=0;
    $sql  = "SELECT COUNT(*) from member";
    $sql .= " WHERE date_registered='$dt'";
    $result = mysql_query($sql,$db);

    // printf("SQL:%s\n",$sql);
    // printf("ERR:%s\n",mysql_error);

    if ($result && ($myrow = mysql_fetch_array($result)))
       $signups = (int) $myrow[0];

    // printf("Signups=%d\n",$signups);
    return $signups;
  }


function getOrders($db,$dt)
  {
    $orders = array();

    $orders[1]["initial"]=0;
    $orders[1]["upgrade"]=0;
    $orders[1]["renewal"]=0;

    $orders[2]["initial"]=0;
    $orders[2]["upgrade"]=0;
    $orders[2]["renewal"]=0;

    $sql  = "SELECT user_level,order_type,COUNT(*) from receipts";
    $sql .= " WHERE txtype=0 AND yymmdd='$dt'";
    $sql .= " GROUP BY user_level,order_type";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        while ($myrow = mysql_fetch_array($result))
          {
            $user_level                       = $myrow[0];
            $order_type                       = $myrow[1];
            $orders[$user_level][$order_type] = (int) $myrow[2];
          }
      }

    return $orders;
  }


function updateActivity($db,$dt,$activity)
  {
    $sql = "SELECT date from activity WHERE date='$dt'";
    $result = mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result)))
      {
        $sql  = "UPDATE activity set";
        $sql .= " visits          = '". $activity["visits"]         . "',";
        $sql .= " signups         = '". $activity["signups"]        . "',";
        $sql .= " neworders_pro   = '". $activity["neworders_pro"]  . "',";
        $sql .= " upgrades_pro    = '". $activity["upgrades_pro"]   . "',";
        $sql .= " renewals_pro    = '". $activity["renewals_pro"]   . "',";
        $sql .= " neworders_elite = '". $activity["neworders_elite"]. "',";
        $sql .= " upgrades_elite  = '". $activity["upgrades_elite"] . "',";
        $sql .= " renewals_elite  = '". $activity["renewals_elite"] . "'";
        $sql .= " WHERE date='$dt'";
        $result = mysql_query($sql,$db);
      }
    else
      {
        $sql  = "INSERT INTO activity set";
        $sql .= " date            = '". $dt                         . "',";
        $sql .= " visits          = '". $activity["visits"]         . "',";
        $sql .= " signups         = '". $activity["signups"]        . "',";
        $sql .= " neworders_pro   = '". $activity["neworders_pro"]  . "',";
        $sql .= " upgrades_pro    = '". $activity["upgrades_pro"]   . "',";
        $sql .= " renewals_pro    = '". $activity["renewals_pro"]   . "',";
        $sql .= " neworders_elite = '". $activity["neworders_elite"]. "',";
        $sql .= " upgrades_elite  = '". $activity["upgrades_elite"] . "',";
        $sql .= " renewals_elite  = '". $activity["renewals_elite"] . "'";
        $result = mysql_query($sql,$db);
      }

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());
  }


$visits  = getUniqueVisits($db,5,$dow);
$signups = getSignups($db,$target_date);
$orders  = getOrders($db,$target_date);

$activity = array("visits"           => $visits,
                  "signups"          => $signups,
                  "neworders_pro"    => $orders[1]["initial"],
                  "upgrades_pro"     => $orders[1]["upgrade"],
                  "renewals_pro"     => $orders[1]["renewal"],
                  "neworders_elite"  => $orders[2]["initial"],
                  "upgrades_elite"   => $orders[2]["upgrade"],
                  "renewals_elite"   => $orders[2]["renewal"],
                 );

updateActivity($db,$target_date,$activity);

printf("--- Activity for (Yesterday)  Date:  %s -----------------------------------\n",$target_date);
print_r($activity);
printf("\n\n-------------------------------------------------------------------------\n");





//=====================================================================================================================================================================





printf("\nStart Prune ........\n");

//----------------------------------------------------------------------------------------------------------

printf("\n------ Locating Empty Tracker Records:  Start of Job  (Pre-FOLD) ----\n");
$sql  = "SELECT member_id from ".TRACKER_PUSHY_WIDGET;
$sql .= " WHERE w1_h0=0 AND w1_h1=0 AND w1_h2=0 AND w1_h3=0 AND w1_h4=0 AND w1_h5=0 AND w1_h6=0 AND w1_c0=0 AND w1_c1=0 AND w1_c2=0 AND w1_c3=0 AND w1_c4=0 AND w1_c5=0 AND w1_c6=0";
$sql .= "   AND w2_h0=0 AND w2_h1=0 AND w2_h2=0 AND w2_h3=0 AND w2_h4=0 AND w2_h5=0 AND w2_h6=0 AND w2_c0=0 AND w2_c1=0 AND w2_c2=0 AND w2_c3=0 AND w2_c4=0 AND w2_c5=0 AND w2_c6=0";
$sql .= "   AND w3_h0=0 AND w3_h1=0 AND w3_h2=0 AND w3_h3=0 AND w3_h4=0 AND w3_h5=0 AND w3_h6=0 AND w3_c0=0 AND w3_c1=0 AND w3_c2=0 AND w3_c3=0 AND w3_c4=0 AND w3_c5=0 AND w3_c6=0";
$sql .= "   AND w4_h0=0 AND w4_h1=0 AND w4_h2=0 AND w4_h3=0 AND w4_h4=0 AND w4_h5=0 AND w4_h6=0 AND w4_c0=0 AND w4_c1=0 AND w4_c2=0 AND w4_c3=0 AND w4_c4=0 AND w4_c5=0 AND w4_c6=0";
$sql .= "   AND w5_h0=0 AND w5_h1=0 AND w5_h2=0 AND w5_h3=0 AND w5_h4=0 AND w5_h5=0 AND w5_h6=0 AND w5_c0=0 AND w5_c1=0 AND w5_c2=0 AND w5_c3=0 AND w5_c4=0 AND w5_c5=0 AND w5_c6=0";
$result = mysql_query($sql,$db);

// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());
printf("ROWS: %s\n\n",mysql_num_rows($result));

if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id  = $myrow["member_id"];
        printf("  ... Empty Tracker Record: member=%s\n",$member_id);
      }
  }



//----------------------------------------------------------------------------------------------------------

printf("\n------ Folding Tables ----\n");
for ($i=0; $i<count($tracker_tables); $i++)
  {
    $table = $tracker_tables[$i];
    printf("  ... Folding table: %s\n",$table);
    $fcount=tracker_fold_table($db, $table);
    printf("        -> Fold Result - Records Affected: %d\n",$fcount);
  }



//----------------------------------------------------------------------------------------------------------

printf("\n------ Locating Empty Tracker Records:  (Post-FOLD) ----\n");
$sql  = "SELECT member_id from ".TRACKER_PUSHY_WIDGET;
$sql .= " WHERE w1_h0=0 AND w1_h1=0 AND w1_h2=0 AND w1_h3=0 AND w1_h4=0 AND w1_h5=0 AND w1_h6=0 AND w1_c0=0 AND w1_c1=0 AND w1_c2=0 AND w1_c3=0 AND w1_c4=0 AND w1_c5=0 AND w1_c6=0";
$sql .= "   AND w2_h0=0 AND w2_h1=0 AND w2_h2=0 AND w2_h3=0 AND w2_h4=0 AND w2_h5=0 AND w2_h6=0 AND w2_c0=0 AND w2_c1=0 AND w2_c2=0 AND w2_c3=0 AND w2_c4=0 AND w2_c5=0 AND w2_c6=0";
$sql .= "   AND w3_h0=0 AND w3_h1=0 AND w3_h2=0 AND w3_h3=0 AND w3_h4=0 AND w3_h5=0 AND w3_h6=0 AND w3_c0=0 AND w3_c1=0 AND w3_c2=0 AND w3_c3=0 AND w3_c4=0 AND w3_c5=0 AND w3_c6=0";
$sql .= "   AND w4_h0=0 AND w4_h1=0 AND w4_h2=0 AND w4_h3=0 AND w4_h4=0 AND w4_h5=0 AND w4_h6=0 AND w4_c0=0 AND w4_c1=0 AND w4_c2=0 AND w4_c3=0 AND w4_c4=0 AND w4_c5=0 AND w4_c6=0";
$sql .= "   AND w5_h0=0 AND w5_h1=0 AND w5_h2=0 AND w5_h3=0 AND w5_h4=0 AND w5_h5=0 AND w5_h6=0 AND w5_c0=0 AND w5_c1=0 AND w5_c2=0 AND w5_c3=0 AND w5_c4=0 AND w5_c5=0 AND w5_c6=0";
$result = mysql_query($sql,$db);

// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());
printf("ROWS: %s\n\n",mysql_num_rows($result));

if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id  = $myrow["member_id"];
        printf("  ... Empty Tracker Record: member=%s\n",$member_id);
      }
  }



//----------------------------------------------------------------------------------------------------------


printf("\n------ Deleting Empty Tracker Records ----\n");
for ($i=0; $i<count($tracker_tables); $i++)
  {
    $table = $tracker_tables[$i];
    printf("  ... From table: %s\n",$table);

    $sql  = "DELETE FROM ".$table;
    $sql .= " WHERE w1_h0=0 AND w1_h1=0 AND w1_h2=0 AND w1_h3=0 AND w1_h4=0 AND w1_h5=0 AND w1_h6=0 AND w1_c0=0 AND w1_c1=0 AND w1_c2=0 AND w1_c3=0 AND w1_c4=0 AND w1_c5=0 AND w1_c6=0";
    $sql .= "   AND w2_h0=0 AND w2_h1=0 AND w2_h2=0 AND w2_h3=0 AND w2_h4=0 AND w2_h5=0 AND w2_h6=0 AND w2_c0=0 AND w2_c1=0 AND w2_c2=0 AND w2_c3=0 AND w2_c4=0 AND w2_c5=0 AND w2_c6=0";
    $sql .= "   AND w3_h0=0 AND w3_h1=0 AND w3_h2=0 AND w3_h3=0 AND w3_h4=0 AND w3_h5=0 AND w3_h6=0 AND w3_c0=0 AND w3_c1=0 AND w3_c2=0 AND w3_c3=0 AND w3_c4=0 AND w3_c5=0 AND w3_c6=0";
    $sql .= "   AND w4_h0=0 AND w4_h1=0 AND w4_h2=0 AND w4_h3=0 AND w4_h4=0 AND w4_h5=0 AND w4_h6=0 AND w4_c0=0 AND w4_c1=0 AND w4_c2=0 AND w4_c3=0 AND w4_c4=0 AND w4_c5=0 AND w4_c6=0";
    $sql .= "   AND w5_h0=0 AND w5_h1=0 AND w5_h2=0 AND w5_h3=0 AND w5_h4=0 AND w5_h5=0 AND w5_h6=0 AND w5_c0=0 AND w5_c1=0 AND w5_c2=0 AND w5_c3=0 AND w5_c4=0 AND w5_c5=0 AND w5_c6=0";
    $result = mysql_query($sql,$db);

    printf("        -> Delete Result - Records Deleted: %d\n",mysql_affected_rows());
  }




//----------------------------------------------------------------------------------------------------------

printf("\n------ Locating Empty Tracker Records:  End Job (Post Delete) ----\n");
$sql  = "SELECT member_id from ".TRACKER_PUSHY_WIDGET;
$sql .= " WHERE w1_h0=0 AND w1_h1=0 AND w1_h2=0 AND w1_h3=0 AND w1_h4=0 AND w1_h5=0 AND w1_h6=0 AND w1_c0=0 AND w1_c1=0 AND w1_c2=0 AND w1_c3=0 AND w1_c4=0 AND w1_c5=0 AND w1_c6=0";
$sql .= "   AND w2_h0=0 AND w2_h1=0 AND w2_h2=0 AND w2_h3=0 AND w2_h4=0 AND w2_h5=0 AND w2_h6=0 AND w2_c0=0 AND w2_c1=0 AND w2_c2=0 AND w2_c3=0 AND w2_c4=0 AND w2_c5=0 AND w2_c6=0";
$sql .= "   AND w3_h0=0 AND w3_h1=0 AND w3_h2=0 AND w3_h3=0 AND w3_h4=0 AND w3_h5=0 AND w3_h6=0 AND w3_c0=0 AND w3_c1=0 AND w3_c2=0 AND w3_c3=0 AND w3_c4=0 AND w3_c5=0 AND w3_c6=0";
$sql .= "   AND w4_h0=0 AND w4_h1=0 AND w4_h2=0 AND w4_h3=0 AND w4_h4=0 AND w4_h5=0 AND w4_h6=0 AND w4_c0=0 AND w4_c1=0 AND w4_c2=0 AND w4_c3=0 AND w4_c4=0 AND w4_c5=0 AND w4_c6=0";
$sql .= "   AND w5_h0=0 AND w5_h1=0 AND w5_h2=0 AND w5_h3=0 AND w5_h4=0 AND w5_h5=0 AND w5_h6=0 AND w5_c0=0 AND w5_c1=0 AND w5_c2=0 AND w5_c3=0 AND w5_c4=0 AND w5_c5=0 AND w5_c6=0";
$result = mysql_query($sql,$db);

// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());
printf("ROWS: %s\n\n",mysql_num_rows($result));

if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id  = $myrow["member_id"];
        printf("  ... Empty Tracker Record: member=%s\n",$member_id);
      }
  }

exit;
?>
