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

$sql  = "SELECT ad_id,product_id,COUNT(*) from ads LEFT JOIN MEMBER USING(member_id) LEFT JOIN PRODUCT USING(product_id)";
$sql .= " WHERE ads.reseller_listing>0";
$sql .= " AND   (member.user_level!='$PUSHY_LEVEL_ELITE' OR ads.product_list > 0 OR ads.pushy_network > 0 OR ads.elite_bar > 0 OR ads.elite_pool > 0)";
$sql .= " AND   product.product_owner=''";
$sql .= " GROUP BY member_id,ad_id,product_id";
printf("SQL: %s<br>\n",$sql);
printf("ERR: %s<br>\n",mysql_error());
$result=mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
      {
        $member_id  = $myrow[0];
        $ad_id      = $myrow[1];
        $product_id = $myrow[2];
        $count      = (int) $myrow[3];
        if ($count > 0)
          {
            printf("%-12s  %-8s  %-8s  %5d\n",$member_id,$ad_id,$prodict_id,$count);
          }
        else
          echo "????\n";
      }
  }
?>
