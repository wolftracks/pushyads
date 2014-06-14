<?php
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_sendmail.inc");

$db=getPushyDatabaseConnection();
giveMemberAward($db, "paw1200", "801");
giveMemberAward($db, "paw1200", "201");
giveMemberAward($db, "paw1200", "701");
giveMemberAward($db, "paw1200", "301");
giveMemberAward($db, "paw1200", "601");
giveMemberAward($db, "paw1200", "501");
giveMemberAward($db, "paw1200", "401");

?>
