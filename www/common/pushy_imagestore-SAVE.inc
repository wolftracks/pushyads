<?php
include_once("phmagick.php");

$MAX_IMAGE_WIDTH =120;
$MAX_IMAGE_HEIGHT=165;

$RESIZE_GIF_FILES=TRUE;  // If False, GIFs are stored as received - Not Resized  (Animated Gif Support)

//--------------------------------------------------------------------------------------------------------
// Supported Image Formats and their  PHP Image Values for use in  PHP Header MimeType Conversion
//    string image_type_to_mime_type ( int $imagetype )   ===  header("Content-type: " . image_type_to_mime_type(IMAGETYPE_PNG));
//--------------------------------------------------------------------------------------------------------

$IMAGE_FORMAT_GIF      = "gif";
$IMAGE_FORMAT_JPEG     = "jpg";
$IMAGE_FORMAT_PNG      = "png";
$IMAGE_FORMAT_BITMAP   = "bmp";

//---------------------------------------------------------------------------------------------------------
// Valid Image Types      ( and their Minimum and Maximum widths/heights/sizes for Upload Audit purposes )
//---------------------------------------------------------------------------------------------------------

$IMAGE_PROPERTIES  = array(
   "min_width"     =>  50,
   "min_height"    =>  70,
   "max_width"     => 800,
   "max_height"    => 1200,
   "max_size"      => 250000,
   "target_width"  => $MAX_IMAGE_WIDTH,
   "target_height" => $MAX_IMAGE_HEIGHT
);


$MEDIA_SERVERS = array(
  /* 0 */   DOMAIN."/products/images",
  /* 1 */   DOMAIN."/products/imageserver",
  /* 2 */   "http://pds1106.s3.amazonaws.com/products/images",

);


$ERROR_IMAGE_FILE_NOT_FOUND        = 1001;
$ERROR_IMAGE_FORMAT_NOT_SUPPORTED  = 1002;
$ERROR_IMAGE_TYPE_INVALID          = 1003;
$ERROR_IMAGE_STORE_FAILURE         = 1004;
$ERROR_IMAGE_STORE_EXCEPTION       = 1005;
$ERROR_IMAGE_MOVE_ERROR            = 1006;
$ERROR_IMAGE_SCALE_EXCEPTION       = 1007;

$ERROR_IMAGE_TOO_NARROW            = 1011;
$ERROR_IMAGE_TOO_WIDE              = 1012;
$ERROR_IMAGE_TOO_SHORT             = 1013;
$ERROR_IMAGE_TOO_TALL              = 1014;
$ERROR_IMAGE_TOO_LARGE             = 1015;


