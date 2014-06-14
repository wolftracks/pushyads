<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");
include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_tracker.inc");


$mid=$_REQUEST["mid"];
$sid=$_REQUEST["sid"];

$db = getPushyDatabaseConnection();


$DEBUG_AD_SELECTION=FALSE;

$DEBUG_COOKIES_FILE_LOG=FALSE;


$AD_COUNT=10;


if ($DEBUG_AD_SELECTION)
  {
    echo "<PRE>\n";
  }

if ($DEBUG_COOKIES_FILE_LOG)
  {
    $fp=fopen("elite_bar_trace.txt","a");
    fputs($fp,sprintf("\n=====  %s  ===============================================\n",getDateTime()));
  }


function selectUplineEliteAds($db,$num,$upline)
 {
   global $DEBUG_AD_SELECTION;
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_ELITE;

   if ($DEBUG_AD_SELECTION)
     {
       printf("*-*-*-* selectUplineEliteAds  Num=%d  upline=%d *-*-*-*\n",$num,count($upline));
       var_dump($upline);
     }

   $excludeProductIds   = array();
   $excludeProductNames = array();

   $products=array();

   if (is_array($upline) && count($upline)>0)
     {
       // OK
     }
   else
     {
        if ($DEBUG_AD_SELECTION)
          {
             printf("*-*-*-* selectUplineEliteAds  No Products Selected based on Upline because Upline List is Empty *-*-*-*\n");
          }
        return $products;
     }

   $mIndex=array();

   $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
   $sql .= " WHERE member.user_level='".$PUSHY_LEVEL_ELITE."' ";
   $sql .= " AND   member.member_disabled=0 ";
   $sql .= " AND   ads.elite_bar>0";

   $sql .= " AND (";
   for ($j=0; $j<count($upline); $j++)
     {
       $sql .= " member.member_id='".$upline[$j]."'";
       if ($j+1 < count($upline))
          $sql .= " OR ";
     }
   $sql .= ")";

   $sql .= " ORDER BY ads.lastview_elitebar, ads.ad_id LIMIT $num";

   $result = mysql_query($sql,$db);

   if ($DEBUG_AD_SELECTION)
     {
       printf("SQL:%s\n",$sql);
       printf("ERR:%s\n",mysql_error());
       printf("ROWS:%s\n",mysql_num_rows($result));
     }


   if ($result && (($count=mysql_num_rows($result))>0))
     {
       if ($count<$num)
          $num=$count;

       while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
         {
           $product_id   = $myrow["product_id"];
           $product_name = strtolower(stripslashes($myrow["product_name"]));
           if (isset($excludeProductIds[$product_id])   ||
               isset($excludeProductNames[$product_name]))
             { }
           else
             {
               $excludeProductIds[$product_id]=TRUE;
               $excludeProductNames[$product_name]=TRUE;
               $products[]=$myrow;
             }
         }

       if (count($products) > 0)
         {
           $count=count($products);
           $viewed = getmicroseconds();
           $sql    = "UPDATE ads set lastview_elitebar='$viewed' WHERE (";
           for ($i=0; $i<$count; $i++)
             {
               $myrow=$products[$i];
               if ($i>0)
                 $sql .= " OR ";
               $sql .= " ad_id='".$myrow["ad_id"]."' ";
             }
           $sql .= " )";
           $result = mysql_query($sql,$db);
         }
     }

   // print_r($products);
   return $products;
 }



function selectElitePoolAds($db,$member_id,$num,$upline,$excludeProductIds,$excludeProductNames)
 {
   global $DEBUG_AD_SELECTION;
   global $PUSHY_ROOT;
   global $PUSHY_LEVEL_ELITE;

   $products=array();
   $mIndex=array();

   if ($DEBUG_AD_SELECTION)
     {
       printf("*-*-*-* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! selectElitePoolAds  Num=%d  upline=%d  productsSelected=%d *-*-*-*<br>\n",$num,count($upline),count($excludeProductIds),count($excludeProductNames));
       var_dump($upline);
       var_dump($excludeProductIds);
       var_dump($excludeProductNames);
       printf("*-*-*-* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>\n");
     }

   $sql  = "SELECT * from ads LEFT JOIN member USING(member_id) LEFT JOIN product USING(product_id) ";
   $sql .= " WHERE member.user_level='".$PUSHY_LEVEL_ELITE."' ";
   $sql .= " AND   ads.member_id!='".$member_id."' ";        // Never Display your own ad
   $sql .= " AND   member.member_disabled=0 ";
   $sql .= " AND   ads.elite_pool>0";

   if (count($upline) > 0)
     {
       $sql .= " AND (";
       for ($j=0; $j<count($upline); $j++)
         {
           $sql .= " member.member_id!='".$upline[$j]."'";
           if ($j+1 < count($upline))
              $sql .= " AND ";
         }
       $sql .= ")";
     }

   if (count($excludeProductIds) > 0)
     {
       $first=TRUE;
       $sql .= " AND ( ";
       foreach($excludeProductIds AS $product_id => $bool)
         {
           if ($first)
             $first=FALSE;
           else
             $sql .= " AND ";
           $sql .= " product.product_id != '$product_id' ";
         }
       $sql .= " ) ";
     }

   if (count($excludeProductNames) > 0)
     {
       $first=TRUE;
       $sql .= " AND ( ";
       foreach($excludeProductNames AS $product_name => $bool)
         {
           if ($first)
             $first=FALSE;
           else
             $sql .= " AND ";
           $sql .= " product.product_name != '$product_name' ";
         }
       $sql .= " ) ";
     }

   //---- NEXT Line is for ROTATION over RANDOM
   $sql .= " ORDER BY ads.lastview_elitebar, ads.ad_id LIMIT $num";

   $result = mysql_query($sql,$db);

   if ($DEBUG_AD_SELECTION)
     {
       printf("SQL:%s\n",$sql);
       printf("ERR:%s\n",mysql_error());
       printf("ROWS:%s\n",mysql_num_rows($result));
     }

   if ($result && (($count=mysql_num_rows($result))>0))
     {
       if ($count<$num)
          $num=$count;

       while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
         {
           $product_id   = $myrow["product_id"];
           $product_name = strtolower(stripslashes($myrow["product_name"]));
           if (isset($excludeProductIds[$product_id])   ||
               isset($excludeProductNames[$product_name]))
             { }
           else
             {
               $excludeProductIds[$product_id]=TRUE;
               $excludeProductNames[$product_name]=TRUE;
               $products[]=$myrow;
             }
         }

       if (count($products) > 0)
         {
           $count=count($products);
           $viewed = getmicroseconds();
           $sql    = "UPDATE ads set lastview_elitebar='$viewed' WHERE (";
           for ($i=0; $i<$count; $i++)
             {
               $myrow=$products[$i];
               if ($i>0)
                 $sql .= " OR ";
               $sql .= " ad_id='".$myrow["ad_id"]."' ";
             }
           $sql .= " )";
           $result = mysql_query($sql,$db);
         }
     }

   // print_r($products);
   return $products;
 }


