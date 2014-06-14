<?php
 $data="";
 $awardContent = loadMemberAwards($db, $mid);
 if (is_array($awardContent))
   {
     // ksort($awardContent);
     $awardContent = sortAwards($awardContent, "ADD");

     if (FALSE)    //---- List AWARD Numbers Out Front ...  in their Display Order
       {
          /*----- Debug ------*/
          $j=0;
          foreach($awardContent AS $award => $content)
            {
              $j++;
              $data .= "$j) Award: $award <br>";
            }
          $data .= "\n&nbsp;<br>\n";
          $data .= "\n&nbsp;<br>\n";
          /*----- Debug ------*/
       }

     foreach($awardContent AS $award => $content)
       {
         $data .= $content;
         $data .= "\n&nbsp;<br>\n";
       }
   }

  $firstname           = trim(stripslashes($memberRecord["firstname"]));
  $lastname            = trim(stripslashes($memberRecord["lastname"]));
  $affiliate_id        = $memberRecord["affiliate_id"];
  $affiliate_website   = DOMAIN."/".$affiliate_id;
?>

<b class=size22>Training, Videos, Downloads, & Other Stuff</b>

<p>



<iframe name="DOWNLOAD_CENTER" style="height:0px; width:0px; display:none"></iframe>

<table width=702 border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td>

    <!------------------------ INTRODUCTION & GOODIES ------------------------>

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width: 702px; ">
      <a href="#" rel="toggle[intro]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png"  style="vertical-align: -6px; margin:3px 10px 0"></a>
      <a href="javascript:animatedcollapse.toggle('intro')" style="text-decoration: none;" class="largetext bold">Introduction & Goodies</a>
    </div>

    <div id="intro" align=right>
    <div style="width: 100%; height: 380px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width=100% border=0 cellspacing=0 cellpadding=20>
        <tr>
          <td align=left class="text black">

            <?php
             echo $data;
            ?>

          </td>
        </tr>
      </table>

    </div>
    </div>

    <!-- ---------------------- TRAINING ------------------------

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width: 702px; ">
      <a href="#" rel="toggle[training]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png"  style="vertical-align: -6px; margin:3px 10px 0"></a>
      <a href="javascript:animatedcollapse.toggle('training')" style="text-decoration: none;" class="largetext bold">Training</a>
    </div>

    <div id="training" align=right>
    <div style="width: 100%; height: 380px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width=100% border=0 cellspacing=0 cellpadding=20 bgcolor=#000000>
        <tr>
          <td align=left class="text black">

            <div align=center style="margin: 40px 0;"<img src="http://pds1106.s3.amazonaws.com/video/coming-soon.png"></div>

          </td>
        </tr>
      </table>

    </div>
    </div -------------------------------------------------------------->

    <!------------------------ AFFILIATE BANNER ADS ------------------------>

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width: 702px; ">
      <a href="#" rel="toggle[banners]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png" style="vertical-align: -6px; margin:3px 10px 0" /></a>
      <a href="javascript:animatedcollapse.toggle('banners')" style="text-decoration: none;" class="largetext bold">Affiliate Banner Ads</a>
    </div>

    <div id="banners" align=right>
    <div style="width: 100%; height: 480px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width=100% border=0 cellspacing=0 cellpadding=20>
        <tr>
          <td class="text">


  Below are animated banner ads for grabbing the attention of your site visitors, and can be placed on any website or blog, according to our
  <a href=javascript:openPopup('/pop-terms.php',660,700)>Terms of Use</a> and
<a href=javascript:openPopup('/pop-copyright.php',660,700)>Copyright</a>.

  <p>Your PUSHY affiliate link is already coded in the snippet below each banner. All you need to do is click on the snippet, then copy and paste the code onto your blog
    or website, and you're all set to go.

<br>&nbsp;