function moveUploadedImage($db, $product_id, $image_filename, $isUpdate=FALSE)
  {
    global $IMAGE_PROPERTIES;
    global $MAX_IMAGE_WIDTH;
    global $MAX_IMAGE_HEIGHT;
    global $RESIZE_GIF_FILES;

    global $ERROR_IMAGE_FILE_NOT_FOUND;
    global $ERROR_IMAGE_FORMAT_NOT_SUPPORTED;
    global $ERROR_IMAGE_TYPE_INVALID;
    global $ERROR_IMAGE_STORE_FAILURE;
    global $ERROR_IMAGE_STORE_EXCEPTION;
    global $ERROR_IMAGE_MOVE_ERROR;
    global $ERROR_IMAGE_SCALE_EXCEPTION;

    global $ERROR_IMAGE_TOO_NARROW;
    global $ERROR_IMAGE_TOO_WIDE;
    global $ERROR_IMAGE_TOO_SHORT;
    global $ERROR_IMAGE_TOO_TALL;
    global $ERROR_IMAGE_TOO_LARGE;


    if (!file_exists($image_filename))
      return array($ERROR_IMAGE_FILE_NOT_FOUND, array());

    list($width,$height,$format,$other)=getimagesize($image_filename);

    if ($format == IMAGETYPE_GIF)  $image_format = "gif";
    else

    if ($format == IMAGETYPE_JPEG) $image_format = "jpg";
    else

    if ($format == IMAGETYPE_PNG)  $image_format = "png";
    else

//  if ($format == IMAGETYPE_BMP)  $image_format = "bmp";
//  else

      return array($ERROR_IMAGE_FORMAT_NOT_SUPPORTED, array());


    $image_size = filesize($image_filename);

    $original_width=$width;
    $original_height=$height;

    $dimensions["width"]      = $width;
    $dimensions["height"]     = $height;
    $dimensions["size"]       = $image_size;
    $dimensions["min-width"]  = $IMAGE_PROPERTIES["min_width"];
    $dimensions["min-height"] = $IMAGE_PROPERTIES["min_height"];
    $dimensions["max-width"]  = $IMAGE_PROPERTIES["max_width"];
    $dimensions["max-height"] = $IMAGE_PROPERTIES["max_height"];
    $dimensions["max-size"]   = $IMAGE_PROPERTIES["max_size"];

    if ($width  < $IMAGE_PROPERTIES["min_width"])
      {
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_TOO_NARROW, $dimensions);
      }
    if ($height < $IMAGE_PROPERTIES["min_height"])
      {
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_TOO_SHORT,  $dimensions);
      }

    if ($width  > $IMAGE_PROPERTIES["max_width"])
      {
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_TOO_WIDE,   $dimensions);
      }
    if ($height > $IMAGE_PROPERTIES["max_height"])
      {
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_TOO_TALL,   $dimensions);
      }

    if ($image_size > $IMAGE_PROPERTIES["max_size"])
      {
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_TOO_LARGE,  $dimensions);
      }

    $targetMediaBaseFile = _get_MediaBaseFile($product_id,$image_format);
    $targetMediaFile     = _get_MediaFileName($product_id,$image_format);
    $tempTargetMediaFile = _get_TempMediaFileName($product_id,$image_format);


    // printf("\n\nMOVE FROM: %s TO: %s\n\n",$image_filename,$tempTargetMediaFile);

    if ((IS_LOCAL) && (!is_array($_FILES) || count($_FILES)==0))
      {
        $mResult = rename($image_filename, $tempTargetMediaFile);
      }
    else
      {
        $mResult = move_uploaded_file($image_filename, $tempTargetMediaFile);
      }
    if ($mResult)
      {
        try
          {
             if ($image_format == "gif" && (!$RESIZE_GIF_FILES))
                {
                   if (file_exists($targetMediaFile))
                     {
                       unlink($targetMediaFile);
                     }
                   rename($tempTargetMediaFile,$targetMediaFile);
                }
             else
               {
                 $p = new phMagick($tempTargetMediaFile,$targetMediaFile);
                 $p->resize($MAX_IMAGE_WIDTH, $MAX_IMAGE_HEIGHT);
                 //---  $p->convert();     // --- NO FORMAT CONVERSION - DO NOT USE - ANIMATED GIFS RESIZE TO GIF BUT NOT TO PNG/JPG!!!
               }
             // printf("%s ==> %s\n",$tempTargetMediaFile,$targetMediaFile);
             // exit;
          }
        catch(Exception $e)
          {
             // printf("************ %s ==> %s\n",$tempTargetMediaFile,$targetMediaFile);
             // exit;
             if (file_exists($tempTargetMediaFile))
               {
                 unlink($tempTargetMediaFile);
               }
             if (!$isUpdate)
               {
                 deleteProduct($db,$product_id);
                 if (file_exists($targetMediaFile))
                   {
                     unlink($targetMediaFile);
                   }
               }
             return array($ERROR_IMAGE_SCALE_EXCEPTION, array());
          }

        if (file_exists($tempTargetMediaFile))
          {
            unlink($tempTargetMediaFile);
          }

                   //---- OK We Have the File Resized and Stored: targetMediaFile

        $currentImageFormat="";
        if (($isUpdate) && ($currentMediaInfo = getProductMedia($db, $product_id)))
          {
            $currentImageFormat = $currentMediaInfo["media_format"];
          }

        list($width,$height,$format,$other)=getimagesize($targetMediaFile);
        $image_size = filesize($targetMediaFile);

        $sql  = "UPDATE product set";
        $sql .= " media_type    = 1, ";                        // 1=IMAGE
        $sql .= " media_format  = '$image_format',";
        $sql .= " media_width   = '$width',";
        $sql .= " media_height  = '$height',";
        $sql .= " media_size    = '$image_size', ";
        $sql .= " media_original_width  = '$original_width',";
        $sql .= " media_original_height = '$original_height',";
        $sql .= " media_server  = 0,";   // LOCAL SERVER
        $sql .= " media_file='".$targetMediaBaseFile."'";
        $sql .= " WHERE product_id='$product_id'";
        $result = mysql_query($sql,$db);
        if (($result) && (is_array($mediaRecord = getProductMedia($db, $product_id))))
          {
              //--- Load the Image
            $fh=fopen($targetMediaFile, "r");
            $image_data=fread($fh, filesize($targetMediaFile));
            fclose($fh);


            $db2 = getPushyImageDatabaseConnection();

            $sql  = "DELETE from images ";
            $sql .= " WHERE media_file = '$targetMediaBaseFile'";
            mysql_query($sql,$db2);

            $sql  = "INSERT into images set";
            $sql .= " media_file   = '$targetMediaBaseFile',";
            $sql .= " media_format = '$image_format',";
            $sql .= " media_width  = '$width',";
            $sql .= " media_height = '$height',";
            $sql .= " media_size   = '$image_size',";
            $sql .= " media_last_modified = ".time().",";
            $sql .= " media_data   = '".addslashes($image_data)."'";
            $result = mysql_query($sql,$db2);
            if (($result) && (mysql_affected_rows()==1))
              {
                if (($isUpdate) && is_array($currentMediaInfo) && strlen($currentImageFormat)>0 &&  $image_format != $currentImageFormat)
                  {                         // In an UPDATE Scenario - New Image File - Different Image Type - Delete Old Image
                    $currentMediaFile = _get_MediaFileName($product_id,$currentImageFormat);
                    if (file_exists($currentMediaFile))
                      {
                        unlink($currentMediaFile);
                      }

                    $currentMediaBaseFile = _get_MediaBaseFile($product_id,$currentImageFormat);
                    $sql  = "DELETE from images WHERE media_file='$currentMediaBaseFile'";
                    mysql_query($sql,$db2);
                  }
                $db = getPushyDatabaseConnection();

                //--*--*--*--*--*--*--*--*--*--*--*--*--*--* For Now - Lets Try Not using the File System at all

                unlink($targetMediaFile);

                //--*--*--*--*--*--*--*--*--*--*--*--*--*--* For Now - Lets Try Not using the File System at all


                return array(0, $mediaRecord);   //============================================== SUCCESS ==========================
              }
            else
              {
                // printf("ERR: %s<br>\n",mysql_error());
                $db = getPushyDatabaseConnection();
                if (file_exists($targetMediaFile))
                  {
                    unlink($targetMediaFile);
                  }
                if (!$isUpdate)
                  {
                    deleteProduct($db,$product_id);
                  }
                return array($ERROR_IMAGE_STORE_EXCEPTION, array());
              }
          }

        if (file_exists($targetMediaFile))
          {
            unlink($targetMediaFile);
          }
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_STORE_FAILURE, array());
      }
    else
      {
        if (!$isUpdate)
          {
            deleteProduct($db,$product_id);
          }
        return array($ERROR_IMAGE_MOVE_ERROR, array());
      }
  }