function getExistingCookieData($elite_bar_cookie_id)
 {
   // --- To DISABLE The Cookie Effect On the Elite Bar TEMPORARILY - Comment Out the Next 2 Lines

      if (isset($_COOKIE[$elite_bar_cookie_id]))
         return $_COOKIE[$elite_bar_cookie_id];


   return "";
 }


//--- Get List of Elite Bar Ads seen - 24 hours
$elite_bar_cookie_data=array();
$existing_cookie_data="";
$elite_bar_cookie_id=PUSHY_ELITE_ADS_VIEWED;

$existing_cookie_data = getExistingCookieData($elite_bar_cookie_id);

if (strlen($existing_cookie_data)>0)
  {
    $tarray=explode("~",$existing_cookie_data);
    for ($i=0; $i<count($tarray); $i++)
      {
        $elite_bar_ad_id = stripa($tarray[$i]);
        if (strlen($elite_bar_ad_id)>0)
          $elite_bar_cookie_data[$elite_bar_ad_id]=TRUE;
      }
  }

$existing_cookie_count=count($elite_bar_cookie_data);


$options = array(
   "minlevel"  =>  $PUSHY_LEVEL_ELITE,
   "idsonly"   =>  TRUE,
   "limit"     =>  10,
   "order"     =>  "Desc"
);


$upline = tree_getUpline($db, $mid, $options);

// printf("----- (1) ---------------------------\n");
// print_r($upline);
if (is_array($upline))
  {
    for ($i=0; $i<count($upline); $i++)
      {
        // printf("----- AAA --- (%s) (%s)\n",$upline[$i],$PUSHY_ROOT);
        if ($upline[$i]==$PUSHY_ROOT)
          {
             // printf("----- BBB ---\n");
             unset($upline[$i]);
          }
        // printf("----- CCC ---\n");
      }
  }
// printf("----- (2) ---------------------------\n");
// print_r($upline);


$uplineAds = selectUplineEliteAds($db,$AD_COUNT,$upline);
$uplineAdsSelected = count($uplineAds);
$adsNeeded = $AD_COUNT - $uplineAdsSelected;
$elitePoolAdsSelected=0;

if ($DEBUG_AD_SELECTION)
  {
     printf("uplineAds Selected=%d\n",$uplineAdsSelected);
     printf("adsNeeded=%d\n",$adsNeeded);
     printf("elitePoolAds Selected=%d\n",$elitePoolAdsSelected);
     printf("PRODUCTS=%d\n\n",count($products));
  }


$products=array();
if ($adsNeeded > 0)
  {
    $product_ids_Selected   = array();
    $product_names_Selected = array();
    for ($j=0; $j<count($uplineAds); $j++)
      {
        $myrow        = $uplineAds[$j];
        $product_id   = $myrow["product_id"];
        $product_name = stripslashes($myrow["product_name"]);
        $product_ids_Selected[$product_id]     = TRUE;
        $product_names_Selected[$product_name] = TRUE;
      }

    $elitePoolAds = selectElitePoolAds($db,$mid,$adsNeeded,$upline,$product_ids_Selected,$product_names_Selected);
    $elitePoolAdsSelected = count($elitePoolAds);

    $m=0;
    for ($j=0; $j<count($uplineAds); $j++)
      {
        $products[$m] = $uplineAds[$j];
        $m++;
      }

    for ($j=0; $j<count($elitePoolAds); $j++)
      {
        $products[$m] = $elitePoolAds[$j];
        $m++;
      }
  }
else
  {
    $m=0;
    for ($j=0; $j<count($uplineAds); $j++)
      {
        $products[$m] = $uplineAds[$j];
        $m++;
      }
  }


