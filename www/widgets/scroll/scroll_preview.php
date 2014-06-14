<?php

if (!isset($_REQUEST["mid"]) || !isset($_REQUEST["wth"]))
  {
    error_preview(__LINE__,"ARGUMENTS Missing or Invalid");
  }

$member_id = $_REQUEST["mid"];
$width     = $_REQUEST["wth"];

include("scaling.inc");
if (!isset($WIDGET_SCALE[$width]))
  {
    error_preview(__LINE__,"WIDGET_SCALE - width not found: ".$width);
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

// print_r($attributes);

$widgetName = $_fn_;

// $filename="vmbar.js";
$filename="preview.js";

ob_start();
  $PUSHY_PREVIEW=TRUE;
  include($filename);
  $contents = ob_get_contents();
ob_end_clean();

$contentLength=strlen($contents);

header("HTTP/1.1 200 OK");
header("Accept-Ranges: bytes");
// header("Content-Length: $contentLength");
header("Content-Type: text/html");

echo $contents;

exit;



function error_preview($line,$message)
  {
    global $DEBUG;

    if ($DEBUG)
      {
        $fp=fopen("headers.txt","a");
        fputs($fp,sprintf("-------DEBUG----- ERROR_PREVIEW\n"));
        fputs($fp,sprintf("Line: %d  Message: %s\n",$line,$message));
        fclose($fp);
      }

    header("HTTP/1.1 412 Precondition Failed");
    exit;
  }
?>
