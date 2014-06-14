<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_invoke.inc");
include_once("pushy_sendmail.inc");

$orderType     = ORDER_TYPE_INITIAL;
$orderLevel    = $PUSHY_LEVEL_ELITE;
$orderAmount   = $PUSHY_LEVEL_ELITE_MONTHLY_FEE;
$description   = "ELITE membership fee";
$banner_image  = "orderelite.png";

include_once("pushyorder.php");
exit;
?>