if ($DEBUG_AD_SELECTION)
  {
     printf("uplineAds Selected=%d\n",$uplineAdsSelected);
     printf("adsNeeded=%d\n",$adsNeeded);
     printf("elitePoolAds Selected=%d\n",$elitePoolAdsSelected);
     printf("PRODUCTS=%d\n\n",count($products));

     for ($z=0; $z<count($products); $z++)
       {
         $myrow         = $products[$z];
         $ad_id         = $myrow["ad_id"];
         $product_id    = $myrow["product_id"];
         $product_name  = $myrow["product_name"];
         $product_title = $myrow["product_title"];
         $ad_owner      = $myrow["member_id"];
         $owner_name    = $myrow["firstname"]." ".$myrow["lastname"];

         printf("%d)  AD=%-5s  PRODUCT=%-5s  Name=%-16s Title=%-20s  AdOwner=%-12s %s\n",
                      $z,
                      $ad_id,
                      $product_id,
                      $product_name,
                      $product_title,
                      $ad_owner,
                      $owner_name);
       }
  }



for ($z=0; $z<count($products); $z++)
  {
    $myrow         = $products[$z];
    $ad_id         = $myrow["ad_id"];
    $product_id    = $myrow["product_id"];
    $product_name  = $myrow["product_name"];
    $product_title = $myrow["product_title"];
    $ad_owner      = $myrow["member_id"];

    //------------------ Hit Counter ---------------------
    if (!isset($elite_bar_cookie_data[$ad_id]))
      {
        // user is seeing this elite bar ad for the first time in the last 24 hours

        tracker_hit($db, TRACKER_ELITE_BAR, $ad_owner, '', $ad_id);
        if ($DEBUG_AD_SELECTION)
          {
            echo " -------------- ELITE-BAR TRACKER:  HIT: $ad_id --------------------\n";
          }
        if ($DEBUG_COOKIES_FILE_LOG)
          {
            fputs($fp,"-------------- ELITE-BAR TRACKER:  HIT: $ad_id --------------------\n");
          }
      }
    $elite_bar_cookie_data[$ad_id]=TRUE; // may already be there - thats OK - accumulative "new picture"
    //------------------ Hit Counter ---------------------
  }


ksort($elite_bar_cookie_data);

$new_cookie_data="";
$new_cookie_count=count($elite_bar_cookie_data);
foreach($elite_bar_cookie_data AS $ad_id=>$bool)
 {
   if ($new_cookie_data=="")
     $new_cookie_data  = $ad_id;
   else
     $new_cookie_data .= "~".$ad_id;
 }

if ($DEBUG_AD_SELECTION)
  {
    printf("EXISTING_COOKIE_DATA='%s'\n",$existing_cookie_data);
    printf("ELITE_BAR_COOKIE_DATA='%s'\n",print_r($elite_bar_cookie_data,TRUE));
    printf("ELITE_BAR_COOKIE_DATA Count='%d'\n",$existing_cookie_count);
    printf("NEW_ELITE_BAR_COOKIE_DATA='%s'\n\n",$new_cookie_data);

    printf("COUNT(PRODUCTS) = %d\n",count($products));
  }


if ($DEBUG_COOKIES_FILE_LOG)
  {
    fputs($fp,sprintf("EXISTING_COOKIE_DATA='%s'\n",$existing_cookie_data));
    fputs($fp,sprintf("ELITE_BAR_COOKIE_DATA='%s'\n",print_r($elite_bar_cookie_data,TRUE)));
    fputs($fp,sprintf("ELITE_BAR_COOKIE_DATA Count='%d'\n",$existing_cookie_count));
    fputs($fp,sprintf("NEW_ELITE_BAR_COOKIE_DATA='%s'\n\n",$new_cookie_data));

    fputs($fp,sprintf("COUNT(PRODUCTS) = %d\n",count($products)));
    fclose($fp);
  }

if ($DEBUG_AD_SELECTION)
  {
    echo "</PRE>\n";
    exit;
  }


$tm=time();

// $expires=timeAtMidnightTomorrow($tm);
$expires=$tm+86400; // Full 24 hours;
// $expires=$tm+60; // one minute
setcookie($elite_bar_cookie_id,$new_cookie_data,$expires,"/","",0);

?>
<!-- !DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript" src="/local-js/tooltips.js"></script>
</head>
<body style="margins:0px; margin:0px; padding:0px; top:0; left:0">

<div id="ToolTip"></div>

<script type="text/javascript">
var slmg=new Array();
var slinx=0;
var elitebar_sumsize=0;
var elitebar_msgsize=0;

var mspeed=1;
var remspeed = mspeed;

function ad_mouseover(obj,title,content,id,inx)
  {
    var el = document.getElementById(id);
    if (el)
      {
        var x=findPosX(el);
        var y=findPosY(el);
        var w=el.offsetWidth;
        var h=el.offsetHeight;

        // alert("ID="+id+"  X="+x+"  Y="+y+"  W="+w+"  H="+h+"  INX="+inx);
        // return;

        // mspeed=0;

        if (y <= -elitebar_msgsize)
          y+=elitebar_sumsize;

        // var msg="ID="+id+"  X="+x+"  Y="+y+"  W="+w+"  H="+h+"  INX="+inx;
        // parent.show_ad_tooltip(obj,title,encodeURIComponent(msg),id,x,y,w,h,inx);
        parent.show_ad_tooltip(obj,title,content,id,x,y,w,h,inx);
      }
  }


function ad_mouseout(obj,id)
 {
   // mspeed=remspeed;
   parent.hide_ad_tooltip(obj,id);
 }


