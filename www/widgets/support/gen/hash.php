<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

$referer_domain='webtribune.com';
$member_id="tjw998468";
$widget_id=0;

$hash=getWidgetKey($referer_domain,$member_id,$widget_id);

printf("%s\n",$hash);



$referer_domain='pushyads.com';
$member_id="tjw998468";
$widget_id=0;

$hash=getWidgetKey($referer_domain,$member_id,$widget_id);

printf("%s\n",$hash);



$referer_domain='pushyads.local';
$member_id="tjw998468";
$widget_id=0;

$hash=getWidgetKey($referer_domain,$member_id,$widget_id);

printf("%s\n",$hash);
?>
