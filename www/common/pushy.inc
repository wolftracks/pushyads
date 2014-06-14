<?php

define("PAYPAL_SEARCH_KEY", "PushyAds");     // CONSTANT ID That Must Be Present On All Paypal Customer Profiles - Cannot CHANGE

$PUSHY_GRACE_PERIOD  = 5;                    // Payment Must be made 'within' this number of days

$PUSHY_ROOT          = "pushy";
define("PUSHY_ROOT", "pushy");

//---- GetResponse Campaign Ids -----------------------
define ("CAMPAIGN_VIP",       "73q");
define ("CAMPAIGN_PRO_ELITE", "md1");
//-----------------------------------------------------


$PUSHY_LEVEL_VIP            = 0;
$PUSHY_LEVEL_PRO            = 1;
$PUSHY_LEVEL_ELITE          = 2;
$PUSHY_LEVEL_HITEK          = 8;
$PUSHY_LEVEL_ROOT           = 9;
$PUSHY_LEVEL_ALL            = 99;


$UserLevels = array(
  $PUSHY_LEVEL_VIP            =>  "VIP",
  $PUSHY_LEVEL_PRO            =>  "PRO",
  $PUSHY_LEVEL_ELITE          =>  "ELITE",
  $PUSHY_LEVEL_HITEK          =>  "HiTek",
  $PUSHY_LEVEL_ROOT           =>  "PUSHY-ROOT",
);


//--------- PUSHY ORDER TYPES ------------------------
define("ORDER_TYPE_INITIAL"   , "initial"  );   // Live Order Placed At Registration Time
define("ORDER_TYPE_RENEWAL"   , "renewal"  );   // Batch Order - at current level
define("ORDER_TYPE_UPGRADE"   , "upgrade"  );   // Live Order - Upgrade
define("ORDER_TYPE_DOWNGRADE" , "downgrade");   // Live Order - Downgrade



//--------- PUSHY IMAGES -----------------------------

$PUSHY_IMAGE_NAME_TOP          = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-top.png";
$PUSHY_IMAGE_NAME_BOTTOM       = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-bottom.png";
$PUSHY_IMAGE_NAME_BOTTOM_BLANK = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-bottom-blank.png";
$PUSHY_IMAGE_NAME_BOTTOM_HOVER = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-bottom-hover.png";
$PUSHY_IMAGE_NAME_LEFT         = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-left.png";
$PUSHY_IMAGE_NAME_RIGHT        = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-right.png";
$PUSHY_IMAGE_NAME_SHELL        = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-shell.png";
$PUSHY_IMAGE_NAME_SHELL_8BIT   = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-shell-8.png";
$PUSHY_IMAGE_NAME_BLACK        = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-screen.png";
$PUSHY_IMAGE_NAME_SPACER       = "http://pds1106.s3.amazonaws.com/widgets/images/spacer.gif";
$PUSHY_IMAGE_NAME_MORPH        = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-morph.gif";
$PUSHY_IMAGE_NAME_PUSHYGUY     = "http://pds1106.s3.amazonaws.com/widgets/images/pushy-pushyguy.gif";
$PUSHY_IMAGE_GET_PUSHY         = "http://pds1106.s3.amazonaws.com/widgets/images/get-pushy.png";
$PUSHY_IMAGE_GET_PUSHY_HOVER   = "http://pds1106.s3.amazonaws.com/widgets/images/get-pushy-hover.png";
$PUSHY_IMAGE_PREVIEW           = "http://pds1106.s3.amazonaws.com/widgets/images/pushy_preview.png";


$PUSHY_LEVEL_VIP_MONTHLY_FEE   = 0.00;
$PUSHY_LEVEL_PRO_MONTHLY_FEE   = 47.00;
$PUSHY_LEVEL_ELITE_MONTHLY_FEE = 97.00;

// Fees By USER LEVEL
$MonthlyFees = array(
   $PUSHY_LEVEL_VIP_MONTHLY_FEE,
   $PUSHY_LEVEL_PRO_MONTHLY_FEE,
   $PUSHY_LEVEL_ELITE_MONTHLY_FEE,
);


$PUSHY_LEVEL_VIP_SALES_COMMISSION_RATE    = .30;
$PUSHY_LEVEL_PRO_SALES_COMMISSION_RATE    = .50;
$PUSHY_LEVEL_ELITE_SALES_COMMISSION_RATE  = .50;
$PUSHY_LEVEL_ELITE_BONUS_COMMISSION_RATE  = .20;

$CommissionRates = array (
  $PUSHY_LEVEL_VIP_SALES_COMMISSION_RATE,       // Commission Paid to VIP   Affiliates
  $PUSHY_LEVEL_PRO_SALES_COMMISSION_RATE,       // Commission Paid to PRO   Affiliates
  $PUSHY_LEVEL_ELITE_SALES_COMMISSION_RATE,     // Commission Paid to ELITE Affiliates
);

//--- CREDIT_POOL  CREDITS ------------------------------
$PUSHY_CREDITS_PERSONAL_DOMAINS  =  40;
$PUSHY_CREDITS_REFERRAL_DOMAINS  =  20;

