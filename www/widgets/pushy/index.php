<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

list($_uri_,$_qs_) = split("\?",$REQUEST_URI);
$_uri_tokens_ = explode("/",$_uri_);
$count=count($_uri_tokens_);

if ($count>0 && strlen($_uri_tokens_[$count-1])>0)
  {
    $widget_data = $_uri_tokens_[$count-1];

    $widgetArray = splitWidgetKey($widget_data);
    $widget_key  = $widgetArray["WidgetConfigurationKey"];
    $tracking_id = $widgetArray["TrackingId"];

    $db=getPushyDatabaseConnection();
    $widget = getWidget($db, $widget_key); // returns FALSE if hash not found || User Not Enabled || Widget Not Enabled
    if (is_array($widget) && is_array($memberRecord=getMemberInfo($db,$widget["member_id"])))
      {
        $WidgetOwner       = $widget["member_id"];
        $widget_categories = $widget["widget_categories"];

        $affiliate_id = $memberRecord["affiliate_id"];
        $affiliate_website   = DOMAIN."/".$affiliate_id;

        tracker_click($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($widget_key, $tracking_id), '');

        $tarray = explode("~",$widget_categories);
        for ($i=0; $i<count($tarray); $i++)
          {
            if (strlen($tarray[$i])>0)
              {
                $category=$tarray[$i];
                tracker_click($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($widget_key, $tracking_id), '', $category);
              }
          }

        $locationHeader = "Location: ".$affiliate_website;
        header ($locationHeader);  /* Redirect browser */
        exit;
      }
  }
header("HTTP/1.1 404 Not Found");
echo "<h2>Page Not Found</h2>\n";
exit;
?>
