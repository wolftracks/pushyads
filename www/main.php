<?php include("initialize.php"); ?>
<?php include("pushy_tracker.inc"); ?>
<?php
  $DEBUG=FALSE;

  $HONOR_COOKIE_FIRST=TRUE;       //---- THIS CAN BE SET TO FALSE FOR TESTING - If you want the affiliate on the URL to OVERRIDE any PRIOR Cookie Setting

  $db=getPushyDatabaseConnection();

  if (($HONOR_COOKIE_FIRST) && isset($_COOKIE["PAREF"]) && (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$_COOKIE["PAREF"]))))
      $PAREF=$_COOKIE["PAREF"];                    //----- Returning --- Use AffID in COOKIE
  else
  if (!isset($PAREF) || (strlen($PAREF)==0))
     $PAREF=PUSHY_ROOT;

  if (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$PAREF)))
    {
      $affMemberId = $affiliateRecord["member_id"];
      tracker_hit($db,TRACKER_AFFILIATE_PAGE,$affMemberId,'','','');
      if (!isset($_COOKIE["PAREF"]))
        {
          setcookie("PAREF",$PAREF,time()+94608000,"/","",0);
          $_COOKIE["PAREF"]=$PAREF;
        }
    }

  $last_known_signin_id=$_COOKIE["PUSHYSIGNIN"];

  $PUSHY_NOSTART = FALSE;

  list($_uri_,$_qs_) = explode("?",$REQUEST_URI);
  if (!isset($_qs_)) $_qs_="";
  if (is_integer(strpos($_qs_,"SessionExpired")))
    $SESSION_EXPIRED=TRUE;
?>
<html>
<head>

<?php
  if ($DEFAULT_TAB == "home")
    {
?>
     <title>PushyAds Advertising Widget | Targeted Traffic</title>

     <META name="description" content="Pushy Ads, creator of the PUSHY widget, generates a viral stampede of targeted traffic for
     website owners. PUSHY! affiliates make money by placing the PUSHY widget on their website. This effective traffic generation
     and lead generation tool is the talk of the Internet Marketing crowd. Joint Ventures and jv partners are welcome.">
<?php
    }
  else
  if ($DEFAULT_TAB == "demo")
    {
?>
     <title>Pushy Ads Widget | Qualified Traffic Demo</title>

     <META name="description" content="PUSHY widget generates income for affiliates, driving highly targeted & qualified traffic to
     their website. Internet Marketing & Advertising have never been so much fun (and lucrative). PUSHY! can be configured to behave,
     any way you want him to, targeting the right site audience for your product, service, or blog.">
<?php
    }
  else
  if ($DEFAULT_TAB == "earn")
    {
?>
     <title>PushyAds Widget | Viral Traffic Generator</title>

     <META name="description" content="PUSHY! Affiliate Marketing is now fun for viewers, as they watch PUSHY! perform his online
     antics. This innovative & Free widget generates targeted traffic from several viral traffic components. Widget advertising & Internet
     marketing are making money for affiliates and affiliate networks who use PUSHY!  FREE signup! Joint Ventures and jv partners are welcome.">
<?php
    }
  else
  if ($DEFAULT_TAB == "order")
    {
?>
     <title>Pushy Ads Traffic Widget | Targeted Traffic</title>

     <META name="description" content="PUSHY advertising widget is Free, while generating highly targeted & qualified traffic. Widget advertising
     not only drives qualified site audiences to your website, but it is making money for PUSHY! affiliates. This viral lead generation tool is
     exploding incomes for Internet Marketers. Joint Ventures and jv partners are welcome.">
<?php
    }
  else
    {  // Blank
?>
     <title>Pushy Ads Advertising Widget | Traffic Generation</title>

     <META name="description" content="PushyAds creates innovative & Free PUSHY widget for targeted traffic generation. Widget
     advertising and Internet marketing are making money for affiliates and affiliate networks using PUSHY!  The negative stigma
     of pushy ads is being dispelled with this cute & highly effective traffic & lead generation tool. Joint Ventures and jv
     partners are welcome.">

<?php
    }
?>

<META name="keywords" content="PushyAds, Pushy Ads, PUSHY, targeted traffic, widget advertising, Traffic Generation, Internet Marketing, money making opportunity, affiliate network, Affiliate Marketing, Lead Generation, online business, home based business, work at home, search engine traffic, joint venture, jv partner">
<META name="owner" content="www.pushyads.com">
<META name="author" content="PushyAds.com">
<META http-equiv="expires" content="Never">
<META name="rating" content="General">
<META name="mssmarttagspreventparsing" content="true">
<META name="ROBOTS" content="index,follow">
<META name="REVISIT-AFTER" content="7 days">
<META name="ROBOTS" content="ALL">
<META http-equiv="content-type" content="text/html; charset=ISO-8859-1">