$PUSHY_CREDITS_MEMBERSHIPS_VIP   =   2;
$PUSHY_CREDITS_MEMBERSHIPS_PRO   =   4;
$PUSHY_CREDITS_MEMBERSHIPS_ELITE =   8;

$PUSHY_CREDITS_PERSONAL_TRAFFIC  =   2;
$PUSHY_CREDITS_REFERRAL_TRAFFIC  =   1;


//---- COOKIES -------------------------------------------
define("PUSHY_ELITE_ADS_VIEWED",         "PUSHY_ELITE_ADS_VIEWED");  // Cookie



//---- TRACKERS ------------------------------------------
define("TRACKER_PUSHY_ADS",              "tracker_pushy_ads");           //-- Table Name ---
define("TRACKER_PUSHY_ADS_MYSITES",      "tracker_pushy_ads_mysites");   //-- Table Name ---
define("TRACKER_PUSHY_ADS_REFERRALS",    "tracker_pushy_ads_referrals"); //-- Table Name ---
define("TRACKER_AD_CATEGORY",            "tracker_ad_category");         //-- Table Name ---
define("TRACKER_PUSHY_WIDGET",           "tracker_pushy_widget");        //-- Table Name ---
define("TRACKER_AFFILIATE_PAGE",         "tracker_affiliate_page");      //-- Table Name ---
define("TRACKER_ELITE_BAR",              "tracker_elite_bar");           //-- Table Name ---
define("TRACKER_WIDGET_CATEGORY",        "tracker_widget_category");     //-- Table Name ---


//---- TRACKER TABLES ------------------------------------
$tracker_tables  = array(
   TRACKER_PUSHY_ADS,
   TRACKER_PUSHY_ADS_MYSITES,
   TRACKER_PUSHY_ADS_REFERRALS,
   TRACKER_AD_CATEGORY,
   TRACKER_PUSHY_WIDGET,
   TRACKER_AFFILIATE_PAGE,
   TRACKER_ELITE_BAR,
   TRACKER_WIDGET_CATEGORY,
);



define("TRACKER_PUSHY_WIDGET_DEFAULT",   "/default/");


//--- ELITE Ad Placement ---------------------------------
$AD_PLACEMENT_ELITE_BAR     = 1;
$AD_PLACEMENT_PUSHY_NETWORK = 2;
$AD_PLACEMENT_PRODUCT_LIST  = 3;
$AD_PLACEMENT_ELITE_AD_POOL = 4;


$MAX_DESCRIPTION_LENGTH=135;


//--- Product Categories ------- Like Auto-Increment, a Category ID Must Never be "Re-used"
$ProductCategories = array(
   "101"  =>  "Advertising",
   "102"  =>  "Animals/Pets",
   "103"  =>  "Art/Entertainment",
   "104"  =>  "Automotive Services",
   "105"  =>  "Aviation/Aerospace",
   "106"  =>  "Beauty",
   "107"  =>  "Building",
   "108"  =>  "Business Opportunities",
   "109"  =>  "Clothing/Fashion",
   "110"  =>  "Communications",
   "111"  =>  "Computer Software",
   "112"  =>  "Computer Hardware",
   "113"  =>  "Current Events",
   "114"  =>  "Culture/Society",
   "115"  =>  "Dating/Romance",
   "116"  =>  "Education/Instruction",
   "117"  =>  "Electronics",
   "118"  =>  "Employment/Careers",
   "119"  =>  "Family/Relationships",
   "120"  =>  "Financial",
   "121"  =>  "Fitness",
   "122"  =>  "Food/Beverage",
   "123"  =>  "Furniture",
   "124"  =>  "Health Care",
   "125"  =>  "Holidays/Events",
   "126"  =>  "Home Services",
   "127"  =>  "Home Applicance",
   "128"  =>  "Insurance",
   "129"  =>  "Legal",
   "130"  =>  "Marketing",
   "131"  =>  "Motor Vehicles",
   "132"  =>  "Real Estate",
   "133"  =>  "Recreation",
   "134"  =>  "Religious",
   "135"  =>  "Sports",
   "136"  =>  "Science",
   "137"  =>  "Self Improvement",
   "138"  =>  "Social Network",
   "139"  =>  "Technology",
   "140"  =>  "Toys",
   "141"  =>  "Travel/Lodging",
   "142"  =>  "Video",
   "143"  =>  "Water Sports",
   "144"  =>  "Weather",
   "145"  =>  "Web/Internet Services",
   "146"  =>  "Wedding",
   "147"  =>  "Writing/Speaking",
);



$PRODUCT_STATUS_ACTIVE    = 0;
$PRODUCT_STATUS_SUSPENDED = 1;   // Normally a temporary situation
$PRODUCT_STATUS_DISABLED  = 2;


$CONTENT_STATUS_PENDING   = 0;
$CONTENT_STATUS_APPROVED  = 1;
$CONTENT_STATUS_REJECTED  = 2;




//----- WIDGET DIMENSIONS AND SCALING --------------------

