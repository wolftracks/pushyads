<?php
include_once("initialize.php");

$firstname="";
$lastname="";
$email="";

/*------------------------------ The VALID CUSTOM FIELDS ------*/
//  [name] =>   Daffy Duck
//  [email] =>  tim4@yahoo.com
//  [mid] =>    dd1251004741946215
//  [affid] =>  1234-dx22
//  [refid] =>  tw234567890123456
//  [update] => false
/*-------------------------------------------------------------*/

$isEmailUpdate = false;

if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
    $db=getPushyDatabaseConnection();
    if (is_array($memberRecord=getMemberInfo($db,$mid)))
      {
        $member_id      = $memberRecord["member_id"];
        $firstname      = stripslashes($memberRecord["firstname"]);
        $lastname       = stripslashes($memberRecord["lastname"]);
        $confirmed      = $memberRecord["confirmed"];
        $registered     = $memberRecord["registered"];
        $email          = strtolower($memberRecord["email"]);
        $previous_email = strtolower($memberRecord["email"]);

        if ((isset($_REQUEST["update"]) && $_REQUEST["update"]=="true") &&
             isset($_REQUEST["email"])  && strlen($_REQUEST["email"])>0)
          {
            $isEmailUpdate = true;
            $email = strtolower($_REQUEST["email"]);
            $PUSHYSIGNIN=$email;
            setcookie("PUSHYSIGNIN",$PUSHYSIGNIN,time()+94608000,"/","",0);
          }

        if (($isEmailUpdate) || ($confirmed==0))
          {
            $sql = "UPDATE member set ";
            if ($isEmailUpdate)
              $sql .= " email='$email',";
            if ($confirmed==0)
              $sql .= " confirmed='".time()."'";
            else
              $sql .= " confirmed='".$confirmed."'";
            $sql .= " WHERE member_id='$member_id'";
            $result = mysql_query($sql,$db);
            if ($result)
              {
                // --- OK ----
              }
            else
              {
                $CONFIRMATION_FAILURE="Confirmation Failed - Please Report this Error";
                include("main.php");
                exit;
              }
          }


        if ($registered > 0)
          {
            include("already_registered_login.php");   // -------- Show Signin Screen ---------
            exit;
          }

      }
    else
     {
       $CONFIRMATION_FAILURE="Confirmation Failed - Confirmation ID Invalid";
       include("main.php");
       exit;
     }
  }
else
  {
    $CONFIRMATION_FAILURE="Unable to Process Confirmation - Confirmation ID Missing";
    include("main.php");
    exit;
  }