function display_elite(ad_id)
  {
    var w=650;
    var h=600;
    var tracker="/members/ads/"+ad_id;
    var args='width='+w+',height='+h+',top=0,left=0'+
             ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
    var win=window.open(tracker,"PushyAds",args);
    if (win) win.focus();
  }


<?php

$TARGET_WIDTH    = 120;
$TARGET_HEIGHT   = 165;
$SCROLLER_WIDTH  = 120;
$SCROLLER_HEIGHT = 1180;  // 1180 / 7 ads  =  168.57 pixels of vertical height per Ad


for ($z=0; $z<count($products); $z++)
// for ($z=0; $z<5; $z++)
  {
    $myrow  = $products[$z];

    $ad_id                 = $myrow["ad_id"];
    $ad_owner              = $myrow["member_id"];

    $product_id            = $myrow["product_id"];
    $product_url           = $myrow["product_url"];
    $affiliate_signup_url  = $myrow["affiliate_signup_url"];
    $pushy_network         = $myrow["pushy_network"];
    $elite_bar             = $myrow["elite_bar"];
    $elite_pool            = $myrow["elite_pool"];
    $product_list          = $myrow["product_list"];
    $date_created          = $myrow["date_created"];
    $last_modified         = $myrow["last_modified"];
    $reseller_listing      = $myrow["reseller_listing"];

    //---- PRODUCT
    $product_owner         = $myrow["product_owner"];
    $product_submit_date   = $myrow["product_submit_date"];
    $product_name          = stripslashes($myrow["product_name"]);

    $product_title         = stripslashes($myrow["product_title"]);
    $product_title         = urlencode($myrow["product_title"]);

    $product_description   = stripslashes($myrow["product_description"]);
    $product_description   = urlencode($product_description);

    $product_categories    = $myrow["product_categories"];

    $media_type            = $myrow["media_type"];
    $media_format          = $myrow["media_format"];
    $media_width           = $myrow["media_width"];
    $media_height          = $myrow["media_height"];
    $media_size            = $myrow["media_size"];
    $media_original_width  = $myrow["media_original_width"];
    $media_original_height = $myrow["media_original_height"];
    $media_server          = $myrow["media_server"];

    $image_url             = _get_MediaURL($product_id,$media_server,$media_format);

    list($scaled, $new_width, $new_height) = _scaled_ImageSize($media_original_width, $media_original_height, $TARGET_WIDTH, $TARGET_HEIGHT);
    $dim = "height=$new_height width=$new_width";
?>

    slmg[slinx]  = "<table align=center height=<?php echo $new_height?> width=<?php echo $TARGET_WIDTH?> border=0 cellspacing=0 cellpadding=0>\n";
    slmg[slinx] += "<tr valign=middle height=<?php echo $TARGET_HEIGHT+2?>>\n";
    slmg[slinx] += "<td align=center valign=middle>";
    slmg[slinx] += "<a onMouseover=javascript:ad_mouseover(this,'<?php echo $product_title?>','<?php echo $product_description?>','eb_<?php echo $z?>',<?php echo $z?>) ";
    slmg[slinx] += " onMouseout=javascript:ad_mouseout(this,'eb_<?php echo $z?>') href=javascript:display_elite('<?php echo $ad_id?>')> ";
    slmg[slinx] += " <img id='eb_<?php echo $z?>' src='<?php echo $image_url?>' <?php echo $dim?> border=0></a></td>\n";
    slmg[slinx] += "</tr>\n";
    slmg[slinx] += "</table>\n";

    slinx++;

<?php
  }
?>


// alert("SLMG COUNT="+slmg.length);

var scroller_enabled=true;
if (slmg.length < 8)
  {
    scroller_enabled=false;
  }


// MULTIPLE MESSAGE Handling
// More than one message may reside within scrolling area
// while pausing: 0 (not desired) or 1 (desired);
// Should one choose this option - value 1, automatically
// a blank space is inserted after each and every message;
// retclass would set the vertical size for this blank space;
var msgnr=1;
if(msgnr==1)
  {
    var retclass='"stileret"';
  }


var ad_separator = '<span style=\"height:2px; line-height:2px\">&nbsp;</span>';
// var ad_separator = '<hr width="100%" border=0>';


//PAUSE between messages in milliseconds: 1000=1s; set to your own;
var mpause = 0;


//CELL-CENTERED if single message in the area chosen above;
//0: (not desired) or 1 (desired):
var celcen=1;


//INITIAL POSITION Option
//1: First message shows up right at top edge (or cell-centered) and pauses;
//0: First message shows up at bottom edge and starts scrolling
//then pauses at the top edge (or cell-centered);
var udopt=1;


//WIDTH of the Scroller in pixels: set to your own;
//"px" unit will automatically be added in the process;
var mwidth=<?php echo $SCROLLER_WIDTH?>;

//HEIGHT of the Scroller in pixels: set to your own;
//"px" unit will automatically be added in the process;
//larger Messages (exceeding height) will slide OK anyway, but
//the exceeding height won't show up on option Recall when called up!


var mheight=<?php echo $SCROLLER_HEIGHT?>;
if (slmg.length < 7)
  mheight=parseInt( <?php echo $SCROLLER_HEIGHT?> / 7  *  slmg.length);


//BACKGROUND: either color(1) or image(2) ;
//1.Background color: could be like: "#ffff00" or "yellow";
//set it "" for no background color;
// var mcolor="aqua";
var mcolor="#ffffff";
//or 2.Background image: "imagename.ext";
//leave it "" for no image background;
var mbground="";


