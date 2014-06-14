<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_tree.inc");
include_once("pushy_tracker.inc");

$width=360;

if ($width <= 200)                     { $css_group = 1;  $css_image_width=37;  $css_image_height=50; }
else
if ($width >= 210  &&  $width <= 230)  { $css_group = 2;  $css_image_width=43;  $css_image_height=59; }
else
if ($width >= 240  &&  $width <= 270)  { $css_group = 3;  $css_image_width=52;  $css_image_height=73; }
else
if ($width >= 280  &&  $width <= 310)  { $css_group = 4;  $css_image_width=60;  $css_image_height=84; }
else
if ($width >= 320)                     { $css_group = 5;  $css_image_width=74;  $css_image_height=102;}


 $TARGET_HEIGHT=$css_image_height;
 $TARGET_WIDTH =$css_image_width;

 $dim="";
 if ($media_height > 0 && $media_width > 0)
   {
     list($scaled, $new_width, $new_height) = _scaled_ImageSize($media_original_width, $media_original_height, $TARGET_WIDTH, $TARGET_HEIGHT);
     $dim = "height=$new_height width=$new_width";
   }
 else
   $dim = "height=$TARGET_HEIGHT width=$TARGET_WIDTH"; // Strectch


echo $dim;

?>
