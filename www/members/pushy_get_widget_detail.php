<?php
include_once("scaling.inc");

$PUSHY_INDEX=$_REQUEST["pushy_index"];


// ----  NEW Widget Detail ---------
if ($PUSHY_INDEX==0)
  {
    include("pushy_new_widget.php");
    exit;
  }


// ----  EXISTING Widget Detail ---------
$WidgetKey = $_REQUEST["widget_key"];

$sql  = "SELECT * from widget ";
$sql .= " WHERE member_id='$mid'";
$sql .= " AND enabled>0";
$sql .= " AND widget_key='$WidgetKey'";
$result = mysql_query($sql,$db);

// printf("SQL:%s\n",$sql);
// printf("ERR:%s\n",mysql_error());

if ($result && ($widget = mysql_fetch_array($result,MYSQL_ASSOC)))
  {
    $WidgetName       = $widget["widget_name"];
    $WidgetKey        = $widget["widget_key"];
    $WidgetDomain     = $widget["domain"];
    $WidgetPosture    = $widget["posture"];
    $WidgetMotion     = $widget["motion"];
    $WidgetTransition = $widget["transition"];
    $WidgetId         = $widget["widget_id"];
    $WidgetCategories = $widget["widget_categories"];
    $WidgetWidth      = $widget["width"];
    $WidgetHeight     = $widget["height"];
    $WidgetStyle      = $widget["style"];
    $WidgetOrigin     = $widget["origin"];
    $WidgetSpeed      = $widget["speed"];
    $WidgetWiggle     = $widget["wiggle"];
    $WidgetDelay      = $widget["delay"];
    $WidgetPause      = $widget["pause"];

    $DateCreated      = $widget["date_created"];
    $DateLastModified = $widget["date_last_modified"];


    $WidgetAttributes=array();
    if (isset($WIDGET_SCALE[$WidgetWidth]))
      {
        $WidgetAttributes = $WIDGET_SCALE[$WidgetWidth];
        $WidgetHeight     = $WidgetAttributes["height"];
      }

    include("pushy_existing_widget.php");
  }
?>
