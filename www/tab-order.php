<?php
// var_dump($_SERVER);
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && is_integer(strpos($_SERVER['HTTP_X_REQUESTED_WITH'],'XMLHttpRequest')))
 {
   $display_style="display:none";
   $button_state="OPEN";
 }
else
 {
   $display_style="";
   $button_state="CLOSE";
 }
?>

<?php include("initialize.php"); ?>

<div class="Verdana size16" style="margin: 10px 0 20px 35px; line-height: 22px;">

<span class="darkred size24 Tahoma"><b>ORDER OPTIONS</b></span>

  <p style="margin-top: 30px;">Here's the bottom line! PUSHY works his magic exclusively for members only. So if you want to see him attract a
  stampede of targeted prospects towards your product, service or opportunity, you'll need to sign up as a member.
  It's <b>FREE</b>, for heaven's sake. So there isn't a smidgen of risk for you at that price, right?

  <p>As you can see below, there are a number of options for making PUSHY do the things you want him to do. Remember,
     if you're marketing anything on the Internet, you have 1 or more of
   <a href="/tab/demo" onClick="javascript:tabClicked('home'); return false">3 problems that need solving</a>.
    Any option you pick below, will solve at least one of those problems.

</div>

<table width="620" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%">

      <table width=710 align=center cellpadding=1 cellspacing=4 bgcolor="#FFEECC" class=bgborder>
         <tr valign=bottom bgcolor="#FFEECC" >
           <td width="" align=left   border=0 height=8></td>
           <td width="85%" align=center border=0></td>
           <td width="15%" align=center border=0></td>
         </tr>

         <!---------------------- PUSHY Membership ----------------------->
         <tr valign=middle height=32>
           <td width=0% align=left style="border:0px; background-color:#FFEECC;"></td>
           <td width=85% style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
             <img src="http://pds1106.s3.amazonaws.com/images/p-members_sm.gif"  alt="PUSHY! Membership" style="vertical-align: -5px;">&nbsp;
             <b class=size20><img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 Membership</b>
             &nbsp;&nbsp;<span class="size16 bold">(Select Your Network Exposure)</span></td>
           <td width=15% align=center valign="absmiddle">
             <input id='order_button_1' style="vertical-align: -6px;" type="button" class=bigbutton value="<?php echo $button_state?>" onClick=javascript:orderTabClicked(1)>
           </td>
         </tr>

         <tr id='order_option_1' style='<?php echo $display_style?>'>
           <td colspan=3>
             <table align=right width=630 cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 8px;">
               <tr valign=bottom height=70>
                 <td colspan=3 style="border:0px; background-color:#FFC757;">

                   <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                     <tr height=70 bgcolor="#FFFFFF">
                       <td width=20% align=center valign=top>
                         <img src="http://pds1106.s3.amazonaws.com/images/p-members.gif" height=100 alt="PUSHY! Member">
                       </td>

                       <td width=80% class=largetext>
                         Make  <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 display your products on a huge
                         network of member websites. You can choose your target market (or niche) by category, pinpointing the kind of customers who are most likely
                         to purchase your products, services, and opportunities. <br><b class=green>Try it out for 30 days FREE!</b>
                       </td>
                     </tr>


                     <tr bgcolor="#FFFFFF">
                       <td colspan=3>

                         <!--------------------------------------------- START SIGNUP FORM --------------------------------------------->
                         <table align=center width=460 cellspacing=3 cellpadding=3 class=bgborder bgcolor="#FFEECC">
                           <tr>
                             <td colspan=3 style="border: 0px; background-color:#FFEECC;">
                                <form id="ORDER_SIGNUP_FORM" name="ORDER_SIGNUP_FORM" method="POST">
                                <input type="hidden" name="paref" value="<?php echo $PAREF?>">
                                <table style="border:1px solid #999999;" width=100% cellpadding=0 cellspacing=0>
                                  <tr bgcolor="#F7F7F7">
                                    <td colspan=2 height=20></td>
                                  </tr>

                                  <tr height=28>
                                    <td width=45% class=signcol1>
                                       <b>Your Firstname:&nbsp;</b></td>
                                    <td width=55% class=signcol2>&nbsp;
                                       <input class=input type="text" name="firstname" maxlength="30" >
                                    </td>
                                  </tr>

                                  <tr height=28>
                                    <td width=45% class=signcol1>
                                       <b>Your Lastname:&nbsp;</b></td>
                                    <td width=55% class=signcol2>&nbsp;
                                      <input class=input type="text" name="lastname" maxlength="30" >
                                    </td>
                                  </tr>

                                  <tr height=28>
                                    <td width=45% class=signcol1>
                                       <b>Your Email:&nbsp;</b></td>
                                    <td width=55% class=signcol2>&nbsp;
                                       <input class=input type="text" name="email" maxlength="70" >
                                    </td>
                                  </tr>

                                  <tr bgcolor="#F7F7F7">
                                    <td colspan=2 height=20>
                                       <div id="ORDER_SIGNUP_FORM_ALREADY_REGISTERED" style="display: none; margin:15px 0px;  text-align:center" align=center>
                                          <span style="color:#CC0000; font-size:12px;" >
                                            The email address you entered has already been registered<br>
                                            <a href=javascript:already_registered_login()>LOGIN HERE</a>
                                            ...or, if you forgot your password <a href=javascript:already_registered_forgot_password()>CLICK HERE</a>
                                          </span>
                                       </div>
                                    </td>
                                  </tr>
                                  <tr valign=center height=54 bgcolor="#F7F7F7" cellpadding=0 cellspacing=0 >
                                    <td colspan=2 width="100%" align=center style="padding-left:10px;"><a href="#" onClick="return false" style="cursor:hand;">
                                      <img src="http://pds1106.s3.amazonaws.com/images/sign-up-y.png" style="cursor: hand;" onClick=javascript:register_submit(document.ORDER_SIGNUP_FORM)></a>
                                    </td>
                                  </tr>

                                  <tr bgcolor="#F7F7F7">
                                    <td colspan=2 height=20>
                                       <div style="margin:15px 20px 0px;  text-align:center" align=center>
                                          <span class="tahoma size12">
                                            <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 hates SPAM as much as you do. So rest assured, your email
                                              address is in safe & secure hands, and will never ever be sold to anyone.
                                          </span>
                                       </div>
                                    </td>
                                  </tr>

                                  <tr bgcolor="#F2F4F7">
                                    <td colspan=2 height=20></td>
                                  </tr>
                                </table>
                                </form>
                             </td>
                           </tr>
                         </table>
                         <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=430 height=31></div>
                         <!--------------------------------------------- END SIGNUP FORM ----------------------------------->

                       </td>
                     </tr>
                   </table>

                 </td>
               </tr>
             </table>

           </td>
         </tr>

         <tr height=8><td colspan=3></td></tr>

         <!---------------------- PUSHY Widget ----------------------->
         <tr valign=middle height=32>
           <td width=0% align=left style="border:0px; background-color:#FFEECC;">&nbsp;</td>
           <td width=85% align=left style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
             <img src="http://pds1106.s3.amazonaws.com/images/p-widget_sm.gif" alt="PUSHY! Widget" style="vertical-align:-5px;">&nbsp;
                <b class=size20><img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 Widget</b>
                &nbsp;&nbsp;<span class="size16 bold">(Install Him on Your Website)</span></td>
           <td width=15% align=center valign="absmiddle">
             <input id='order_button_2' style="vertical-align: -6px;" type="button" class=bigbutton value="<?php echo $button_state?>" onClick=javascript:orderTabClicked(2)>
           </td>
         </tr>

         <tr id='order_option_2' style='<?php echo $display_style?>'>
           <td colspan=3>
             <table align=right width=630 cellspacing=2 cellpadding=2  class=bgborder bgcolor="#FFC757" style="margin:8px;">
               <tr valign=bottom height=70>
                 <td colspan=3 style="border:0px; background-color:#FFC757;">
                   <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                     <tr height=70 bgcolor="#FFFFFF">
                       <td width=20% align=center valign=top>

                         <img src="http://pds1106.s3.amazonaws.com/images/p-widget.gif" alt="Pushy Widget">
                       </td>
                       <td width=80% colspan=2 class=largetext>
                         Install <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 on your website! Use him to earn money,
                         build your list, offer subscriptions, brand your product, yourself, or increase exposure for a service or opportunity. The uses and options are endless.
                         Choose from a number of different behaviors (bounce, hover, float, etc).
                         <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 gets results!

                         <p style="margin-top:20px;"><a href="/tab-demo.php" onClick="javascript:tabClicked('demo'); return false"><img src="http://pds1106.s3.amazonaws.com/images/watch-demo.png"></a></p>

                       </td>
                     </tr>
                   </table>
                 </td>
               </tr>
             </table>
           </td>
         </tr>

         <tr height=8><td colspan=3></td></tr>

         <!---------------------- PUSHY Network ----------------------->
         <tr valign=middle height=32>
           <td width=0% align=left style="border:0px; background-color:#FFEECC;">&nbsp;</td>
           <td width=85% align=left style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:10;">
             <img src="http://pds1106.s3.amazonaws.com/images/p-network_sm.gif" alt="PUSHY! Network" style="vertical-align:-5px;">&nbsp;
             <b class=size20><img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 Network</b>
             &nbsp;&nbsp;<span class="size16 bold">(Let Him "Push" Your Products)</span></td>
           <td width=15% align=center valign="absmiddle">
              <input id='order_button_3' style="vertical-align: -6px;" type="button" class=bigbutton value="<?php echo $button_state?>" onClick=javascript:orderTabClicked(3)></td>
         </tr>

         <tr id='order_option_3' style='<?php echo $display_style?>'>

           <td colspan=3>
             <table align=right width=630 cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin:8px;">
               <tr valign=bottom height=70>
                 <td colspan=3 style="border:0px; background-color:#FFC757;">
                   <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                     <tr height=70 bgcolor="#FFFFFF">
                       <td width=20% align=center valign=top>
                         <img src="http://pds1106.s3.amazonaws.com/images/p-network.gif" alt="PUSHY! Network">
                        </td>

                        <td width=80% colspan=2 class=largetext>
                            Give <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 commands to push your
                            product, service, or opportunity throughout his vast network of websites. Select how much exposure you want, and which type of markets you want
                            them exposed to. <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 knows how
                            to command attention, and drive the right kind of traffic to your website.

                         <p style="margin-top:20px;"><img src="http://pds1106.s3.amazonaws.com/images/button_coming_soon.png"></p>

                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

              </table>
            </td>
         </tr>

         <tr><td></td></tr>
         <tr><td></td></tr>

      </table>
      <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=615 height=31></div>
    </td>
  </tr>
</table>