<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">

<script type="text/javascript">
  window.history.forward(1);
</script>

<script type="text/javascript" src="/local-js/modal/subModal.js"></script>
<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.cookie.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript">

var mid="<?php echo $mid?>";
var sid="<?php echo $sid?>";

var pushy_properties =
 {
   origin:      true,
   motion:      true,
   transition:  true,
   speed:       true,
   wiggle:      true,
   delay:       true,
   pause:       true
 }

//   ---- UNSELECTED      SELECTED ---------------
//   "tab_home.png",      "tab_home_active.png"
//   "tab_demo.png",      "tab_demo_active.png"
//   "tab_earn.png",      "tab_earn_active.png"
//   "tab_order.png",     "tab_order_active.png"

var image_base_names = ['home', 'demo', 'earn', 'order'];

var imageHost="http://pds1106.s3.amazonaws.com/images/";
var image_selected={};
var image_unselected={};

if (document.images)
{
  for (var i=0; i<image_base_names.length; i++)
    {
      var basename = image_base_names[i];
      image_unselected[basename] = imageHost+'tab-'+basename+'.png';
      image_selected[basename]   = imageHost+'tab-'+basename+'-active.png';
    }
}



<?php
 if ($DEFAULT_TAB == "demo")
    echo "var current='demo';";
 else
 if ($DEFAULT_TAB == "earn")
    echo "var current='earn';";
 else
 if ($DEFAULT_TAB == "order")
   echo "var current='order';";
 else
   echo "var current='home';";
?>


function tabClicked(tab)
 {
   if (tab==current)
     {
       return;
     }

   var currentTabElement = document.getElementById('img-'+current);
   var newTabElement     = document.getElementById('img-'+tab);    //clicked

   var currentTabURL     = image_unselected[current];
   var newTabURL         = image_selected[tab];

   var canvas            = document.getElementById("CANVAS");

   tabUrl = "/tab-"+tab+".php?mid="+mid+"&sid="+sid;

   current=tab;

   pushy_properties.origin      = true;
   pushy_properties.motion      = true;
   pushy_properties.transition  = true;
   pushy_properties.speed       = true;
   pushy_properties.wiggle      = true;
   pushy_properties.delay       = true;
   pushy_properties.pause       = true;

   $.ajax({
      type:     "GET",
      url:      tabUrl,
      cache:    false,
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  // typically only one of textStatus or errorThrown will have info
                  var httpStatus=XMLHttpRequest.status;
                  canvas.innerHTML="Request Failed - HTTP Status:"+ httpStatus;
                },
      success:  function(response, textStatus)
                {
                  canvas.innerHTML = response;
                }
   });


   newTabElement.src=newTabURL;
   currentTabElement.src=currentTabURL;
 }


function showlogin()
 {
   $('#div-menu').hide();
   $('#div-login').show();
 }
function hidelogin()
 {
   $('#div-login').hide();
   $('#div-menu').show();
 }
function login()
 {
   $('#div-login').hide();
   $('#div-menu').show();
 }

function already_registered_login()
 {
   window.scroll(0,0);
   showlogin();
 }
function already_registered_forgot_password()
 {
   window.scroll(0,0);
   forgotPassword();
 }

function login_submit()
 {
   var theForm=document.loginForm;
   theForm.useremail.value = theForm.useremail.value.trim();
   theForm.password.value  = theForm.password.value.trim();

   if (!signin_Validate(theForm))
     return;

   $.ajax({
      type:     "POST",
      url:      "signin.php",
      data:     {
                   signin_id:         theForm.useremail.value.toLowerCase(),
                   signin_password:   theForm.password.value.toLowerCase()
                },
      cache:    false,
      dataType: "json",
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  // typically only one of textStatus or errorThrown will have info
                  var httpStatus=XMLHttpRequest.status;
                  alert("Request Failed - HTTP Status:"+ httpStatus);

                  // if (textStatus)
                  //   alert("textStatus: "+textStatus);
                  // if (errorThrown)
                  //   alert("errorThrown:\n"+ objectToString(errorThrown));
                  // alert(objectToString(this));  // the options for this ajax request
                },
      success:  function(response, textStatus)
                {
                  var status=response.status;
                  if (status != 0)
                    alert( response.message );
                  else
                    {
                      // alert(objectToString(rersponse));
                      var data=response.data;
                      // alert("Redirecting to("+data.url+")");
                      top.location.href=data.url;
                    }
                }
   });
 }


function signin_Validate(theForm)
  {
    if (theForm.useremail.value == "" || theForm.useremail.value == " ")
      {
        alert("Please enter your Email address");
        theForm.useremail.focus();
        return (false);
      }
    if (theForm.password.value == "" || theForm.password.value == " ")
      {
        alert("Please enter your Password ");
        theForm.password.focus();
        return (false);
      }
     return (true);
   }