//BORDER for scrolling area: 1, ... ;
//set it 0(zero) for no border;
var mborder=0;

//BORDER COLOR
var mbcolor="#000000";
var mbcolorlight="#E0E0E0";
var mbcolordark ="#404040";

//PADDING - Padding between Border and Text - This is ususally Non-Zero Only if mborder is NonZero
var mpadding=0;

//SPEED in pixels: the higher the faster - set your own!

//LIVE SPEED-CHANGE OPTION: 0 (not desired) or 1 (desired);
//vesclass would be the STYLE
//celcolor would be background color
var lsopt = 0;
if(lsopt==1)
  {
    var vesclass='class="stileupdn"';
    var celcolor='#ccffcc';
  }


//RECALL OPTION:
//set it 0 if not desired and the scroll will go on;
//set it 1 if desired: after a complete cycle the scroll stops
//and you may recall randomly any Message/Image;
var rcopt=0;
if(rcopt==1)
  {
    var cbtxt = new Array();
    //MUST BE as many TEXTs as the number of Messages/Images;
    //Set your own TEXTs, like Messages Title or what you have in there:
    //cbtxt = ["1-TITLE", "2-Features", "3-Recall Option", "4-Other Possibilities", "5-Check"];
    cbtxt=[];
  }
//end Parameters


//---------------------------------------------------------------------------------------------------------------

var ikj = 0;
var jkk = 1;
var resumemspeed = mspeed;
var sumsize = 0;
var divmsg = 0;
var msgsize = new Array();
var wmsgs = '';
var wmsg = '';
sizeup = 0;

if (rcopt == 1)
 {
   if (cbtxt.length == 0 || slmg.length == 0 || cbtxt.length != slmg.length) rcopt = 0;
 }

if (rcopt == 1)
 {
   var varoptm = "";
   for (jkj = 0; jkj < cbtxt.length - 1; jkj++)
      varoptm = varoptm + '<option>' + cbtxt[jkj] + '</option>';
   varoptm = varoptm + '<option selected>' + cbtxt[cbtxt.length - 1] + '</option>';
 }

function speedup()
 {
   if (mspeed == 0)
      mspeed = remspeed;
   else
   if (mspeed < 9)
     {
       mspeed *= 2;
       remspeed = mspeed;
     }
 }

function slowst()
 {
   mspeed = 0;
 }

function slowdn()
 {
   if (mspeed == 0)
     mspeed = resumemspeed;
   else
   if (mspeed > resumemspeed)
     {
       mspeed /= 2;
       remspeed = mspeed;
     }
 }

function iescroll1()
 {
   if (iedv0.style.pixelTop + sizeup <= mspeed)
     {
       if (ikj == slmg.length - 1 && rcopt == 1)
         {
           iedv.style.visibility = "hidden";
           iedv0.style.pixelTop = 0;
           iedv0.innerHTML = slmg[ikj];
           callbackm.style.visibility = "visible";
           return;
         }
       else
       if (sizeup == sumsize)
          sizeup = 0;

       iedv0.style.pixelTop = -sizeup;
       sizeup += msgsize[ikj];
       if (ikj == slmg.length - 1)
          ikj = 0;
       else
          ikj++;
       _set_time_out("iescroll1()", mpause);
       return;
     }
   else
     {
       iedv0.style.pixelTop -= mspeed;
       _set_time_out("iescroll1()", 20);
     }
 }

function iescroll()
 {
   if (jkk == 0 && iedv.style.pixelTop <= mspeed || jkk == 1 && iedv0.style.pixelTop <= mspeed)
     {
       if (ikj == slmg.length - 1 && rcopt == 1)
         {
           if (jkk == 0)
             {
               iedv0.style.visibility = "hidden";
               iedv.style.pixelTop = 0;
             }
           else
             {
               iedv.style.visibility = "hidden";
               iedv0.style.pixelTop = 0;
             }
           callbackm.style.visibility = "visible";
           return;
         }
       else
       if (ikj == slmg.length - 1 && rcopt == 0)
          ikj = 0;
       else
       if (ikj < slmg.length - 1)
          ikj++;

       if (jkk == 0)
         jkk = 1;
       else
         jkk = 0;

       if (jkk == 0)
         {
           iedv0.style.pixelTop = 0;
           sizeup = iedv0.offsetHeight - mheight;
           if (sizeup > 5 && mheight > 20) sizeup += 10;
           iedv.style.pixelTop = mheight;
           iedv.innerHTML = slmg[ikj];
         }
       else
         {
           iedv.style.pixelTop = 0;
           sizeup = iedv.offsetHeight - mheight;
           if (sizeup > 5 && mheight > 20) sizeup += 10;
           iedv0.style.pixelTop = mheight;
           iedv0.innerHTML = slmg[ikj];
         }
       _set_time_out("iescroll()", mpause);
       return;
     }
   else
   if (sizeup > 0)
     {
       if (jkk == 0)
         {
           iedv0.style.pixelTop -= mspeed;
           sizeup -= mspeed;
           _set_time_out("iescroll()", 20);
         }
       else
         {
           iedv.style.pixelTop -= mspeed;
           sizeup -= mspeed;
           _set_time_out("iescroll()", 20);
         }
     }
   else
     {
       iedv0.style.pixelTop -= mspeed;
       iedv.style.pixelTop -= mspeed;
       _set_time_out("iescroll()", 20);
     }
 }

