<?php
 $DEBUG=FALSE;
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $member_id="jbv1443";

 $memberRecord = getMemberInfo($db,$member_id);

 $awardList = getMemberAwards($db,$member_id,$memberRecord);

 print_r($awardList);

 $result = giveMemberAward($db,$member_id,"101");
 $result = giveMemberAward($db,$member_id,"401");
 $result = giveMemberAward($db,$member_id,"202");

 $awardList = getMemberAwards($db,$member_id);

 print_r($awardList);


?>
