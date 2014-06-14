<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");
include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();

$DEBUG=TRUE;

$mid='hfe1201w';
$mid='cfv1210';

// How many resellers are selling My Ads ?
$count=0;
$sql  = "SELECT COUNT(*) from ads LEFT JOIN MEMBER USING(member_id) LEFT JOIN PRODUCT USING(product_id)";
$sql .= " WHERE ads.reseller_listing>0";
$sql .= " AND   (member.user_level!='$PUSHY_LEVEL_ELITE' OR ads.product_list > 0 OR ads.pushy_network > 0 OR ads.elite_bar > 0 OR ads.elite_pool > 0)";
$sql .= " AND   product.product_owner='$mid'";
$result=mysql_query($sql,$db);
// printf("SQL: %s<br>\n",$sql);
// printf("ERR: %s<br>\n",mysql_error());
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
      {
        $count = (int) $myrow[0];
      }
  }
printf("%5d\n",$count);
?>
