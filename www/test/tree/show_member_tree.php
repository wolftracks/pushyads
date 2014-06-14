<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();



 tree_display($db,"mkd1476");

 exit;
?>
