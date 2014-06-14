<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

$CURRENT_DIRECTORY   = getcwd();
$THIS_FILE_DIRECTORY = dirname(__FILE__);

$msg  = "(pushy_service) S=$HTTP_HOST";
?>
alert('<?php echo $msg?>');
