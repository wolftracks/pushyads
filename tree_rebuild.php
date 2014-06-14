<?php

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 echo "\n\n------------------------ BEFORE --------------------\n\n";
 tree_display($db, $PUSHY_ROOT);


 echo "\n\n---------------------- REBUILDING -----------------\n\n";
 tree_Rebuild($db);


 echo "\n\n------------------------ AFTER --------------------\n\n";
 tree_display($db, $PUSHY_ROOT);


?>
