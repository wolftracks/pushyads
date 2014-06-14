<?php

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();


 tree_display($db, $PUSHY_ROOT);

?>