//------------------------------------------------- Sign Up

function register_submit(theForm)
 {
   if (!registerValidation(theForm))
     return;

   var formName = theForm.name;

   var data={
               firstname:  theForm.firstname.value,
               lastname:   theForm.lastname.value,
               email:      theForm.email.value.toLowerCase(),
               paref:      theForm.paref.value
            }
//   var_dump(data);
//   return;

   $.ajax({
      type:     "POST",
      url:      "register.php",
      data:     data,
      cache:    false,
      dataType: "json",
      error:    function (XMLHttpRequest, textStatus, errorThrown)
                {
                  var httpStatus=XMLHttpRequest.status;
                  alert("Request Failed - HTTP Status:"+ httpStatus+" : "+textStatus);
                },
      success:  function(response, textStatus)
                {
                  var status=response.status;
                    // alert("Status="+status);
                    // alert(objectToString(response));
                  if (status != 0)
                    {
                      if (status==211) // ALREADY REGISTERED
                        {
                          var id = formName+"_ALREADY_REGISTERED";
                          var el = document.getElementById(id);
                          el.style.display = '';
                        }
                      else
                        alert( response.message );
                    }
                  else
                    {
                      // alert(objectToString(response));
                      var data=response.data;
                      // alert("Redirecting to("+data.url+")");
                      top.location.href=data.url;
                    }
                }
   });
 }



function registerValidation(theForm)
  {
    theForm.firstname.value = theForm.firstname.value.trim();
    theForm.lastname.value  = theForm.lastname.value.trim();
    theForm.email.value     = theForm.email.value.trim();

    if (theForm.firstname.value.length == 0)
      {
        alert("Please enter your First Name");
        theForm.firstname.focus();
        return (false);
      }
    if (theForm.lastname.value.length == 0)
      {
        alert("Please enter your Last Name");
        theForm.lastname.focus();
        return (false);
      }
    if (theForm.email.value.length == 0)
      {
        alert("Please enter your Email address");
        theForm.email.focus();
        return (false);
      }
    if ((!isValidEmailAddress(theForm.email.value)))
      {
        alert("Email Address invalid: Please Re-enter your Email Address.");
        theForm.email.focus();
        return (false);
      }

    return true;
  }




//------------------------------------------------- Order Tab

function orderTabClicked(option)
 {
   for (var i=1; i<=3; i++)
     {
       var id  = 'order_option_'+i;
       var el  =  document.getElementById(id);
       var bid = 'order_button_'+i;
       var bel =  document.getElementById(bid);
       if (el && (bel))
         {
           if (i==option && (el.style.display == 'none'))
             {
               el.style.display='';
               bel.value='CLOSE';
             }
           else
             {
               el.style.display='none';
               bel.value='OPEN';
             }
         }
     }
 }


function forgotPassword(ref)
  {
    hidelogin();
    showPopWin('Password Request', '/forgot_password.php', 360, 170, onResponse);
  }

function onResponse(returnVal) {
  if (returnVal)
    {
      //    var msg  = "Function Name : "+returnVal.functionName+"\n";
      //        msg += "Signin ID     : "+returnVal.signin_id+"\n";
      //        msg += "Password      : "+returnVal.password+"\n";
      //    alert(msg);
    }
  else
    {
      // alert("onResponse - No Values");
    }
}



//==== Player ====================

var player_image_base_names = ['why_pushy', 'how_pushy', 'push_pushy'];

var player_imageHost="http://pds1106.s3.amazonaws.com/images/";
var player_image_selected={};
var player_image_unselected={};
var player_image_hover={};

if (document.images)
{
  for (var i=0; i<player_image_base_names.length; i++)
    {
      var player_basename = player_image_base_names[i];
      player_image_unselected[player_basename] = player_imageHost+player_basename+'.gif';
      player_image_selected[player_basename]   = player_imageHost+player_basename+'_active.gif';
      player_image_hover[player_basename]      = player_imageHost+player_basename+'_over.gif';
    }
}


//======= Player ==========

function init_player()
  {

  }


var player_button_current='';
function loadVideo(button,file)
  {
    if (button != player_button_current)
      {
        if (player_button_current!='')
          {
            var currentPlayerButton   = document.getElementById(player_button_current);
            var currentPlayerImageUrl = player_image_unselected[player_button_current];
          }
        var newPlayerButton       = document.getElementById(button);
        var newPlayerImageUrl     = player_image_selected[button];
        newPlayerButton.src       = newPlayerImageUrl;
        if (player_button_current!='')
          currentPlayerButton.src   = currentPlayerImageUrl;
      }
    player_button_current=button;

    var clip =
      {
        url: file,
        autoPlay: true,
        fadeInSpeed: 2000,
        fadeOutSpeed: 2000,
        autoBuffering: true
      }
    flowplayer("vplayer").play(clip);
  }


