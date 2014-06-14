<?php
include_once("initialize.php");

$orderFailed=FALSE;
if (isset($_REQUEST["order_failed"]) && $_REQUEST["order_failed"]=="true")
  $orderFailed=TRUE;

$firstname="";
$lastname="";
$email="";

$registered=0;
if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
    $db=getPushyDatabaseConnection();
    if (is_array($memberRecord=getMemberInfo($db,$mid)))
      {
        $member_id  = $memberRecord["member_id"];
        $firstname  = stripslashes($memberRecord["firstname"]);
        $lastname   = stripslashes($memberRecord["lastname"]);
        $registered = $memberRecord["registered"];
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
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<link rel="shortcut icon" href="http://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>

<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/video_player.js"></script>

<script type="text/javascript">
var previously_registered='<?php echo $registered?>';

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

   registered=0;
   if (previously_registered.length>0)
     registered=parseInt(previously_registered);
   if (registered==0)
     {
       theForm.password2.value = theForm.password2.value.trim();
       data.confirm_password   = theForm.password2.value.toLowerCase();
     }

   if (!signin_Validate(theForm,registered))
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


function signin_Validate(theForm,registered)
  {
    if (theForm.useremail.value.length==0)
      {
        alert("Please enter your Email address");
        theForm.useremail.focus();
        return (false);
      }


    if (registered == 0)
      {
        if (theForm.password.value.length == 0)
         {
           alert("Please create your Signin Password");
           theForm.password.value="";
           theForm.password2.value="";
           theForm.password.focus();
           return (false);
         }

        if (!isValidPW(theForm.password.value))
         {
           alert("Your Password must be 6 to 20 alphanumeric characters (a-z,0-9),\nat least 1 of which Must be Numeric\n");
           theForm.password.value="";
           theForm.password2.value="";
           theForm.password.focus();
           return (false);
         }
        if (theForm.password2.value.length == 0)
         {
           alert("Please Confirm your \"Password\".");
           theForm.password2.focus();
           return (false);
         }

        if (theForm.password2.value != theForm.password.value)
         {
           alert("Passwords Do Not Match. Please Re-Enter your \"Password\".");
           theForm.password.value="";
           theForm.password2.value="";
           theForm.password.focus();
           return (false);
         }

        if (!theForm.terms.checked)
         {
           alert("You must confirm that you have read and agree to the Terms Of Use.");
           theForm.terms.focus();
           return (false);
         }
      }
    else
      {
        if (theForm.password.value.length == 0)
         {
           alert("Please enter your Password");
           theForm.password.value="";
           theForm.password.focus();
           return (false);
         }
      }

    return (true);
  }
</script>

<script type="text/javascript">
function page_loaded() {
  if (pushy_SetScrollerLocation) pushy_SetScrollerLocation();
  start_video('http://pds1106.s3.amazonaws.com/video/int/vip_signup.flv');
}
function page_unloaded() {
  if (pushy_SetScrollerLocation) pushy_SetScrollerLocation();
  start_video('http://pds1106.s3.amazonaws.com/video/int/vip_signup.flv');
}
window.onload=page_loaded;
window.onunload=page_unloaded;
</script>

</head>

<body class=background topmargin=0>

<table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>
      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td align=center style="padding: 20px 0 0 20px;"><img src="http://pds1106.s3.amazonaws.com/images/login.png"></td>
          <td rowspan=3 align=right valign=top style="padding-top: 90px;">
            <img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
          </td>
        </tr>

        <tr>
          <td>
            <table width=440 height=270 align=center border=0 cellpadding=0 cellspacing=0>
              <tr>
                <td class=videosmscreen>
                  <div id="vplayer" style="position:relative; top:-15px; left:38px; z-index:0; width:394px; height:220px; background:#000000"></div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>


      <table align=left width=625 border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td width=40 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
          <td width=587 height=34 valign=bottom class=boback></td>
        </tr>
        <tr>
          <td width=40 valign=top class=cellleft>&nbsp;</td>
          <td width=587 valign=top>

          <!--------------------------------------------- START CONTENT ------------------------------------------------>

          <table align=left width=587 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td valign=top>
                <div style="margin: -3px 35px 25px 35px ;">

                <?php
                   if ($orderFailed)
                     {
                ?>
                       <div align=center class=size28><b>Your order failed, <?php echo $firstname?></b></div>
                       <p style="margin-top: 15px;">Theres still time to fix the problem by using another type of payment and get
                <?php
                     }
                   else
                     {
                ?>
                       <div align=center class=size28><b>You're kidding, right <?php echo $firstname?>?</b></div>
                       <p style="margin-top: 15px;">There's still time to change your mind and get all the
                <?php
                     }
                ?>

                <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 <b>One Time Offer Bonuses</b>
                by taking BIG TIME advantage of either the

                <a href="<?php echo SECURE_SERVER?>/order_pro.php?mid=<?php echo $member_id?>"><b>PRO</b></a> or

                <a href="<?php echo SECURE_SERVER?>/order_elite.php?mid=<?php echo $member_id?>"><b>ELITE</b></a>

                membership before that clock over there runs out of time.

                <p align=center class=bold style="margin-top: 15px;">You can always upgrade your account later, <br><i class=darkred>BUT you won't get
                these bonuses worth <br>$422 to $1,272 for FREE after today!</i></p>

                <p>If you're absolutely, positively, unquestionably convinced beyond a shadow of doubt that you would
                rather signup as a <b>VIP</b> for free, then go ahead log in below, and go
                <b>Get</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482</p>

                    <br>
                    <!--------------------------------------------- START SIGNUP FORM --------------------------------------------->
                    <table align=center width=350 cellspacing=3 cellpadding=3 border=1 bgcolor="#FFC44D">
                      <tr>
                        <td colspan=3 style="border: 0px; background-color:#FFC44D;">

                          <form name="loginForm" method="POST">
                          <input type="hidden" name="mid" value="<?php echo $mid?>">
                          <table style="border:1px solid #46587A;" width=100% cellpadding=0 cellspacing=0>
                            <tr bgcolor="#F7F7F7">
                              <td colspan=2 height=20></td>
                            </tr>

                            <tr height=28>
                              <td style="padding-left: 50px;" align=left width=100% class=signlabel>
                                <b>Signin ID (Email):</b>
                              </td>
                            </tr>
                            <tr height=28>
                              <td style="padding-left: 45px;" width=100% class=signinput>
                                <input class=input  style="width:80%" type="text" name="useremail" value="<?php echo $email?>">
                              </td>
                            </tr>

                            <?php
                            if ($registered == 0)
                              { // NOT YET REGISTERD - MUST CREATE A PASSWORD
                            ?>
                            <tr height=44>
                              <td style="padding-left: 50px;" align=left width=100% class=signlabel>
                                <b>Create Password:</b>
                                <br><span class=smalltext> 6 or more alphanumeric characters (a-z,0-9)</span>
                              </td>
                            </tr>
                            <tr height=28>
                              <td style="padding-left: 45px;" width=100% class=signinput>
                                <input class=input style="width:80%" type="password" name="password" maxlength="20">
                              </td>
                            </tr>

                            <tr height=28>
                              <td style="padding-left: 50px;" align=left width=100% class=signlabel>
                                <b>Confirm Password:</b>
                              </td>
                            </tr>
                            <tr height=28>
                              <td style="padding-left: 45px;" width=100% class=signinput>
                                <input class=input style="width:80%" type="password" name="password2" maxlength="20">
                              </td>
                            </tr>

                            <tr height=35 valign=bottom>
                              <td style="padding-left: 47px;" width=100% class=signlabel>
                                 <input name="terms" type="checkbox">&nbsp;&nbsp;&nbsp;
                                <b class=text>I agree to <a href=javascript:openPopup('/pop-terms.php',660,700)>Terms of Use</a></b>
                              </td>
                            </tr>

                            <?php
                              }
                            else
                              {
                            ?>
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
                            <?php
                              }
                            ?>

                            <tr bgcolor="#F7F7F7">
                              <td colspan=2 height=20></td>
                            </tr>
                            <tr valign=center height=54 bgcolor="#F7F7F7" cellpadding=0 cellspacing=0 >
                              <td colspan=2 width="100%" align=center>
                                <a href=javascript:login_submit()><img src="http://pds1106.s3.amazonaws.com/images/login-button.gif" border=0></a>
                              </td>
                            </tr>
                            <tr bgcolor="#F2F4F7">
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
      </tr>

      <tr>
        <td width=40  height=38 background="http://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>
        <td width=587 height=38>
          <table width=100% border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td width=547 height=38 valign=top class=cellbottom></td>
              <td width=40 height=38 valign=top align=right background="http://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    </td>
  </tr>
</table>

    </td>
  </tr>
</table>

<script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f958e8ce1f1a881fe6bc12d1f8c23633.js?mid=<?php echo $mid?>"></script>
</body>
</html>
