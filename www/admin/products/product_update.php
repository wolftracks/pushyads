<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_jsontools.inc");

$db = getPushyDatabaseConnection();

$RESPONSE["result"]=0;
$RESPONSE["data"]="";
$RESPONSE["message"]="";

$member_id           = $_REQUEST["member_id"];
$ad_id               = $_REQUEST["ad_id"];
$product_id          = $_REQUEST["product_id"];
$product_name        = $_REQUEST["product_name"];
$product_title       = $_REQUEST["product_title"];
$product_description = $_REQUEST["product_description"];
$product_categories  = $_REQUEST["product_categories"];

$sql  = "UPDATE product_pending set ";
$sql .= " product_name        ='".addslashes($product_name)."', ";
$sql .= " product_title       ='".addslashes($product_title)."', ";
// $sql .= " product_categories  ='".addslashes($product_categories)."', ";
$sql .= " product_description ='".addslashes($product_description)."'";
$sql .= " WHERE product_owner='$member_id' AND replaces_product_id='$product_id'";
$result = mysql_query($sql,$db);
if ($result)
  {
    $affiliate_signup_url = "";
    if (isset($_REQUEST["affiliate_signup_url"]) && strlen($_REQUEST["affiliate_signup_url"])>7)
        $affiliate_signup_url = $_REQUEST["affiliate_signup_url"];
    if (strlen($affiliate_signup_url) > 0)
      {
        $sql  = "UPDATE ads set affiliate_signup_url='$affiliate_signup_url' ";
        $sql .= " WHERE ad_id='$ad_id' ";
        $sql .= " AND   member_id='$member_id' ";
        mysql_query($sql,$db);
      }
  }
else
  {
    $RESPONSE["result"]=201;
    $RESPONSE["message"]="Product Update Failed";
  }

if ($RESPONSE["result"] == 0)
  {
    $RESPONSE["message"]=" Product Updated ";
  }

sendJSONResponse($RESPONSE["result"], $RESPONSE["data"], $RESPONSE["message"]);
exit;
?>
