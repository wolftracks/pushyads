<?php include("initialize.php"); ?>
<?php
  // echo "PAREF='$PAREF'<br>";

  if (!isset($_COOKIE["PAREF"]))
    {
      if (isset($PAREF) && strlen($PAREF) > 0)
        {
          $db=getPushyDatabaseConnection();
          if (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$PAREF)))
            {
              setcookie("PAREF",$PAREF,time()+94608000,"/","",0);
            }
        }
    }

  $last_known_signin_id=$_COOKIE["PUSHYSIGNIN"];

  $PUSHY_NOSTART = FALSE;
?>
<html>
<title>Pushy Ads Advertising Widget</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>

<script type="text/javascript" src="/local-js/swfobject.js"></script>
<script type="text/javascript" src="/local-js/vplayer.js"></script>

<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.cookie.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript">
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


var current='home';
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
                      top.location=data.url;
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



//------------------------------------------------- Home Tab

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


function forgotPassword()
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

function initialize()
  {
    <?php
       if ($PUSHY_NOSTART)
         {
    ?>
           pushy_initialize("createPlayer()");  // nostart option must be set on the pushy script tag
    <?php
         }
       else
         {
    ?>
           createPlayer();                      // nostart option is not set - pushy just initializes on load  - shows the IE zindedx problem
    <?php
         }
    ?>
  }

</script>
</head>

<body class=background topmargin=0 onLoad="initialize()">

