<?php
include_once("initialize.php");


//--------------------------------------------------------------------------------------
// $msg  = "-- THANKYOU --<br><br>";
//
//
// $msg  .= sprintf("Method: $REQUEST_METHOD<br><br><br>\n");
//
//   if (is_array($_REQUEST) && count($_REQUEST) > 0)
//     {
//       $msg  .= sprintf("-------- REQUEST VARS -- (Get/Post/Cookie/Files) ---<br>\n");
//       while (list($key00, $value00) = each($_REQUEST))
//         {
//           // --- ASSIGN THE VARIABLES
//           // $str = "\$".$key00." = $value00;";
//           // printf("%s<br>\n",$str);
//           // eval($str);
//
//           $msg  .= sprintf("%s=%s<br>\n",$key00,$value00);
//         }
//       $msg  .= sprintf("<br><br><br>\n");
//     }
//
// echo $msg;
// $fp=fopen("LOG.TXT","a");
// fputs($fp,getDateTime()."\n".$msg);
// fclose($fp);
//--------------------------------------------------------------------------------------


$firstname="";
$lastname="";
$email="";

$registered=0;
$mid="";
if (isset($_REQUEST["custom_mid"]))
  $mid=$_REQUEST["custom_mid"];
else
if (isset($_REQUEST["mid"]))
  $mid=$_REQUEST["mid"];

