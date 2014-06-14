<?php
include_once("pushy_constants.inc");
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tree.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_imagestore.inc");

$dateArray   = getDateTodayAsArray();
$createDate1 = calStepDays(-1,$dateArray);
$createDate  = calStepDays(-23,$dateArray);

printf("DAYS=%d\n",dateDifference($createDate,$dateArray));

printf("DAYS=%d\n",dateDifference($createDate1,$dateArray));

printf("DAYS=%d\n",dateDifference($dateArray,$dateArray));


?>
