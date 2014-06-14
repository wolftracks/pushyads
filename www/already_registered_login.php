<?php
include_once("initialize.php");

$firstname="";
$lastname="";
$email="";

if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
    $db=getPushyDatabaseConnection();
    if (is_array($memberRecord=getMemberInfo($db,$mid)))
      {
        $member_id  = $memberRecord["member_id"];
        $firstname  = stripslashes($memberRecord["firstname"]);
        $lastname   = stripslashes($memberRecord["lastname"]);
        $email      = $memberRecord["email"];
      }
    else
     {
       include("main.php");
       exit;
     }
  }
else
  {
    include("main.php");
    exit;
  }
?>
<html>
<title>Pushy Ads Advertising Widget</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">

<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/video_player.js"></script>

<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css" />
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>

<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>

<script type="text/javascript">

function login_submit()
 {
   var theForm=document.loginForm;
   theForm.useremail.value = theForm.useremail.value.trim();
   theForm.password.value  = theForm.password.value.trim();

   var data = {
                signin_id:         theForm.useremail.value.toLowerCase(),
                signin_password:   theForm.password.value.toLowerCase(),
                mid:               theForm.mid.value
              };

   if (!signin_Validate(theForm))
     return;


   $.ajax({
      type:     "POST",
      url:      "signin.php",
      data:     data,
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
    if (theForm.useremail.value.length==0)
      {
        alert("Please enter your Email address");
        theForm.useremail.focus();
        return (false);
      }

    if (theForm.password.value.length == 0)
     {
       alert("Please enter your Password");
       theForm.password.value="";
       theForm.password.focus();
       return (false);
     }

    return (true);
  }


function forgotMyPassword()
  {
    showPopWin('Password Request', '/forgot_password.php', 360, 170, null);
  }

</script>
</head>

<body class=background topmargin=0>

<p> &nbsp;<br> </p>
<p> &nbsp;<br> </p>
<table align=center valign=top width=850px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td>
      <!--------------------------------------------- START CONTENT ------------------------------------------------>
      <table align=left width=490 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td valign=top>
            <div style="margin: -3px 35px 25px 30px ;">
              <p>
                <div align=center class=size24><b class=darkred>You are already registered, <?php echo $firstname?>!</b></div>
                <p style="margin-top: 25px;">Enter your signin information below to sign into your PushyAds Back Office.</p>
              </p>

                <br>
                <!--------------------------------------------- START SIGNUP FORM --------------------------------------------->
                <table align=center width=350 cellspacing=3 cellpadding=3 border=1 bgcolor="#FFC44D">
                  <tr>
                    <td style="border: 0px;">

                      <form name="loginForm" method="POST">
                      <input type="hidden" name="mid" value="<?php echo $mid?>">
                      <table style="border:1px solid #46587A;" width=100% cellpadding=0 cellspacing=0>
                        <tr bgcolor="#F7F7F7">
                          <td colspan=2 height=20></td>
                        </tr>

                        <tr height=28 >
                          <td style="padding-left: 50px;" align=left width=100% class=signlabel>
                            <b>Signin ID (Email):</b>
                          </td>
                        </tr>
                        <tr height=28>
                          <td style="padding-left: 45px;" width=100% class=signinput>
                            <input class=input  style="width:80%" type="text" name="useremail" value="<?php echo $email?>">
                          </td>
                        </tr>
                        <tr height=28>
                          <td style="padding-left: 50px;" align=left width=100% class=signlabel>
                            <b>Password:</b>
                          </td>
                        </tr>
                        <tr height=28>
                          <td style="padding-left: 45px;" width=100% class=signinput>
                            <input class=input style="width:80%" type="password" name="password" maxlength="20">
                          </td>
                        </tr>
                        <tr bgcolor="#F7F7F7">
                          <td colspan=2 height=10></td>
                        </tr>
                        <tr valign=center height=54 bgcolor="#F7F7F7" cellpadding=0 cellspacing=0 >
                          <td colspan=2 width="100%" align=center>
                            <a href=javascript:login_submit()><img src="http://pds1106.s3.amazonaws.com/images/login-button.gif" border=0></a>
                          </td>
                        </tr>
                        <tr bgcolor="#F7F7F7">
                          <td colspan=2 height=15></td>
                        </tr>
                        <tr bgcolor="#F7F7F7">
                          <td colspan=2 align="center" height=15><a href=javascript:forgotMyPassword()>I Forgot My Password</a></td>
                        </tr>
                        <tr bgcolor="#F7F7F7">
                          <td colspan=2 height=15></td>
                        </tr>
                      </table>
                      </form>
                    </td>
                  </tr>
                </table>
                <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=330 height=31></div>
                <!--------------------------------------------- END SIGNUP FORM ----------------------------------->
            </div>
          </td>
        </tr>
      </table>
      <!--------------------------------------------- END CONTENT ------------------------------------------------>
    </td>

    <td align=right valign=top style="padding-top: 8px;"><img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png"></td>

  </tr>
  <tr>
    <td width=490 height=38>
      <table width=100% border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=40  height=38 background="http://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>
          <td width=450 height=38 valign=top class=cellbottom></td>
          <td width=40  height=38 valign=top align=right background="http://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>


</body>
</html>
