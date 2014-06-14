<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

$referer_domain='ibmt.com';
$member_id="tjw998468";
$widget_id=0;

$hash = getWidgetKey($referer_domain,$member_id,$widget_id);

printf("DOMAIN    : %s\n",$referer_domain);
printf("MEMBER    : %s\n",$member_id);
printf("WIDGET    : %s\n",$widget_id);
printf("WIDGET_KEY: %s\n",$hash);

$db=getPushyDatabaseConnection();
$key  = $referer_domain.":".$member_id.":".$widget_id;
$hash = md5($key);
$widget = getWidget($db, $hash); // returns FALSE if hash not found || User Not Enabled || Widget Not Enabled
if (!is_array($widget))
  {
    printf("Widget Not Found or Not Enabled\n");
    exit;
  }


// $WIDGET_ACTION_NONE         =  0;  // static
// $WIDGET_ACTION_BOUNCE       =  1;
// $WIDGET_ACTION_HOVER        =  2;
//
// $WIDGET_ORIGIN_TOP_LEFT     =  0;
// $WIDGET_ORIGIN_TOP_RIGHT    =  1;
// $WIDGET_ORIGIN_BOTTOM_LEFT  =  2;
// $WIDGET_ORIGIN_BOTTOM_RIGHT =  3;


$WidgetAction     = $widget["action"];
$WidgetCategories = $widget["widget_categories"];
$WidgetWidth      = $widget["width"];
$WidgetStyle      = $widget["style"];
$WidgetOrigin     = $widget["origin"];

printf("WidgetAction     : %s\n",$WidgetAction  );
printf("WidgetCategories : %s\n",$WidgetCategories);
printf("WidgetWidth      : %s\n",$WidgetWidth   );
printf("WidgetStyle      : %s\n",$WidgetStyle   );
printf("WidgetOrigin     : %s\n",$WidgetOrigin  );

if ($WidgetAction == $WIDGET_ACTION_BOUNCE)
  {
    $revisit = trackWidget($db, $widget, $REMOTE_ADDR);
    if ($revisit)
      {
        $WidgetAction = $WIDGET_ACTION_NONE;
        printf("(Revisit) WidgetAction   => %s\n",$WidgetAction  );
      }
  }
?>
