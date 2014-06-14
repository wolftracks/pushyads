<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 // include_once("pushy_tree.inc");
 include_once("tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 tree_Rebuild($db);
 // tree_Rebuild($db, $PUSHY_ROOT, 0);

 tree_display($db,$PUSHY_ROOT);
 printf("\n\n\n");

 exit;
?>