if (isset($_REQUEST["update"]) && $_REQUEST["update"]=="true")
  {
    // Email Address Updated
    $EMAIL_UPDATE_CONFIRMATION = $email;
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

<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/video_player.js"></script>

<script type="text/javascript">
function page_loaded() {
  if (pushy_SetScrollerLocation) pushy_SetScrollerLocation();
  start_video('http://pds1106.s3.amazonaws.com/video/int/confirmed.flv');
}
function page_unloaded() {
  if (pushy_SetScrollerLocation) pushy_SetScrollerLocation();
  start_video('http://pds1106.s3.amazonaws.com/video/int/confirmed.flv');
}
window.onload=page_loaded;
window.onunload=page_unloaded;
</script>
</head>

<body class=background topmargin=0>

<?php include("register_header.php"); ?>

  <!--------------------------------------------- START CONTENT ------------------------------------------------>
  <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
    <tr>
      <td style="padding: 20px 0 0 130px;"><img src="http://pds1106.s3.amazonaws.com/images/woohoo.png"></td>
      <td rowspan=3 align=right valign=top style="padding-top: 90px;">
        <img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
      </td>
    </tr>

    <tr>
      <td valign=top align=center><span class=tag2><i>You're almost there, <?php echo $firstname?></i></span>
        <p class=maintext align=center style="margin: 20px 15px 30px 40px;">
           See that clock ticking below <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" height=20 style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482? Well, that's all the time you have
           left to grab all the goodies below.
        </p>
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

        <table align=left width=587 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
          <tr>
            <td valign=top>
              <div style="margin: -6px 35px 25px 35px ;">

              <div align=center class="size18 darkred bold" style="margin-bottom: 30px;">IMPORTANT: You have not yet completed your
                <br>signup process! You MUST click on one of the <br>links at the bottom of this page <?php echo $firstname?>! </div>

              <div align=center class="size28 darkgreen"><b>WHAT GOODIES?</b></div>

              <p>
                 This is the part where <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 tells you how you need to decide
                 whether it's worth it to just stay a free <b>VIP</b> member (that's what you are now, a <b>V</b>ery <b>I</b>mportant <b>P</b>USHY member). Or, if you should
                 become a much more <b>ELITE</b> type of member where everyone looks at you and says, <i>"<b>WOW <?php echo $firstname?>, I sure wish I had what you
                 have</b>" <span class=size14>(Psssst, HINT HINT, I'll share a secret with you on how I use this clout to get an avalanch of sales)</i></span> </i>.
              </p>

              <p>
                 And here is where <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 begins to give you more compelling &
                 convincing evidence & testimony, with all the irresistable reasons why you want, and need to become an <b>ELITE</b> member, and what it will mean to you, your
                 family, your future, and your financial health. </p>

              <p>
                 See, as an <b>ELITE</b> member, here's just a fraction of what you will get:
              </p>


                 <ul style="list-style-image: url('http://pds1106.s3.amazonaws.com/images/checkmark-sm.jpg');">

                   <li style="padding: 0 0 10px 15px"><b>Unlimited exposure</b> for your products, services, blogs, and opportunities throughout
                     <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">'s <b class=darkred>Viral Traffic Network</b>.
                       <span class=size14><i>This network is made up of web sites all over the Internet with audiences whose interests match your type of products, services, or
                       opportunites</i></span>.

                   <li style="padding: 0 0 10px 15px"><b>Access to all his secret <span class=darkred>Viral Traffic</span></b> components (designed to generate a surge of relentless,
                     unstoppable traffic, and most importantly, highly qualified prospects to your web page(s). <span class=size14><i>This one will make your head spin, when you see how
                     ingenius they are set up.</i></span></li>

                   <li style="padding: 0 0 10px 15px"><b>50% Recurring Commissions</b> on all monthly memberships paid by members you refer to the Network.
                     <span class=size14><i>You'll be blown away when you see the high conversions on this puppy :)</i></span> </li>

                   <li style="padding: 0 0 10px 15px"><b>20% Recurring INFINITY Bonus</b> on all monthly memberships paid  by members you don't even know, or didn't even refer
                     <span class=size14><i>(described in detail inside the Commission Schedule). Ya baby! This one's BIG.</i></span></li>

                   <li style="padding: 0 0 10px 15px"><b>All these products to the right for <span class=darkred>FREE</span>.</b> <span class=size14><i>And as you can see,
                     these aren't your every day, run-of-the-mill, puffed-up-with-nothing-inside products. These have solid value & substance you can really sink your teeth in, and get
                     enormous use out of!</i></span></li>

                 </ul>

                 Want to know the truth? <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 ran out of bullets when
                 listing all the features and benefits for the <b>ELITE</b> membership. But frankly <?php echo $firstname?>, if all you got was the first bullet above, you would get
                 a ship load more value than what you're paying for.
              </p>

              <p>
                 Oh, sure, you can become an <b>ELITE</b> member later. But you won't get all this stuff on the right for <b class=darkred>FREE</b> (valued at over $XXX), unless
                 you make that decision RIGHT NOW.
              </p>

              <p>
                 Hey, I'm <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 What else would you expect from me?
                 Besides, if I can show you how being <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px">&#8482 is going to
                 increase your conversions, sales, revenues, profits, popularity, and sex life (OK, maybe not your sex life ;-), but wouldn't you want me to twist your arm? Umm, well,
                 ya, of course you would, right?
              </p>

              <p>
                 OK, enough with all this convincing, bribery, and creating of an offer so absolutely irresistable, you find yourself utterly helpless to refuse
                 <span class=size14><i>(especially since you already know it's going to be the best thing you will do for yourself, your family, and your future for years to come)</i></span>.
              </p>

              <p>
                Besides, the clock is ticking over there<?php echo $firstname?>, and just about to run out of time. I'm not going to tell you, "You deserve it" or any of the rest of that
                corny sales talk (even if it *IS* true :)

              <p>
                Just go ahead and do it!&nbsp;  Generate some <b>Viral Traffic!</b>&nbsp; Create some BIG <b>Residual
                Income!</b>&nbsp; <b class=darkred>Make your family proud!</b>&nbsp; And above all else,
                <b>Make <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 proud</b> :)&nbsp; ...Ready?&nbsp; Set?&nbsp; Let's go!
              </p>

              <p align=center class=size22 style="margin: 35px 0;">

                <a href="<?php echo SECURE_SERVER?>/order_elite.php?mid=<?php echo $mid?>"><b>I want to be an ELITE
                <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 member</b></a>
              </p>

              <p align=center style="margin-bottom: 45px;">
                <a href="givemevip.php?mid=<?php echo $mid?>">I'll kick myself later, but no thanks. Just give me minimal VIP access</a>
              </p>

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

      </td>

      <td valign=top>

        <table width=100% border=0 cellpadding=20 cellspacing=0 >
          <tr>
            <td valign=top align=center style="background-color: #FFFFFF; filter:alpha(opacity = 60);  -moz-opacity:0.6; opacity: 0.6; height:500px" onmouseover="this.style.opacity=9;this.filters.alpha.opacity=90" onmouseout="this.style.opacity=0.6;this.filters.alpha.opacity=60">

              <img src="http://pds1106.s3.amazonaws.com/images/temp-ebook19.jpg" width=170>
              <br>
              <img src="http://pds1106.s3.amazonaws.com/images/temp-ebook13.jpg" width=170>
              <p>
              <img src="http://pds1106.s3.amazonaws.com/images/temp-ebook15.jpg" width=170>
              <br>
              <img src="http://pds1106.s3.amazonaws.com/images/temp-ebook12.jpg" width=170>
              <p>&nbsp;</p>
              <img src="http://pds1106.s3.amazonaws.com/images/temp-ebook20.jpg" width=170>
              <br>
              <img src="http://pds1106.s3.amazonaws.com/images/temp-ebook3.jpg" width=170>
              <br>&nbsp;

            </td>
          </tr>

          <tr>
            <td>
              <div align=center style="margin-top: -880px; background-color: #E9EDF2; position: relative; width:259px; height: 278px; border: 1px solid #999999">
                <div style="padding: 15px">
                <span class="bold text">JV Products Highlighted</span>

                <p class=text align=left>A description for each individual product will be given, along with the JV Partner's name.

                <p class=text align=left>Value will be given to each product, hilighting it's features and benefits included as part of the <b>ELITE</b> membership package.

                <p class=text align=left>This sidebar will span the entire height of the sales page to the left, giving as much space as possible to each product.

                  </p>
                </div>
              </div>

           </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
  <!--------------------------------------------- END CONTENT ------------------------------------------------>

<?php include("register_footer.php"); ?>
<script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f958e8ce1f1a881fe6bc12d1f8c23633.js?mid=<?php echo $mid?>"></script>
</body>
</html>