$WIDGET_MODEL_WIDTH  = 360;
$WIDGET_MODEL_HEIGHT = 445;

$WIDGET_WIDTH_LOW  = 180;
$WIDGET_WIDTH_HIGH = $WIDGET_MODEL_WIDTH;

$WIDGET_ASPECT_RATIO = $WIDGET_MODEL_HEIGHT / $WIDGET_MODEL_WIDTH;

function getWidgetHeight($width)
 {
   global $WIDGET_ASPECT_RATIO;
   $height = round($WIDGET_ASPECT_RATIO * $width);
   return $height;
 }

function isWidgetWidthSupported($width)
 {
   global $WIDGET_WIDTH_LOW;
   global $WIDGET_WIDTH_HIGH;
   for ($i=$WIDGET_WIDTH_LOW; $i<=$WIDGET_WIDTH_HIGH; $i+=10)
     {
       if ($width==$i) return TRUE;
     }
   return FALSE;
 }

function scaleWidget($widget_width)
 {
   global $WIDGET_MODEL_WIDTH;
   global $WIDGET_MODEL_HEIGHT;
   global $WIDGET_ASPECT_RATIO;

   $widget_height           = round($WIDGET_ASPECT_RATIO * $widget_width);
   $aspect_ratio            = $WIDGET_ASPECT_RATIO;                  // Height / Width
   $width_ratio             = $widget_width  / $WIDGET_MODEL_WIDTH;
   $height_ratio            = $widget_height / $WIDGET_MODEL_HEIGHT;
   $get_pushy_width         = round($width_ratio  * 140);
   $get_pushy_height        = round($height_ratio * 36);
   $pushy_scroller_width    = round($width_ratio  * 296);
   $pushy_scroller_height   = round($height_ratio * 257);

   $get_pushy_offsetX       = round(111 * $width_ratio);    // Offsets are Internal Offsets Relative to Pushy Widget Shell
   $get_pushy_offsetY       = round(409 * $height_ratio);   // Offsets are Internal Offsets Relative to Pushy Widget Shell
   $pushy_scroller_offsetX  = round(32  * $width_ratio);    // Offsets are Internal Offsets Relative to Pushy Widget Shell
   $pushy_scroller_offsetY  = round(128 * $height_ratio);   // Offsets are Internal Offsets Relative to Pushy Widget Shell

   return array(
                 "widget_width"           => $widget_width,
                 "widget_height"          => $widget_height,

                 "aspect_ratio"           => $aspect_ratio,   // need to expose? else remove
                 "width_ratio"            => $width_ratio,    // need to expose? else remove
                 "height_ratio"           => $height_ratio,   // need to expose? else remove

                 "get_pushy_width"        => $get_pushy_width,
                 "get_pushy_height"       => $get_pushy_height,
                 "pushy_scroller_width"   => $pushy_scroller_width,
                 "pushy_scroller_height"  => $pushy_scroller_height,

                 "get_pushy_offsetX"      => $get_pushy_offsetX,
                 "get_pushy_offsetY"      => $get_pushy_offsetY,
                 "pushy_scroller_offsetX" => $pushy_scroller_offsetX,
                 "pushy_scroller_offsetY" => $pushy_scroller_offsetY,
               );
 }



//---------------------------------------------
//
// for ($i=$WIDGET_WIDTH_LOW; $i<=$WIDGET_WIDTH_HIGH; $i+=10)
//   {
//     $width  = $i;
//     $height = round($WIDGET_ASPECT_RATIO * $width);
//   }
//
//
// <!-- * * * * * * * * * * * * * * -->
// <div id="PUSHY_HOME"   style="position:absolute; top:300px; left:200px; width:<!php echo $widget_width!>px;         height:<!php echo $widget_height!>px;"></div>
// <img id="SHELL"        style="position:absolute; top:0px;   left:600px; width:<!php echo $widget_width!>px;         height:<!php echo $widget_height!>px;"        src="pushy-shell.png" border="0">
// <img id="GETPUSHY"     style="position:absolute; top:500px; left:600px; width:<!php echo round(140 * $wratio)!>px;  height:<!php echo round(36  * $hratio)!>px;"  src="get-pushy.png" border="0">
// <iframe id="SCROLLER"  style="position:absolute; top:500px; left:600px; width:<!php echo round(296 * $wratio)!>px;  height:<!php echo round(257 * $hratio)!>px;"  src="http://tjw.ap.com/"  frameborder="0" scrolling="no"></iframe>
// <!-- * * * * * * * * * * * * * * -->
//
// <script type="text/javascript">
//
// SET_DHTML("PUSHY_HOME"+NO_DRAG,"SHELL","GETPUSHY"+NO_DRAG,"SCROLLER"+NO_DRAG);
//
// dd.elements.SHELL.moveTo(dd.elements.PUSHY_HOME.x,dd.elements.PUSHY_HOME.y);
//
// dd.elements.GETPUSHY.moveTo(dd.elements.PUSHY_HOME.x+<!php echo round(111 * $wratio)!>,dd.elements.PUSHY_HOME.y+<!php echo round(409 * $hratio)!>);
// dd.elements.GETPUSHY.maximizeZ();
// dd.elements.SHELL.addChild(dd.elements.GETPUSHY);
// dd.elements.SCROLLER.moveTo(dd.elements.PUSHY_HOME.x+<!php echo round( 32 * $wratio)!>,dd.elements.PUSHY_HOME.y+<!php echo round(128 * $hratio)!>);
// dd.elements.SCROLLER.maximizeZ();
// dd.elements.SHELL.addChild(dd.elements.SCROLLER);
//
// dd.elements.GETPUSHY.div.onmouseover=getpushy_over;
// dd.elements.GETPUSHY.div.onmouseout=getpushy_out;
// dd.elements.GETPUSHY.div.onclick=getpushy_clicked;
//
// </script>
//
//---------------------------------------------