function getProductMedia($db, $product_id)
  {
    if (!isNumeric($product_id)) return FALSE;

    $sql  = "SELECT media_type, media_format, media_width, media_height, media_size, media_server, media_file from product";
    $sql .= " WHERE product_id='$product_id'";
    $result = mysql_query($sql,$db);
    if ($result && (mysql_num_rows($result)==1) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        return($myrow);
      }
    if ($result)
      mysql_free_result($result);
    return FALSE;
  }


function deleteProduct($db, $product_id)
  {
    if (!isNumeric($product_id)) return FALSE;

    $db2=getPushyImageDatabaseConnection();

    $filetypes = array("gif", "pnf", "jpg");
    for ($i=0; $i<count($filetypes); $i++)
      {
        $ftype = $filetypes[$i];
        $tempBaseFile   = _get_MediaBaseFile($product_id,$ftype);
        $tempMediaFile  = _get_MediaFileName($product_id,$ftype);
        if (file_exists($tempMediaFile))
          {
            unlink($tempMediaFile);
          }

        $sql  = "DELETE FROM images";
        $sql .= " WHERE media_file='$tempBaseFile'";
        mysql_query($sql,$db2);
      }

    $db=getPushyDatabaseConnection();
    $sql  = "DELETE FROM product";
    $sql .= " WHERE product_id='$product_id'";
    mysql_query($sql,$db);

    return TRUE;
  }


//------------------------------------------------------------------------------------------ USED INTERNALLY ONLY

function _get_MediaFileName($product_id,$media_format)
  {        // Assumes its Local
    if (!isNumeric($product_id)) return FALSE;
    $fn = PUSHY_DOCROOT."/products/images/"._get_MediaBaseFile($product_id,$media_format);
    return($fn);
  }

function _get_MediaURL($product_id,$media_server,$media_format)
  {
    global $MEDIA_SERVERS;

    if (!isNumeric($product_id)) return FALSE;

    //-------------------------------------
    $media_server=1;   // /products/imageserver
    //-------------------------------------

    $mediaserver_url  = $MEDIA_SERVERS[$media_server]."/"._get_MediaBaseFile($product_id,$media_format);
    return($mediaserver_url);
  }