function ns4scroll1()
 {
    if (ns4lr0.top + sizeup <= mspeed)
      {
        if (ikj == slmg.length - 1 && rcopt == 1)
          {
            ns4lr.visibility = "hide";
            ns4lr0.top = 0;
            ns4lr0.document.write(slmg[ikj]);
            ns4lr0.document.close();
            document.callbm.document.cbackm.visibility = "show";
            return;
          }
        else
        if (sizeup == sumsize)
          sizeup = 0;

        ns4lr0.top = -sizeup;
        sizeup += msgsize[ikj];
        if (ikj == slmg.length - 1)
          ikj = 0;
        else
          ikj++;
        _set_time_out("ns4scroll1()", mpause);
        return;
      }
    else
      {
        ns4lr0.top -= mspeed;
        _set_time_out("ns4scroll1()", 20);
      }
 }

function ns4scroll()
 {
   if (jkk == 0 && ns4lr.top <= mspeed || jkk == 1 && ns4lr0.top <= mspeed)
     {
       if (ikj == slmg.length - 1 && rcopt == 1)
         {
           if (jkk == 0)
             {
               ns4lr0.visibility = "hide";
               ns4lr.top = 0;
             }
           else
             {
               ns4lr.visibility = "hide";
               ns4lr0.top = 0;
             }
           document.callbm.document.cbackm.visibility = "show";
           return;
         }
       else
       if (ikj == slmg.length - 1 && rcopt == 0)
          ikj = 0;
       else if (ikj < slmg.length - 1)
           ikj++;

       if (jkk == 0)
          jkk = 1;
       else
           jkk = 0;

       if (jkk == 0)
         {
           ns4lr0.top = 0;
           sizeup = ns4lr0.document.height - mheight;
           if (sizeup > 5 && mheight > 20) sizeup += 10;
           ns4lr.top = mheight;
           ns4lr.document.write(slmg[ikj]);
           ns4lr.document.close();
         }
       else
         {
           ns4lr.top = 0;
           sizeup = ns4lr.document.height - mheight;
           if (sizeup > 5 && mheight > 20) sizeup += 10;
           ns4lr0.top = mheight;
           ns4lr0.document.write(slmg[ikj]);
           ns4lr0.document.close();
         }
       _set_time_out("ns4scroll()", mpause);
       return;
     }
   else
   if (sizeup > 0)
     {
       if (jkk == 0)
         {
           ns4lr0.top -= mspeed;
           sizeup -= mspeed;
           _set_time_out("ns4scroll()", 20);
         }
       else
         {
           ns4lr.top -= mspeed;
           sizeup -= mspeed;
           _set_time_out("ns4scroll()", 20);
         }
     }
   else
     {
       ns4lr0.top -= mspeed;
       ns4lr.top -= mspeed;
       _set_time_out("ns4scroll()", 20);
     }
}

function domscroll1()
 {
    if (parseInt(domdv0.style.top) + sizeup <= mspeed)
      {
        if (ikj == slmg.length - 1 && rcopt == 1)
          {
            domdv.style.visibility = "hidden";
            domdv0.style.top = 0;
            domdv0.innerHTML = slmg[ikj];
            document.getElementById('callbackm').style.visibility = "visible";
            document.getElementById('callbackm').innerHTML = '<form name="boxxm" method="post">' + '<select name="listm" onFocus onChange="upcallm(this.form);">' + varoptm + '</select></form>';
            return;
          }
        else
        if (sizeup == sumsize)
          sizeup = 0;

        domdv0.style.top = -sizeup + "px";
        sizeup += msgsize[ikj];
        if (ikj == slmg.length - 1)
          ikj = 0;
        else
          ikj++;
        _set_time_out("domscroll1()", mpause);
        return;
      }
    else
      {
        domdv0.style.top = parseInt(domdv0.style.top) - mspeed + "px";
        _set_time_out("domscroll1()", 20);
      }
 }

function domscroll()
 {
   if (jkk == 0 && parseInt(domdv.style.top) <= mspeed || jkk == 1 && parseInt(domdv0.style.top) <= mspeed)
     {
       if (ikj == slmg.length - 1 && rcopt == 1)
         {
           if (jkk == 0)
             {
               domdv0.style.visibility = "hidden";
               domdv.style.top = 0;
             }
           else
             {
               domdv.style.visibility = "hidden";
               domdv0.style.top = 0;
             }
           document.getElementById('callbackm').style.visibility = "visible";
           document.getElementById('callbackm').innerHTML = '<form name="boxxm" method="post">' + '<select name="listm" onFocus onChange="upcallm(this.form);">' + varoptm + '</select></form>';
           return;
         }
       else
       if (ikj == slmg.length - 1 && rcopt == 0)
          ikj = 0;
       else
       if (ikj < slmg.length - 1)
          ikj++;

       if (jkk == 0)
          jkk = 1;
       else
          jkk = 0;

       if (jkk == 0)
         {
           domdv0.style.top = 0;
           sizeup = domdv0.offsetHeight - mheight;
           if (sizeup > 5 && mheight > 20) sizeup += 10;
           domdv.style.top = mheight + "px";
           domdv.innerHTML = slmg[ikj];
         }
       else
         {
           domdv.style.top = 0;
           sizeup = domdv.offsetHeight - mheight;
           if (sizeup > 5 && mheight > 20) sizeup += 10;
           domdv0.style.top = mheight + "px";
           domdv0.innerHTML = slmg[ikj];
         }
       _set_time_out("domscroll()", mpause);
       return;
     }
   else
   if (sizeup > 0)
     {
       if (jkk == 0)
         {
           domdv0.style.top = parseInt(domdv0.style.top) - mspeed + "px";
           sizeup -= mspeed;
           _set_time_out("domscroll()", 20);
         }
       else
         {
           domdv.style.top = parseInt(domdv.style.top) - mspeed + "px";
           sizeup -= mspeed;
           _set_time_out("domscroll()", 20);
         }
     }
   else
     {
       domdv0.style.top = parseInt(domdv0.style.top) - mspeed + "px";
       domdv.style.top = parseInt(domdv.style.top) - mspeed + "px";
       _set_time_out("domscroll()", 20);
     }
 }