//----- WIDGET PROPERTIES -------

$WIDGET_POSTURE_STATIC         =  0; // Static   - Default (0)
$WIDGET_POSTURE_HOVER          =  1;

$WIDGET_MOTION_NONE            =  0;
$WIDGET_MOTION_BOUNCE          =  1;
$WIDGET_MOTION_FLOAT           =  2;

$WIDGET_TRANSITION_NONE        =  0;
$WIDGET_TRANSITION_ZOOM        =  1;
$WIDGET_TRANSITION_MORPH       =  2;
$WIDGET_TRANSITION_FADE        =  3;

$WIDGET_POSTURES = array();
$WIDGET_POSTURES[$WIDGET_POSTURE_STATIC]         =  "Static";
$WIDGET_POSTURES[$WIDGET_POSTURE_HOVER]          =  "Hover";

$WIDGET_MOTIONS = array();
$WIDGET_MOTIONS[$WIDGET_MOTION_NONE]             =  "None";
$WIDGET_MOTIONS[$WIDGET_MOTION_BOUNCE]           =  "Bounce";
$WIDGET_MOTIONS[$WIDGET_MOTION_FLOAT]            =  "Float";

$WIDGET_TRANSITIONS = array();
$WIDGET_TRANSITIONS[$WIDGET_TRANSITION_NONE]     =  "None";
$WIDGET_TRANSITIONS[$WIDGET_TRANSITION_ZOOM]     =  "Zoom";
$WIDGET_TRANSITIONS[$WIDGET_TRANSITION_MORPH]    =  "Morph";
$WIDGET_TRANSITIONS[$WIDGET_TRANSITION_FADE]     =  "Fade";




//------

$WIDGET_ORIGIN_TOP_LEFT        =  0;
$WIDGET_ORIGIN_TOP_RIGHT       =  1;
$WIDGET_ORIGIN_BOTTOM_LEFT     =  2;
$WIDGET_ORIGIN_BOTTOM_RIGHT    =  3;

$WIDGET_ORIGINS = array();
$WIDGET_ORIGINS[$WIDGET_ORIGIN_TOP_LEFT]         = "Top Left";
$WIDGET_ORIGINS[$WIDGET_ORIGIN_TOP_RIGHT]        = "Top Right";
$WIDGET_ORIGINS[$WIDGET_ORIGIN_BOTTOM_LEFT]      = "Bottom Left";
$WIDGET_ORIGINS[$WIDGET_ORIGIN_BOTTOM_RIGHT]     = "Bottom Right";

//------

$WIDGET_SPEED_MEDIUM           =  0;
$WIDGET_SPEED_SLOW             =  1;
$WIDGET_SPEED_FAST             =  2;

$WIDGET_SPEEDS = array();
$WIDGET_SPEEDS[$WIDGET_SPEED_MEDIUM]             = "Medium";
$WIDGET_SPEEDS[$WIDGET_SPEED_SLOW]               = "Slow";
$WIDGET_SPEEDS[$WIDGET_SPEED_FAST]               = "Fast";

//------


$AUTOSHIP_STATUS_ACTIVE        = 1;
$AUTOSHIP_STATUS_DEACTIVATED   = 3;
$AUTOSHIP_STATUS_WAITING       = 4;
$AUTOSHIP_STATUS_PENDING       = 5;
$AUTOSHIP_STATUS_SUSPENDED     = 7;

$autoship_status_list = array(
   $AUTOSHIP_STATUS_ACTIVE      => "ACTIVE",
   $AUTOSHIP_STATUS_DEACTIVATED => "DEACTIVATED",
   $AUTOSHIP_STATUS_WAITING     => "WAITING",
   $AUTOSHIP_STATUS_PENDING     => "PENDING",
   $AUTOSHIP_STATUS_SUSPENDED   => "SUSPENDED"
);


define("AFFILIATE_PAYMENT_STATUS_UNSCHEDULED", 0);  // Payment Calculation - Performed Monthly by cron/affiliates/affiliate_rollup_monthly.php
define("AFFILIATE_PAYMENT_STATUS_SCHEDULED"  , 1);  // Can be switched between Scheduled/Unscheduled by Admin Tool
                                                    // Once Status Goes to PAID - Status Can No Longer Be changed
