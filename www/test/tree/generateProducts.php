<?php
 $DEBUG=FALSE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");
 include_once("pushy_imagestore.inc");
 include_once("tree_gen_options.php");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $urls=array(
   "http://yahoo.com",
   "http://cnn.com",
   "http://msn.com",
   "http://ibm.com",
   "http://microsoft.com",
   "http://google.com",
   "http://autoprospector.com",
   "http://wikipedia.org",
   "http://autoprospector.com/members",
   "http://twitter.com",
   "http://facebook.com",
 );


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


 function getProductCount($db,$member_id)
   {
     $count=0;
     $sql  = "SELECT COUNT(*) FROM ads WHERE member_id='$member_id'";
     $result = mysql_query($sql,$db);
     if ($result && ($myrow = mysql_fetch_array($result)))
       {
         $count=$myrow[0];
       }
     return($count);
   }


 function genCategories()
   {
     global $ProductCategories;
     $keys = array_keys($ProductCategories);
     sort($keys);

     $temp=array();
     $num=rand(2,14);
     for ($j=0; $j<$num; $j++)
       {
         $z=rand(0,count($keys)-1);
         $k=$keys[$z];
         while (isset($temp[$k]))
           {
             $z=rand(0,count($keys)-1);
             $k=$keys[$z];
           }
         $temp[$k]=TRUE;
       }

     $keys = array_keys($temp);
     sort($keys);
     $s="~";
     for ($j=0; $j<count($keys); $j++)
       $s .= $keys[$j]."~";
     return $s;
   }


 $ADS_CREATED=0;
 $PRODUCTS_CREATED=0;
 $IMAGES_CREATED=0;

 $adPlacement=0;

 $sql  = "SELECT * FROM member ORDER BY member_id";
 $result = mysql_query($sql,$db);
 if ($result)
   {
     $count=mysql_num_rows($result);
     while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
       {
         $memberRecord = $myrow;
         $member_id    = $memberRecord["member_id"];
         $user_level   = $memberRecord["user_level"];


         $pcount=getProductCount($db,$member_id);
         if ($user_level == 2 && $pcount < 4)
            { /* OK */ }
         else
         if ($pcount == 0)
            { /* OK */ }
         else
           {
             continue;
           }

         $product_name  = genText(8,12,TRUE);
         // printf("%s\n",$product_name);
         $product_title = genText(8,18,TRUE);
         // printf("%s\n",$product_title);
         $product_description = genText(26, 50, FALSE);
         // printf("%s\n",$product_description);

         $product_categories=genCategories();

         $j=rand(0,count($urls)-1);
         $url=$urls[$j];
         $product_url       = $url;

         $affiliate_signup_url = "";
         if ($user_level == $PUSHY_LEVEL_ELITE)
           {
             $j=rand(0,count($urls)-1);
             $url=$urls[$j];
             $affiliate_signup_url = $url;
           }

     //- $isApproved = rand(0,4);
     //- if ($isApproved == 3)
     //-    $isApproved = 0;
     //- else
     //-    $isApproved = 1;

         //-----------------------------------------------------------------

         $sql  = "INSERT into product set ";
         $sql .= " product_submit_date='".getDateToday()."', ";
         $sql .= " product_owner='$member_id', ";


   //-----------------------------------------------------------------  Should be 0  (ALWAYS)
   //--- $sql .= " product_approved=$isApproved, ";


         $sql .= " product_name='".addslashes($product_name)."', ";
         $sql .= " product_title='".addslashes($product_title)."', ";
         $sql .= " product_description='".addslashes($product_description)."', ";
         $sql .= " product_categories='$product_categories' ";
         $res = mysql_query($sql,$db);
         if ($res && (mysql_affected_rows()==1))
           {

             $PRODUCTS_CREATED++;

             $product_id = mysql_insert_id($db);
             if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
               {

               }
             else
               {

                 $j=rand(0,count($bgcolors)-1);
                 $bg=$bgcolors[$j];

                 $j=rand(0,1);
                 // $j=rand(0,count($fgcolors)-1);
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


                 $w=52;
                 $h=73;

                 $w=round(52 * 1.25);
                 $h=round(73 * 1.25);

                 $UPDATE=FALSE;

                 $j=rand(1,3);
                 if ($j==1) $image_format="gif";
                 if ($j==2) $image_format="png";
                 if ($j==3) $image_format="png";

                 $filename="qwerty.$image_format";

                 $textArray=array("P: $product_id", $memberRecord["lastname"], $memberRecord["firstname"], $UserLevels[$user_level]);
                 printf("FILE=%s\n",$filename);
                 print_r($textArray);
                 //exit;

                 $IMAGES_CREATED++;

                 $res=createImageFile($filename, $textArray, $bg, $fg, $w, $h, $image_format);

                 if (!($res))
                   {
                     printf("Image Create Failed\n");
                     exit;
                   }

                 $UPLOAD_FAILURE = 0;
                 $ERROR_MESSAGE  = "";
                 $IMAGE_UPLOADED = FALSE;
                 $IMAGE_INFO     = array();

                 $isUpdate=FALSE;
                 if (isset($UPDATE) && ($UPDATE))
                    $isUpdate=TRUE;

                 list($rc,$res)=moveUploadedImage($db, $product_id, $filename, $isUpdate);
                 if ($rc==0)
                   {
                     $IMAGE_UPLOADED = TRUE;
                     $IMAGE_INFO=$res;
                     $IMAGE_INFO["image_name"]=$product_id.".".$image_format;

                     printf("... UPLOAD SUCCESSFUL\n");
                     print_r($IMAGE_INFO);
                   }
                 else
                     printf("... UPLOAD FAILED RC=%d\n",$rc);

                 if (file_exists($filename))
                   {
                     unlink($filename);
                   }
               }


             if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP || ($IMAGE_UPLOADED))
               {
                 // printf("<PRE>");
                 // print_r($IMAGE_INFO);
                 // printf("</PRE>");

                 // Very Last thing We Want to Do is create an Ad Record for this Product
                 $sql  = "INSERT into ads set ";
                 $sql .= " member_id='$member_id', ";
                 $sql .= " reseller_listing=0, ";
                 $sql .= " product_id='$product_id', ";
                 $sql .= " product_url='$product_url', ";
                 $sql .= " affiliate_signup_url='$affiliate_signup_url', ";

                 if ($user_level == $PUSHY_LEVEL_ELITE)
                    {
                      $adPlacement++;
                      if ($adPlacement==5) $adPlacement=1;

                      if ($adPlacement==1)
                         $sql .= " pushy_network=1";
                      if ($adPlacement==2)
                         $sql .= " elite_bar=1";
                      if ($adPlacement==3)
                         $sql .= " elite_pool=1";
                      if ($adPlacement==4)
                         $sql .= " product_list=1";
                    }

                 // $sql .= " pushy_network=0, ";
                 // $sql .= " elite_bar=0, ";
                 // $sql .= " elite_pool=0, ";
                 // $sql .= " product_list=0, ";

                 $sql .= " date_created='".getDateToday()."', ";
                 $sql .= " last_modified='".getDateToday()."'";
                 $res = mysql_query($sql,$db);
                 if ($res && (mysql_affected_rows()==1))
                   {
                      // OK
                      $ADS_CREATED++;
                   }
                 else
                   {
                     deleteProduct($db,$product_id);

                     $ERROR=TRUE;
                     $ERROR_MESSAGE = "Failed";
                   }
               }
             else
               {

                 deleteProduct($db,$product_id);
                 $ERROR=TRUE;

                 // $UPLOAD_FAILURE and ERROR_MESSAGE was SET by Image Uploader
                 if ($UPLOAD_FAILURE != 0 && strlen($ERROR_MESSAGE)==0)
                   {
                     $ERROR_MESSAGE = "Image File Upload Failure ($UPLOAD_FAILURE) ";
                   }
               }
           }
         else
           {
             $ERROR=TRUE;
             $ERROR_MESSAGE = "Unable to Add Product";  // Should Never Happen
           }


       }
   }

 printf("\n\nADS CREATED=%d   PRODUCTS CREATED=%d   With Images=%d\n", $ADS_CREATED,  $PRODUCTS_CREATED, $IMAGES_CREATED);

 exit;