<table width="100%" cellpadding=0 cellspacing=0 border=0">

  <tr valign="top" height=70>
    <td colspan=3><div align=center class=bold style="width: 100%; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">81x31</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/88x31.gif" style="vertical-align: -2px;">&nbsp;
      <textarea  style="height: 31; width: 530; overflow: auto; font: 9px arial;" onClick=javascript:this.select()><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/88x31.gif" border=0></a></textarea>
    </td>
  </tr>

  <tr valign="top" width=100%>
    <td width=140><div align=center class=bold style="width: 120px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">120x60</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/120x60.gif" style="vertical-align: -3px;">
      <textarea  name="field" onClick=javascript:this.select() style="height: 110; width: 120; overflow: auto; font: 9px arial; margin-top: 5px;"><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/120x60.gif" border=0></a></textarea>

      <p>
      <div align=center class=bold style="width: 120px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">120x240</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/120x240.gif" style="vertical-align: -3px;">
      <textarea onClick=javascript:this.select()  style="height: 120; width: 120; overflow: auto; font: 9px arial; margin-top: 5px; margin-bottom: 20px;"><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/120x240.gif" border=0></a></textarea>

    </td>

    <td align=center><div align=center class=bold style="width: 250px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">250x250</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/250x250.gif" style="vertical-align: -3px;">
      <textarea onClick=javascript:this.select()   style="height: 60; width: 250; overflow: auto; font: 9px arial; margin-top: 5px;"><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/.gif" border=0></a></textarea>

      <p><div align=center class=bold style="width: 234px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">234x60</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/234x60.gif" style="vertical-align: -3px;">
      <textarea onClick=javascript:this.select()   style="height: 55; width: 234; overflow: auto; font: 9px arial; margin-top: 5px;"><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/234x60.gif" border=0></a></textarea>
    </td>

    <td align=right><div align=center class=bold style="width: 240px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">240x480</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/240x480.gif" style="vertical-align: -3px;">
      <textarea onClick=javascript:this.select()   style="height: 60; width: 240; overflow: auto; font: 9px arial; margin-top: 5px;"><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/240x480.gif" border=0></a></textarea>
    </td>

  </tr>

  <tr valign="top" height=155>
    <td colspan=3><div align=center class=bold style="width: 642px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">728x90</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/728x90.gif" style="vertical-align: -2px; width: 642; margin-bottom: 5px">
      <textarea onClick=javascript:this.select()   style="height: 31; width: 642px; overflow: auto; font: 9px arial; "><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/728x90.gif" border=0></a></textarea>
    </td>
  </tr>

  <tr valign="top">
    <td ><div align=center class=bold style="width: 125px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">125x125</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/125x125.gif" style="vertical-align: -2px; margin-bottom: 5px">
      <textarea onClick=javascript:this.select()   style="height: 100; width: 125px; overflow: auto; font: 9px arial; "><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/125x125.gif" border=0></a></textarea>
    </td>

    <td colspan=2><div align=center class=bold style="width: 468px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">468x60</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/468x60.gif" style="vertical-align: -2px; margin-bottom: 5px">
      <textarea onClick=javascript:this.select()   style="height: 31; width: 468px; overflow: auto; font: 9px arial; "><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/468x60.gif" border=0></a></textarea>


      <p>
      <table width=100% border=0 cellspacing=0 cellpadding=0>
        <tr valign="top">
          <td><div align=center class=bold style="width: 300px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">300x250</div>
            <img src="http://pds1106.s3.amazonaws.com/banners/300x250.gif" style="vertical-align: -2px; margin-bottom: 5px">
            <textarea onClick=javascript:this.select()   style="height: 55; width: 300px; overflow: auto; font: 9px arial; "><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/300x250.gif" border=0></a></textarea>
          </td>

          <td><div align=center class=bold style="width: 160px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">160x320</div>
            <img src="http://pds1106.s3.amazonaws.com/banners/160x320.gif" style="vertical-align: -2px; margin-bottom: 5px">
            <textarea onClick=javascript:this.select()   style="height: 85; width: 160px; overflow: auto; font: 9px arial; "><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/160x320.gif" border=0></a></textarea>
          </td>
        </tr>
      </table>

    </td>
  </tr>

  <tr valign="top">
    <td colspan=3><div align=center class=bold style="width: 200px; background-color: #CC0000; color: #FFFF00; margin-bottom: 5px;">200x400</div>
      <img src="http://pds1106.s3.amazonaws.com/banners/200x400.gif" style="vertical-align: -2px; margin-bottom: 5px"> <br>
      <textarea  onClick=javascript:this.select()  style="height: 65; width: 200px; overflow: auto; font: 9px arial; "><a href="<?php echo $affiliate_website?>" alt="Get PUSHY!" title="Get PUSHY!"><img src="http://pds1106.s3.amazonaws.com/banners/200x400.gif" border=0></a></textarea>
    </td>
  </tr>

