<?php

// --------------- INCLUDED BY index.php ---------------------

$mid = $_REQUEST["mid"];
$sid = $_REQUEST["sid"];
$pst = $_REQUEST["pst"];
$org = $_REQUEST["org"];
$mtn = $_REQUEST["mtn"];
$trn = $_REQUEST["trn"];
$siz = $_REQUEST["siz"];
$spd = $_REQUEST["spd"];
$wig = $_REQUEST["wig"];
$dly = $_REQUEST["dly"];
$pau = $_REQUEST["pau"];

$DEBUG_ALERT = FALSE;

if ($DEBUG_ALERT)
  {
     //------ DISPLAY Args Received via JS Alert ----
     $js  = " var msg='';  \n";
     $js .= "     msg+='mid=$mid\\n'; \n";
     $js .= "     msg+='sid=$sid\\n'; \n";
     $js .= "     msg+='pst=$pst\\n'; \n";
     $js .= "     msg+='org=$org\\n'; \n";
     $js .= "     msg+='mtn=$mtn\\n'; \n";
     $js .= "     msg+='trn=$trn\\n'; \n";
     $js .= "     msg+='siz=$siz\\n'; \n";
     $js .= "     msg+='spd=$spd\\n'; \n";
     $js .= "     msg+='wig=$wig\\n'; \n";
     $js .= "     msg+='dly=$dly\\n'; \n";
     $js .= "     msg+='pau=$pau\\n'; \n";
     $js .= " alert(msg); \n";
     header("HTTP/1.1 200 OK");
     header("Accept-Ranges: bytes");
     header("Content-Type: application/x-javascript");

     echo $js;

     exit;
  }

$DEFAULT_INTERVAL=0;           // "seconds"      between "Restart" for DOMAIN/IPAddress  - if interval is NOT Specified
$DEFAULT_WIGGLE_INTERVAL=15;   // "seconds"      between Wiggles;

$member_id         = $mid;
$WidgetName        = $_fn_;                         // from index.php
$WidgetKey         = $_fn_;
$WidgetDomain      = "pushyads.com";
$WidgetId          = "";
$WidgetCategories  = "";

$WidgetWidth       = $wth;
$WidgetStyle       = 0;
$WidgetPosture     = $pst;
$WidgetMotion      = $mtn;
$WidgetTransition  = $trn;
$WidgetOrigin      = $org;
$WidgetSpeed       = $spd;
$WidgetWiggle      = $wig;
$WidgetDelay       = $dly;
$WidgetPause       = $pau;

include("scaling.inc");

$wth=$siz;
if (!isset($WIDGET_SCALE[$wth]))
  {
    preview_error(__LINE__,"WIDGET_SCALE - width not found: ".$width);
  }


$attributes = $WIDGET_SCALE[$wth];

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

$wiggle_interval=$wig;

ob_start();
  $PUSHY_PREVIEW=TRUE;
  $DISPLAY_URL = PUSHYWIDGETS."/scroll/$WidgetKey.php?mid=$mid&wth=$wth";

  include("_pushy_config-min.js");
  // include("control.js");
  include("control-min.js");

  $contents = ob_get_contents();
ob_end_clean();

$contentLength=strlen($contents);

header("HTTP/1.1 200 OK");
header("Accept-Ranges: bytes");
// header("Content-Length: $contentLength");
header("Content-Type: application/x-javascript");

echo $contents;

exit;



function preview_error($line,$message)
  {
    global $DEBUG;

    if ($DEBUG)
      {
        $fp=fopen("headers.txt","a");
        fputs($fp,sprintf("-------DEBUG----- PREVIEW_ERROR\n"));
        fputs($fp,sprintf("Line: %d  Message: %s\n",$line,$message));
        fclose($fp);
      }

    header("HTTP/1.1 412 Precondition Failed");
    exit;
  }
?>
