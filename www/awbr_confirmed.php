<?php
include_once("initialize.php");

//------------------------------------------------------------------------------------------
// $msg  = "-- CONFIRMED --<br><br>";
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
// exit;
//------------------------------------------------------------------------------------------

$firstname="";
$lastname="";
$email="";

/*------------------------------ The VALID FIELDS ------*/
// email=tim5@ibmt.net
// custom_mid=kyg1617
// custom_affid=1618-cz92
// custom_refid=paw1200
/*------------------------------------------------------*/

$mid="";
$registered=0;
$isEmailUpdate = false;
if (isset($_REQUEST["custom_mid"]))
  {
    $mid=$_REQUEST["custom_mid"];
  }
else
if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
  }

if (strlen($mid)>0)
  {
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
        if (isset($_REQUEST["email"])  && strlen($_REQUEST["email"])>0)
            $email = strtolower($_REQUEST["email"]);

        if ($registered>0)
          {
            if ($email != $previous_email || (is_integer(strpos($_REQUEST["PUSHYSIGNIN"],"@")) && $email!=strtolower($_REQUEST["PUSHYSIGNIN"])) )
              {
                $isEmailUpdate = true;
                $PUSHYSIGNIN=$email;
                setcookie("PUSHYSIGNIN",$PUSHYSIGNIN,time()+94608000,"/","",0);
              }
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
            if ($isEmailUpdate)
              {
                // Email Address Updated
                $EMAIL_UPDATE_CONFIRMATION = $email;
                include("main.php");
                exit;
              }

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


if ($isEmailUpdate)
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
           See that clock ticking below <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" height=20 style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482? 
           Well, that's all the time you have left to grab the goodies below.
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
      <td valign=top>


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
                 This is where we tell you about an opportunity to increase your financial position, if you become an <b>ELITE</b> 
                 <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 member, instead of a FREE 
                 <b>VIP</b> member. <span class=size14><i>(ELITE members get more ads, more traffic, more exposure, and well, there's much more... read on...)</i></span> </i>.
              </p>

              <p>
                 Besides all it will mean to your and your family when your business starts succeding once you start getting the one ingredient you've been missing (<i>inexpensive 
                 targeted traffic</i>), here is a list of all the compelling and convincing reasons you need to become an <b>ELITE</b> member. </p>

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
                 Oh, sure, you can become an <b>ELITE</b> member later. But you won't get all these bonuses on the right for <b class=darkred>FREE</b> (<b class=darkred><i>valued at over 
                 $1,271</b></i>), unless you make that decision RIGHT NOW.
              </p>

              <p>
                 Hey, I'm <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 What else would you expect from me?
                 Besides, if I can show you how being <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px">&#8482 is going to
                 increase your conversions, sales, revenues, profits, popularity, and sex life (OK, maybe not your sex life ;-), but wouldn't you want me to twist your arm? Umm, well,
                 ya, of course you would, right?
              </p>

              <p>
                Besides, I'm giving you a <b class=darkred>100% Iron Clad money back Guarantee</b> (scroll down below). So you have nothing to lose, and everything to gain. Just go ahead and do 
                it!&nbsp;  Generate some <b>Viral Traffic!</b>&nbsp; Create some BIG <b>Residual Income!</b>&nbsp; <b class=darkred>Make your family proud!</b>&nbsp;  ...Ready?&nbsp;                  Set?&nbsp; Let's go!
              </p>

              <p align=center class=size22 style="margin: 35px 0;">

                <a href="<?php echo SECURE_SERVER?>/order_elite.php?mid=<?php echo $mid?>"><b>I want to be an ELITE
                <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 member</b></a>
              </p>

              <p align=center style="margin-bottom: 20px;">
                <a href="givemevip.php?mid=<?php echo $mid?>">I'll kick myself later, but no thanks. Just give me minimal VIP access</a>
              </p>





             <br><div align=center  style="margin: -5px 0 25px;"> . . . . . . . . </div>




            <p align=center class="size20 bold Verdana"><i>Here's What You Get for FREE...</i></p>

            <div align=center class="size32 bold Verdana darkred">$1,271 Worth of Bonuses</div>

            <p align=center class=bold>Included with your ELITE Membership</p>  

              <table align=center width=500 cellpadding=0 cellspacing=0 style="margin-top: 30px; border: 4px solid #C6CC9D" class=size20 background="http://pds1106.s3.amazonaws.com/images/100-bg-450.gif">
                <tr>
                  <td align=center>

                    <table align=center width=90% border=0 cellpadding=5 cellspacing=0 class=size20 style="margin: 10px 0;">
                      <tr height=50>
                        <td>&nbsp;</td>
                        <td class=bold>List of BONUSES</td>
                        <td align=right class=bold>Value</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Your Video Course</i>
                          <span class=size14>
                          <br>10 videos, 10 audios, 2 PDF, MRR, etc
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$197</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Viral Traffic 4U</i>
                          <span class=size14>
                          <br>Lifetime Platinum Membership
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$540</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Web Audio Plus</i>
                          <span class=size14>
                          <br>Audio software comes with MRR
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$37</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Blog Buzz </i>
                          <span class=size14>
                          <br>Blog finding software comes with MRR
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$37</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Simple Online Business </i>
                          <span class=size14>
                          <br>Business building system comes with MRR
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$47</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Triple Your Conversions </i>
                          <span class=size14>
                          <br>28 proven strategies with MRR
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$57</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Traffic 2.0</i>
                          <span class=size14>
                          <br>Web 2.0 traffic building system
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$47</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Tube Traffic Secrets</i>
                          <span class=size14>
                          <br>Harness a piece of Youtube's 75 million visitors
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$37</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Bum Marketing Magic</i>
                          <span class=size14>
                          <br>Article Marketing System + PLR
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$67</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>8 Min Lead Capture Pages</i>
                          <span class=size14>
                          <br>Software that generates Lead Capture Pages
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$37</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Article Submitter</i>
                          <span class=size14>
                          <br>Software that Submits Articles to 85+ Directories
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$47</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Article Directory List</i>
                          <span class=size14>
                          <br>212 Article Directories with PR + Alexa Ranking
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$27</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>Directory Submitter</i>
                          <span class=size14>
                          <br>Software that Submits your site to all top Directories
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$47</td>
                      </tr>

                      <tr height=60>
                        <td valign=middle>&nbsp;</td>
                        <td valign=middle><i class="size24 darkred bold">TOTAL BONUS VALUE  =</i></td>
                        <td align=right valign=middle class="darkred bold size24" style="border-top: 2px solid #CC0000;">$1,271</td>
                      </tr>

                    </table>

                  </td>
                </tr>
              </table>
       <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=480 height=31></div>

        <!-------------------------- START ORDER FORM ------------------------->
        <div align=left style="margin: 25px 0  0 10px; border: 3px dashed #CC0000; width: 492; background-color: #FFFAF0;">

          <div align=left style="padding: 15px;" class="darkred size18">
            <input type=checkbox checked>
            <i><b>YES!</b> I see the huge value, and want to take full advantage of all these bonuses and Pushy Technology to start driving targeted traffic to my website <b>Right Now</b>!</i>
          </div>

          <div align=center style="margin-bottom: 35px"><a href="<?php echo SECURE_SERVER?>/order_elite.php?mid=<?php echo $mid?>">
            <img src="http://pds1106.s3.amazonaws.com/images/elite_button.png" style="margin-top: 10px"></a>
            <span class="size20 bold black"><i>Only $97 p/mo</i></span>
          </div>

          <div align=center><img src="http://pds1106.s3.amazonaws.com/images/cc.jpg"></div>

          <div style="margin: 25px 0 25px 60px; padding: 15px; height: 286px; background-image: url('http://pds1106.s3.amazonaws.com/images/guarantee.jpg'); background-repeat: no-repeat;">
            <div style="padding: 30px 0 8px 120px;" class="times size28 bold">GUARANTEE</div>
            <div style="padding-left:120px ; padding-right: 30px; max-width: 200px" class=size14>We guarantee your complete satisfaction with your PUSHY membership, or we will give you </div>
            <div style="margin-left: 37px; width: 280px; " class=size14>100% of your money back, no questions asked. 
              You get to keep all the bonuses, along with any MRR or PLR rights with them. Just let us know within 30 days of your purchase, and we will honor your request.
              That's how confident we are in the power of PUSHY!
            </div>
          </div>
        </div>

       <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=480 height=31></div>

        <!-------------------------- END ORDER FORM ------------------------->

        <p align=center style="margin: 40px 0 50px;"><b><a href="givemevip.php?mid=<?php echo $mid?>">
          No Thanks! Just give me the minimal Free VIP access for now</a></b>
        </p>



       <p align=right style="margin: 130px 0" class="red bold">Look at all these bonuses you get for FREE
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">Huge value with your ELITE membership 
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">You get $1,271 worth of Bonues for FREE
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">All 14 bonuses, 10 videos, 10 MP3s 
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">You can start using everything Right Now
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">All the tools you need in your business
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">$1,271 in valuable bonuses for FREE
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">MRR & PLR rights enable you to sell them
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>

  
       <p align=right style="margin: 190px 0" class="red bold">Make money on all these FREE bonuses
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold"> Look at all you get for FREE
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>


       <p align=right style="margin: 190px 0" class="red bold">Get started Now for BIG profits tomorrow
         <img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble_r.gif" width=100 style="vertical-align: middle"></p>

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
            <td valign=top style="background-color: #FFFFFF; filter:alpha(opacity = 60);  -moz-opacity:0.6; opacity: 0.6; height:500px" onmouseover="this.style.opacity=9;this.filters.alpha.opacity=90" onmouseout="this.style.opacity=0.6;this.filters.alpha.opacity=60">


              <!------------------- BONUS #1 -------------------->
              <div align=center class="size12 black"><img src="http://pds1106.s3.amazonaws.com/images/yvc_260.jpg" width=210>
                 <p class="impact size16 darkred">BONUS #1 </p>
                 <p><b class="Verdana size14 darkred">Your Video Course</b> <br><i>"A Blueprint To Building Your Own Online Business"</i>, by Ben Hulme
              </div>
  
              <p class="Verdana size12">This top rated video course includes:
  
                <ul class="Verdana size12" align=left>
                  <li>2 PDF ebooks 
                  <li>10 training videos (over 3 1/2 hrs)
                  <li>10 MP3 training audios
                  <li>6 sets of professional graphics 
                  <li>Professionally designed website
                  <li>Professional sales letter 
                  <li>Full Master Resell Rights 
                  </li>
                </ul>
            </p>

            <p class="verdana size14 bold darkred" align=center>Bonus #1 Value = $197</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #2 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/viral-traffic-4u.jpg" width=150>
                 <br><span class="impact size16 darkred">BONUS #2 </span>
                 <p><b class="Verdana size14 darkred">Viral Traffic 4U</b> <br><span class="verdana darkred">(<i>Lifetime Platinum Membership</i>)
              </div>

              <p class="Verdana size12 black">This incredible viral traffic system gives you tools and exposure as a Platinum Member, that will generate hords of traffic for any website, 
                including your <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png"> affiliate website. 

              <p class="verdana size14 bold darkred" align=center>Bonus #2 Value = $540</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #3 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/web-audio-plus.gif" width=140>
                 <p><span class="impact size16 darkred">BONUS #3 </span>
                 <p><b class="Verdana size14 darkred">Web Audio Plus</b> <br><span class="verdana darkred">(<i>with Master Resale Rights</i>)
              </div>

              <p class="Verdana size12 black">Add streaming audio to your website without the hassle of monthly fees. Easy to use and install audio buttons and recordings,
                using your own personal touch.

            <p class="verdana size14 bold darkred" align=center>Bonus #3 Value = $37</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #4 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/blogbuzz.gif" width=160>
                 <br><span class="impact size16 darkred">BONUS #4 </span>
                 <p><b class="Verdana size14 darkred">Blog Buzz</b> <br><span class="verdana darkred">(<i>with Master Resale Rights</i>)
              </div>

              <p class="Verdana size12 black">Blog finding software helps build backlinks on blogs with high PR and Alexa ranking, boosting your link building & SEO efforts instantly 
                in any niche.

            <p class="verdana size14 bold darkred" align=center>Bonus #4 Value = $37</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #5 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/plr-voodoo.jpg" width=150>
                 <br><span class="impact size16 darkred">BONUS #5 </span>
                 <p><b class="Verdana size14 darkred">PLR Voodoo</b> <br><span class="verdana darkred">(<i>with Master Resale Rights</i>)
              </div>

              <p class="Verdana size12 black">Learn everything you need to know about Private Label Rights, and how they can make a newbie look like an Internet Marketing guru with 
                very little effort.

            <p class="verdana size14 bold darkred" align=center>Bonus #5 Value = $47</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #6 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/simple-online-business.jpg" width=150>
                 <br><span class="impact size16 darkred">BONUS #6 </span>
                 <p><b class="Verdana size14 darkred">Simple Online Business</b> <br><span class="verdana darkred">(<i>with Master Resale Rights</i>)
              </div>

              <p class="Verdana size12 black">An incredibly valuable guide to setting up an Internet business, with 92 pages chock full of priceless tips and resources. Worth a month's 
                membership all by itself.

            <p class="verdana size14 bold darkred" align=center>Bonus #6 Value = $47</p>

            <hr style="margin: 20px 10px;">

              <!------------------- GUARANTEE -------------------->
              <div align=center class="bold black"><i>Check Out Your</i>
                <p><span class="size30 verdana darkred">No Risk </span>
                <p class="bold verdana size32 black">GUARANTEE</p>
              </div>

              <div align=center><img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble.gif"></div> 

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #7 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/triple-your-conversions.jpg" width=140>
                 <br><span class="impact size16 darkred">BONUS #7 </span>
                 <p><b class="Verdana size14 darkred">Triple Your Conversions</b> <br><span class="verdana darkred">(<i>with Master Resale Rights</i>)</span>
              </div>

              <p class="Verdana size12 black">28 proven ways to skyrocket signups and sales conversions almost overnight. Secret persuasion techniques that will triple your signups, 
                subscriptions, and orders.

            <p class="verdana size14 bold darkred" align=center>Bonus #7 Value = $57</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #8 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/traffic20.jpg" width=150>
                 <br><span class="impact size16 darkred">BONUS #8 </span>
                 <p><b class="Verdana size14 darkred">Traffic 2.0</b>
              </div>

              <p class="Verdana size12 black">Drive traffic to your website, using next generation techniques. Full of resources, links, illustrations, and Web 2.0 properties that bring 
                eyeballs to your website. 

            <p class="verdana size14 bold darkred" align=center>Bonus #8 Value = $47</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #9 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/tube-traffic-tactics.jpg" width=140>
                 <br><span class="impact size16 darkred">BONUS #9 </span>
                 <p><b class="Verdana size14 darkred">Tube Traffic Secrets</b> 
              </div>

              <p class="Verdana size12 black">Learn a host of techniques for harnessing a piece of Youtube's 75 million visitors a month. You'll be amazed at the simplicity and speed that even newbies can perform. 

            <p class="verdana size14 bold darkred" align=center>Bonus #9 Value = $37</p>

            <hr style="margin: 20px 10px;">




              <!------------------- BONUS #10 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/bum-marketing-magic.jpg">
                 <br><span class="impact size16 darkred">BONUS #10 </span>
                 <p><b class="Verdana size14 darkred">Bum Marketing Magic</b> <br><span class="verdana darkred">(<i>with PLR rights</i>)
              </div>

              <p class="Verdana size12 black">Start making money from scratch using the "Bum Marketing Method" (a dummy proof variation of article marketing) to sell products through 
                a number of traffic generating strategies. 

            <p class="verdana size14 bold darkred" align=center>Bonus #10 Value = $67</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #11 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/pagegenerator.gif" width=140>
                 <br><span class="impact size16 darkred">BONUS #11 </span>
                 <p><b class="Verdana size14 darkred">8 Minute Capture Page Generator</b> 
              </div>

              <p class="Verdana size12 black">Simple, fill-in-the-blanks template that creates a lead capture page in html within minutes. Just upload to your server and start capturing names 
                and email addresses for your promotions. 

            <p class="verdana size14 bold darkred" align=center>Bonus #11 Value = $37</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #12 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/article-submit.jpg" width=170>
                 <br><span class="impact size16 darkred">BONUS #12 </span>
                 <p><b class="Verdana size14 darkred">Article Submitter</b> 
              </div>

              <p class="Verdana size12 black">Download the software and start submitting your articles to 85 directories minutes from now. Tested & proven to be the most effective way to 
                increase popularity in search engines. Speeds up process immensely.

            <p class="verdana size14 bold darkred" align=center>Bonus #12 Value = $47</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #13 -------------------->
              <div class="size12 black"><img src="http://pds1106.s3.amazonaws.com/images/article-list.gif" style="float: left; margin-right: 12px;">
                 <span class="impact size16 darkred">BONUS #12 </span>
                 <p><b class="Verdana size14 darkred">Article Directory List</b> 
              </div>

              <p class="Verdana size12 black">Swipe our secret list of 212 article directories, listing their Google page rank (PR), and Alexa ranking. The higher the ranking for the article directories 
                your articles are on, the better your site will rank in search engines.

            <p class="verdana size14 bold darkred" align=center>Bonus #13 Value = $27</p>

            <hr style="margin: 20px 10px;">

              <!------------------- BONUS #14 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/directory-submit.jpg" width=170>
                 <br><span class="impact size16 darkred">BONUS #14 </span>
                 <p><b class="Verdana size14 darkred">Directory Submitter</b> 
              </div>

              <p class="Verdana size12 black">Download this cutting edge software and start submitting your website to high traffic directories. This will increase your backlinks, Google page 
                rank, and search engine rankings for your websites.

              <p class="verdana size14 bold darkred" align=center>Bonus #14 Value = $47</p>

              <hr style="margin: 20px 10px;">



            </td>
          </tr>

          <!---------------------------------------------tr>
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
          </tr -------------------------------------------->
        </table>

      </td>
    </tr>
  </table>



  <!--------------------------------------------- END CONTENT ------------------------------------------------>

<?php include("register_footer.php"); ?>
<script type="text/javascript" src="<?php echo PUSHYWIDGETS?>/control/f958e8ce1f1a881fe6bc12d1f8c23633.js?mid=<?php echo $mid?>"></script>
</body>
</html>
