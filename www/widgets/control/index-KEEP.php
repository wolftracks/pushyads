<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$TEST_MODE    = FALSE;
$DUMP_HEADERS = FALSE;
$DEBUG        = FALSE;

$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];
$HTTP_REFERER   = $_SERVER["HTTP_REFERER"];

$CURRENT_DIRECTORY   = getcwd();
$THIS_FILE_DIRECTORY = dirname(__FILE__);

$PUSHY_SITE=FALSE;

$DEFAULT_INTERVAL=0;           // "seconds"      between "Restart" for DOMAIN/IPAddress  - if interval is NOT Specified
$DEFAULT_WIGGLE_INTERVAL=10;   // "seconds"      between Wiggles;


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

list($_uri_,$_qs_) = explode("?",$REQUEST_URI);
if (!isset($_qs_)) $_qs_='';

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

list($_fn_,$_ext_) = explode(".",$file);
if (!isset($_ext_)) $_ext_='';

if ($_ext_ != "js")
  {
    notFound(__LINE__, "Extension invalid: '$_ext_'");
    exit;
  }


//------------------------------ PREVIEW -------------------------
if (strlen($_fn_)>3 && substr($_fn_,0,2)=="p_") // preview
  {
    include("control_preview.php");
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


// printf("URI=%s\n",$_uri_);
// printf("TOKENS=%d\n",count($_uri_tokens));
// printf("FILE=%s\n",$file);
// printf("FN=%s\n",$_fn_);
// printf("EXT=%s\n",$_ext_);
// exit;

$db=getPushyDatabaseConnection();
$widget = getWidget($db, $_fn_); // returns FALSE if hash not found || User Not Enabled || Widget Not Enabled
if (!is_array($widget))
  {
    notFound(__LINE__, "Widget Not Found: Hash='$_fn_'");
    exit;
  }


$member_id   = $widget["member_id"];
$WidgetOwner = $widget["member_id"];

if (startsWith($referer_domain,"pushyads.")   ||
    $member_id == $PUSHY_ROOT)
  {
    $PUSHY_SITE=TRUE;
  }

$WidgetName        = $widget["widget_name"];
$WidgetKey         = $widget["widget_key"];
$WidgetDomain      = $widget["domain"];
$WidgetId          = $widget["widget_id"];
$WidgetCategories  = $widget["widget_categories"];

$WidgetWidth       = $widget["width"];
$WidgetStyle       = $widget["style"];
$WidgetPosture     = $widget["posture"];
$WidgetMotion      = $widget["motion"];
$WidgetTransition  = $widget["transition"];
$WidgetOrigin      = $widget["origin"];
$WidgetSpeed       = $widget["speed"];
$WidgetWiggle      = $widget["wiggle"];
$WidgetDelay       = $widget["delay"];
$WidgetPause       = $widget["pause"];
$WidgetFirstAccess = $widget["date_first_access"];
$WidgetLastAccess  = $widget["date_last_access"];
$WidgetWeeklyAccessCount = $widget["weekly_access_count"];
$WidgetTotalAccessCount  = $widget["total_access_count"];

if ($DEBUG)
  {
    $fp=fopen("headers.txt","a");
    fputs($fp,sprintf("-------DEBUG-------\n"));
    fputs($fp,sprintf(":WidgetKey: %s\n",$WidgetKey));
    fputs($fp,sprintf(":WidgetDomain: %s\n",$WidgetDomain));
    fclose($fp);
  }


$member_disabled=1;
$affiliate_website='';
if (is_array($memberRecord=getMemberInfo($db,$member_id)))
  {
    $member_disabled   = $memberRecord["member_disabled"];
    $affiliate_id      = $memberRecord["affiliate_id"];
    $affiliate_website = DOMAIN."/".$affiliate_id;
  }
if ($member_disabled || strlen($affiliate_website)==0)
  {
    notSupported(__LINE__, "REMOTE_ADDR Missing/Invalid: '$REMOTE_ADDR'");
    notSupported(__LINE__, "Member Disabled or Not Found: '$member_id'");
    exit;
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


$_qs_tokens_ = explode("&",$_qs_);
$_qs_array_  = array();
for ($i=0; $i<count($_qs_tokens_); $i++)
  {
    if (strlen(trim($_qs_tokens_[$i]))>0)
      {
        $temp=trim($_qs_tokens_[$i]);
        list($_k_,$_v_) = explode("=",$temp);
        if (!isset($_v_)) $_v_='';
        $_qs_array_[$_k_]=$_v_;
      }
  }


$today=getDateToday();
$owner=FALSE;

// ---- Not sure this is needed anymore --- "EXPLICITLY adding interval=0" overrides DEFAULT_INTERVAL and  says always show motion --- (for Owner Testing)
// if (isset($_qs_array_["member"]) && ($_qs_array_["member"]==$member_id))
//   $owner=TRUE;

$interval=$DEFAULT_INTERVAL;
if (isset($_qs_array_["interval"]))
   $interval=(int)$_qs_array_["interval"];

//--------- Should be No Failures After this Point - as we are now Tracking Impressions ---
$response = trackWidget($db, $widget, $REMOTE_ADDR);
if (is_array($response))
  {
    $revisit     = $response["revisit"];
    $revisit     = FALSE;
    $last_visit  = $response["last_visit"];
        // impressions should be used as a relative number because visitor records are removed - e.g. (impressions % 5 == 0) - evry 5th impression
    $impressions = $response["impressions"];

    if (!$owner)
      {
        $tm=time();
        if (($revisit) && ($tm - $last_visit) < $interval)
          {
            $WidgetMotion     = $WIDGET_MOTION_NONE;
            $WidgetTransition = $WIDGET_TRANSITION_NONE;

            //------------------  These are Alternatives to the sheer Clock Based Motion Modification Above
            // if ($impressions != 1))
            //   {  // Turn off Motion on All But Every First impression
            //     $WidgetMotion = $WIDGET_MOTION_NONE;
            //     $WidgetTransition = $WIDGET_TRANSITION_NONE;
            //   }
            // if (($impressions % 5) != 0)
            //   {  // Turn off Motion on All But Every 5th impression
            //     $WidgetMotion = $WIDGET_MOTION_NONE;
            //     $WidgetTransition = $WIDGET_TRANSITION_NONE;
            //   }
            //------------------
          }
      }
  }


//------- Update Widget Access Stats --- Credit Report ----
$sql  = "UPDATE widget set ";
if ($WidgetFirstAccess=="")
  $sql .= " date_first_access='$today',";
$sql .= " date_last_access='$today',";
$sql .= " weekly_access_count=weekly_access_count+1,";
$sql .= " total_access_count=total_access_count+1";
$sql .= " WHERE widget_key='$WidgetKey'";
$sql .= " AND   member_id ='$member_id'";
mysql_query($sql,$db);
//------- Update Widget Access Stats --- Credit Report ----



 //   $WidgetPosture = $WIDGET_POSTURE_HOVER;
 //   $WidgetMotion  = $WIDGET_MOTION_NONE;

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

//---------------------------------------------
// For now, we will use the assumption that all
// Pushy Widgets hosted On the pushyads.xxx domain
// or for which Pushy is the Owner
// will not show the GET PUSHY Link
//---------------------------------------------
$GET_PUSHY_LINK=TRUE;
if ($PUSHY_SITE)
 {
   $GET_PUSHY_LINK=FALSE;
 }

$nostart=FALSE;
if (isset($_qs_array_["nostart"]))
  $nostart=TRUE;

// --- Property Overrides
if (isset($_qs_array_["delay"]))
  $WidgetDelay=(int)$_qs_array_["delay"];   // Wait this many seconds before starting Pushy (All Postures,Motions)

if (isset($_qs_array_["pause"]))
  $WidgetPause=(int)$_qs_array_["pause"];   // Wait this many seconds in the starting position before beginning animation (Motion, Transition)

$wiggle_interval=0;
if ($WidgetWiggle>0)                   // Wiggle Turned On ?
  {
    $wiggle_interval=$DEFAULT_WIGGLE_INTERVAL;               // Time in seconds between wiggles - 0=No Wiggle
    if (isset($_qs_array_["wiggle_interval"]))
      $wiggle_interval=(int)$_qs_array_["wiggle_interval"];  // Time between wiggles - 0=No Wiggle
  }


//------------------------------------- TRACKING HERE -----
$tracking_id="";
if (isset($_qs_array_["tracker"]))
  {
    $tracking_id=strtolower($_qs_array_["tracker"]);
  }

tracker_hit($db, TRACKER_PUSHY_WIDGET, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '');

//------------------------------------- TRACKING HERE -----


$affiliate_website = PUSHYWIDGETS."/pushy/".buildWidgetKey($WidgetKey, $tracking_id);


ob_start();
  $DISPLAY_URL = PUSHYWIDGETS."/scroll/$WidgetKey.php";
  if (strlen($_qs_)>0)
    $DISPLAY_URL .= "?".$_qs_;
  if ($TEST_MODE)
    {
      include("_pushy_config-min.js");
      include("control.js");
    }
  else
    {
      include("_pushy_config-min.js");
      include("control-min.js");
    }
  $contents = ob_get_contents();
ob_end_clean();

$contentLength=strlen($contents);


if ($DUMP_HEADERS)
  {
    $fp=fopen("headers.txt","a");
    fputs($fp,"--------------------------------------------------------\n");
    fputs($fp,"HTTP/1.1 200 OK\n");
    fputs($fp,"Accept-Ranges: bytes\n");
    fputs($fp,"Content-Type: application/x-javascript\n\n");
//  fputs($fp,$contents);
    fclose($fp);
  }


header("HTTP/1.1 200 OK");
header("Accept-Ranges: bytes");
// header("Content-Length: $contentLength");
header("Content-Type: application/x-javascript");

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