define("AFFILIATE_PAYMENT_STATUS_PAID"       , 2);  // This Amount Has been Submitted for Payment - Record is Considered HISTORY (cannot be altered)


function getPayPeriods($months=1)
 {
   $payPeriods=array();
   $dateArray=getDateTodayAsArray();
   if ($dateArray["day"]>=15)
     {
       $dateArray["day"]=15;
       $payPeriods[] = dateArrayToString($dateArray);
     }
   for ($i=$months; $i>0; $i--)
     {
       $dateArray=calstepMonths(-1,$dateArray);
       $period=$dateArray;

       $calData=calendar($period);
       $dim=$calData["DaysInMonth"];
       $period["day"]=$dim;
       $payPeriods[]=dateArrayToString($period);

       $period["day"]=15;
       $payPeriods[]=dateArrayToString($period);
     }

   return($payPeriods);
 }


function getNextPaymentDue($anniversaryTarget)
 {
   $nextPaymentDue = $anniversaryTarget;
   $calData = calendar($nextPaymentDue);
   $dim     = $calData["DaysInMonth"];

   if ($nextPaymentDue["day"] > $dim)
     {
       $nextPaymentDue = calStepMonths(1,$nextPaymentDue);
       $nextPaymentDue["day"]=1;
     }

   return($nextPaymentDue);
 }

function getNextAnniversaryTarget($anniversaryTarget,$months=1)
 {
              // Anniversary Dates Do NOT have to be Valid Dates  e.g. 2/31/2009
              // They represent the Target from which the NextPaymentDue Date is then calculated.
              // The Next Payment Due Date will be an Actual Valid Calendar Date based on the target Anniversary.
   return calStepMonths($months,$anniversaryTarget);
 }


function newServiceID()
  {
    $seq=1;
    $YC = "ABCDEFGHIJKLMNOP";
    $MC = "ZABCDEFGHIJKL";
    $dt=getDateTodayAsArray();
    $ky=$dt["year"]-2002;
    $km=$dt["month"];

    $ms=msFromStartOfDay();
    $msresult=sprintf("%08d",$ms);
    $service_id=substr($YC,$ky,1).substr($MC,$km,1).sprintf("%02d",$dt["day"])."-".$msresult;
    return $service_id;
  }


$TXTYPE_SALE                 =  0;
$TXTYPE_CREDIT               =  1;  // Credit - Sales
$TXTYPE_VOID                 =  2;  // Void   - Sales
$TXTYPE_CHARGE_BACK          =  3;
$TXTYPE_BAD_DEBT             =  4;
$TXTYPE_NSF                  =  5;
$TXTYPE_PAYMENT              =  6;

$us_states = array (
  "AK" => "Alaska",
  "AL" => "Alabama",
  "AR" => "Arkansas",
  "AZ" => "Arizona",
  "CA" => "California",
  "CO" => "Colorado",
  "CT" => "Connecticut",
  "DC" => "Dist of Columbia",
  "DE" => "Delaware",
  "FL" => "Florida",
  "GA" => "Georgia",
  "HI" => "Hawaii",
  "IA" => "Iowa",
  "ID" => "Idaho",
  "IL" => "Illinois",
  "IN" => "Indiana",
  "KS" => "Kansas",
  "KY" => "Kentucky",
  "LA" => "Louisiana",
  "MA" => "Massachusetts",
  "MD" => "Maryland",
  "ME" => "Maine",
  "MI" => "Michigan",
  "MN" => "Minnesota",
  "MO" => "Missouri",
  "MS" => "Mississippi",
  "MT" => "Montana",
  "NC" => "North Carolina",
  "ND" => "North Dakota",
  "NE" => "Nebraska",
  "NH" => "New Hampshire",
  "NJ" => "New Jersey",
  "NM" => "New Mexico",
  "NV" => "Nevada",
  "NY" => "New York",
  "OH" => "Ohio",
  "OK" => "Oklahoma",
  "OR" => "Oregon",
  "PA" => "Pennsylvania",
  "RI" => "Rhode Island",
  "SC" => "South Carolina",
  "SD" => "South Dakota",
  "TN" => "Tennessee",
  "TX" => "Texas",
  "UT" => "Utah",
  "VA" => "Virginia",
  "VT" => "Vermont",
  "WA" => "Washington",
  "WI" => "Wisconsin",
  "WV" => "West Virginia",
  "WY" => "Wyoming"
);


