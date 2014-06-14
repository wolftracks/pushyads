<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");

$DEBUG=FALSE;

$db = getPushyDatabaseConnection();

$PAGE="index.php";
if (isset($_REQUEST["tp"]))
  {
    if (file_exists($_REQUEST["tp"].".php"))
      {
        $PAGE=$_REQUEST["tp"].".php";
      }
    else
    if (file_exists($_REQUEST["tp"]))
      {
        $PAGE=$_REQUEST["tp"];
      }
  }

include($PAGE);
exit;
?>
