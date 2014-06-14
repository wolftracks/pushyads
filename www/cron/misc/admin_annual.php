<?php
include_once("pushy_constants.inc");
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tree.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_imagestore.inc");

$TRACE=TRUE;

$db=getPushyDatabaseConnection();

set_time_limit(0);

//----------------------------------------------------------------------------
//
// CURRENT YEAR TOTALS NMUST BE ZEROED OUT AT START OF NEW YEAR
//
//----------------------------------------------------------------------------
$dateTodayAsArray  = getDateTodayAsArray();
$dateToday         = dateArrayToString($dateTodayAsArray);

$sql  = "UPDATE member set ";
$sql .= " current_year_personal_domains  = 0,";
$sql .= " current_year_referral_domains  = 0,";
$sql .= " current_year_personal_traffic  = 0,";
$sql .= " current_year_referral_traffic  = 0,";
$sql .= " current_year_memberships_vip   = 0,";
$sql .= " current_year_memberships_pro   = 0,";
$sql .= " current_year_memberships_elite = 0";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------

exit;
?>