</table>



          </td>
        </tr>
      </table>

    </div>
    </div>

    <!------------------------ AFFILIATE EMAIL ADS ------------------------>

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width: 702px; ">
      <a href="#" rel="toggle[emails]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png" style="vertical-align: -6px; margin:3px 10px 0" /></a>
      <a href="javascript:animatedcollapse.toggle('emails')" style="text-decoration: none;" class="largetext bold">Affiliate Email Ads</a>
    </div>

    <div id="emails" align=right>
    <div style="width: 100%; height: 420px; overflow-y: scroll; border-left: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width=100% border=0 cellspacing=0 cellpadding=20>
        <tr>
          <td class="text">

          Below is some ad copy you can use for sending emails to your list of friends and subscribers, or placing on your website. Make sure you understand our
          <a href=javascript:openPopup('/pop-terms.php',660,700)>Terms of Use</a> and
          <a href=javascript:openPopup('/pop-aff-agmt.php',660,700)>Affiliate Agreement</a> regarding spam.

          <p>Also included is a list of approved <b>Subject Headings</b> for your emails. The power is in their brevity, and curiosity they will raise in your readers, increasing your open rates,
            and click through rates. Just copy and paste them into your email or autoresponder program, and you're ready to go.

          <p style="padding: 0 0 10px 20px;"><b class=darkgreen>SUBJECT HEADINGS:</b>
            <br>No, really, I'm serious
            <br>Never seen anything like this before
            <br>I know you've been wondering how
            <br>Videos, demo, and no signup cost
            <br>Drop what you're doing... no, really!
            <br>Get a load of this
            <br>This is hilarious
            <br>This is over the top
            <br>Thank me later
            <br>Brace yourself for this one
            <br>Get ready to be shocked
            <br>Holy cow, is he serious
            <br>Talk about Viral...
          </p>

    <!------------------------ EMAIL #1 ------------------------>

<table width=100% cellspacing=0 cellpadding=5 bgcolor=#FFFCF5 style="border: 1px solid #FFEFCC; margin-bottom: 30px;">
  <tr align=center>
    <td class="largetext bold darkgreen" bgcolor=#FFEFCC>EMAIL #1 (copy & paste)</td>
  </tr>

  <tr valign="top">
    <td class=text style="padding: 20px;">

Hi [FIRSTNAME],

<p>I know you've been looking for a way to get more qualified traffic to your website. Drop what you're doing and go check this out. Really! I've never seen anything like this before.

<blockquote> <?php echo $affiliate_website?> </blockquote>

<p>When you get there, you'll see why so many people are attracted to this guy they call PUSHY! It's hilarious, and he really does drive targeted traffic to your website, in a way nobody has ever done it before.

<p>I would give you more details. But you really need to see it to believe it for yourself. They have videos, a demo you can play with, and it doesn't cost anything to signup. Go check it out! You can thank me later ;-)

<p>Your friend,
<br><?php echo $firstname?> <?php echo $lastname?>

    </td>
  </tr>
</table>

    <!------------------------ EMAIL #2 ------------------------>

<table width=625 cellspacing=0 cellpadding=5 bgcolor=#FFFCF5 style="border: 1px solid #FFEFCC; margin-bottom: 30px;">
  <tr align=center>
    <td class="largetext bold darkgreen" bgcolor=#FFEFCC>EMAIL #2 (copy & paste)</td>
  </tr>

  <tr valign="top">
    <td class=text style="padding: 20px;">

Hi [FIRSTNAME],

<p>You need to check this out! I've never seen anything like this before.

<p>I know you have been wondering how to generate more revenue from your website traffic, and customer base. Well, I think you're going to like what you see here.

<blockquote> <?php echo $affiliate_website?> </blockquote>

<p>All you do is copy and paste a snippet of code on your web page, then watch your traffic start converting into dollars for you. Really! It's just that simple.

<p>Go take a look. There are videos, a demo, and it doesn't cost anything to signup.

<p>Your friend,
<br><?php echo $firstname?> <?php echo $lastname?>

    </td>
  </tr>
</table>

    <!------------------------ EMAIL #3 ------------------------>

<table width=625 cellspacing=0 cellpadding=5 bgcolor=#FFFCF5 style="border: 1px solid #FFEFCC; margin-bottom: 30px;">
  <tr align=center>
    <td class="largetext bold darkgreen" bgcolor=#FFEFCC>EMAIL #3 (copy & paste)</td>
  </tr>

  <tr valign="top">
    <td class=text style="padding: 20px;">

Hi [FIRSTNAME],

<p>Wow! Talk about online automation! These guys have taken viral traffic generation to a whole different level.

<blockquote> <?php echo $affiliate_website?> </blockquote>

<p>They created this character named PUSHY! who actually pushes qualified traffic to your sales pages. He makes the sales process fun for your prospects.

<p>Check it out! It's really quite ingenius, and it doesn't cost a dime to signup. There's a demo and videos you can watch to see exactly how it all happens.

<p>Your friend,
<br><?php echo $firstname?> <?php echo $lastname?>

    </td>
  </tr>
</table>

    <!------------------------ EMAIL #4 ------------------------>

