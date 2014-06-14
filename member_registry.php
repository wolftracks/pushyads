<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db=getPushyDatabaseConnection();

$dateArray    = getDateTodayAsArray();
$ts_today     = timestampFromDateArray($dateArray);

$targetArray  = calStepDays(-1,$dateArray);
$ts_target    = timestampFromDateArray($targetArray);

$calData=calendar($targetArray);
$dow = $calData["DayOfWeek"];

$targetDate   = dateArrayToString($targetArray);
$targetDateSecondsFrom = $ts_target;   // Inclusive
$targetDateSecondsTo   = $ts_today-1;  // Inclusive

$total_visits     = 0;
$total_signups    = 0;
$total_confirmed  = 0;
$total_registered = 0;
$total_orders     = 0;

//-------------------------------------------------------------

$sql = "SELECT sum(w5_h".$dow.") from ".TRACKER_AFFILIATE_PAGE;
$result = mysql_query($sql,$db);

if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $total_visits=(int)$myrow[0];
  }

//-------------------------------------------------------------

$sql  = "SELECT COUNT(*) from member";
$sql .= " WHERE record_created='$targetDate'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $total_signups=(int)$myrow[0];
  }

//-------------------------------------------------------------

$sql  = "SELECT COUNT(*) from member";
$sql .= " WHERE confirmed >= '$targetDateSecondsFrom'";
$sql .= " AND   confirmed <= '$targetDateSecondsTo'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $total_confirmed=(int)$myrow[0];
  }

//-------------------------------------------------------------

$sql  = "SELECT COUNT(*) from member";
$sql .= " WHERE date_registered='$targetDate'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $total_registered=(int)$myrow[0];
  }

//-------------------------------------------------------------

$sql  = "SELECT COUNT(*) from receipts";
$sql .= " WHERE txtype=0 AND returned=0 AND yymmdd='$targetDate'";
$sql .= "  AND user_level>0 and order_type='initial'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $total_orders=(int)$myrow[0];
  }



printf("total_visits     = %d\n",$total_visits    );
printf("total_signups    = %d\n",$total_signups   );
printf("total_confirmed  = %d\n",$total_confirmed );
printf("total_registered = %d\n",$total_registered);
printf("total_orders     = %d\n",$total_orders    );



// DROP TABLE IF EXISTS member_registry;
// CREATE TABLE member_registry (
//   `date` varchar(10) default '',
//   visits  int(11) NOT NULL default '0',
//   signups int(11) NOT NULL default '0',
//   confirmations int(11) NOT NULL default '0',
//   registrations int(11) NOT NULL default '0',
//   orders        int(11) NOT NULL default '0'
// ) TYPE=MyISAM;



$sql = "SELECT date from member_registry WHERE date='$targetDate'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $sql  = "UPDATE member_registry set";
    $sql .= " visits          = '". $total_visits     . "',";
    $sql .= " signups         = '". $total_signups    . "',";
    $sql .= " confirmations   = '". $total_confirmed  . "',";
    $sql .= " registrations   = '". $total_registered . "',";
    $sql .= " orders          = '". $total_orders     . "'";
    $sql .= " WHERE date = '$targetDate'";
    $result = mysql_query($sql,$db);
  }
else
  {
    $sql  = "INSERT INTO member_registry set";
    $sql .= " date            = '". $targetDate       . "',";
    $sql .= " visits          = '". $total_visits     . "',";
    $sql .= " signups         = '". $total_signups    . "',";
    $sql .= " confirmations   = '". $total_confirmed  . "',";
    $sql .= " registrations   = '". $total_registered . "',";
    $sql .= " orders          = '". $total_orders     . "'";
    $result = mysql_query($sql,$db);
  }

// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());

?>
