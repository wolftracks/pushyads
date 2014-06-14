<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

include("scaling.inc");

$base_width=360;
if (!isset($WIDGET_SCALE[$base_width]))
  {
    exit;
  }


printf("%s?%s\n\n","<",php);

printf("%sWIDGET_SCALE = array(\n",'$');

for ($z=180; $z<=360; $z+=10)
  {
    $attributes = $WIDGET_SCALE[$base_width];

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

    $new_width=$z;
    $ratio = $new_width/$width;

    /****
    if ($new_width <= 240)
      $font=9;
    else
    if ($new_width <= 300)
      $font=10;
    else
    if ($new_width <= 360)
      $font=11;
    else
      $font=12;
    ***/

    if ($new_width <= 220)
      $font=9;
    else
    if ($new_width <= 270)
      $font=10;
    else
    if ($new_width <= 320)
      $font=11;
    else
      $font=12;

    $ratio = $new_width/$width;

    if ($new_width==360)
      {
         printf("\n 360 => array (\n");
         foreach($attributes AS $k=>$v) {
              printf("          %s=> %3d,\n",padRight("\"$k\"",20),$v);
         }
         printf("        ),\n");
      }

    else

      {

         $t_width              =  $new_width;
         $t_height             =  (int) ($ratio * $height);

         $t_top_width          =  $t_width;
         $t_top_height         =  (int) ($ratio * $top_height);

         $t_left_width         =  (int) ($ratio * $left_width);
         $t_left_height        =  (int) ($ratio * $left_height);

         $t_client_width       =  (int) ($ratio * $ifrm_width);

         $t_right_width        =  $new_width - $t_left_width - $t_client_width;
         $t_right_height       =  (int) ($ratio * $right_height);

         $t_bottom_width       =  $t_width;
         $t_bottom_height      =  (int) ($t_height - ($t_top_height + $t_left_height));

         $t_ifrm_width         =  (int) ($ratio * $ifrm_width);
         $t_ifrm_height        =  (int) ($ratio * $ifrm_height);

         $t_scroll_width       =  (int) ($ratio * $scroll_width);
         $t_scroll_height      =  (int) ($ratio * $scroll_height);

         $t_table_width        =  (int) ($ratio * $table_width);

         $t_image_td_width     =  (int) ($ratio * $image_td_width);
         $t_image_width        =  (int) ($ratio * $image_width);

         $t_text_td_width      =  (int) ($ratio * $table_width)   - (int) ($ratio * $image_td_width);

         $t_text_font_size     =  $font;


         $test = array(
                   "width"             => $t_width,
                   "height"            => $t_height,

                   "top_width"         => $t_top_width,
                   "top_height"        => $t_top_height,

                   "left_width"        => $t_left_width,
                   "left_height"       => $t_left_height,

                   "right_width"       => $t_right_width,
                   "right_height"      => $t_right_height,

                   "client_width"      => $t_client_width,

                   "bottom_width"      => $t_bottom_width,
                   "bottom_height"     => $t_bottom_height,

                   "ifrm_width"        => $t_ifrm_width,
                   "ifrm_height"       => $t_ifrm_height,

                   "scroll_width"      => $t_scroll_width,
                   "scroll_height"     => $t_scroll_height,

                   "table_width"       => $t_table_width,

                   "image_td_width"    => $t_image_td_width,
                   "image_width"       => $t_image_width,

                   "text_td_width"     => $t_text_td_width,

                   "text_font_size"    => $t_text_font_size,
                 );


         // print_r($test);

         foreach($test AS $k=>$v) {
           $attributes[$k]=$v;
         }

         printf("\n $new_width => array (\n");
         foreach($attributes AS $k=>$v) {
              printf("          %s=> %3d,\n",padRight("\"$k\"",20),$v);
         }
         printf("        ),\n");
      }
 }
printf(");\n\n");

printf("?%s\n",">");

?>
