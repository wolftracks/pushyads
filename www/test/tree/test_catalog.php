<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 // include_once("pushy_tree.inc");
 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $count    = getMemberProductCount($db, "tjw998468");
 $products = getMemberProductsPending($db, "tjw998468");
 printf("tjw998468 - count=%d\n",$count);
 print_r($products);

 $count    = getMemberProductCount($db, "bh3298as");
 $products = getMemberProductsPending($db, "bh3298as");
 printf("bh3298as - count=%d\n",$count);
 print_r($products);

 exit;
?>
