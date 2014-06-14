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

    $ad_prefix="";
    if (strlen($ad_id) > 2)
      {
        if (substr($ad_id,0,2) == "a-"  ||
            substr($ad_id,0,2) == "m-"  ||
            substr($ad_id,0,2) == "r-")
          {
            $ad_prefix = substr($ad_id,0,1);
            $ad_id     = substr($ad_id,2);
          }
      }

    $db=getPushyDatabaseConnection();
    $sql = "SELECT member_id, product_url, product.product_categories from ads JOIN product USING(product_id) WHERE ad_id='$ad_id'";
    $result = mysql_query($sql,$db);
    if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $ad_owner     = $myrow["member_id"];
        $product_url  = $myrow["product_url"];
        $adCategories = $myrow["product_categories"];

        tracker_click($db, TRACKER_PUSHY_ADS, $ad_owner, '', $ad_id);

        if ($ad_prefix == "m")
           tracker_click($db, TRACKER_PUSHY_ADS_MYSITES, $ad_owner, '', $ad_id);
        else
        if ($ad_prefix == "r")
           tracker_click($db, TRACKER_PUSHY_ADS_REFERRALS, $ad_owner, '', $ad_id);

        $tarray = explode("~",$adCategories);
        for ($n=0; $n<count($tarray); $n++)
          {
            if (strlen($tarray[$n])>0)
              {
                $category=$tarray[$n];
                tracker_click($db, TRACKER_AD_CATEGORY, $ad_owner, '', $ad_id, $category);
              }
          }

        $locationHeader = "Location: ".$product_url;
        header ($locationHeader);  /* Redirect browser */
        exit;
      }
  }
header("HTTP/1.1 404 Not Found");
echo "<h2>Page Not Found</h2>\n";
exit;
?>