<table width=625 cellspacing=0 cellpadding=5 bgcolor=#FFFCF5 style="border: 1px solid #FFEFCC; margin-bottom: 30px;">
  <tr align=center>
    <td class="largetext bold darkgreen" bgcolor=#FFEFCC>EMAIL #4 (copy & paste)</td>
  </tr>

  <tr valign="top">
    <td class=text style="padding: 20px;">

Hi [FIRSTNAME],

<p>Holy cow! Brace yourself before you go to this website.

<blockquote> <?php echo $affiliate_website?> </blockquote>

<p>Everyone needs traffic to their website, right?

<p>Get ready to be shocked and amazed at how these guys are doing it. And to top it all off, they're giving away huge commissions to affiliates, who are going to make a killing (especially those who get in early).

<p>Thank me later. Just get on over there and jump on this right now, before everyone and their brother, sister, aunt, and uncle have signed up. Oh, and It doesn't cost a dime to get started. See ya there!

<p>Your friend,
<br><?php echo $firstname?> <?php echo $lastname?>

    </td>
  </tr>
</table>


          </td>
        </tr>
      </table>

    </div>
    </div>


    <!------------------------ AFFILIATE VIDEOS ------------------------------>

    <div class="text aff_rpts4" style="border: 1px solid #FFCC33; background-image: url('http://pds1106.s3.amazonaws.com/images/mystuff-bg.jpg'); background-repeat: repeat-x; padding:3px; width: 702px; ">
      <a href="#" rel="toggle[aff_videos]" data-openimage="http://pds1106.s3.amazonaws.com/images/minus.png" data-closedimage="http://pds1106.s3.amazonaws.com/images/plus.png">
        <img src="http://pds1106.s3.amazonaws.com/images/minus.png"  style="vertical-align: -6px; margin:3px 10px 0"></a>
      <a href="javascript:animatedcollapse.toggle('aff_videos')" style="text-decoration: none;" class="largetext bold">Affiliate Videos</a>
    </div>

    <div id="aff_videos" align=right>
    <div style="width:100%; height:768px; overflow-y:scroll; border-left: 1px solid #FFCC00; border-bottom: 1px solid #FFCC00; scrollbar-base-color: #E5E6EE; scrollbar-arrow-color: #000000; scrollbar-DarkShadow-Color: #999999;">

      <table width="95%" align="center" border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td class="text">
             <p>Below are 14 videos you can put on your website or blog, according to our
             <a href=javascript:openPopup('/pop-terms.php',660,700)>Terms of Use</a> and
             <a href=javascript:openPopup('/pop-copyright.php',660,700)>Copyright</a>. Just copy and paste the snippet of code onto your web page, and it's ready to view by your site visitors.
               Easy shpeezy!

             <p>The videos are programmed to automatically open up a separate browser at the end of the video, taking your prospects to your PUSHY affiliate website at
              <b><a href="javascript:account_TestAffiliateSite('<?php echo $affiliate_website?>')"><?php echo $affiliate_website?></a></b>.

             <p>Each video is approximately 1 to 4 minutes long, discussing some aspect of PUSHY Technology. They are meant to "tease" your site visitors with just enough information
               to make them want to find out more by going to your affiliate website.

             <p>Use keyword rich titles and descriptions on your web pages for each video, describing "targeted traffic", "traffic generation", "generating viral traffic", "website promotion",
               "PUSHY Technology", etc.

             <p> Click on the video icon to watch the videos. Then copy and paste the code onto your blog or website, and you're all set to go.

             <br>&nbsp;

             <table width="90%" align="center" cellpadding=6 cellspacing=6 border=0>

                  <?php
                     $videos=14;
                     for ($index=1; $index<=$videos; $index++)
                       {
                         $width=480;
                         $height=272;
                         $iwidth=$width-16;
                         $iheight=$height-10;
                         $player="my_video";
                         $affid=$affiliate_id;
                         $vfile="pushyads_".$index."b";
                  ?>
                         <tr height=64>
                            <td  width="90%" align="left">
                               <table height="64" style="border: 1px  solid #FFCC00; border-collapse:collapse;"  bgcolor="#FFEECC" width="100%" border=0 cellspacing=0 cellpadding=5>
                                   <tr>
                                      <td width="5%" style="font-size:14px;">
                                         #<?php echo $index?>
                                      </td>
                                      <td width="10%">
                                         <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/<?php echo $vfile?>.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
                                      </td>
                                      <td width="85%"><?php include("aff_video_code.php");?></td>
                                   </tr>
                               </table>
                            </td>
                         </tr>
                  <?php
                       }
                  ?>

             </table>


          </td>
        </tr>
      </table>

    </div>
    </div>

    </td>
  </tr>
</table>
