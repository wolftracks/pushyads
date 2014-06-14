<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$DEFAULT_INTERVAL=0;           // "seconds"      between "Restart" for DOMAIN/IPAddress  - if interval is NOT Specified
$DEFAULT_WIGGLE_INTERVAL=10;   // "seconds"      between Wiggles;

include("scaling.inc");

$_fn_ = "2ff0bf884d5f568610bbc0a66551fc5d";

$db=getPushyDatabaseConnection();
$widget = getWidget($db, $_fn_); // returns FALSE if hash not found || User Not Enabled || Widget Not Enabled
if (!is_array($widget))
  {
    notFound(__LINE__, "Widget Not Found: Hash='$_fn_'");
    exit;
  }


$member_id = $widget["member_id"];

$member_disabled=1;
$affiliate_website='';
if (is_array($memberRecord=getMemberInfo($db,$member_id)))
  {
    $member_disabled   = $memberRecord["member_disabled"];
    $affiliate_id      = $memberRecord["affiliate_id"];
    $affiliate_website = DOMAIN."/".$affiliate_id;
  }
if ($member_disabled || strlen($affiliate_website)==0)
  {
    notSupported(__LINE__, "REMOTE_ADDR Missing/Invalid: '$REMOTE_ADDR'");
    notSupported(__LINE__, "Member Disabled or Not Found: '$member_id'");
    exit;
  }


$WidgetName        = $widget["widget_name"];
$WidgetKey         = $widget["widget_key"];
$WidgetDomain      = $widget["domain"];
$WidgetId          = $widget["widget_id"];
$WidgetCategories  = $widget["widget_categories"];

$WidgetWidth       = $widget["width"];
$WidgetStyle       = $widget["style"];
$WidgetPosture     = $widget["posture"];
$WidgetMotion      = $widget["motion"];
$WidgetTransition  = $widget["transition"];
$WidgetOrigin      = $widget["origin"];
$WidgetSpeed       = $widget["speed"];
$WidgetWiggle      = $widget["wiggle"];
$WidgetDelay       = $widget["delay"];
$WidgetPause       = $widget["pause"];
$WidgetFirstAccess = $widget["date_first_access"];
$WidgetLastAccess  = $widget["date_last_access"];
$WidgetWeeklyAccessCount = $widget["weekly_access_count"];
$WidgetTotalAccessCount  = $widget["total_access_count"];


$width=$WidgetWidth;

if (!isset($WIDGET_SCALE[$width]))
  {
    notSupported(__LINE__,"WIDGET_SCALE - width not found: ".$width);
  }

$attributes = $WIDGET_SCALE[$width];

$width              = $attributes["width"];
$height             = $attributes["height"];

$top_width          = $attributes["top_width"];
$top_height         = $attributes["top_height"];

$left_width         = $attributes["left_width"];
$left_height        = $attributes["left_height"];

$client_width       = $attributes["client_width"];

$right_width        = $attributes["right_width"];
$right_height       = $attributes["right_height"];

$bottom_width       = $attributes["bottom_width"];
$bottom_height      = $attributes["bottom_height"];

$ifrm_width         = $attributes["ifrm_width"];
$ifrm_height        = $attributes["ifrm_height"];

$scroll_width       = $attributes["scroll_width"];
$scroll_height      = $attributes["scroll_height"];

$table_width        = $attributes["table_width"];

$image_td_width     = $attributes["image_td_width"];
$image_width        = $attributes["image_width"];

$text_td_width      = $attributes["text_td_width"];
$text_font_size     = $attributes["text_font_size"];


$GET_PUSHY_LINK=TRUE;
$nostart=TRUE;

$wiggle_interval=0;
if ($WidgetWiggle>0)                   // Wiggle Turned On ?
  {
    $wiggle_interval=$DEFAULT_WIGGLE_INTERVAL;               // Time in seconds between wiggles - 0=No Wiggle
    if (isset($_qs_array_["wiggle_interval"]))
      $wiggle_interval=(int)$_qs_array_["wiggle_interval"];  // Time between wiggles - 0=No Wiggle
  }

$filename="_pushy_config.php";

ob_start();
  include("_pushy_config.php");
  include("test-min.js");
  $contents = ob_get_contents();
ob_end_clean();

echo $contents;
?>
