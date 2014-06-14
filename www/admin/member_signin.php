<?php
require("pushy_constants.inc");
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");

$member_id=$_REQUEST["member_id"];

$db = getPushyDatabaseConnection();

list($rc,$session)=newSession($db, $member_id, TRUE);   // Special ADMINISTRATOR SESSION

$newLocation = "http://".$HTTP_HOST."/members/membersite.php?mid=$member_id&sid=$session";

// echo $newLocation."<br>\n";
// echo $rc."<br>\n";
// echo $session."<br>\n";
// exit;

$locationHeader = "Location: ".$newLocation;
header ($locationHeader);  /* Redirect browser */

// printf('<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$newLocation.'">');
?>