function player_button_over(button)
 {
   if (button==player_button_current)
     {
       return;
     }

   var playerButton   = document.getElementById(button);
   var playerImageUrl = player_image_hover[button];
   playerButton.src   = playerImageUrl;
 }


function player_button_out(button)
 {
   if (button==player_button_current)
     {
       return;
     }

   var playerButton   = document.getElementById(button);
   var playerImageUrl = player_image_unselected[button];
   playerButton.src   = playerImageUrl;
 }



function initialize()
  {
    var player =
      {
        // our Flash component
        src: "http://pds1106.s3.amazonaws.com/player/flowplayer-3.1.2.swf",

        // we need at least this Flash version
        version: [9,115],

        wmode: 'transparent',

        // older versions will see a custom message
        onFail: function()
          {
            var msg  = "You need to upgrade your Flash version for this site.\n";
                msg += "Your version is " + this.getVersion() + "\n";
            alert(msg);
          }
      }

   var controls =
     {
       autoHide:'always',
       backgroundGradient: 'high',
       volumeSliderGradient: 'none',
       buttonColor: '#000000',
       timeColor: '#69CDFA',
       bufferGradient: 'none',
       progressGradient: 'none',
       sliderColor: '#C9C9C9',
       progressColor: '#FFA000',
       bufferColor: '#C78000',
       backgroundColor: '#929292',
       durationColor: '#ffffff',
       sliderGradient: 'none',
       volumeSliderColor: '#FFA000',
       buttonOverColor: '#FFA000',
       timeBgColor: '#262626',
       height: 20,
       hideDelay: 2500,
       borderRadius: '15',
       width: '90%',
       bottom: 3,
       left: '50%',
       opacity: 0.7,
       // tooltips configuration
       tooltips: {
           // enable english tooltips on all buttons
           buttons: true,
           // customized texts for buttons
           play: 'Play',
           pause: 'Pause',
           fullscreen: 'Full Screen'
       },

          // background color for all tooltips
       tooltipColor: '#C9C9C9',
          // text color
       tooltipTextColor: '#000000'
     }


    var myContent =
      {
         url: 'http://pds1106.s3.amazonaws.com/player/flowplayer.content-3.1.0.swf',
            // display properties
         top: 0,
         left: 0,
         width: 380,
         height: 214,

         // styling properties
         // borderRadius: 90,
         // backgroundImage: 'url(http://pds1106.s3.amazonaws.com/images/30.jpg)',
             // linked stylesheet
         // stylesheet: 'content-plugin.css',
             // "inline" styling (overrides external stylesheet rules),
         style: {
                   '.title': {
                      fontSize: 16,
                      fontFamily: 'verdana,arial,helvetica'
                    }
                },


              /* initial HTML content. content can also be fetched from the HTML document
                 like we did in the example above. see the source code for that.  */
             // html: '<img src="http://pds1106.s3.amazonaws.com/images/28.jpg" align="left"/>',

             // clicking on the plugin hides it (but you can do anything)
         onClick: function()
           {
             this.hide();
           }
      }


    var settings =
      {
         onLoad: function()
           {    // called when player has finished loading
              this.setVolume(80);    // set volume property
           },

         clip:
           {
             // url: "http://content.bitsontherun.com/videos/3ta6fhJQ.flv",
         //  url: "http://pds1106.s3.amazonaws.com/video/int/why_pushy.flv",
         //  autoPlay: false,
             wmode:'transparent',
             fadeInSpeed: 3000,
             fadeOutSpeed: 3000,
             autoBuffering: true,
             metaData: false,
               // Setting which defines how video is scaled on the video screen. Available options are:
               //  * fit:   Fit to window by preserving the aspect ratio encoded in the file's metadata.
               //  * half:  Half-size (preserves aspect ratio)
               //  * orig:  Use the dimensions encoded in the file. If too big - then 'scale'
               //  * scale: (DEFAULT) Scale the video to fill all available space. Ignores dimensions in metadata
             scaling: 'scale'
           },


         plugins:
           {
             controls: controls,
             // controls: null,

             // myContent: myContent

             dummy:null
           }
      }

    flowplayer("vplayer", player, settings);

    var f = function() { init_player(); }
    setTimeout(f, 2000);
  }


