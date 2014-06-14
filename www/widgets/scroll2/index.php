<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_tree.inc");
include_once("pushy_tracker.inc");

$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

$CURRENT_DIRECTORY   = getcwd();
$THIS_FILE_DIRECTORY = dirname(__FILE__);

$PUSHY_SITE=FALSE;

$TEST_MODE   =FALSE;
$DUMP_HEADERS=FALSE;
$DEBUG       =FALSE;

$referer_domain = "";
$referer_page   = "";
if (strlen($HTTP_REFERER) > 8 && substr($HTTP_REFERER,0,7) == "http://")
  {
    $referer=substr($HTTP_REFERER,7);
    $pos=strpos($referer,"/");
    if (is_integer($pos))
      {
        $referer_domain = substr($referer,0,$pos);
        $referer_page   = substr($referer,$pos);
        $pos=strpos($referer_domain,":");
        if (is_integer($pos))
          $referer_domain = substr($referer_domain,0,$pos);
        if (substr($referer_domain,0,4)=="www.")
          $referer_domain = substr($referer_domain,4);

        if (strlen($referer_page) > 0 && substr($referer_page,strlen($referer_page)-1,1) == "/")
          $referer_page = substr($referer_page,0,strlen($referer_page)-1);
      }
  }
list($_uri_,$_qs_) = split("\?",$REQUEST_URI);

$file="";
$URI_ELEMENT_COUNT=0;
$_uri_tokens_ = explode("/",$_uri_);
$j=0;
for ($i=0; $i<count($_uri_tokens_); $i++)
  {
    // printf("i=%d token=%s\n",$i,$_uri_tokens_[$i]);
    if (strlen(trim($_uri_tokens_[$i]))>0)
      {
        $j++;
        if ($j==2)
          $file=trim($_uri_tokens_[$i]);
        else
        if ($j>2)
          {
            notFound(__LINE__, "URI Parse Error - Too Many Tokens");
          }
      }
  }

if (strlen($file)==0)
  {
    notFound(__LINE__, "Unable to Parse File Name");
  }

list($_fn_,$_ext_) = split("\.",$file);

//printf("URI=%s\n",$_uri_);
//printf("TOKENS=%d\n",count($_uri_tokens));
//printf("FILE=%s\n",$file);
//printf("FN=%s\n",$_fn_);
//printf("EXT=%s\n",$_ext_);
//exit;

if ($_ext_ != "php")
  {
    notFound(__LINE__, "Extension invalid: '$_ext_'");
    exit;
  }


//------------------------------ PREVIEW -------------------------
if (strlen($_fn_)>3 && substr($_fn_,0,2)=="p_") // preview
  {
    include("scroll_preview.php");
    exit;
  }
//------------------------------ PREVIEW -------------------------


if ($DUMP_HEADERS)
  {
    $fp=fopen("headers.txt","w");
    fputs($fp,"--------------------------------------------------------\n");
    fputs($fp,sprintf("DateTime: %s\n",getDateTime()));
    fputs($fp,sprintf("REFERER: %s\n",$referer));
    fputs($fp,sprintf("REFERER_DOMAIN: %s\n",$referer_domain));
    fputs($fp,sprintf("REFERER_PAGE:   %s\n",$referer_page));
    fputs($fp,sprintf("REMOTE_ADDR:    %s\n",$REMOTE_ADDR));

    /*******-------------
    fputs($fp,"\n");
    fputs($fp,sprintf("Method: %s\n",$_SERVER["REQUEST_METHOD"]));

    if (is_array($_REQUEST) && count($_REQUEST) > 0)
      {
        fputs($fp,sprintf("-------- REQUEST VARS -- (Get/Post/Cookie/Files) ---\n"));
        while (list($key00, $value00) = each($_REQUEST))
          {
            fputs($fp,sprintf("%s=%s\n",$key00,$value00));
          }
        fputs($fp,sprintf("\n\n\n"));
      }

    if (is_array($_COOKIE) && count($_COOKIE) > 0)
      {
        fputs($fp,sprintf("-------- COOKIE VARS ---------\n"));
        while (list($key00, $value00) = each($_COOKIE))
          {
            fputs($fp,sprintf("%s=%s\n",$key00,$value00));
          }
        fputs($fp,sprintf("\n\n\n"));
      }

    if (is_array($_SERVER) && count($_SERVER) > 0)
      {
        fputs($fp,sprintf("-------- SERVER VARS ---------\n"));
        while (list($key00, $value00) = each($_SERVER))
          {
            fputs($fp,sprintf("%s=%s\n",$key00,$value00));
          }
        fputs($fp,sprintf("\n\n\n"));
      }
    --------------------*****/
    fclose($fp);
  }


if (!$TEST_MODE && !startsWith($referer_domain,"pushyads.") && $REMOTE_ADDR != "127.0.0.1")
  {
    if (strlen($referer_domain) < 5 || !is_integer(strpos($referer_domain,".")))
      {
        notSupported(__LINE__, "Referer Domain Missing/Invalid: '$referer_domain'");
        exit;
      }

    if (strlen($REMOTE_ADDR) < 7)
      {
        notSupported(__LINE__, "REMOTE_ADDR Missing/Invalid: '$REMOTE_ADDR'");
        exit;
      }
  }


