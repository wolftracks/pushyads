<?php
 include("initialize.php");

 include_once("scaling.inc");
?>


<?php
include_once("members/tips/pushy_properties.tips");
?>

<div class="Verdana size16" style="margin: 10px 0 20px 35px; line-height: 22px;">

<span class="darkred size24 Tahoma"><b>DEMO
  <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" height=20 style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482
    HERE</b></span>

<p style="margin-top: 30px;">Want to see <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482 dance, bounce, float,
and perform any number of antics on your website? He'll demand attention from your site visitors and get them to take the kind of action that generates
a lot of moolah for you. Go ahead and play with him below. You can change his size, make him go fast or slow, zoom out, fade in, wiggle, and a whole
lot of other stuff.

<p style="line-height: 20px;">After you fall in love with him, go ahead and <a href="javascript:newmemberSignUp()"><b>SIGNUP for FREE</b></a> so you can
get the simple line of code to place him on your website. Wait 'til you see what happens next! Get ready to be blown away! It's <b>YOUR TURN</b> to
start cashing in <b>BIG TIME</b> from
<b>GETTIN'</b> <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482
<p>&nbsp;</p>

</div>

<form action=NULL>
<center>
   <table align=center width=710 height=670 cellspacing=0 cellpadding=0>
     <tr>
       <td align=center valign=top background="http://pds1106.s3.amazonaws.com/images/pushy-border-710.gif">
         <table align=right width="80%" cellspacing=0 cellpadding=0 style="margin: 68px 10px 0 0;">
           <tr>
             <td align=center valign=top>
               <table width=100% cellpadding=0 cellspacing=0 border=0>

                 <tr>
                   <td colspan=2 valign=top>

                     <table width=100%  cellpadding=0 cellspacing=0 border=0 class="tahoma size18">
                        <tr>
                          <td width=50% valign=top>

                            <table width=300 cellpadding=4 cellspacing=0 border=0 style="margin-top: 30px; border: 1px solid #FFCC00;" bgcolor=#FFEECC>

                              <tr height=10>
                                <td colspan=2>

                                  <table width=100% border=0 cellpadding=0 cellspacing=0 border=0 >
                                    <tr>
                                      <td colspan=2 class="tahoma size18">
                                        <div style="position: absolute; width:400px; margin-top: -23px">
                                          <b>Configure <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px" alt="PUSHY!" title="PUSHY!">&#8482's Behavior!</b>
                                          <img src="http://pds1106.s3.amazonaws.com/images/arrow_blue.png" style="vertical-align: top;">
                                        </div>
                                      </td>
                                    </tr>
                                  </table>

                                </td>
                              </tr>

                              <tr height=6><td colspan=2></td></tr>

                              <!------- SIZE ------->

                              <tr id="PUSHY_WIDGET_SIZE_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Size:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-SIZE')">
                                </td>
                                <td>
                                  <SELECT name="widget_size" style="width:160px"><option value="null"> ------ Select ------ </option>
                                    <?php
                                       foreach($WIDGET_SCALE AS $widget_width => $widget_scale_element)
                                         {
                                           $widget_height = $widget_scale_element["height"];
                                           $text = "W $widget_width &nbsp;x&nbsp;  H $widget_height  pixels";
                                           echo"  <option value=\"$widget_width\">".$text."</option>\n";
                                         }
                                    ?>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- POSTURE ------->

                              <tr id="PUSHY_WIDGET_POSTURE_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Posture:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-POSTURE')">
                                </td>
                                <td>
                                  <SELECT name="widget_posture" style="width:160px" onChange=javascript:pushy_posture_changed(this.form,0)><option value="null"> ------ Select ------ </option>
                                    <?php
                                       for ($i=0; $i<count($WIDGET_POSTURES); $i++)
                                         {
                                           echo"  <option value=\"$i\">".$WIDGET_POSTURES[$i]."</option>\n";
                                         }
                                    ?>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- HOME ------>

                              <tr id="PUSHY_WIDGET_ORIGIN_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Home:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-HOME')">
                                </td>
                                <td>
                                  <SELECT name="widget_origin" style="width:160px"><option value="null"> ------ Select ------ </option>
                                    <?php
                                       for ($i=0; $i<count($WIDGET_ORIGINS); $i++)
                                         {
                                           echo"  <option value=\"$i\">".$WIDGET_ORIGINS[$i]."</option>\n";
                                         }
                                    ?>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- MOTION ----->

                              <tr id="PUSHY_WIDGET_MOTION_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Motion:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-MOTION')">
                                </td>
                                <td>
                                  <SELECT name="widget_motion" style="width:160px" onChange=javascript:pushy_motion_changed(this.form,0)><option value="null"> ------ Select ------ </option>
                                    <?php
                                       for ($i=0; $i<count($WIDGET_MOTIONS); $i++)
                                         {
                                           echo"  <option value=\"$i\">".$WIDGET_MOTIONS[$i]."</option>\n";
                                         }
                                    ?>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- TRANSITION ----->

                              <tr id="PUSHY_WIDGET_TRANSITION_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Transition:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-TRANSITION')">
                                </td>
                                <td>
                                  <SELECT name="widget_transition" style="width:160px"><option value="null"> ------ Select ------ </option>
                                    <?php
                                       for ($i=0; $i<count($WIDGET_TRANSITIONS); $i++)
                                         {
                                           echo"  <option value=\"$i\">".$WIDGET_TRANSITIONS[$i]."</option>\n";
                                         }
                                    ?>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- SPEED ------->

                              <tr id="PUSHY_WIDGET_SPEED_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Speed:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-SPEED')">
                                </td>
                                <td>
                                  <SELECT name="widget_speed" style="width:160px">
                                     <option value="0" selected> Medium </option>
                                     <option value="1"> Slow </option>
                                     <option value="2"> Fast </option>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- WIGGLE ------->

                              <tr id="PUSHY_WIDGET_WIGGLE_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Wiggle:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-WIGGLE')">
                                </td>
                                <td>
                                  <SELECT name="widget_wiggle" style="width:100px">
                                     <option value="0"> Off </option>
                                     <option value="1" selected> On  </option>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- DELAY ------->

                              <tr id="PUSHY_WIDGET_DELAY_0" height=42 valign=middle>
                                <td align=right class="tahoma  bold size16">Delay:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-DELAY')">
                                </td>
                                <td>
                                  <SELECT name="widget_delay" style="width:100px">
                                    <?php
                                       for ($i=0; $i<=10; $i++)
                                         {
                                           $sel="";
                                           if ($i==0)
                                             {
                                               $text = "No Delay ";
                                               $sel="selected";
                                             }
                                           else
                                           if ($i==1)
                                             $text = $i." second ";
                                           else
                                             $text = $i." seconds ";
                                           echo"  <option value=\"$i\" $sel>".$text."</option>\n";
                                         }
                                    ?>
                                  </SELECT>
                                </td>
                              </tr>

                              <!------- PAUSE ------->

                              <tr id="PUSHY_WIDGET_PAUSE_0" height=42  valign=middle>
                                <td align=right class="tahoma  bold size16">Pause:
                                  <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-PAUSE')">
                                </td>
                                <td>
                                  <SELECT name="widget_pause" style="width:100px">
                                    <?php
                                       for ($i=0; $i<=10; $i++)
                                         {
                                           $sel="";
                                           if ($i==0)
                                             {
                                               $text = "No Pause ";
                                               $sel="selected";
                                             }
                                           else
                                           if ($i==1)
                                             $text = $i." second ";
                                           else
                                             $text = $i." seconds ";
                                           echo"  <option value=\"$i\" $sel>".$text."</option>\n";
                                         }
                                   ?>
                                  </SELECT>
                                </td>
                              </tr>
                              <tr height=10><td></td></tr>
                            </table>
                            <div align=left style="margin-left:5px;"><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=290 height=21></div>


                            <table width=100% cellpadding=0 cellspacing=0 border=0>
                              <!--------------------------------------- DEMO/SUBMIT -------------------------------------------->
                              <tr valign=top>
                                <td align=center>
                                  <input type=button value="PREVIEW PUSHY" class="bigbutton bold" style="width:159px; 0 18px 0 -5px;" onClick=javascript:pushy_demo(this.form)>
                                  <div style="margin: -1px -5px 0 -5px;"><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=153 height=15></div>
                                </td>
                              </tr>
                            </table>

                          </td>

                          <td width=50%  valign=top style="padding-top: 30px;">
                             <img src="http://pds1106.s3.amazonaws.com/images/pushyman-sh.png" width=320 height=384>
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
</center>
</form>
