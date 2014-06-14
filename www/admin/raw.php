<?php
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");

$db = getPushyDatabaseConnection();

$member_id=$_REQUEST["member_id"];

$memberRecord=getMemberInfo($db,$member_id);

if (is_array($memberRecord) && $memberRecord["member_id"]==$member_id)
  {

     printf("<PRE>\n%s\n</PRE>\n",print_r($memberRecord,TRUE));

  }
else
  printf("Member  '%s'  Not Found\n",$member_id);
?>
