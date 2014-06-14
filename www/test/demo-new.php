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
?>
<html>
<title>Pushy Ads Advertising Widget</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<!-- LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css" -->
<!-- script type="text/javascript" src="/local-js/modal/subModal.js"></script -->

<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.cookie.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>
<script type="text/javascript">
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
</script>
</head>

<body class=background topmargin=0>

<table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>

      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td style="padding: 30px 0 30px 21px;" width=490><img src="http://pds1106.s3.amazonaws.com/images/main-pushy.png"></td>

          <td id="div-menu" width=220 height=124 class="login loglink" valign=top align=left>
             <span style="padding-left: 0px; color:#808080;"><a style="text-decoration:none;" href="javascript:showlogin()">Login</a></span>
             <!-- span style="padding-left: 6px;"><a style="text-decoration:none; color:white" href="javascript:signin()">Login</a></span -->
             <span style="padding-left: 30px;"><a href="">Contact</a></span>
          </td>

          <td id="div-login" width=180 height=124 style="display:none; width:180px; top:0px; left:0px; padding:0; margin:0" valign=top align=left>
             <span style="width:180x; top:0px; left:0px; padding:0; margin:0; border:0px; solid #565656; border-collapse: collapse; background-color:#D0D0D0">
                <form name="loginForm" method="POST">
                  <table width=180 align=left bgcolor="#D0D0D0" width="100%" cellpadding=0 cellspacing=0 border=0 style="padding-top:5px;">
                     <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;">Username (Email):</span></td></tr>
                     <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;"><input name="useremail" style="font-size:14px;" type=text size=24 value="<?php echo $last_known_signin_id?>"></td></tr>
                     <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;">Password:</span></td></tr>
                     <tr><td colspan=3 style="padding-left:4px;"><span style="font-size:12px;"><input name="password"  style="font-size:14px;" type=password size=24 value=""></td></tr>
                     <tr valign=bottom>
                       <td align=left  width="40%" style="padding:4px;">&nbsp;&nbsp; <a style="font-size:14px; text-decoration:none" href=javascript:login_submit()>Enter</a></td>
                       <td align=right width="40%" style="padding:4px;"><a style="font-size:12px; text-decoration:none" href=javascript:hidelogin()>Cancel</a> &nbsp;&nbsp;</td>
                       <td width="20%" class=smalltext>&nbsp;</td>
                     </tr>
                  </table>
                </form>
             </span>
          </td>

          <td rowspan=2 align=right valign=top style="padding-top: 90px;"><img src="http://pds1106.s3.amazonaws.com/images/backofc-pushy270.png"></td>
          <!-- td rowspan=3 align=right valign=top style="padding-top: 20px;" width=270><div id="PUSHY_HOME" style="position:absolute; height:333px; width:270px"></div></td -->

        </tr>

        <tr>
          <td colspan=2>

            <table border=0 cellpadding=0 cellspacing=0>
              <tr>
                <td>


                  <script type="text/javascript" src="/local-js/signup.js"></script>
                  <div class=videoskin style="width:625px height:270px;">
                     <div style="width:380px; height:214px; margin-top:0px; margin-left:0px;">

                        <div id="flashcontent" style="width:380px; height:214px; margin-top:8px; margin-bottom:8px; margin-right:8px; margin-left:237px;"></div>
                        <script type="text/javascript">
                          var url="http://autoprospector.s3.amazonaws.com/signup/BlankPlayer.swf?path=http://autoprospector.s3.amazonaws.com/signup/signup1.flv&buffersize=0&startauto=true&redirect=";
                          var so = new SWFObject(url, "flashcontent", "380", "214", "8", "#ffffff");
                          so.write("flashcontent");
                        </script>

                     </div>
                  </div>

                  <!-- div><img src="http://pds1106.s3.amazonaws.com/images/video-skin-shadow.png"></div -->
                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>
                  <br>
                  <div class=tabs><a href="main.php"><img src="http://pds1106.s3.amazonaws.com/images/tab-home.png" class=tabpadding></a><img src="http://pds1106.s3.amazonaws.com/images/tab-demo-active.png" class=tabpadding><a href="earn.php"><img src="http://pds1106.s3.amazonaws.com/images/tab-earn.png" class=tabpadding></a><a href="order.php"><img src="http://pds1106.s3.amazonaws.com/images/tab-order.png"></a></div>


      <table align=right width=946 border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=38 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
          <td width=908 height=34 valign=bottom class=back></td>
        </tr>
        <tr>
          <td width=38 valign=top class=cellleft>&nbsp;</td>
          <td width=908 valign=top>

          <!--------------------------------------------- START CONTENT ------------------------------------------------>

          <table align=right width=908 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td valign=top>
                <div style="margin: -6px 35px 25px 35px ;">
                <font size=6><b>PUSHY DEMOS GO HERE</b></font>

                <p>&nbsp;</p>
                Blah, blah, blah, blah, blah...
                <p>&nbsp;</p>

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

    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f95c5aa2ed12439ddf7266eadaa487b1.js"></script>

<?php
  }
else
  {  // Runs when invoked on PUSHYADS.NET (LOCAL)
?>

    <script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f7604011ed3cd0188d8e457aec6615d8.js"></script>

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
       alert(msg);
    </script>
<?php
  }
else
if (strlen($REGISTRATION_FAILURE)>0)
  {
?>
    <script type="text/javascript">var msg="<?php echo $REGISTRATION_FAILURE?>"; alert(msg);</script>
<?php
  }
?>

<!-- script type="text/javascript">
function signin()
  {
    showPopWin('Sign-In', '/sign_in_dialog.php', 360, 170, onResponse);
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
</script -->

</body>
</html>
