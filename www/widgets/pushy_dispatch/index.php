<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");


$pushy_servers = array(
  "pushyads.local",
  "pushyads.com",
);


$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

$CURRENT_DIRECTORY   = getcwd();
$THIS_FILE_DIRECTORY = dirname(__FILE__);

$msg  = "(dispatch) S=$HTTP_HOST R=$REQUEST_URI";
$n=rand( 0, count($pushy_servers)-1 );

//  $uri=str_replace("/pushy_dispatch/","/slave$n/",$REQUEST_URI);
//  $newLocation = $uri;


// $uri=str_replace("/pushy_dispatch/","/control/",$REQUEST_URI);
$uri=str_replace("/pushy_dispatch/","/pushy_service/",$REQUEST_URI);
$newLocation = "http://".$pushy_servers[$n].$uri;

$locationHeader = "Location: ".$newLocation;
header ($locationHeader);  // Redirect browser
exit;
?>