$us_state_names = array (
  "Alabama"               => "AL",
  "Alaska"                => "AK",
  "Arizona"               => "AZ",
  "Arkansas"              => "AR",
  "California"            => "CA",
  "Colorado"              => "CO",
  "Connecticut"           => "CT",
  "Delaware"              => "DE",
  "Dist of Columbia"      => "DC",
  "Florida"               => "FL",
  "Georgia"               => "GA",
  "Hawaii"                => "HI",
  "Idaho"                 => "ID",
  "Illinois"              => "IL",
  "Indiana"               => "IN",
  "Iowa"                  => "IA",
  "Kansas"                => "KS",
  "Kentucky"              => "KY",
  "Louisiana"             => "LA",
  "Maine"                 => "ME",
  "Maryland"              => "MD",
  "Massachusetts"         => "MA",
  "Michigan"              => "MI",
  "Minnesota"             => "MN",
  "Mississippi"           => "MS",
  "Missouri"              => "MO",
  "Montana"               => "MT",
  "Nebraska"              => "NE",
  "Nevada"                => "NV",
  "New Hampshire"         => "NH",
  "New Jersey"            => "NJ",
  "New Mexico"            => "NM",
  "New York"              => "NY",
  "North Carolina"        => "NC",
  "North Dakota"          => "ND",
  "Ohio"                  => "OH",
  "Oklahoma"              => "OK",
  "Oregon"                => "OR",
  "Pennsylvania"          => "PA",
  "Rhode Island"          => "RI",
  "South Carolina"        => "SC",
  "South Dakota"          => "SD",
  "Tennessee"             => "TN",
  "Texas"                 => "TX",
  "Utah"                  => "UT",
  "Vermont"               => "VT",
  "Virginia"              => "VA",
  "Washington"            => "WA",
  "West Virginia"         => "WV",
  "Wisconsin"             => "WI",
  "Wyoming"               => "WY"
);


$can_provinces = array (
  "AB" => "Alberta",
  "BC" => "British Columbia",
  "MB" => "Manitoba",
  "ON" => "Ontario",
  "NB" => "New Brunswick",
  "NF" => "Newfoundland",
  "NW" => "Northwest Territories",
  "NS" => "Nova Scotia",
  "NT" => "Nunavut",
  "PE" => "Prince Edward Island",
  "QB" => "Quebec",
  "SK" => "Saskatchewan",
  "YK" => "Yukon"
);


$can_province_names = array (
  "Alberta"               => "AB",
  "British Columbia"      => "BC",
  "Manitoba"              => "MB",
  "Ontario"               => "ON",
  "New Brunswick"         => "NB",
  "Newfoundland"          => "NF",
  "Northwest Territories" => "NW",
  "Nova Scotia"           => "NS",
  "Nunavut"               => "NT",
  "Prince Edward Island"  => "PE",
  "Quebec"                => "QB",
  "Saskatchewan"          => "SK",
  "Yukon"                 => "YK"
);


$url_stop_words = array(
   "a"        => TRUE,
   "also"     => TRUE,
   "am"       => TRUE,
   "an"       => TRUE,
   "and"      => TRUE,
   "are"      => TRUE,
   "as"       => TRUE,
   "at"       => TRUE,
   "be"       => TRUE,
   "but"      => TRUE,
   "by"       => TRUE,
   "can"      => TRUE,
   "com"      => TRUE,
   "de"       => TRUE,
   "en"       => TRUE,
   "etc"      => TRUE,
   "for"      => TRUE,
   "from"     => TRUE,
   "i"        => TRUE,
   "if"       => TRUE,
   "in"       => TRUE,
   "is"       => TRUE,
   "it"       => TRUE,
   "la"       => TRUE,
   "nor"      => TRUE,
   "of"       => TRUE,
   "on"       => TRUE,
   "or"       => TRUE,
   "onto"     => TRUE,
   "or"       => TRUE,
   "that"     => TRUE,
   "the"      => TRUE,
   "this"     => TRUE,
   "to"       => TRUE,
   "was"      => TRUE,
   "will"     => TRUE,
   "with"     => TRUE
);


