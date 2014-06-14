<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");

$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

list($_uri_,$_qs_) = explode("?",$REQUEST_URI);

$media_file="";
$URI_ELEMENTS=0;
$_uri_tokens_ = explode("/",$_uri_);
for ($i=0; $i<count($_uri_tokens_); $i++)
  {
    if (strlen(trim($_uri_tokens_[$i]))>0)
      {
        $temp=trim($_uri_tokens_[$i]);
        // printf("%d) %s<br>\n",$URI_ELEMENTS,$temp);

        if ($URI_ELEMENTS==2)
           {
             $media_file=$temp;
             // printf("  * media_file=%s<br>\n",$media_file);
           }
        $URI_ELEMENTS++;
      }
  }

$QS_ELEMENTS=0;
$_qs_tokens_ = explode("&",$_qs_);
for ($i=0; $i<count($_qs_tokens_); $i++)
  {
    if (strlen(trim($_qs_tokens_[$i]))>0)
      {
        $temp=trim($_qs_tokens_[$i]);
        list($_k_,$_v_) = split("=",$temp);

        //------ printf("%d) (%s)  %s=>%s<br>\n",$QS_ELEMENTS,$temp,$_k_,$_v_);

        $QS_ELEMENTS++;
      }
  }


if (strlen($media_file) > 0)
  {
    list($fn,$ext) = explode(".",$media_file);

    if ($ext == "jpg" || $ext == "jpeg") $mime = image_type_to_mime_type(IMAGETYPE_JPEG);
    else
    if ($ext == "gif")                   $mime = image_type_to_mime_type(IMAGETYPE_GIF);
    else
    if ($ext == "png")                   $mime = image_type_to_mime_type(IMAGETYPE_PNG);
    // else
    // if ($ext == "bmp")                $mime = image_type_to_mime_type(IMAGETYPE_BMP);
    else
      {
        header('HTTP/1.1 404 Not Found');
        exit;
      }


    $db = getPushyImageDatabaseConnection();
    $sql = "SELECT * from images WHERE media_file='$media_file'";
    $result = mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        mysql_free_result($result);
        $media_last_modified = $myrow["media_last_modified"];
        $media_size          = $myrow["media_size"];
        $media_data          = $myrow["media_data"];

        $etag = md5("$media_last_modified:$media_size");

        $headers = getallheaders();
        // if Browser sent ID, we check if they match
        if (ereg($etag, $headers['If-None-Match']))
          {
            header('HTTP/1.1 304 Not Modified');
            exit;
          }
        else
        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $media_last_modified ||
             trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)
          {
            header("HTTP/1.1 304 Not Modified");
            exit;
          }
        else
          {
            header("Accept-Ranges: bytes");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s", $media_last_modified)." GMT");
            header("ETag: \"{$etag}\"");
            header("Content-Length: $media_size");
            header("Content-Type: {$mime}");

            echo $myrow["media_data"];
            exit;
          }
      }
  }

header("HTTP/1.1 404 Not Found");
printf("<h2>You are not Authorized to Access This Page</h2><br>\n");
exit;
?>