<table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>

      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=21  align=left >&nbsp;</td>
          <td width=416 align=left style="padding: 30px 0 30px 0px"><img src="http://pds1106.s3.amazonaws.com/images/main-pushy.png" width=416></td>
          <td width=44  align=left >&nbsp;</td>

          <td width=189 height=124 valign=top align=left>
             <div id="div-menu" class="login loglink" style="width:189px; height:124px">
                <span style="padding-left: 30px; color:#808080;"><a style="text-decoration:none;" href="javascript:showlogin()">Login</a></span>
                <span style="padding-left: 30px;"><a href="">Contact</a></span>
             </div>

             <div id="div-login" style="display:none;width:189px; height:124px">
                <span style="width:189x; top:0px; left:0px; padding:0; margin:0; border:0px; solid #565656; border-collapse: collapse; background-color:#D0D0D0">
                   <form name="loginForm" method="POST">
                     <table width=189 align=left bgcolor="#D0D0D0" width="100%" cellpadding=0 cellspacing=0 border=0 style="padding-top:5px;">
                        <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;">Username (Email):</span></td></tr>
                        <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;"><input name="useremail" style="font-size:14px;" type=text size=24 value="<?php echo $last_known_signin_id?>"></td></tr>
                        <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;">Password:</span></td></tr>
                        <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;"><input name="password"  style="font-size:14px;" type=password size=24 value=""></td></tr>
                        <tr valign=bottom>
                          <td align=left  width="40%" style="padding:4px;">&nbsp;&nbsp; <a style="font-size:14px; text-decoration:none" href=javascript:login_submit()>Enter</a></td>
                          <td align=right width="40%" style="padding:4px;"><a style="font-size:12px; text-decoration:none" href=javascript:hidelogin()>Cancel</a> &nbsp;&nbsp;</td>
                          <td width="20%" class=smalltext>&nbsp;</td>
                        </tr>
                        <tr><td colspan=3 align=center valign=middle height=40><span style="font-size:12px;"><a href=javascript:forgotPassword()>Forget Your Password?</a></td></tr>
                     </table>
                   </form>
                </span>
             </div>
          </td>

          <!-- td rowspan=2 align=right valign=top style="padding-top: 90px;"><img src="http://pds1106.s3.amazonaws.com/images/backofc-pushy270.png"></td -->
          <td width=270 rowspan=2 align=right valign=top style="padding-top: 90px;"><div id="PUSHY_HOME" style="position:absolute; height:333px; width:270px"></div></td>

        </tr>

        <tr>
          <td colspan=4>

            <table border=0 cellpadding=0 cellspacing=0>
              <tr>
                <td>

                  <!----
                  <script type="text/javascript" src="/local-js/signup.js"></script>
                  <div class=videoskin style="width:625px height:270px;">
                        <div id="flashcontent" style="position:relative; z-index:0; width:380px; height:270px; padding-top:10px;  padding-left:237px; padding-right:10px;"></div>
                        <script type="text/javascript">
                          var url="http://autoprospector.s3.amazonaws.com/signup/BlankPlayer.swf?path=http://autoprospector.s3.amazonaws.com/signup/signup1.flv&buffersize=0&startauto=true&redirect=";
                          var so = new SWFObject(url, "flashcontent", "380", "214", "8", "#ffffff");
                          // so.addParam('wmode','opaque');
                          so.addParam('wmode','transparent');
                          so.addParam('quality','high');
                          so.addParam('allowfullscreen','true');
                          so.addParam('allowscriptaccess','always');
                          so.write("flashcontent");
                        </script>
                  </div>


                  <div style="width:625px height:270px;"><img src="http://pds1106.s3.amazonaws.com/images/video-skin-shadow.png" width=625 height=270></div>

                  --->

                  <table cellpadding=0 cellspacing=0 border=0>
                    <tr>
                      <td width=232 class=playlist valign=top style="padding-top:66px;">
                        <table width=232 height=160 cellpadding=0 cellspacing=0 border=0 style="padding-left:46px;">
                          <tr><td width=183 height=53><a href=javascript:loadVideo('http://autoprospector.s3.amazonaws.com/signup/signup1.flv')
                                onMouseOver="document.why_pushy.src='http://pds1106.s3.amazonaws.com/player/images/why_pushy_over.png';"
                                onMouseOut ="document.why_pushy.src='http://pds1106.s3.amazonaws.com/player/images/why_pushy.png';"><img name="why_pushy" src="http://pds1106.s3.amazonaws.com/player/images/why_pushy.png" border=0></a></td></tr>

                          <tr><td width=183 height=53><a href=javascript:loadVideo('http://autoprospector.s3.amazonaws.com/video/video-pm1203211688610833.flv')
                                onMouseOver="document.how_pushy.src='http://pds1106.s3.amazonaws.com/player/images/how_pushy_over.png';"
                                onMouseOut ="document.how_pushy.src='http://pds1106.s3.amazonaws.com/player/images/how_pushy.png';"><img name="how_pushy" src="http://pds1106.s3.amazonaws.com/player/images/how_pushy.png" border=0></a></td></tr>

                          <tr><td width=183 height=53><a href=javascript:loadVideo('http://autoprospector.s3.amazonaws.com/video/video-cw1197666564481748.flv')
                                onMouseOver="document.push_pushy.src='http://pds1106.s3.amazonaws.com/player/images/push_pushy_over.png';"
                                onMouseOut ="document.push_pushy.src='http://pds1106.s3.amazonaws.com/player/images/push_pushy.png';"><img name="push_pushy" src="http://pds1106.s3.amazonaws.com/player/images/push_pushy.png" border=0></a></td></tr>
                        </table>
                      </td>
                      <td width=396 class=playlist_monitor>
                        <div style="position:relative; z-index:0; width:380px; height:270px; padding-top:10px; padding-left:8px; padding-right:10px;">
                           <div id="vplayer" style="z-index:0;"></div>
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
      <div class=tabs><a href=javascript:tabClicked('home')><img id='img-home' src="http://pds1106.s3.amazonaws.com/images/tab-home-active.png" class=tabpadding></a><a href=javascript:tabClicked('demo')><img id='img-demo' src="http://pds1106.s3.amazonaws.com/images/tab-demo.png" class=tabpadding></a><a href=javascript:tabClicked('earn')><img id='img-earn' src="http://pds1106.s3.amazonaws.com/images/tab-earn.png" class=tabpadding></a><a href=javascript:tabClicked('order')><img id='img-order' src="http://pds1106.s3.amazonaws.com/images/tab-order.png" class=tabpadding></a></div>
      <table align=right width=946 border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=38 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
          <td width=908 height=34 valign=bottom class=back></td>
        </tr>
        <tr>
          <td width=38 valign=top class=cellleft>&nbsp;</td>
          <td width=908 valign=top>

          <!--------------------------------------------- START CONTENT ------------------------------------------------>

          <table align=right width=908 height=500 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td valign=top>
                <div id=CANVAS style="margin: -6px 35px 25px 35px ;">

                  <?php
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

      <p>&nbsp;</p>
      <p>&nbsp;</p>

    </td>
  </tr>
</table>

<?php
if (is_integer(strpos(DOMAIN,"pushyads.com")))
  {  // Runs when invoked on PUSHYADS.COM
?>

    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f95c5aa2ed12439ddf7266eadaa487b1.js<?php echo ($PUSHY_NOSTART?'?nostart':'')?>?delay=2000&pause=1500&interval=60"></script>

<?php
  }
else
  {  // Runs when invoked on PUSHYADS.NET (LOCAL)
?>

    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f7604011ed3cd0188d8e457aec6615d8.js<?php echo ($PUSHY_NOSTART?'?nostart':'')?>?delay=2000&pause=1500&interval=60"></script>

<?php
  }
?>


<!-- script type="text/javascript">document.getElementById("F0").firstname.focus()</script -->
<?php
if (strlen($ALREADY_REGISTERED)>0)
  {
?>
    <script type="text/javascript">
       var msg  = "The email address you entered has previously been registered\n\n";
           msg += "To sign into your back office, click the Login link above.\n\n";
           msg += "If you have forgotton your password, click the Contact link above.\n \n";
       var f = function() {alert(msg);}
       setTimeout(f,800);
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
?>

</body>
</html>
