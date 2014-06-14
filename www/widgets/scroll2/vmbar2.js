<html>
<head>
<style type="text/css">
a:link {
   color: #CC0000;
}
a:visited {
   color: #CC0000;
}
a:hover {
   color: #0000FF;
   text-decoration: none;
}
.viplink a:link {
   color: #3333FF;
}
.viplink a:visited {
   color: #3333FF;
}
.viplink a:hover {
   color: #FF5E00;
   text-decoration: none;
}

table.pushy_rollover:hover {
   background-color: #FFFF30;
}

.hlt {background-color: #FFFF30; color: #000000;}

.pushy_rollover {h: expression(onmouseover=new Function("this.className = 'hlt';"));}

.hlt {h: expression(onmouseout=new Function("this.className = 'pushy_rollover';"));}

a img {
   border: none;
}


/*------------------------------------*/
/* Image containers for 5 size groups */
/* .img_1 = 180-200                   */
/* .img_2 = 210-230                   */
/* .img_3 = 240-270                   */
/* .img_4 = 280-310                   */
/* .img_5 = 320-360                   */
/*------------------------------------*/

.img_1 {
   margin-right: 6px;
   height:      50px;
   width:       37px;
   overflow:  hidden;
}


.img_2 {
   margin-right: 6px;
   height:      59px;
   width:       43px;
   overflow:  hidden;
}


.img_3 {
   margin-right: 9px;
   height:      73px;
   width:       52px;
   overflow:  hidden;
}


.img_4 {
   margin-right: 12px;
   height:       84px;
   width:        60px;
   overflow:   hidden;
}


.img_5 {
   margin-right:  13px;
   height:       102px;
   width:         74px;
   overflow:    hidden;
}

/*-------------------------------------*/
/* Title containers for 5 size groups  */
/* .title_1 = 180-200                  */
/* .title_2 = 210-230                  */
/* .title_3 = 240-270                  */
/* .title_4 = 280-310                  */
/* .title_5 = 320-360                  */
/*-------------------------------------*/

.title_1 {
   font: 13px arial;
   height:     18px;
   overflow: hidden;
}

.title_2 {
   font: 15px arial;
   height:     21px;
   overflow: hidden;
}

.title_3 {
   font: 17px arial;
   height:     22px;
   overflow: hidden;
}

.title_4 {
   font: 19px tahoma;
   height:     26px;
   overflow: hidden;
}

.title_5 {
   font: 21px tahoma;
   height:      28px;
   overflow:  hidden;
}

/*-------------------------------------------*/
/* Description containers for 5 size groups  */
/* .desc_1 = 180-200                         */
/* .desc_2 = 210-230                         */
/* .desc_3 = 240-270                         */
/* .desc_4 = 280-310                         */
/* .desc_5 = 320-360                         */
/*-------------------------------------------*/

.desc_1 {
   font: 11px/12px Arial;
   margin-left:      1px;
   height:          37px;
   margin-bottom:   9px;
   overflow:      hidden;
}

.desc_2 {
   font: 13px/14px Arial;
   margin-left:      1px;
   height:          43px;
   margin-bottom:   10px;
   overflow:      hidden;
}

.desc_3 {
   font: 15px/16px Arial;
   margin-left:      1px;
   height:          49px;
   margin-bottom:   11px;
   overflow:      hidden;
}

.desc_4 {
   font: 17px/18px Arial;
   margin-left:      1px;
   height:          54px;
   margin-bottom:   14px;
   overflow:      hidden;
}

.desc_5 {
   font: 19px/21px Arial;
   margin-left:      1px;
   height:          66px;
   margin-bottom:   18px;
   overflow:      hidden;
}




/*---------------------------------------------------*/
/* Title containers for VIP ads within 5 size groups */
/* .title_vip1 = 180-200                             */
/* .title_vip2 = 210-230                             */
/* .title_vip3 = 240-270                             */
/* .title_vip4 = 280-310                             */
/* .title_vip5 = 320-360                             */
/*---------------------------------------------------*/

.title_vip_1 {
   font: 12px arial;
   height:     26px;
   overflow: hidden;
   margin-bottom:    9px;
}

.title_vip_2 {
   font: 13px arial;
   height:     31px;
   overflow: hidden;
   margin-bottom:   10px;
}

.title_vip_3 {
   font: 15px arial;
   height:     35px;
   overflow: hidden;
   margin-bottom:   11px;
}

.title_vip_4 {
   font: 17px arial;
   height:     37px;
   overflow: hidden;
   margin-bottom:   14px;
}

.title_vip_5 {
   font: 17px arial;
   height:     40px;
   overflow: hidden;
   margin-bottom:   16px;
}

/*---------------------------------------------------------*/
/* Description containers for VIP ads within 5 size groups */
/* .desc_vip1 = 180-200                                    */
/* .desc_vip2 = 210-230                                    */
/* .desc_vip3 = 240-270                                    */
/* .desc_vip4 = 280-310                                    */
/* .desc_vip5 = 320-360                                    */
/*---------------------------------------------------------*/

.desc_vip_1 {
   font: 11px/12px Arial;
}

.desc_vip_2 {
   font: 13px/14px Arial;
}

.desc_vip_3 {
   font: 15px/16px Arial;
}

.desc_vip_4 {
   font: 17px/18px Arial;
}

.desc_vip_5 {
   font: 17px/18px Arial;
}

</style>
</head>

<script type=text/javascript>
function display(ad_id)
  {
    var w=650;
    var h=600;
    var tracker="/ads/"+ad_id;
    var args='width='+w+',height='+h+',top=0,left=0'+
             ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
    window.open(tracker,"PushyAds",args);
  }

/****
function openPopupWindow(url,top,left,width,height)
  {
    var args='width='+width+',height='+height+',top='+top+',left='+left+
             ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';
    window.open(url,"PushyAds",args);
  }
****/
</script>
<body>


<?php
//--------------------------------------------
//
// sample test invocation in browser:  http://pushyads.local/widgets/scroll/a58bcc6367d057782fff9d356083998c.php
//
//-----

include_once("pushy_ad_selection.php");


//---- EACH Widget Category for this Pushy Instance represents a HIT to that Category for This Member's Widget
$tarray = explode("~",$WidgetCategories);
for ($i=0; $i<count($tarray); $i++)
  {
    if (strlen($tarray[$i])>0)
      {
        $category=$tarray[$i];
        tracker_hit($db, TRACKER_WIDGET_CATEGORY, $WidgetOwner, buildWidgetKey($WidgetKey, $tracking_id), '', $category);
      }
  }


// ---- RESULT is the "ads" array
//
// printf("<PRE>\n");
// print_r($ads);
// printf("</PRE>\n");
// exit;
//
//--------------------------------------------
?>


<div style="position:absolute; top:0px; left:0px;">
<script type="text/javascript">
var slmg=new Array();
var slinx=0;
<?php

if ($width <= 200)                     { $css_group = 1;  $css_image_width=37;  $css_image_height=50; }
else
if ($width >= 210  &&  $width <= 230)  { $css_group = 2;  $css_image_width=43;  $css_image_height=59; }
else
if ($width >= 240  &&  $width <= 270)  { $css_group = 3;  $css_image_width=52;  $css_image_height=73; }
else
if ($width >= 280  &&  $width <= 310)  { $css_group = 4;  $css_image_width=60;  $css_image_height=84; }
else
if ($width >= 320)                     { $css_group = 5;  $css_image_width=74;  $css_image_height=102;}


$ads_displayed = array();
$pushy_ads_cookie_data=array();
$existing_cookie_data="";
$refdomain=str_replace(".","_",$referer_domain);
// $pushy_ads_cookie_id="PUSHY_ADS_$refdomain";
$pushy_ads_cookie_id="PUSHY_ADS_VIEWED";

if (isset($_COOKIE[$pushy_ads_cookie_id]))
  {
    $existing_cookie_data=$_COOKIE[$pushy_ads_cookie_id];
    $tarray=explode("~",$existing_cookie_data);
    for ($i=0; $i<count($tarray); $i++)
      {
        $pushy_ads_id = stripa($tarray[$i]);
        if (strlen($pushy_ads_id)>0)
          $pushy_ads_cookie_data[$pushy_ads_id]=TRUE;
      }
  }
$existing_cookie_count=count($pushy_ads_cookie_data);



//============================================================
for ($i=0; $i<count($ads); $i++)
  {
    if (is_array($ads[$i]))
      {
         $myrow = $ads[$i];

         $ad_owner              = $myrow["member_id"];
         $ad_owner_user_level   = $myrow["user_level"];

         $ad_id                 = $myrow["ad_id"];
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

         $ad_prefix="a-";
         if ($ad_owner == $WidgetOwner) // If the Owner of this Ad is Also The Owner of this Widget
           $ad_prefix="m-";
         else
         if ($ad_owner == $refid)       // If the Owner of this Ad is My Referer (Direct Upline)
           $ad_prefix="r-";

         //-------
         if (!isset($pushy_ads_cookie_data[$ad_id]))
           {

             // user is seeing this ad for the first time in the last 24 hours
             tracker_hit($db, TRACKER_PUSHY_ADS, $ad_owner, '', $ad_id);

             if ($ad_owner == $WidgetOwner) // If the Owner of this Ad is Also The Owner of this Widget
                tracker_hit($db, TRACKER_PUSHY_ADS_MYSITES, $ad_owner, '', $ad_id);
             else
             if ($ad_owner == $refid) // If the Owner of this Ad is My Referer (Direct Upline)
                tracker_hit($db, TRACKER_PUSHY_ADS_REFERRALS, $ad_owner, '', $ad_id);

           }
         $pushy_ads_cookie_data[$ad_id]=TRUE; // may already be there - thats OK - accumulative "new picture"
         //-------


         //---- PRODUCT
         $product_owner         = $myrow["product_owner"];
         $product_submit_date   = $myrow["product_submit_date"];
         $product_name          = stripslashes($myrow["product_name"]);
         $product_title         = stripslashes($myrow["product_title"]);
         $product_description   = stripslashes($myrow["product_description"]);
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

         if ($ad_owner_user_level == $PUSHY_LEVEL_VIP)
           {

?>
             slmg[slinx]  = "<table border=0 cellspacing=0 cellpadding=0>\n";
             slmg[slinx] += "<tr>\n";
             slmg[slinx] += "<td valign=top colspan=2>\n";
             slmg[slinx] += "<div class='title_vip_<?php echo $css_group?> viplink'>\n";
             slmg[slinx] += " <a href=javascript:display('<?php echo $ad_prefix.$ad_id?>')><b><?php echo $product_title?></b></a>\n";
             slmg[slinx] += " <span class='desc_vip_<?php echo $css_group?>'><?php echo $product_description?></span>\n";
             slmg[slinx] += "</div>\n";
             slmg[slinx] += "</td>\n";
             slmg[slinx] += "</tr>\n";
             slmg[slinx] += "</table>\n";
             slinx++;

<?php

           }
         else
           {
             // Here is WHERE WE Want to use Original Image Width/Height to determine ASPECT RATIO

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
?>

             slmg[slinx]  = "<table border=0 cellspacing=0 cellpadding=0 class=pushy_rollover>\n";
             slmg[slinx] += "<tr>\n";
             slmg[slinx] += "<td valign=top>\n";

             slmg[slinx] += "<div class='img_<?php echo $css_group?>'>\n";
             slmg[slinx] += " <a href=javascript:display('<?php echo $ad_prefix.$ad_id?>')><img src='<?php echo $image_url?>' <?php echo $dim?> border=0></a>\n";
             slmg[slinx] += "</div>\n";
             slmg[slinx] += "</td>\n";
             slmg[slinx] += "<td valign=top>\n";
             slmg[slinx] += "<div class='title_<?php echo $css_group?>'>\n";
             slmg[slinx] += " <a href=javascript:display('<?php echo $ad_prefix.$ad_id?>')><b><?php echo $product_title?></b></a>\n";
             slmg[slinx] += "</div>\n";
             slmg[slinx] += "<div class='desc_<?php echo $css_group?>'><?php echo $product_description?></div>\n";

             slmg[slinx] += "</td>\n";
             slmg[slinx] += "</tr>\n";
             slmg[slinx] += "</table>\n";

             slinx++;
<?php
           }
      }
  }
//============================================================




$tm=time();

// $expires=timeAtMidnightTomorrow($tm);
$expires=$tm+86400; // Full 24 hours;

$new_cookie_data="";
$new_cookie_count=count($pushy_ads_cookie_data);
foreach($pushy_ads_cookie_data AS $ad_id=>$bool)
 {
   if ($new_cookie_data=="")
     $new_cookie_data  = $ad_id;
   else
     $new_cookie_data .= "~".$ad_id;
 }
setcookie($pushy_ads_cookie_id,$new_cookie_data,$expires,"/","",0);
?>


var scroller_enabled=true;
if (slinx < 3)
  {
    scroller_enabled=false;
  }

//**********************************************************************************************
//*
//**********************************************************************************************
// alert("SLMG COUNT="+slmg.length+" : "+slinx+" TH:"+<?php echo $TARGET_HEIGHT?>);

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
var mwidth=<?php echo $scroll_width?>;

//HEIGHT of the Scroller in pixels: set to your own;
//"px" unit will automatically be added in the process;
//larger Messages (exceeding height) will slide OK anyway, but
//the exceeding height won't show up on option Recall when called up!
var mheight=<?php echo $scroll_height?>;

if (slinx == 1)
  mheight = 80;
else
if (slinx == 2)
  mheight = 160;


//BACKGROUND: either color(1) or image(2) ;
//1.Background color: could be like: "#ffff00" or "yellow";
//set it "" for no background color;
// var mcolor="aqua";
var mcolor="#ffffff";
//or 2.Background image: "imagename.ext";
//leave it "" for no image background;
var mbground="";


// var ad_separator = '<hr width="100%" border=0>';
// var ad_separator = '<span style="height:1px;">&nbsp;</span>';
var ad_separator = '';


//BORDER for scrolling area: 1, ... ;
//set it 0(zero) for no border;
var mborder=0;

//BORDER COLOR
var mbcolor="#0000A8";
var mbcolorlight="#0000D8";
var mbcolordark ="#000090";

//PADDING - Padding between Border and Text - This is ususally Non-Zero Only if mborder is NonZero
var mpadding=2;

//SPEED in pixels: the higher the faster - set your own!
var mspeed=2;


//PAUSE between messages in milliseconds: 1000=1s; set to your own;
var mpause = 4000;


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
var remspeed = mspeed;
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
               // document.getElementById('hidslider').innerHTML = slmg[pp] + '<div ' + retclass + '>'+ad_separator+'</div>';
               document.getElementById('hidslider').innerHTML = slmg[pp] + ad_separator;
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
</div>
</body>
</html>