$countries = array (
  "USA"  =>  "United States",
  "CAN"  =>  "Canada",
  "AFG"  =>  "Afghanistan",
  "ALB"  =>  "Albania",
  "DZA"  =>  "Algeria",
  "ASM"  =>  "American Samoa",
  "AND"  =>  "Andorra",
  "AGO"  =>  "Angola",
  "AIA"  =>  "Anguilla",
  "ATA"  =>  "Antarctica",
  "ATG"  =>  "Antigua and Barbuda",
  "ARG"  =>  "Argentina",
  "ARM"  =>  "Armenia",
  "ABW"  =>  "Aruba",
  "AUS"  =>  "Australia",
  "AUT"  =>  "Austria",
  "AZE"  =>  "Azerbaijan",
  "BHS"  =>  "Bahamas",
  "BHR"  =>  "Bahrain",
  "BGD"  =>  "Bangladesh",
  "BRB"  =>  "Barbados",
  "BLR"  =>  "Belarus",
  "BEL"  =>  "Belgium",
  "BLZ"  =>  "Belize",
  "BEN"  =>  "Benin",
  "BMU"  =>  "Bermuda",
  "BTN"  =>  "Bhutan",
  "BOL"  =>  "Bolivia",
  "BIH"  =>  "Bosnia and Herzegowina",
  "BWA"  =>  "Botswana",
  "BVT"  =>  "Bouvet Island",
  "BRA"  =>  "Brazil",
  "IOT"  =>  "British Indian Ocean Territory",
  "VGB"  =>  "British Virgin Islands",
  "BRN"  =>  "Brunei Darussalam",
  "BGR"  =>  "Bulgaria",
  "BFA"  =>  "Burkina Faso",
  "BDI"  =>  "Burundi",
  "KHM"  =>  "Cambodia",
  "CMR"  =>  "Cameroon",
  "CAN2" =>  "Canada",
  "CPV"  =>  "Cape Verde",
  "CYM"  =>  "Cayman Islands",
  "CAF"  =>  "Central African Republic",
  "TCD"  =>  "Chad",
  "CHL"  =>  "Chile",
  "CHN"  =>  "China",
  "CXR"  =>  "Christmas Island",
  "CCK"  =>  "Cocos (Keeling) Islands",
  "COL"  =>  "Colombia",
  "COM"  =>  "Comoros",
  "COG"  =>  "Congo",
  "COK"  =>  "Cook Islands",
  "CRI"  =>  "Costa Rica",
  "HRV"  =>  "Croatia",
  "CUB"  =>  "Cuba",
  "CYP"  =>  "Cyprus",
  "CZE"  =>  "Czech Republic",
  "DNK"  =>  "Denmark",
  "DJI"  =>  "Djibouti",
  "DMA"  =>  "Dominica",
  "DOM"  =>  "Dominican Republic",
  "TMP"  =>  "East Timor",
  "ECU"  =>  "Ecuador",
  "EGY"  =>  "Egypt",
  "SLV"  =>  "El Salvador",
  "GNQ"  =>  "Equatorial Guinea",
  "ERI"  =>  "Eritrea",
  "EST"  =>  "Estonia",
  "ETH"  =>  "Ethiopia",
  "FLK"  =>  "Falkland Islands",
  "FRO"  =>  "Faroe Islands",
  "FJI"  =>  "Fiji",
  "FIN"  =>  "Finland",
  "FRA"  =>  "France",
  "GUF"  =>  "French Guiana",
  "PYF"  =>  "French Polynesia",
  "ATF"  =>  "French Southern Territories",
  "GAB"  =>  "Gabon",
  "GMB"  =>  "Gambia",
  "GEO"  =>  "Georgia",
  "DEU"  =>  "Germany",
  "GHA"  =>  "Ghana",
  "GIB"  =>  "Gibraltar",
  "GRC"  =>  "Greece",
  "GRL"  =>  "Greenland",
  "GRD"  =>  "Grenada",
  "GLP"  =>  "Guadeloupe",
  "GUM"  =>  "Guam",
  "GTM"  =>  "Guatemala",
  "GIN"  =>  "Guinea",
  "GNB"  =>  "Guinea-Bissau",
  "GUY"  =>  "Guyana",
  "HTI"  =>  "Haiti",
  "HMD"  =>  "Heard and Mc Donald Islands",
  "VAT"  =>  "Holy See",
  "HND"  =>  "Honduras",
  "HKG"  =>  "Hong Kong",
  "HUN"  =>  "Hungary",
  "ISL"  =>  "Iceland",
  "IND"  =>  "India",
  "IDN"  =>  "Indonesia",
  "IRN"  =>  "Iran",
  "IRQ"  =>  "Iraq",
  "IRL"  =>  "Ireland",
  "ISR"  =>  "Israel",
  "ITA"  =>  "Italy",
  "IVO"  =>  "Ivory Coast",
  "JAM"  =>  "Jamaica",
  "JPN"  =>  "Japan",
  "JOR"  =>  "Jordan",
  "KAZ"  =>  "Kazakhstan",
  "KEN"  =>  "Kenya",
  "KIR"  =>  "Kiribati",
  "KWT"  =>  "Kuwait",
  "KGZ"  =>  "Kyrgyzstan",
  "LAO"  =>  "Laos",
  "LVA"  =>  "Latvia",
  "LBN"  =>  "Lebanon",
  "LSO"  =>  "Lesotho",
  "LBR"  =>  "Liberia",
  "LBY"  =>  "Libya",
  "LIE"  =>  "Liechtenstein",
  "LTU"  =>  "Lithuania",
  "LUX"  =>  "Luxembourg",
  "MAC"  =>  "Macau",
  "MKD"  =>  "Macedonia",
  "MDG"  =>  "Madagascar",
  "MWI"  =>  "Malawi",
  "MYS"  =>  "Malaysia",
  "MDV"  =>  "Maldives",
  "MLI"  =>  "Mali",
  "MLT"  =>  "Malta",
  "MHL"  =>  "Marshall Islands",
  "MTQ"  =>  "Martinique",
  "MRT"  =>  "Mauritania",
  "MUS"  =>  "Mauritius",
  "MYT"  =>  "Mayotte",
  "MEX"  =>  "Mexico",
  "FSM"  =>  "Micronesia",
  "MCO"  =>  "Monaco",
  "MNG"  =>  "Mongolia",
  "MSR"  =>  "Montserrat",
  "MAR"  =>  "Morocco",
  "MOZ"  =>  "Mozambique",
  "MMR"  =>  "Myanmar",
  "NAM"  =>  "Namibia",
  "NRU"  =>  "Nauru",
  "NPL"  =>  "Nepal",
  "NLD"  =>  "Netherlands",
  "ANT"  =>  "Netherlands Antilles",
  "NCL"  =>  "New Caledonia",
  "NZL"  =>  "New Zealand",
  "NIC"  =>  "Nicaragua",
  "NER"  =>  "Niger",
  "NGA"  =>  "Nigeria",
  "NIU"  =>  "Niue",
  "NFK"  =>  "Norfolk Island",
  "NKO"  =>  "North Korea",
  "MNP"  =>  "Northern Mariana Islands",
  "NOR"  =>  "Norway",
  "OMN"  =>  "Oman",
  "PAK"  =>  "Pakistan",
  "PLW"  =>  "Palau",
  "PAN"  =>  "Panama",
  "PNG"  =>  "Papua New Guinea",
  "PRY"  =>  "Paraguay",
  "PER"  =>  "Peru",
  "PHL"  =>  "Philippines",
  "PCN"  =>  "Pitcairn",
  "POL"  =>  "Poland",
  "PRT"  =>  "Portugal",
  "PRI"  =>  "Puerto Rico",
  "QAT"  =>  "Qatar",
  "REU"  =>  "Reunion",
  "ROM"  =>  "Romania",
  "RUS"  =>  "Russian Federation",
  "RWA"  =>  "Rwanda",
  "KNA"  =>  "Saint Kitts &amp; Nevis",
  "LCA"  =>  "Saint Lucia",
  "VCT"  =>  "Saint Vincent/Grenadines",
  "WSM"  =>  "Samoa",
  "SMR"  =>  "San Marino",
  "STP"  =>  "Sao Tome &amp; Principe",
  "SAU"  =>  "Saudi Arabia",
  "SEN"  =>  "Senegal",
  "SYC"  =>  "Seychelles",
  "SLE"  =>  "Sierra Leone",
  "SGP"  =>  "Singapore",
  "SVN"  =>  "Slovenia",
  "SLB"  =>  "Solomon Islands",
  "SOM"  =>  "Somalia",
  "ZAF"  =>  "South Africa",
  "SKO"  =>  "South Korea",
  "ESP"  =>  "Spain",
  "LKA"  =>  "Sri Lanka",
  "SHN"  =>  "St. Helena",
  "SPM"  =>  "St. Pierre and Miquelon",
  "SDN"  =>  "Sudan",
  "SUR"  =>  "Suriname",
  "SVA"  =>  "Svalbard",
  "SWZ"  =>  "Swaziland",
  "SWE"  =>  "Sweden",
  "CHE"  =>  "Switzerland",
  "SYR"  =>  "Syrian Arab Republic",
  "TWN"  =>  "Taiwan",
  "TJK"  =>  "Tajikistan",
  "TZA"  =>  "Tanzania",
  "THA"  =>  "Thailand",
  "TGO"  =>  "Togo",
  "TKL"  =>  "Tokelau",
  "TON"  =>  "Tonga",
  "TTO"  =>  "Trinidad and Tobago",
  "TUN"  =>  "Tunisia",
  "TUR"  =>  "Turkey",
  "TKM"  =>  "Turkmenistan",
  "TCA"  =>  "Turks and Caicos Islands",
  "TUV"  =>  "Tuvalu",
  "UGA"  =>  "Uganda",
  "UKR"  =>  "Ukraine",
  "ARE"  =>  "United Arab Emirates",
  "GBR"  =>  "United Kingdom",
  "USA2" =>  "United States",
  "URY"  =>  "Uruguay",
  "UMI"  =>  "U. S. Minor Outlying Islands",
  "UZB"  =>  "Uzbekistan",
  "VUT"  =>  "Vanuatu",
  "VAT"  =>  "Vatican City",
  "VEN"  =>  "Venezuela",
  "VNM"  =>  "Vietnam",
  "VIR"  =>  "Virgin Islands (U.S.)",
  "WLF"  =>  "Wallis and Futuna Islands",
  "ESH"  =>  "Western Sahara",
  "YEM"  =>  "Yemen",
  "YUG"  =>  "Yugoslavia",
  "ZAI"  =>  "Zaire",
  "ZMB"  =>  "Zambia",
  "ZWE"  =>  "Zimbabwe",
 );


//---------------- TIME ZONES ----------------
$TZ_HAWAII   = 1;
$TZ_ALASKA   = 2;
$TZ_PACIFIC  = 3;
$TZ_MOUNTAIN = 4;
$TZ_CENTRAL  = 5;
$TZ_EASTERN  = 6;
$TZ_ATLANTIC = 7; // Canada Only

$timezones = array( 0 => "None",
    $TZ_HAWAII   => "Hawaii",
    $TZ_ALASKA   => "Alaska",
    $TZ_PACIFIC  => "Pacific",
    $TZ_MOUNTAIN => "Mountain",
    $TZ_CENTRAL  => "Central",
    $TZ_EASTERN  => "Eastern",
    $TZ_ATLANTIC => "Atlantic"
  );

?>
