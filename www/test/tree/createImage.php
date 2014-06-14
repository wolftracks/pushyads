<?php

$bgcolors=array(
   array(0,0,0),
   array( 60, 60, 60),
   array(100,100,100),
   array(140,140,140),
   array(180,180,180),

   array(160,60,60),
   array(60,160,60),
   array(60,60,160),
   array(160,160,60),
   array(60,160,160),
   array(160,60,160),

   array(140,0,0),
   array(0,140,0),
   array(0,0,140),
   array(140,140,0),
   array(0,140,140),
   array(140,0,140),
);

$fgcolors=array(
   array(255,255,255),
   array(255,255,0),
   array(240,240,0),
   array(240,0,0),
   array(0,240,0),
   array(255,255,255),
   array(0,0,240),
   array(0,240,240),
   array(240,0,240),
);


for ($z=1901; $z<=1909; $z++)
  {
    $j=rand(0,count($bgcolors)-1);
    $bg=$bgcolors[$j];

    $j=rand(0,count($fgcolors)-1);
    $fg=$fgcolors[$j];


    $w=120;
    $h=165;

    $j = rand(1,50);
    if (rand(1,2)==2)
      $w -= $j;
    else
      $w += $j;
    $j = rand(1,50);
    if (rand(1,2)==2)
      $h -= $j;
    else
      $h += $j;

    createImageFile($z, array("P: $z", "Anderson", "Elizabeth", "Elite"), $bg, $fg, $w, $h, "gif");
  }

function createImageFile($product_id, $text, $bg, $fg, $width, $height, $image_format)
  {

    $img = imagecreate( $width, $height );
    $background  = imagecolorallocate( $img, $bg[0], $bg[1], $bg[2] );
    $text_colour = imagecolorallocate( $img, $fg[0], $fg[1], $fg[2] );

    $fontSize=4;  // 1 (smallest) to 5 (largest)
    $fontHeight=18;
    $fontWidth=8;

    $textLength= 0;
    $textLines = count($text);
    for ($i=0; $i<count($text); $i++)
      {
        if (strlen($text[$i]) > $textLength)
          $textLength = strlen($text[$i]);
      }

    $y = round(($height-($textLines*$fontHeight)) / 3);
    if ($x<0) $x=0;
    $x = round(($width-($textLength*$fontWidth))  / 2);
    if ($y<0) $y=0;

    for ($i=0; $i<count($text); $i++)
      {
        imagestring( $img, $fontSize, $x, $y, $text[$i], $text_colour );
        if ($fontSize > 2)
           $fontSize--;
        $y+=$fontHeight;
      }

    if ($image_format == "gif")
      {
        $image_format="gif";
        $image_file=$product_id.".".$image_format;
        imagegif( $img, $image_file);
      }
    else
    if ($image_format == "png")
      {
        $image_format="png";
        $image_file=$product_id.".".$image_format;
        imagepng( $img, $image_file);
      }
    else
    if ($image_format == "jpg" || $image_format == "jpeg")
      {
        $image_format="jpg";
        $image_file=$product_id.".".$image_format;
        imagejpeg( $img, $image_file);
      }
    else
      return(FALSE);


    printf("%s: W=%d  H=%d  BG=%02x%02x%02x  FG=%02x%02x%02x  X=%d  Y=%d  TXT=%s\n",
             $image_file,$width,$height, $bg[0],$bg[1],$bg[2],  $fg[0],$fg[1],$fg[2],  $x, $y, $text);


    imagecolordeallocate($img, $text_color );
    imagecolordeallocate($img, $background );
    imagedestroy( $img );
    return(TRUE);
  }

?>
