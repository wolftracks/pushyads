e
<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$TEST_MODE    = FALSE;
$DUMP_HEADERS = FALSE;
$DEBUG        = FALSE;

$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

$CURRENT_DIRECTORY   = getcwd();
$THIS_FILE_DIRECTORY = dirname(__FILE__);

$PUSHY_SITE=FALSE;

$DEFAULT_INTERVAL=3600;        // "seconds"      between "Restart" for DOMAIN/IPAddress  - if interval is NOT Specified  ( 60 MINUTES )
$DEFAULT_WIGGLE_INTERVAL=60;   // "seconds"      between Wiggles;


$HTTP_REFERER = "http://www.hughes.co.uk/mywebsite/index.html";


$referer_url    = "";  // does not include query string
$referer_domain = "";
$referer_page   = "";
if (strlen($HTTP_REFERER) > 8 && (substr($HTTP_REFERER,0,7) == "http://" || substr($HTTP_REFERER,0,8) == "https://"))
  {
    list($referer_url,$_temp_) = explode("?",$HTTP_REFERER);
    if (strlen($referer_url) > 0 && substr($referer_url,strlen($referer_url)-1,1) == "/")
      $referer_url = substr($referer_url,0,strlen($referer_url)-1);

    $pos=strpos($HTTP_REFERER,":");
    if (is_integer($pos))
      $pos+=3;
    $referer=substr($HTTP_REFERER,$pos);
    $pos=strpos($referer,"/");
    if (is_integer($pos))
      {
        $referer_domain = substr($referer,0,$pos);
        $referer_page   = substr($referer,$pos);
        $pos=strpos($referer_domain,":");
        if (is_integer($pos))
          $referer_domain = substr($referer_domain,0,$pos);
        if (substr($referer_domain,0,4)=="www.")
          $referer_domain = substr($referer_domain,4);

        list($referer_page,$_temp_) = explode("?",$referer_page);
        if (strlen($referer_page) > 0 && substr($referer_page,strlen($referer_page)-1,1) == "/")
          $referer_page = substr($referer_page,0,strlen($referer_page)-1);
      }
  }

    printf("DateTime: %s\n",getDateTime());
    printf("REFERER:        %s\n",$referer);
    printf("REFERER_URL:    %s\n",$referer_url);
    printf("REFERER_DOMAIN: %s\n",$referer_domain);
    printf("REFERER_PAGE:   %s\n",$referer_page);


$WidgetDomain="hughes.co.uk";


?>
