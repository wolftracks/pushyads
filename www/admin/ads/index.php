<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");
include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");
$StatusMessage="";

$imgdir="images";

$op=$_REQUEST["op"];

$db = getPushyDatabaseConnection();

if (!isset($op) || strlen($op)==0 || $op=="ListAds")
  {
    include("adlist.php");
    exit;
  }

exit;
?>
