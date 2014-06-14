<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_invoke.inc");
include_once("pushy_sendmail.inc");

$orderType     = ORDER_TYPE_UPGRADE;
$orderLevel    = $PUSHY_LEVEL_PRO;
$orderAmount   = $PUSHY_LEVEL_PRO_MONTHLY_FEE;
$description   = "PRO membership fee";

$banner_image  = "orderpro.png";

include_once("pushyorder.php");
exit;
?>