function pushy_posture_changed(theForm,inx)
  {
    // ShowFormVariables(theForm);
    if (theForm.widget_posture.value == '1')   // Hover - HOME is relevant - Show It
      {
        /*-- IN --*/
        var el=document.getElementById('PUSHY_WIDGET_ORIGIN_'+inx);
        if (el) el.style.display='';
        pushy_properties.origin=true;


        /*-- OUT --*/
        var el=document.getElementById('PUSHY_WIDGET_MOTION_'+inx);
        if (el) el.style.display='none';
        pushy_properties.motion=false;

        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='none';
        pushy_properties.speed=false;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='none';
        pushy_properties.pause=false;
      }
    else
      {                                                           // Not Hover - Not Relevant
        /*-- IN --*/
        var el=document.getElementById('PUSHY_WIDGET_MOTION_'+inx);
        if (el) el.style.display='';
        pushy_properties.motion=true;

        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='';
        pushy_properties.speed=true;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='';
        pushy_properties.pause=true;

        /*-- OUT --*/
        var el=document.getElementById('PUSHY_WIDGET_ORIGIN_'+inx);
        if (el) el.style.display='none';
        pushy_properties.origin=false;

      }
  }


function pushy_motion_changed(theForm,inx)
  {
    if (theForm.widget_motion.value == '0')   // No Motion - Speed and Pause are N/A - Hide Them
      {
        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='none';
        pushy_properties.speed=false;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='none';
        pushy_properties.pause=false;

      }
    else
      {                                                           // else - Show Them
        var el=document.getElementById('PUSHY_WIDGET_SPEED_'+inx);
        if (el) el.style.display='';
        pushy_properties.speed=true;

        var el=document.getElementById('PUSHY_WIDGET_PAUSE_'+inx);
        if (el) el.style.display='';
        pushy_properties.pause=true;
      }
  }


function pushy_transition_changed(theForm,inx)
  {
  }


function pushy_demo(theForm)
 {
    if (theForm.widget_size.selectedIndex == 0)
      {
        alert("Please select a Size for your Pushy Widget.");
        theForm.widget_size.focus();
        return (false);
      }

    if (theForm.widget_posture.selectedIndex == 0)
      {
        alert("Please make a Posture selection for your Pushy Widget.");
        theForm.widget_posture.focus();
        return (false);
      }


    if ((pushy_properties.origin) && theForm.widget_origin.selectedIndex == 0)
      {
        alert("Please Select a Home Location for your Pushy Widget.");
        theForm.widget_origin.focus();
        return (false);
      }


    if ((pushy_properties.motion) && theForm.widget_motion.selectedIndex == 0)
      {
        alert("Please make a Motion selection for your Pushy Widget.");
        theForm.widget_motion.focus();
        return (false);
      }

    if ((pushy_properties.transition) && theForm.widget_transition.selectedIndex == 0)
      {
        alert("Please make a Transition selection for your Pushy Widget.");
        theForm.widget_transition.focus();
        return (false);
      }

    var frameWidth  = 950;
    var frameHeight = 700;

    var url  = '/dialog/preview/start_preview.php?';
        url += 'mid=DEMO&';
        url += 'sid=DEMO&';
        url += 'frameWidth='+frameWidth+'&';
        url += 'frameHeight='+frameHeight+'&';
        url += 'pst='+theForm.widget_posture.value+'&';
        url += 'mtn='+theForm.widget_motion.value+'&';
        url += 'org='+theForm.widget_origin.value+'&';
        url += 'trn='+theForm.widget_transition.value+'&';
        url += 'wth='+theForm.widget_size.value+'&';
        url += 'spd='+theForm.widget_speed.value+'&';
        url += 'wig='+theForm.widget_wiggle.value+'&';
        url += 'dly='+theForm.widget_delay.value+'&';
        url += 'pau='+theForm.widget_pause.value;

    pushy_pause_wiggle();
    showPopWin('Pushy Demo', url, frameWidth, frameHeight, pushy_resume_wiggle, true, false);
 }


function popDialog(url,w,h)
 {
   var frameWidth  = w+20;
   var frameHeight = h+20;
   pushy_pause_wiggle();
   showPopWin('PushyAds', url+"?dlg=1", frameWidth, frameHeight, pushy_resume_wiggle, true, false);
 }


function customerServiceContactUs()
  {
    showPopWin('Contact Us', '/dialog/support/contact.php', 760, 660, function onSupportResponse(returnVal) { });
  }

function newmemberSignUp()
  {
    pushy_pause_wiggle();
    showPopWin("Sign Up", "/dialog/register/signup.php", 510, 380, pushy_resume_wiggle);
  }
</script>
</head>

<body class=background topmargin=0 onLoad="initialize()">
<script type="text/javascript" src="/local-js/wz_tooltips.js"></script>