if (strlen($mid)>0 || isset($_REQUEST["email"]))
  {
    $db=getPushyDatabaseConnection();

    if (strlen($mid)>0)
      {
        $memberRecord=getMemberInfo($db,$mid);
      }
    else
    if (isset($_REQUEST["email"]))
      {
        $email=strtolower($_REQUEST["email"]);
        $memberRecord=getMemberInfoForEmail($db,$email);
      }

    if (is_array($memberRecord))
      {
        $member_id  = $memberRecord["member_id"];
        $mid        = $memberRecord["member_id"];
        $refid      = $memberRecord["refid"];
        $affid      = $memberRecord["affiliate_id"];
        $firstname  = stripslashes($memberRecord["firstname"]);
        $lastname   = stripslashes($memberRecord["lastname"]);
        $email      = strtolower($memberRecord["email"]);

        $registered = $memberRecord["registered"];
        $confirmed  = $memberRecord["confirmed"];

        if ($registered > 0)
          {
            $newLocation = DOMAIN."/awbr_confirmed.php?custom_mid=$mid&mid=$mid";
            $locationHeader = "Location: ".$newLocation;
            header ($locationHeader);  // Redirect browser
            exit;
          }
        if ($confirmed > 0)
          {
            $newLocation = DOMAIN."/awbr_confirmed.php?custom_mid=$mid&mid=$mid";
            $locationHeader = "Location: ".$newLocation;
            header ($locationHeader);  // Redirect browser
            exit;
          }
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

<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/video_player.js"></script>

<LINK type=text/css rel=stylesheet href="/local-js/modal/subModal.css">
<script type="text/javascript" src="/local-js/modal/subModal.js"></script>
<script type="text/javascript">

function safe_list()
  {
    var url = "/pop-safelist.php";
    var wWidth  = 670;
    var wHeight = 600;

    var topmargin  = 0;
    var leftmargin = 0;

    var win=window.open(url,"SafeList",
       'width='+wWidth+',height='+wHeight+',top='+topmargin+',left='+leftmargin+
       ',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes');
  }

function re_enter()
  {
    showPopWin("Sign Up", "/dialog/register/signup.php?mid=<?php echo $mid?>", 510, 380, null);
  }


function initialize() {
  start_video('http://pds1106.s3.amazonaws.com/video/int/awaiting_confirmation.flv');
}
window.onload=initialize;

</script>
</head>

<body class=background topmargin=0>

<?php include("register_header.php"); ?>

  <!--------------------------------------------- START CONTENT ------------------------------------------------>

  <div style="display:">
    <form id="SignupForm" name="SignupForm" method="post" action="http://www.aweber.com/scripts/addlead.pl" onSubmit="return ValidateForm(this)">
      <input type="hidden" name="meta_web_form_id" value="401205923" />
      <input type="hidden" name="meta_split_id" value="" />
      <input type="hidden" name="listname" value="pushyads" />
      <input type="hidden" name="redirect" value="http://pushyads.com/awbr_thankyou.php" id="redirect_879c5988dd55641fc057fe180de827c4" />
      <input type="hidden" name="meta_adtracking" value="PushySignup" />
      <input type="hidden" name="meta_message" value="1" />
      <input type="hidden" name="meta_required" value="email,name (awf_first),name (awf_last)" />
      <input type="hidden" name="meta_forward_vars" value="1" />
      <input type="hidden" name="meta_tooltip" value="" />

      <input type="hidden" name="custom mid"       value="<?php echo $mid?>" />
      <input type="hidden" name="custom refid"     value="<?php echo $refid?>" />
      <input type="hidden" name="custom affid"     value="<?php echo $affid?>" />

      <input type="hidden" name="mid"       value="<?php echo $mid?>" />
      <input type="hidden" name="refid"     value="<?php echo $refid?>" />
      <input type="hidden" name="affid"     value="<?php echo $affid?>" />

      <input type="hidden" name="email" value='<?php echo $email?>' />
      <input type="hidden" name="name (awf_first)" value="<?php echo $firstname?>" />
      <input type="hidden" name="name (awf_last)" value="<?php echo $lastname?>"  />
    </form>
  </div>


  <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
    <tr>
      <td align=center style="padding: 20px 0 0 20px;"><img src="http://pds1106.s3.amazonaws.com/images/next.png"></td>
      <td rowspan=2 align=right valign=top style="padding-top: 90px;"><img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
      </td>
    </tr>



    <tr>
      <td>
        <table align=center border=0 cellpadding=0 cellspacing=0>
          <tr>
            <td class=videosmscreen width=440 height=270>

              <div id="vplayer" style="position:relative; top:-15px; left:38px; z-index:0; width:394px; height:220px; background:#000000"></div>

            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>


  <table align=center width=625 border=0 cellpadding=0 cellspacing=0>
    <tr>
      <td width=40 height=34 valign=top background="http://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
      <td width=587 height=34 valign=bottom class=boback></td>
    </tr>
    <tr>
      <td width=40 valign=top class=cellleft>&nbsp;</td>
      <td width=587 valign=top>

        <table align=left width=587 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0 class=largetext>
          <tr>
            <td valign=top>
              <div style="margin: -6px 35px 25px 35px ;">
              <div align=center class="size22 darkred"><b>Cool <?php echo $firstname?>! Now, check your email</b></div>

              <p align=center>
                 <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 just sent you a top secret message to:

                 <p align=center><b><?php echo $email?></b>

              <p style="margin: 40px 0;">
              <div align=center class="size22 darkred"><b>You MUST click on the confirmation link in the <br>email from us to gain access to the Next Step!</b></div>

              <p>
                 Yep, it's top secret, cuz it's meant for your eyes only. So go ahead and check your email,
                 then click on the link inside of it, so you can get to the next step.
              </p>

              <p>
                 If the email above isn't correct, then <a href=javascript:re_enter()><b>CLICK HERE</b></a> to enter the correct email address and try again.
              </p>

              <p style="margin-bottom: 40px">If you're absolutely sure the email above is correct, and you can't find our email in your "IN" box, then you'll need to check
                your "spam" or "junk" folder in your email program. Also, to protect yourself from this happening in the future,

                 <a href=javascript:safe_list()>click here</a>.

              </p>


              <?php
                if (IS_LOCAL || is_integer(strpos($email,"webtribune.com")))
                  {
              ?>
                    <p align=center>
                       User clicks on the following link in his email<br>
                       <a href="<?php echo DOMAIN."/confirmed.php?mid=$member_id"?>"><?php echo DOMAIN."/confirmed.php?mid=$member_id"?></a>
                    </p>
              <?php
                  }
              ?>


              </div>
            </td>
          </tr>
        </table>

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

  <!--------------------------------------------- END CONTENT ------------------------------------------------>

<?php include("register_footer.php"); ?>



<?php
  if (IS_LOCAL || is_integer(strpos($email,"webtribune.com")))
    {  }
  else
    {
?>

<script type="text/javascript">
    function prep_reqistration_request(mid)
      {
        var data={
                   mid: mid
                 }
        $.ajax({
           type:     "POST",
           url:      "prep_registration.php",
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
                       if (status == 0)
                         {
                            var theForm = document.forms["SignupForm"];
                            theForm.submit();
                         }
                       else
                         {
                           // alert("NOPE - status="+status);
                         }
                     }
        });
      }

    prep_reqistration_request('<?php echo $mid?>');
</script>

<?php
    }
?>

</body>
</html>