function upcallm(form)
 {
   ikj = form.listm.selectedIndex;
   if (document.getElementById)
     {
       domdv0.style.visibility = "hidden";
       domdv.style.visibility = "visible";
       domdv.style.top = 0;
       domdv.innerHTML = slmg[ikj];
     }
   else
   if (document.all)
     {
       iedv0.style.visibility = "hidden";
       iedv.style.visibility = "visible";
       iedv.style.pixelTop = 0;
      iedv.innerHTML = slmg[ikj];
     }
   else
   if (document.layers)
     {
       ns4lr0.visibility = "hide";
       ns4lr.visibility = "show";
       ns4lr.top = 0;
       ns4lr.document.write(slmg[ikj]);
       ns4lr.document.close();
     }
   return;
 }

function startscroll()
 {
   if (slmg.length == 0)
     return;

   if (document.getElementById)
     {
       domdv0 = document.getElementById('scrolldiv');
       domdv = document.getElementById('scrolldivs');
       if (msgnr == 0 && celcen == 1)
         {
           for (pp = 0; pp < slmg.length; pp++)
             {
               document.getElementById('hidslider').innerHTML = slmg[pp];
               msgsize[pp] = document.getElementById('hidslider').offsetHeight;
               if (msgsize[pp] < mheight)
                 {
                   wmsg = '<table cellspacing="0" cellpadding="0" align="center"><tr><td height="' + mheight + 'px" valign="center">' + slmg[pp] + '</td></tr></table>';
                   slmg[pp] = wmsg;
                   document.getElementById('scrolldiv').innerHTML = slmg[0];
                 }
             }
         }

       if (msgnr == 1)
         {
           for (pp = 0; pp < slmg.length; pp++)
             {
               // document.getElementById('hidslider').innerHTML = slmg[pp] + '<div ' + retclass + '>&nbsp;</div>';
               document.getElementById('hidslider').innerHTML = slmg[pp] + '<div ' + retclass + '>' + ad_separator + '</div>';
               wmsg += document.getElementById('hidslider').innerHTML;
               msgsize[pp] = document.getElementById('hidslider').offsetHeight;
               sumsize += msgsize[pp];
             }
           divmsg = Math.round(mheight / sumsize);
         }

       if (udopt == 0)
         {
           domdv0.style.top = mheight + "px";
           if (msgnr == 0)
             {
               domdv0.innerHTML = slmg[0];
               domdv.style.top = mheight + "px";
             }
           else
             {
               for (pp = 0; pp <= 2 * divmsg; pp++)
                  domdv0.innerHTML += wmsg;
               if (divmsg == 0)
                  domdv0.innerHTML += wmsg;
             }
         }
       else
         {
           if (msgnr == 0)
             {
               domdv0.style.top = 0;
               domdv.style.top = 0;
             }
           else
             {
               domdv0.style.top = 0;
               for (pp = 0; pp <= 2 * divmsg; pp++)
                 domdv0.innerHTML += wmsg;
               if (divmsg == 0)
                 domdv0.innerHTML += wmsg;
             }
         }
       if (msgnr == 0)
         domscroll();
       else
         domscroll1();
     }
   else
   if (document.all)
     {
       if (msgnr == 0 && celcen == 1)
         {
           for (pp = 0; pp < slmg.length; pp++)
             {
               hidslider.innerHTML = slmg[pp];
               msgsize[pp] = hidslider.offsetHeight;
               if (msgsize[pp] < mheight)
                 {
                   wmsg = '<table cellspacing="0" cellpadding="0" align="center"><tr><td height="' + mheight + 'px" valign="center">' + slmg[pp] + '</td></tr></table>';
                   slmg[pp] = wmsg;
                   scrolldiv.innerHTML = slmg[0];
                 }
             }
         }

       if (msgnr == 1)
         {
           for (pp = 0; pp < slmg.length; pp++)
             {
               hidslider.innerHTML = slmg[pp] + '<div ' + retclass + '>&nbsp;</div>';
               wmsg += hidslider.innerHTML;
               msgsize[pp] = hidslider.offsetHeight;
               sumsize += msgsize[pp];
             }
           divmsg = Math.round(mheight / sumsize);
         }

       iedv0 = scrolldiv;
       iedv = scrolldivs;
       if (udopt == 0)
         {
           iedv0.style.pixelTop = mheight;
           if (msgnr == 0)
             {
               iedv0.innerHTML = slmg[0];
               iedv.style.pixelTop = mheight;
             }
           else
             {
               for (pp = 0; pp <= 2 * divmsg; pp++)
                  iedv0.innerHTML += wmsg;
               if (divmsg == 0)
                  iedv0.innerHTML += wmsg;
             }
         }
       else
         {
           if (msgnr == 0)
             {
               iedv0.style.pixelTop = 0;
               iedv.style.pixelTop = 0;
             }
           else
             {
               iedv0.style.pixelTop = 0;
               for (pp = 0; pp <= 2 * divmsg; pp++)
                  iedv0.innerHTML += wmsg;
               if (divmsg == 0)
                  iedv0.innerHTML += wmsg;
             }
         }

       if (msgnr == 0)
         iescroll();
       else
         iescroll1();
     }
   else
   if (document.layers)
     {
       ns4lr0 = document.ns4s.document.ns4s0;
       if (msgnr == 0 && celcen == 1)
         {
           ns4lr0.visibility = "hide";
           for (pp = 0; pp < slmg.length; pp++)
             {
               ns4lr0.document.write(slmg[pp]);
               ns4lr0.document.close();
               msgsize[pp] = ns4lr0.document.height;
               if (msgsize[pp] < mheight)
                 {
                   wmsg = '<table cellspacing="0" cellpadding="0" align="center"><tr><td height="' + mheight + 'px" valign="center">' + slmg[pp] + '</td></tr></table>';
                   slmg[pp] = wmsg;
                 }
             }
         }

       if (msgnr == 1)
         {
           ns4lr0.visibility = "hide";
           for (pp = 0; pp < slmg.length; pp++)
             {
               ns4lr0.document.write(slmg[pp] + '<div ' + retclass + '>&nbsp;</div>');
               ns4lr0.document.close();
               wmsg += slmg[pp] + '<div ' + retclass + '>&nbsp;</div>';
               msgsize[pp] = ns4lr0.document.height;
               sumsize += msgsize[pp];
             }
           divmsg = Math.round(mheight / sumsize);
         }

       ns4lr = document.ns4s.document.ns4s1;
       if (udopt == 0) ns4lr0.top = mheight;
       else if (udopt == 1) ns4lr0.top = 0;
       ns4lr0.visibility = "show";
       if (msgnr == 0)
         {
           ns4lr0.document.write(slmg[0]);
           ns4lr0.document.close();
         }
       if (msgnr == 1)
         {
           for (pp = 0; pp <= 2 * divmsg; pp++) wmsgs += wmsg;
           if (divmsg == 0) wmsgs += wmsg;
           ns4lr0.document.write(wmsgs);
           ns4lr0.document.close();
         }
       if (udopt == 0)
          ns4lr.top = mheight;
       else
       if (udopt == 1)
          ns4lr.top = 0;
       if (msgnr == 0)
          ns4scroll();
       else
          ns4scroll1();
     }

   elitebar_sumsize=sumsize;
   elitebar_msgsize=msgsize[0]
 }