<table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>

      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=21  align=left >&nbsp;</td>
          <td width=416 align=left style="padding: 30px 0 30px 0px"><img src="http://pds1106.s3.amazonaws.com/images/main-pushy.png" width=416></td>
          <td width=31  align=left >&nbsp;</td>

          <td width=202 height=124 valign=top align=left>
             <div id="div-menu" class="login loglink" style="width:189px; height:124px">
                <span style="padding-left: 30px; color:#808080;"><a style="text-decoration:none;" href="javascript:showlogin()">Login</a></span>
                <span style="padding-left: 30px;"><a href=javascript:customerServiceContactUs()>Contact</a></span>
             </div>

             <div id="div-login" style="display: none; width:202px; height: 157px; background-image: url('http://pds1106.s3.amazonaws.com/images/bg-login.png'); background-repeat: no-repeat;">

                   <form name="loginForm" action=javascript:login_submit()>
                     <table width=189 align=right bgcolor="#FFEECC" width="100%" cellpadding=0 cellspacing=0 border=0 style="font: 12px tahoma,arial; border-left: 1px solid #FFAA00; border-bottom: 1px solid #FFAA00; border-right: 1px solid #FFAA00;">
                        <tr height=20 valign=bottom><td colspan=2 style="padding-left:10px;"><span style="color:#855800"><b>Username: &nbsp;(Email)</b></span></td></tr>
                        <tr height=23 valign=bottom>
                          <td colspan=2 style="padding-left:4px;">
                            <input name="useremail" style="width:170px;" class=input_small type=text value="<?php echo $last_known_signin_id?>">
                          </td>
                        </tr>
                        <tr height=20 valign=bottom><td valign=bottom colspan=3 style="padding-left:10px;"><span style="color:#855800"><b>Password:</b></span></td></tr>
                        <tr height=23 valign=bottom>
                          <td colspan=2 style="padding-left:4px;">
                            <input name="password"  style="width:170px;" class=input_small type=password value="">
                          </td>
                        </tr>
                        <tr valign=bottom height=30>
                          <td align=center width="50%"><input type=submit style="width:65px;" value=Enter>
                          </td>
                          <td align=center width="50%"><input type=button value=Cancel onClick=javascript:hidelogin()>
                          </td>
                        </tr>
                        <tr height=27>
                          <td colspan=2 align=center valign=middle>
                            <a href=javascript:forgotPassword('<?php echo $PAREF?>')><b>Forget Your Password?</b></a>
                          </td>
                        </tr>
                     </table>
                   </form>
              </div>
          </td>

<?php
if ($DEBUG)
  {
?>
          <!-- td rowspan=2 align=right valign=top style="padding-top: 90px;"><img src="http://pds1106.s3.amazonaws.com/images/backofc-pushy270.png"></td -->
          <td width=270 rowspan=2 align=right valign=top style="padding-top: 90px;"><div id="PUSHY_HOME" style="height:333px; width:270px; background:#CC0000"></div></td>
<?php
  }
else
  {
?>
          <td width=270 rowspan=2 align=right valign=top style="padding-top: 90px;"><div id="PUSHY_HOME" style="height:333px; width:270px;"></div></td>
<?php
  }
