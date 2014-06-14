<?php
//------  REQUIRED by image_uploaded.php -----------------------
//
// $upload_image_variable =  Image Form Variable    e.g. $image_variable"new_product_image";
// $product_id            =  The unique Product ID in the Product Table where the Image Media Data will be stored
//
//--------------------------------------------------------------

//------  Set by image_uploaded.php ----------------------------
//
//  $IMAGE_UPLOADED   Boolean  -  TRUE=Success  FALSE=Failure
//  $IMAGE_INFO       image record as stored  - see "image_key"
//
//  ---- Failures -----
//  $UPLOAD_FAILURE   = Error Code (Internal Debuggiing)
//  $ERROR_MESSAGE    = Descriptive Error Message
//
//--------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
//
//  $_FILES['userfile']['name']     - The original name of the file on the client machine.
//  $_FILES['userfile']['type']     - The mime type of the file, if the browser provided this information.
//                                    An example would be "image/gif". This mime type is however not checked
//                                    on the PHP side and therefore don't take its value for granted.
//  $_FILES['userfile']['size']     - The size, in bytes, of the uploaded file.
//  $_FILES['userfile']['tmp_name'] - The temporary filename of the file in which the uploaded file was stored on the server.
//  $_FILES['userfile']['error']    - The error code associated with this file upload. This element was added in PHP 4.2.0
//
//--------------------------------------------------------------------------------------------------------------------------------

// printf("<PRE>");
// print_r($_FILES);
// printf("\n\n---------------------------------\n\n");
// print_r($IMAGE_PROPERTIES);
// printf("\n\n---------------------------------\n\n");
// print_r($_REQUEST);
// printf("</PRE>");

$UPLOAD_FAILURE = 0;
$ERROR_MESSAGE  = "";
$IMAGE_UPLOADED = FALSE;
$IMAGE_INFO     = array();


if ($_FILES[$upload_image_variable]["size"] > 0)
  {
     $ftype=$_FILES[$upload_image_variable]["type"];
     if (strlen($ftype)>5 && substr($ftype,0,5)=="image")
       {
         // $filename1 = preg_replace("/\s/", "", trim($_FILES[$upload_image_variable]['tmp_name']));
         $filename1 = $_FILES[$upload_image_variable]['tmp_name'];
         list($fn,$ext) = split("\.",strtolower($_FILES[$upload_image_variable]['name']));
         if (strlen($ext)==3)
            $ext=strtolower($ext);
         if (strlen($ext)==3 &&
              ( $ext  == "gif" ||
                $ext  == "jpg" ||
                $ext  == "png" ||
                $ext  == "bmp" ) )
           {
             $image_name=$fn.".".$ext; // File Name As Uploaded

             $isUpdate=FALSE;
             if (isset($UPDATE) && ($UPDATE))
                $isUpdate=TRUE;

             list($rc,$result)=moveUploadedImage($db, $product_id, $filename1, $isUpdate);
             if ($rc==0)
               {
                 $IMAGE_UPLOADED = TRUE;
                 $IMAGE_INFO=$result;
                 $IMAGE_INFO["image_name"]=$image_name;
               }
             else
               {
// printf("<PRE>");
// printf("\n\n--- IMAGE RESULT ----------------\n\n");
// print_r($result);
// printf("</PRE>");
                 $UPLOAD_FAILURE = 21;
                 $ERROR_MESSAGE  = "Image Upload Failed";

                 switch ($rc)
                   {
                     case $ERROR_IMAGE_FILE_NOT_FOUND:
                       {
                         $UPLOAD_FAILURE = 22;
                         $ERROR_MESSAGE  = "Image File Not Found ";
                         break;
                       }
                     case $ERROR_IMAGE_FORMAT_NOT_SUPPORTED:
                       {
                         $UPLOAD_FAILURE = 23;
                         $ERROR_MESSAGE  = "Image File - Unrecognized Image Format ";
                         break;
                       }
                     case $ERROR_IMAGE_TYPE_INVALID:
                       {
                         $UPLOAD_FAILURE = 24;
                         $ERROR_MESSAGE  = "Image File Type Invalid ";
                         break;
                       }
                     case $ERROR_IMAGE_STORE_FAILURE:
                       {
                         $UPLOAD_FAILURE = 25;
                         $ERROR_MESSAGE  = "Image Store Failed";
                         break;
                       }
                     case $ERROR_IMAGE_STORE_EXCEPTION:
                       {
                         $UPLOAD_FAILURE = 26;
                         $ERROR_MESSAGE  = "Image Store Exception ";
                         break;
                       }
                     case $ERROR_IMAGE_MOVE_ERROR:
                       {
                         $UPLOAD_FAILURE = 27;
                         $ERROR_MESSAGE  = "Image Move Error ";
                         break;
                       }
                     case $ERROR_IMAGE_TOO_NARROW:
                       {
                         $UPLOAD_FAILURE = 28;
                         $dimensions=$result;
                         $ERROR_MESSAGE  = "Image Too Narrow: Image Width=".$dimensions["width"]." pixels  Minimum=".$dimensions["min-width"]." pixels ";
                         break;
                       }
                     case $ERROR_IMAGE_TOO_WIDE:
                       {
                         $UPLOAD_FAILURE = 29;
                         $dimensions=$result;
                         $ERROR_MESSAGE  = "Image Too Wide: Image Width=".$dimensions["width"]." pixels  Maximum=".$dimensions["max-width"]." pixels ";
                         break;
                       }
                     case $ERROR_IMAGE_TOO_SHORT:
                       {
                         $UPLOAD_FAILURE = 30;
                         $dimensions=$result;
                         $ERROR_MESSAGE  = "Image Too Short: Image Height=".$dimensions["height"]." pixels  Minimum=".$dimensions["min-height"]." pixels ";
                         break;
                       }
                     case $ERROR_IMAGE_TOO_TALL:
                       {
                         $UPLOAD_FAILURE = 31;
                         $dimensions=$result;
                         $ERROR_MESSAGE  = "Image Too Tall: Image Height=".$dimensions["height"]." pixels  Maximum=".$dimensions["max-height"]." pixels ";
                         break;
                       }
                     case $ERROR_IMAGE_TOO_LARGE:
                       {
                         $UPLOAD_FAILURE = 32;
                         $dimensions=$result;
                         $ERROR_MESSAGE  = "Image Too Large: Image Size=".$dimensions["size"]." bytes   Maximum=".$dimensions["max-size"]." bytes ";
                         break;
                       }
                     case $ERROR_IMAGE_SCALE_ERROR:
                       {
                         $UPLOAD_FAILURE = 33;
                         $ERROR_MESSAGE  = "Image Scale Error ";
                         break;
                       }
                   }
               }
           }
         else
           {
              $UPLOAD_FAILURE = 4;
              $ERROR_MESSAGE = "Image File - Unsupported Type - Must be JPG,GIF,PNG,BMP ";
           }
       }
     else
       {
         $UPLOAD_FAILURE = 3;
         $ERROR_MESSAGE = "Image File - Unrecognized Image Format ";
       }
  }
else
  {
    $ERROR_MESSAGE = "Image File Not Found ";
    $UPLOAD_FAILURE = 1;
  }
?>