function genText($minlen, $maxlen, $caps=FALSE)
  {
    $chars="abca defge hijki lmmopqro stuvwu xz";

    $tlen=rand($minlen, $maxlen);
    $blank=false;
    $text="";
    $lastBlank=0;
    for ($i=0; $i<$tlen; $i++)
      {
        $n=rand(0,strlen($chars)-1);
        $ch = $chars[$n];
        if ($i-$lastBlank > 14)
          {
            $ch=" ";
          }
        if (($caps) && $blank)
          $ch=strtoupper($ch);
        $text .= $ch;
        $blank=false;
        if ($ch==" ") {$blank=true; $lastBlank=$i;}
      }
    if ($caps)
      $text=ucfirst($text);
    $text=str_replace("  "," ",$text);
    $text=str_replace("  "," ",$text);
    $text=striplt($text);

    $pos=strpos($text," ");
    if (!is_integer($pos))
      {
        $m = (int) strlen($text)/2;
        $s1 = substr($text,0,$m);
        $s2 = substr($text,$m);
        // printf("M=%d  s1=%s  s2=%s\n",$m,$s1,$s2);
        if ($caps)
           $text = ucfirst($s1)." ".ucfirst($s2);
        else
           $text = $s1." ".$s2;
      }

    return($text);
  }




function createImageFile($image_file, $text, $bg, $fg, $width, $height, $image_format)
  {

    $img = imagecreate( $width, $height );
    $background  = imagecolorallocate( $img, $bg[0], $bg[1], $bg[2] );
    $text_colour = imagecolorallocate( $img, $fg[0], $fg[1], $fg[2] );

    $fontSize=4;  // 1 (smallest) to 5 (largest)
    $fontHeight=18;
    $fontWidth=8;

    $textLength= 0;
    $textLines = count($text);
    for ($i=0; $i<$textLines; $i++)
      {
        if (strlen($text[$i]) > $textLength)
          $textLength = strlen($text[$i]);
      }

    // printf("TextLength=%d\n",$textLength);
    // printf("TextLines=%d\n" ,$textLines);
    // printf("Width=%d\n"  ,$width);
    // printf("Height=%d\n" ,$height);
    // printf("fontHeight=%d\n" ,$fontHeight);
    // printf("fontWidth=%d\n"  ,$fontWidth);
    // exit;


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
        imagegif( $img, $image_file);
      }
    else
    if ($image_format == "png")
      {
        $image_format="png";
        imagepng( $img, $image_file);
      }
    else
    if ($image_format == "jpg" || $image_format == "jpeg")
      {
        $image_format="jpg";
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