?>


        </tr>

        <tr>
          <td colspan=4>

            <table border=0 cellpadding=0 cellspacing=0>
              <tr>
                <td>

                  <table cellpadding=0 cellspacing=0 border=0>
                    <tr>
                      <td width=230 class=playlist valign=top style="padding-top:68px;">
                        <table width=230 height=160 cellpadding=0 cellspacing=0 border=0 style="padding-left:46px;">
                          <tr><td width=185 height=53><a href=javascript:loadVideo('why_pushy','http://pds1106.s3.amazonaws.com/video/int/why_pushy.flv')>
                                <img id="why_pushy" name="why_pushy" src="http://pds1106.s3.amazonaws.com/images/why_pushy.gif" border=0 onMouseOver=player_button_over("why_pushy") onMouseOut=player_button_out("why_pushy")></a></td></tr>
                          <tr><td width=185 height=53><a href=javascript:loadVideo('how_pushy','http://pds1106.s3.amazonaws.com/video/int/how_pushy.flv')>
                                <img id="how_pushy" name="how_pushy" src="http://pds1106.s3.amazonaws.com/images/how_pushy.gif" border=0 onMouseOver=player_button_over("how_pushy") onMouseOut=player_button_out("how_pushy")></a></td></tr>
                          <tr><td width=185 height=53><a href=javascript:loadVideo('push_pushy','http://pds1106.s3.amazonaws.com/video/int/pushy_cash.flv')>
                                <img id="push_pushy" name="push_pushy" src="http://pds1106.s3.amazonaws.com/images/push_pushy.gif" border=0 onMouseOver=player_button_over("push_pushy") onMouseOut=player_button_out("push_pushy")></a></td></tr>
                        </table>
                      </td>
                      <td width=396 class=playlist_monitor>
                        <div style="position:relative; z-index:0; width:380px; height:270px; padding-top:10px; padding-left:8px; padding-right:10px;">
                           <div id="vplayer" style="z-index:0; width:380px; height:214px;">
                             <img src="http://pds1106.s3.amazonaws.com/video/why_pushy_player.gif" width=380 height=214 border=0 onClick=javascript:loadVideo('why_pushy','http://pds1106.s3.amazonaws.com/video/int/why_pushy.flv')>
                           </div>
                        </div>
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>

      <br>

      <?php
        if ($DEFAULT_TAB == "demo")
          {
      ?>
           <div class=tabs><a href="/tab/home" onClick="javascript:tabClicked('home'); return false"><img id='img-home' src="http://pds1106.s3.amazonaws.com/images/tab-home.png" class=tabpadding></a><a href="/tab/demo" onClick="javascript:tabClicked('demo'); return false;"><img id='img-demo' src="http://pds1106.s3.amazonaws.com/images/tab-demo-active.png" class=tabpadding></a><a href="/tab/earn" onClick="javascript:tabClicked('earn'); return false;"><img id='img-earn' src="http://pds1106.s3.amazonaws.com/images/tab-earn.png" class=tabpadding></a><a href="/tab/order" onClick="javascript:tabClicked('order'); return false;"><img id='img-order' src="http://pds1106.s3.amazonaws.com/images/tab-order.png" class=tabpadding></a></div>
      <?php
          }
        else
        if ($DEFAULT_TAB == "earn")
          {
      ?>
           <div class=tabs><a href="/tab/home" onClick="javascript:tabClicked('home'); return false"><img id='img-home' src="http://pds1106.s3.amazonaws.com/images/tab-home.png" class=tabpadding></a><a href="/tab/demo" onClick="javascript:tabClicked('demo'); return false;"><img id='img-demo' src="http://pds1106.s3.amazonaws.com/images/tab-demo.png" class=tabpadding></a><a href="/tab/earn" onClick="javascript:tabClicked('earn'); return false;"><img id='img-earn' src="http://pds1106.s3.amazonaws.com/images/tab-earn-active.png" class=tabpadding></a><a href="/tab/order" onClick="javascript:tabClicked('order'); return false;"><img id='img-order' src="http://pds1106.s3.amazonaws.com/images/tab-order.png" class=tabpadding></a></div>
      <?php
          }
        else
        if ($DEFAULT_TAB == "order")
          {
      ?>
           <div class=tabs><a href="/tab/home" onClick="javascript:tabClicked('home'); return false"><img id='img-home' src="http://pds1106.s3.amazonaws.com/images/tab-home.png" class=tabpadding></a><a href="/tab/demo" onClick="javascript:tabClicked('demo'); return false;"><img id='img-demo' src="http://pds1106.s3.amazonaws.com/images/tab-demo.png" class=tabpadding></a><a href="/tab/earn" onClick="javascript:tabClicked('earn'); return false;"><img id='img-earn' src="http://pds1106.s3.amazonaws.com/images/tab-earn.png" class=tabpadding></a><a href="/tab/order" onClick="javascript:tabClicked('order'); return false;"><img id='img-order' src="http://pds1106.s3.amazonaws.com/images/tab-order-active.png" class=tabpadding></a></div>
      <?php
          }
        else
          {
      ?>
           <div class=tabs><a href="/tab/home" onClick="javascript:tabClicked('home'); return false"><img id='img-home' src="http://pds1106.s3.amazonaws.com/images/tab-home-active.png" class=tabpadding></a><a href="/tab/demo" onClick="javascript:tabClicked('demo'); return false;"><img id='img-demo' src="http://pds1106.s3.amazonaws.com/images/tab-demo.png" class=tabpadding></a><a href="/tab/earn" onClick="javascript:tabClicked('earn'); return false;"><img id='img-earn' src="http://pds1106.s3.amazonaws.com/images/tab-earn.png" class=tabpadding></a><a href="/tab/order" onClick="javascript:tabClicked('order'); return false;"><img id='img-order' src="http://pds1106.s3.amazonaws.com/images/tab-order.png" class=tabpadding></a></div>
      <?php
          }
      ?>


<div align=right style="position: absolute; margin: -100px 0 0 700px;">
  <a href="javascript:newmemberSignUp()"><img src="http://pds1106.s3.amazonaws.com/images/free_pushy.png" alt="Click Here to Get PUSHY for FREE" title="Click Here to Get PUSHY for FREE"></a>
</div>


      <table align=right width=946 border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=38 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
          <td width=908 height=34 valign=bottom class=back></td>
        </tr>

<?php
if ($DEBUG)
  {
?>
  <tr height="80">
    <td colspan=2 width="100%">
       <PRE>
          <textarea cols=100 rows=10 id="PUSHY_SCRATCH_PAD"></textarea>
       </PRE>
    </td>
  </tr>
<?php
  }