$db=getPushyDatabaseConnection();
$widget = getWidget($db, $_fn_); // returns FALSE if hash not found || User Not Enabled || Widget Not Enabled
if (!is_array($widget))
  {
    notFound(__LINE__, "Widget Not Found: Hash='$_fn_'");
    exit;
  }

$refid="";
$member_id        = $widget["member_id"];
$WidgetOwner      = $widget["member_id"];

if (startsWith($referer_domain,"pushyads."))
  {
    $PUSHY_SITE=TRUE;
    if (strlen($referer_page) > 1 && substr($referer_page,0,1) == "/")
      {
        $temp_aff_id = substr($referer_page,1);
        $memberRecord=getMemberInfoForAffiliate($db,$temp_aff_id);
        if (is_array($memberRecord))
          {
            $WidgetOwner = $memberRecord["member_id"];
            $refid       = $memberRecord["refid"];   // My Referer
          }
      }
  }

if (strlen($refid)==0)
  {
    $memberRecord=getMemberInfo($db,$WidgetOwner);  // My (Widget Owner's)  Member Record
    if (is_array($memberRecord))
      {
        $refid = $memberRecord["refid"];   // My Referer
      }
  }

$WidgetScroller   = $widget["_pushy_scroller_"];   // This is a SYSTEM LEVEL Setting Only  - Honored only for user='pushy'  (PUSHY_ROOT)

$WidgetKey        = $widget["widget_key"];
$WidgetDomain     = $widget["domain"];
$WidgetAction     = $widget["action"];
$WidgetId         = $widget["widget_id"];
$WidgetCategories = $widget["widget_categories"];
$WidgetWidth      = $widget["width"];
$WidgetStyle      = $widget["style"];
$WidgetOrigin     = $widget["origin"];
$WidgetSpeed      = $widget["speed"];


// if ($DEBUG)
if (TRUE)
  {
    $fp=fopen("headers.txt","a");
    fputs($fp,sprintf("-------DEBUG-------\n"));
    fputs($fp,sprintf(":WidgetKey: %s\n",$WidgetKey));
    fputs($fp,sprintf(":WidgetDomain: %s\n",$WidgetDomain));
    fputs($fp,sprintf(":WidgetOwner: %s\n",$WidgetOwner));
    fputs($fp,sprintf(":referer_page: %s\n",$referer_page));
    fputs($fp,sprintf(":temp_aff_id: %s\n",$temp_aff_id));
    fclose($fp);
  }

//--- Must be coming from the Domain that was specified by this Widget Configuration
if (!$TEST_MODE && !$PUSHY_SITE && $REMOTE_ADDR != "127.0.0.1")
  {
    if ($WidgetDomain != $referer_domain)
      {
        notFound(__LINE__, "Widget is not coming from Configured Domain: Domain Expected: ".$WidgetDomain."  Referer: ".$referer_domain);
        exit;
      }
  }


include("scaling.inc");

$width=$WidgetWidth;

if (!isset($WIDGET_SCALE[$width]))
  {
    notSupported(__LINE__,"WIDGET_SCALE - width not found: ".$width);
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

$widgetName = "PUSHY";

$_qs_tokens_ = explode("&",$_qs_);
$_qs_array_  = array();
for ($i=0; $i<count($_qs_tokens_); $i++)
  {
    if (strlen(trim($_qs_tokens_[$i]))>0)
      {
        $temp=trim($_qs_tokens_[$i]);
        list($_k_,$_v_) = split("=",$temp);
        $_qs_array_[$_k_]=$_v_;
      }
  }

$tracking_id="";
if (isset($_qs_array_["tracker"]))
  {
    $tracking_id=strtolower($_qs_array_["tracker"]);
  }

$filename="vmbar.js";
if ($member_id == $PUSHY_ROOT && strlen($WidgetScroller)>0 && file_exists($WidgetScroller))
  {
    $filename=$WidgetScroller;
  }

ob_start();
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



function notFound($line,$message)
  {
    global $DEBUG;

    if ($DEBUG)
      {
        $fp=fopen("headers.txt","a");
        fputs($fp,sprintf("-------DEBUG----- FAIL: NOTFOUND\n"));
        fputs($fp,sprintf("Line: %d  Message: %s\n",$line,$message));
        fclose($fp);
      }

    header("HTTP/1.1 404 Not Found");
    exit;
  }
function notSupported($line,$message)
  {
    global $DEBUG;

    if ($DEBUG)
      {
        $fp=fopen("headers.txt","a");
        fputs($fp,sprintf("-------DEBUG----- FAIL: NOTSUPPORTED\n"));
        fputs($fp,sprintf("Line: %d  Message: %s\n",$line,$message));
        fclose($fp);
      }

    header("HTTP/1.1 412 Precondition Failed");
    exit;
  }
?>
