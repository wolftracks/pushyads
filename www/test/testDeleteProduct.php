<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");

$db = getPushyDatabaseConnection();

include_once("pushy_imagestore.inc");

for ($i=122; $i<=128; $i++)
  {
    printf("Deleting Product ...  %d<br>\n",$i);
    deleteProduct($db,$i);
  }



?>