?>

        <tr>
          <td width=38 valign=top class=cellleft>&nbsp;</td>
          <td width=908 valign=top>

          <!--------------------------------------------- START CONTENT ------------------------------------------------>

          <table align=right width=908 height=500 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td valign=top>
                <div id=CANVAS style="margin: -6px 35px 25px 35px ;">

                  <?php
                    if ($DEFAULT_TAB == "demo")
                      include("tab-demo.php");
                    else
                    if ($DEFAULT_TAB == "earn")
                      include("tab-earn.php");
                    else
                    if ($DEFAULT_TAB == "order")
                      include("tab-order.php");
                    else
                      include("tab-home.php");
                  ?>

                </div>
              </td>
            </tr>
          </table>
          <!--------------------------------------------- END CONTENT ------------------------------------------------>
        </td>
      </tr>
      <tr>
        <td width=38  height=38 background="http://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>

        <td width=908 height=38>
          <table width=100% border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td width=866 height=38 valign=top class=cellbottom></td>
              <td width=38 height=38 valign=top align=right background="http://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
            </tr>
          </table>
        </td>

      </tr>
    </table>

    </td>
  </tr>
</table>

<?php
if (is_integer(strpos(DOMAIN,"pushyads.com")))
  {  // Runs when invoked on PUSHYADS.COM
?>
    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/2ff0bf884d5f568610bbc0a66551fc5d.js?tracker=PUSHY_DEFAULT"></script>
<?php
  }
else
  {  // Runs when invoked on PUSHYADS.LOCAL
?>
    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/2ff0bf884d5f568610bbc0a66551fc5d.js?tracker=PUSHY_DEFAULT"></script>
<?php
  }
?>


<!-- script type="text/javascript">document.getElementById("F0").firstname.focus()</script -->
<?php
if (strlen($ALREADY_REGISTERED)>0)
  {
?>
    <script type="text/javascript">
      var el=document.getElementById("ALREADY_REGISTERED");
      if (el) el.style.display="";
      window.scroll(0,400);
    </script>
<?php
  }
else
if (strlen($REGISTRATION_FAILURE)>0)
  {
?>
    <script type="text/javascript">
       var msg="<?php echo $REGISTRATION_FAILURE?>";

       var f = function() {alert(msg);}
       setTimeout(f,800);
    </script>
<?php
  }
else
if (strlen($CONFIRMATION_FAILURE)>0)
  {
?>
    <script type="text/javascript">
       var msg="<?php echo $CONFIRMATION_FAILURE?>";

       var f = function() {alert(msg);}
       setTimeout(f,800);
    </script>
<?php
  }
else
if (strlen($EMAIL_UPDATE_CONFIRMATION)>0)
  {
?>
    <script type="text/javascript">

       var msg  = "Your Email Address Has Been Updated \n\n";
           msg += "You may Now Sign In With Your New Email Address: \n\n";
           msg += " <?php echo $EMAIL_UPDATE_CONFIRMATION?> \n\n";

       var f = function() {alert(msg);}
       setTimeout(f,800);
    </script>
<?php
  }
else
if ($SESSION_EXPIRED)
  {
?>
    <script type="text/javascript">
       var msg  = "Your Session Has Expired\n";
           msg += "Please Sign In\n\n";
       var f = function() {alert(msg);}
       setTimeout(f,800);
    </script>
<?php
  }
?>

<div align=center class="arial size12 link6" style="width:100%; text-align:center;">
                <a href="/pop-copyright.php"  onClick="javascript:openPopup('/pop-copyright.php',660,700); return false;">Copyright Notice</a>
  &nbsp;|&nbsp; <a href="/pop-privacy.php"    onClick="javascript:openPopup('/pop-privacy.php',660,700); return false;">Privacy Notice</a>
  &nbsp;|&nbsp; <a href="/pop-disclaimer.php" onClick="javascript:openPopup('/pop-disclaimer.php',660,700); return false;">Earnings Disclaimer</a>
  &nbsp;|&nbsp; <a href="/dialog/support/page_abuse.php"  onClick="javascript:popDialog('/dialog/support/page_abuse.php',715,610); return false;">Report Abuse</a>
  &nbsp;|&nbsp; <a href="/pop-terms.php"      onClick="javascript:openPopup('/pop-terms.php',660,700); return false;">Terms of Use</a>
  &nbsp;|&nbsp; <a href="/pop-tos.php"        onClick="javascript:openPopup('/pop-tos.php',660,700); return false;">Terms of Service</a>
</div>

<br>&nbsp;<br>
<span class="arial size9 color:#333333"><?php echo $PAREF."~".$affMemberId?></span>
</body>
</html>