function _set_time_out(p,n)
  {
    if (scroller_enabled)
      {
        setTimeout(p, n);
      }
  }

document.write('<table border="'+mborder+'" cellspacing="0" cellpadding="'+mpadding+'" background="'+mbground+'" bordercolor="'+mbcolor+'" bordercolorlight="'+mbcolorlight+'" bordercolordark="'+mbcolordark+'"><tr><td valign="top" height="' + mheight + 'px" width="' + mwidth + 'px">');
if (document.layers)
 {
   document.write('<ilayer id="ns4s" width="' + mwidth + '" height="' + mheight + '" bgcolor="' + mcolor + '"><layer id="ns4s0" width="' + mwidth + '" onMouseover="mspeed=0" onMouseout="mspeed=remspeed"></layer><layer id="ns4s1" width="' + mwidth + '" onMouseover="mspeed=0" onMouseout="mspeed=remspeed"></layer></ilayer>')
 }

if (document.all || document.getElementById)
 {
   document.write('<span style="height:' + mheight + 'px;"><div style="position:relative;overflow:hidden;width:' + mwidth + 'px;height:' + mheight + 'px;clip:rect(0 ' + mwidth + 'px ' + mheight + 'px 0);background-color:' + mcolor + ';" onMouseover="mspeed=0;" onMouseout="mspeed=remspeed"><div id="scrolldiv" style="position:absolute;overflow:hidden;width:' + mwidth + 'px;">');
   if (udopt == 1 && msgnr == 0 && celcen == 0)
      document.write(slmg[0]);
   document.write(wmsg);
   if (udopt == 1 && msgnr == 1)
      document.write(wmsg);
   document.write('</div><div id="scrolldivs" style="position:relative;overflow:hidden;width:' + mwidth + 'px;"></div></div><div id="hidslider" style="position:absolute;visibility:hidden;width:' + mwidth + 'px;"></div></span>');
 }

document.write('</td>');

if (lsopt == 1)
   document.write('<td bgcolor=' + celcolor + '><div ' + vesclass + '><b><a href="#" onMouseover="speedup();">Fast</a><br /><br /><a href="#" onMouseover="slowst();">Stop</a><br /><br /><a href="#" onMouseover="slowdn();">Slow</a></b></div></td>');

document.write('</tr></table>');
if (rcopt == 1)
 {
   document.write('<table border="0" cellspacing="0" cellpadding="0"><tr><td>');
   if (!document.layers)
      document.write('<span id="callbackm" style="visibility:hidden;">');
   document.write('<ilayer id="callbm"><layer id="cbackm" visibility="hide"><form name="boxm" method="post"><select name="listm" onFocus onChange="upcallm(this.form);">' + varoptm + '</select></form></layer></ilayer>');
   if (!document.layers)
      document.write('</span>');
   document.write('</td></tr></table>');
 }

//** --- invoke --- **/
startscroll();
</script>
</body>
</html>
