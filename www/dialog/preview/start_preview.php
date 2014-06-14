<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_jsontools.inc");
include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");

$DEBUG=FALSE;

$db = getPushyDatabaseConnection();

$PAGE="panel.php";

include($PAGE);
exit;
?>
