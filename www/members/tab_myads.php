<?php
include("tips/myads.tips");

// $PUSHY_LEVEL_VIP
// $PUSHY_LEVEL_PRO
// $PUSHY_LEVEL_ELITE
?>

<div align=right style="position:absolute; margin: -10px 0 0 640px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/tab_myads.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim.gif"></a>
</div>

 <font size=5><b>Submit or Edit your Product Ads here <?php echo $firstname?></b></font>

          <!--------------------------------------------- START CONTENT ------------------------------------------------>

 <p class=largetext>Want to see <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"> get results for your products? The way he gets
   people's attention on his network of websites will get traffic to YOUR website, like you've never seen before. You don't have a product or service? Well, then
   you can advertise the <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">
   <a href=javascript:membership_plan()>affiliate opportunity</a> and make <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px">
   cash. Go ahead! <b>Get</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"> to start dancing up and down for
   your products by submitting them below. </p>

 <table width=710 align=center cellpadding=0 cellspacing=0 bgcolor=#FFEECC class=bgborder>
   <tr>
     <td>
       <table width=100% align=center cellpadding=0 cellspacing=10 style="margin: 10px 0;">

         <!----------------------------------------------------------------------------------------------------------------------------->
         <tr valign=middle height=32>
           <td width=90% valign=middle style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
              <span class="tahoma size20 bold"> Advertise on <span style="color:#CC0000">Your</span>
                <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px"> Network</span> &nbsp;
              <span class="tahoma size14 bold">(included in membership)</span>
           </td>
           <td width=10% align=center valign="absmiddle">
             <input id='order_button_1' style="vertical-align: -6px; width: 90px;" type="button" class=bigbutton value="CLOSE" onClick=javascript:myads_OptionTabClicked(1)>
           </td>
         </tr>

         <tr id='order_option_1' style="display:;">
           <td colspan=2>
             <table align=right width=660 cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 5px 0 10px 0;">
               <tr valign=center height=70>
                 <td width="100%">

                   <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                     <tr height=70 bgcolor="#FFFFFF">
                        <td>

                           <table cellpadding=0 cellspasing=0 border=0><tr><td>

                             <?php
                                if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP && $memberRecord["free_trial"]==0)
                                  {
                                    include("myads_vip_expire.php");
                                  }
                                else
                                  {
                                    include("myads_your_network.php");
                                  }
                             ?>

                           </td></tr></table>

                        </td>
                     </tr>
                   </table>
                 </td>
               </tr>
             </table>
           </td>
         </tr>

         <!----------------------------------------------------------------------------------------------------------------------------->
         <tr valign=middle height=32>
           <td width=90% valign=middle style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
              <span class="tahoma size20 bold"> Advertise on <span style="color:#CC0000">Entire</span>
                <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px"> Network</span> &nbsp;
              <span class="tahoma size14 bold">(upgrade option)</span>
           </td>

           <td width=10% align=center valign="absmiddle">
             <input id='order_button_2' style="vertical-align: -6px; width: 90px;" type="button" class=bigbutton value="OPEN" onClick=javascript:myads_OptionTabClicked(2)>
           </td>
         </tr>

         <tr id='order_option_2' style="display:none;">
           <td colspan=2>

             <table align=right width=660 cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin-top: 5px;">
               <tr valign=center height=70>
                 <td width="100%">

                   <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                     <tr height=70 bgcolor="#FFFFFF">
                       <td width=20% align=center valign=top>
                         <img src="http://pds1106.s3.amazonaws.com/images/p-network.gif" alt="Pushy Network">
                       </td>

                       <td width=80% colspan=2 class=largetext>
                         Give <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"><sup>tm</sup> commands to push your product,
                         service, or opportunity throughout his vast network of websites. Select how much exposure you want, and which type of markets you want them
                         exposed to. <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"><sup>tm</sup> knows how to command
                         attention, and drive the right kind of traffic to your website.

                         <p style="margin-top:20px;"><img src="http://pds1106.s3.amazonaws.com/images/button_coming_soon.png"></p>
                       </td>
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
 <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=680 height=31></div>
