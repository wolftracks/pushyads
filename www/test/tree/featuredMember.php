<?php
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");

 // include_once("pushy_tree.inc");
 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 echo "-------------------------------------------------\n";

 $mem = getMemberInfo($db,"tjw998468");
 $result=getFeaturedMember($db,$mem);
 print_r($result);

 echo "-------------------------------------------------\n";

 $result=getFeaturedMember($db);
 print_r($result);

 echo "-------------------------------------------------\n";

 $mem = getMemberInfo($db,"p4043kts");
 $mresult=getFeaturedMember($db,$memberRecord);
 print_r($mresult);

 echo "-------------------------------------------------\n";


?>
