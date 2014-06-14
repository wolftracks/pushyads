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
        $member_id=$memberRecord["member_id"];
        $firstname=stripslashes($memberRecord["firstname"]);
        $lastname =stripslashes($memberRecord["lastname"]);
        $email    =$memberRecord["email"];
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

<script type="text/javascript" src="/local-js/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="/local-js/video_player.js"></script>

<script type="text/javascript">
function page_loaded() {
  if (pushy_SetScrollerLocation) pushy_SetScrollerLocation();
  start_video('http://pds1106.s3.amazonaws.com/video/int/givemevip.flv');
}
function page_unloaded() {
  if (pushy_SetScrollerLocation) pushy_SetScrollerLocation();
  start_video('http://pds1106.s3.amazonaws.com/video/int/givemevip.flv');
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
      <td align=center style="padding: 20px 0 0 0;"><img src="http://pds1106.s3.amazonaws.com/images/noway.png"></td>
      <td rowspan=3 align=right valign=top style="padding-top: 90px;">
        <img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
      </td>
    </tr>

    <tr>
      <td valign=top align=center><span class=tag2><i>Last chance, <?php echo $firstname?></i></span>
        <p class=maintext align=center style="margin: 20px 15px 30px 42px;">
           If $97 is a bit spendy for your pocket book right now, there's good news (see below)! 
           Just remember, the clock is still ticking over there!
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
            <div align=center class="size28 darkgreen"><b>Less Costly Option</b></div>

            <p>
               So here's the deal <?php echo $firstname?>. I realize you can't beat <b class=darkred>FREE</b>! Afterall, if you can get a <b class=darkred>FREE</b> <b>VIP</b>
               <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 membership for 30 days, why not 
                 take advantage of it right? That's a great question. But here's a better one...
            </p>

            <p>
               If most serious <b>VIP</b> members eventually upgrade to <b>PRO</b> or <b>ELITE</b> members anyway (because of the incredible results they get from the
               <b class=darkred>Viral Traffic Network</b>), does it make any sense to give up all these great products when you can make that decision <b>NOW</b> instead of later?
            </p>

            <p>
               <!-- Listen <?php echo $firstname?> ...and here's where <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482                starts to really get pushy (but in a lovable way, of course ;-)...----> If it's just money holding you back from getting the <b>ELITE</b> membership, then heck,
            </p>

            <p align=center class="size24 darkred bold" style="padding-top: 10px">Knock 50 Bucks off <br><span class="size16 black">Your Monthly Membership cost!</span></p>

            <p align=center><img src="http://pds1106.s3.amazonaws.com/images/47.gif">
              <br><span class=size18>(instead of <sup style="vertical-align: 5px">$</sup>97)</span></p>


            <p style="margin-top: 40px"">
               Here's what <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 will do for you....
            </p>

            <p>
               He'll give you all the bonuses an <b>ELITE</b> member would get except: 

               <ul class=size14>
                 <li> Viral Traffic 4U (Lifetime Platinum Membership)
                 <li> Web Audio Plus (with Master Resale Rights)
                 <li> Blog Buzz (with Master Resale Rights)
                 <li> PLR Voodoo (with Master Resale Rights)
                 <li> Simple Online Business (with Master Resale Rights)
                 <li> Triple Your Conversions (with PLR rights)
                 <li> Tube Traffic Tactics
                 <li> Traffic 2.0
                 </li>
                </ul>

            <div align=right class="size18 darkred bold" style="padding-top: 10px; margin: 20px 0">You'll still get all the bonuses to the right
               <img src="http://pds1106.s3.amazonaws.com/images/arrow-anim-r.gif" style="vertical-align: -5px;">
            </div>

            <div align=center><img src="http://pds1106.s3.amazonaws.com/images/422worth.gif" style="margin-bottom: 15px;"></div>


            <p>
               Obviously, you won't have a lot of the benefits an <b>ELITE</b> member would
               have, like the <b>INFINITY Bonus</b>, or all the <b class=darkred>Viral Traffic</b> components, but hey, you get some... and definitely enough to create some traffic and
               moolah (lots of it too).
            </p>

            <p>
               So there you go! Case closed! Nuf said! If there's still some time left on the clock over there and you want to watch the
               <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 videos again to make absolutely sure you're 
                 making the right decision, then hurry up and go watch 'em again. Otherwise, it's that time <?php echo $firstname?>!
            </p>

            <p>
               Yep! It's that exciting & climactic time that you've been waiting for with bated breath and racing heart
               <span class=size14><i>(Ya, I know, <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 has a 
                 way of doing that to people :)</i></span>  as you sit anxiously on the edge of your seat, waiting for me to shut up so you can just go ahead and do it! OK, I'll shut up. I'm not 
                 going to hold you back a minute longer. Go ahead <?php echo $firstname?>! <b>The choice is simple!</b>
            </p>

              <p align=center class=size20 style="margin: 35px 0;">
                <a href="<?php echo SECURE_SERVER?>/order_pro.php?mid=<?php echo $member_id?>"><b>You're right, make me a PRO
                <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 member</b></a></p>

              <p align=center class=size20 style="margin-bottom: 45px;">
                <a href="<?php echo SECURE_SERVER?>/order_elite.php?mid=<?php echo $member_id?>"><b>I want to be an ELITE
                <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 member instead</b></a></p>

              <p align=center><a href="login.php?mid=<?php echo $member_id?>">
               Just give me the minimal Free VIP access for now</a>
             </p>



             <br><div align=center  style="margin: -5px 0 25px;"> . . . . . . . . </div>




            <p align=center class="size20 bold Verdana"><i>Here's What You Get for FREE...</i></p>

            <div align=center class="size32 bold Verdana darkred">$422 Worth of Bonuses</div>

            <p align=center class=bold>Included with your PRO Membership</p>  

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
                        <td valign=top><i class=darkred>Bum Marketing Magic</i>
                          <span class=size14>
                          <br>Article Marketing System + PLR
                          </span> 
                        </td>
                        <td align=right valign=top class=darkred>$67</td>
                      </tr>

                      <tr height=60>
                        <td valign=top><img src="http://pds1106.s3.amazonaws.com/images/bullet_x.png"></td>
                        <td valign=top><i class=darkred>8 Min Lead Capture Page Generator</i>
                          <span class=size14>
                          <br>Lead Capture Page Generating Software
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
                        <td valign=middle><i class="size24 darkred bold">TOTAL BONUS VALUE &nbsp;&nbsp; =</i></td>
                        <td align=right valign=middle class="darkred bold size24" style="border-top: 2px solid #CC0000;">$422</td>
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

          <div align=center style="margin-bottom: 35px"><a href="<?php echo SECURE_SERVER?>/order_pro.php?mid=<?php echo $member_id?>">
            <img src="http://pds1106.s3.amazonaws.com/images/pro_button.png" style="margin-top: 10px"></a>
            <span class="size20 bold black"><i>Only $47 p/mo</i></span>
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

        <p align=center style="margin: 60px 0 40px;"><a href="javascript: history.go(-1)">
          <b>Let me look at the ELITE bonuses again</b></a>
        </p>

        <p align=center style="margin: 40px 0 50px;"><a href="login.php?mid=<?php echo $member_id?>">
          Just give me the minimal Free VIP access for now</a>
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

            <hr width=90% style="margin: 20px 0;">

              <!------------------- BONUS #2 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/bum-marketing-magic.jpg" >
                 <br><span class="impact size16 darkred">BONUS #2 </span>
                 <p><b class="Verdana size14 darkred">Bum Marketing Magic</b> <br><span class="verdana darkred">(<i>with PLR rights</i>)
              </div>

              <p class="Verdana size12 black">Start making money from scratch using the "Bum Marketing Method" (a dummy proof variation of article marketing) to sell products through 
                a number of traffic generating strategies. 

            <p class="verdana size14 bold darkred" align=center>Bonus #2 Value = $67</p>

            <hr width=90% style="margin: 20px 0;">

              <!------------------- BONUS #3 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/pagegenerator.gif" width=140>
                 <br><span class="impact size16 darkred">BONUS #3 </span>
                 <p><b class="Verdana size14 darkred">8 Minute Capture Page Generator</b> 
              </div>

              <p class="Verdana size12 black">Simple, fill-in-the-blanks template that creates a lead capture page in html within minutes. Just upload to your server and start capturing names 
                and email addresses for your promotions. 

            <p class="verdana size14 bold darkred" align=center>Bonus #3 Value = $37</p>

            <hr width=90% style="margin: 20px 0;">

              <!------------------- BONUS #4 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/article-submit.jpg" width=170>
                 <br><span class="impact size16 darkred">BONUS #4 </span>
                 <p><b class="Verdana size14 darkred">Article Submitter</b> 
              </div>

              <p class="Verdana size12 black">Download the software and start submitting your articles to 85 directories minutes from now. Tested & proven to be the most effective way to 
                increase popularity in search engines. Speeds up process immensely.

            <p class="verdana size14 bold darkred" align=center>Bonus #4 Value = $47</p>

            <hr width=90% style="margin: 20px 0;">

              <!------------------- BONUS #5 -------------------->
              <div class="size12 black"><img src="http://pds1106.s3.amazonaws.com/images/article-list.gif" style="float: left; margin-right: 12px;">
                 <span class="impact size16 darkred">BONUS #5 </span>
                 <p><b class="Verdana size14 darkred">Article Directory List</b> 
              </div>

              <p class="Verdana size12 black">Swipe our secret list of 212 article directories, listing their Google page rank (PR), and Alexa ranking. The higher the ranking for the article directories 
                your articles are on, the better your site will rank in search engines.

            <p class="verdana size14 bold darkred" align=center>Bonus #5 Value = $27</p>

            <hr width=90% style="margin: 20px 0;">

              <!------------------- BONUS #6 -------------------->
              <div class="size12 black" align=center><img src="http://pds1106.s3.amazonaws.com/images/directory-submit.jpg" width=170>
                 <br><span class="impact size16 darkred">BONUS #6 </span>
                 <p><b class="Verdana size14 darkred">Directory Submitter</b> 
              </div>

              <p class="Verdana size12 black">Download this cutting edge software and start submitting your website to high traffic directories. This will increase your backlinks, Google page 
                rank, and search engine rankings for your websites.

              <p class="verdana size14 bold darkred" align=center>Bonus #6 Value = $47</p>

              <hr width=90% style="margin: 20px 0 40px;">

              <!------------------- GUARANTEE -------------------->
              <div align=center class="bold black"><i>Check Out Your</i>
                <p><span class="size30 verdana darkred">No Risk </span>
                <p class="bold verdana size32 black">GUARANTEE</p>
              </div>

              <div align=center><img src="http://pds1106.s3.amazonaws.com/images/arrow_scribble.gif"></div> 

              <div align=center style="margin-top: 35px;"><img src="http://pds1106.s3.amazonaws.com/images/as_seen_on.gif"></div> 

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
