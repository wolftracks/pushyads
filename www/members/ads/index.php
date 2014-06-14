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

list($_uri_,$_qs_) = split("\?",$REQUEST_URI);
$_uri_tokens_ = explode("/",$_uri_);
$count=count($_uri_tokens_);

if ($count>0 && strlen($_uri_tokens_[$count-1])>0)
  {
    $ad_id=$_uri_tokens_[$count-1];
    $db=getPushyDatabaseConnection();
    $sql = "SELECT member_id, product_url from ads WHERE ad_id='$ad_id'";
    $result = mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $ad_owner    = $myrow["member_id"];
        $product_url = $myrow["product_url"];

        tracker_click($db, TRACKER_ELITE_BAR, $ad_owner, '', $ad_id);

        $locationHeader = "Location: ".$product_url;
        header ($locationHeader);  /* Redirect browser */
        exit;
      }
  }
header("HTTP/1.1 404 Not Found");
echo "<h2>Page Not Found</h2>\n";
exit;
?>