function _get_MediaBaseFile($product_id,$media_format)
  {
    if (!isNumeric($product_id)) return FALSE;
    $fn = "$product_id.$media_format";
    return($fn);
  }


function _get_TempMediaFileName($product_id,$media_format)
  {        // Assumes its Local
    if (!isNumeric($product_id)) return FALSE;
    $fn = PUSHY_DOCROOT."/products/images/"._get_TempMediaBaseFile($product_id,$media_format);
    return($fn);
  }

function _get_TempMediaBaseFile($product_id,$media_format)
  {
    if (!isNumeric($product_id)) return FALSE;
    $fn = "$product_id~".time().".$media_format";
    return($fn);
  }



function _exists_media_file($product_id,$media_format)
  {
    return file_exists(_get_MediaFileName($product_id,$media_format));
  }


function _scaleImage_XY($src,$tgt,$maxWidth,$maxHeight)
  {
    if (file_exists($src))
      {
        list($width,$height,$format,$other)=getimagesize($src);
        if ($format == IMAGETYPE_JPEG)
          {
            list($scaled,$new_width,$new_height) = _scaled_ImageSize($width,$height,$maxWidth,$maxHeight);
            if ($scaled)
              {
                $rc=scaleImage_X_Y($src,$tgt,"h:$new_height");
                if ($rc==0)
                  {
                    // delete src?
                    return(0);
                  }
                else
                  return($rc);
              }
            else
              {
                // no need to scale - just move
                return(0);
              }
          }
        else
          {
            return(-2);  // Image Format Not Supported
          }
      }
    else
      return(-3);  // File Not Found
  }


function _scaleImage_X_Y($src,$tgt,$sz)
  {
     $scaleWidth=false;
     $scaleHeight=false;
     if (strlen($sz) > 2 && (substr($sz,0,2)=="w:" || substr($sz,0,2)=="h:"))
       {
         if (substr($sz,0,2)=="w:")
           $scaleWidth=true;
         else
           $scaleHeight=true;
         $tarray=explode(":",$sz);
         if (count($tarray)!=2)
           return (-1);

         $size=(int) $tarray[1];
       }
     else
       return -1;

     $src_img = imagecreatefromjpeg($src);
     if ($src_img)
       {
            // calc portrait or landscape
         if ($scaleHeight)
            $scale = imagesy($src_img) /$size;
         else
            $scale = imagesx($src_img) /$size;

            // calc thumbnail dimensions
         $new_w = round(imagesx($src_img)/$scale);
         $new_h = round(imagesy($src_img)/$scale);
            // create empty image of correct size & copy/resize image into it
         $dst_img = imagecreatetruecolor($new_w,$new_h);
         imagecopyresized($dst_img,$src_img,0,0,0,0,
         $new_w,$new_h,
         imagesx($src_img),imagesy($src_img));
         imagedestroy($src_img);
            // save thumbnail to file
         imagejpeg($dst_img, $tgt);
         imagedestroy($dst_img);
         return 0;
       }
     return(-2);
  }


function _scaled_ImageSize($actualWidth, $actualHeight, $maxWidth, $maxHeight)
  {
    if ($maxWidth==0 || $maxHeight==0)
      return array(true, 0, 0);

    $resultWidth =$actualWidth;
    $resultHeight=$actualHeight;
    if ($resultWidth > $maxWidth)
      {
        $ratio = $maxWidth/$resultWidth;
        $resultWidth  = (int) $maxWidth;
        $resultHeight = (int) ($resultHeight * $ratio);
      }

    if ($resultHeight > $maxHeight)
      {
        $ratio = $maxHeight/$resultHeight;
        $resultHeight = (int) $maxHeight;
        $resultWidth  = (int) ($resultWidth * $ratio);
      }

    if ($resultWidth < $maxWidth && $resultHeight < $maxHeight)
      {
        if ($resultWidth/$maxWidth > $resultHeight/$maxHeight)
          $ratio = ($maxWidth/$resultWidth);
        else
          $ratio = ($maxHeight/$resultHeight);

        $resultWidth  = (int) ($resultWidth  * $ratio);
        $resultHeight = (int) ($resultHeight * $ratio);
      }

    $scaled=false;
    if ($resultWidth != $actualWidth || $resultHeight != $actualHeight)
       $scaled=true;

    // printf ("Scaled(%s): %d x %d  =>  %d x %d\n", ($scaled?"true":"false"), $actualWidth,$actualHeight,$resultWidth,$resultHeight);
    return array($scaled, (int) $resultWidth, (int) $resultHeight);
  }
?>
