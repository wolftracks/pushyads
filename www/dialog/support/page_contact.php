<div id="ServiceMessage">
  <table width=710 align=center cellpadding=0 cellspacing=15 bgcolor="#FFEECC" class=bgborder>
    <tr valign=middle >
      <td align=center valign=top>

        <table align=center width=100% cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 0px;">
          <tr valign=bottom height=70>
            <td style="border:0px; background-color:#FFC757;">

              <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
                <tr height=70 bgcolor="#FFFFFF">
                  <td width=20% align=center valign=top style="padding-top: 25px;">
                    <img src="http://pds1106.s3.amazonaws.com/images/support.png" alt="Pushy Support">
                  </td>

                  <td width=80% class=largetext style="padding-top: 25px;">
                    <span class=size20><b>Need some</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px">&#8482 <b>Help?</b></span>

                    <p class=text>Did you look to see if your question is in our <b>FAQ</b>? If not, please <a href="faq.php"><b>Click Here</b></a> where we answer
                    many of the questions that we believe you may have.</p>

              <!----
                    <p style="margin-top: 20px; color: #FF9900;" class=size20><b>Contact Us Here!</b></p>

                    <p class=text style="margin-right: 20px;">Most questions are answered in our <b>F</b>requently <b>A</b>sked <b>Q</b>uestions (<b>FAQ</b>) above.
                    However, if you can't find the question answered above, please use this form to submit a support ticket. We will respond
                    within 24-48 hours (usually sooner).</p>

                    <p class=text style="margin-right: 20px;">All support tickets are assigned a unique <b>Tracking Number</b>. In order to help us serve you more efficiently,
                    please insert this number in any subsequent communications you might have on the same issue (or <u>leave blank</u> if your question is new).</p>

              ---->
                  </td>
                </tr>

                <tr bgcolor="#FFFFFF">
                  <td align=center colspan=3>

                     <!--------------------------------------------- START CONTACT FORM --------------------------------------------->
                        <form name="ServiceMessageForm">
                        <input type="hidden" name="sid" value="<?php echo $sid?>">
                        <input type="hidden" name="mid" value="<?php echo $mid?>">

                     <table style="border:1px solid #339933; background-color: #FBFFFA;" width=90% cellpadding=0 cellspacing=0>
                       <tr>
                         <td colspan=2 height=20></td>
                       </tr>

                       <tr height=28>
                             <td width=158 align="right" class=text><b>Tracking Number:</b>&nbsp;</td>
                             <td width=390 align="left">
                               <input class=form_input type=text size=16 name="service_id" value="" maxlength="30"> <span class=smalltext>(if related to previous support ticket)</span></td>
                       </tr>

                       <tr height=28>
                         <td width=158 align="right" class=text><b>Your First Name:</b>&nbsp;</td>
                         <td width=390 align="left">
                           <input class=form_input type=text size=16 name="in_firstname" value="<?php echo $firstname?>" maxlength="30"></td>
                       </tr>

                       <tr height=28>
                         <td width=158 align="right" class=text><b>Your Last Name:</b>&nbsp;</td>
                         <td width=390 align="left">
                           <input class=form_input type=text size=16 name="in_lastname" value="<?php echo $lastname?>" maxlength="30"></td>
                       </tr>

                       <tr height=28>
                         <td width=158 align="right" class=text><b>Your Email:</b>&nbsp;</td>
                         <td width=390 align="left">
                           <input class=form_input type=text size=30 name="in_email" value="<?php echo $email?>" maxlength="60"></td>
                       </tr>

                       <tr height=28 >
                         <td width=158 align="right" class=text><b>Subject:</b>&nbsp;</td>
                         <td width=390 align="left">
                           <input class=form_input type=text size=30 name="in_subject" value="<?php echo $subject?>" maxlength="60"></td>
                       </tr>

                       <tr height=5><td></td></tr>

                       <tr>
                         <td width=158 align="right" valign=top  class=text><b>Your Message:&nbsp;</b></td>
                         <td width=390 align="left" valign=top  >
                           &nbsp;<textarea name="in_message" class="textform darkgreen" cols=46 rows=8 maxlength="1000"></textarea>
                         </td>
                       </tr>

                       <tr height=70>
                         <td valign=bottom></td>
                         <td valign=bottom>
                           <input type="button" style="height: 40px; width: 200px; margin-left: 5px;" class=size16 value="Submit Support Ticket" onClick=support_submitServiceRequest(this.form)><br>&nbsp;</td>
                       </tr>
                     </table>
                     </form>
                     <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=530 height=31></div>
                     <!--------------------------------------------- END CONTACT FORM ----------------------------------->
                  </td>
                </tr>
              </table>

            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</div>
