<!------- NEW PUSHY WIDGET ----->
  <form action=NULL>
  <input type="hidden" name="mid" value="<?php echo $mid?>">
  <input type="hidden" name="sid" value="<?php echo $sid?>">
  <input type="hidden" name="option" value="Create">
    <table align=right width=98% cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 4px 0 15px 0;">
      <tr valign=bottom height=70>
        <td>
          <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
            <tr height=70 bgcolor="#FFFFFF">
              <td width=20% valign=top>

                <br>

                <!----------------------------------------------- START NEW PUSHY CONFIGURATION ---------------------->

                <table width=100% border=0 cellpadding=0 cellspacing=0 border=0 >
                  <tr>
                    <td colspan=2>

                      <table width=100% align=left cellpadding=4 cellspacing=0 border=0  class="tahoma size18">

                        <!------- NICKNAME ------->

                        <tr height=50>
                          <td width=15% valign=top>&nbsp;</td>
                          <td width=34% valign=middle colspan=2>
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: middle;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-NICKNAME')"></a>
                            <input name="widget_name" class=form_input style="width:166px;" value="" maxlength=60>
                          </td>
                          <td width=51% valign=middle><b>FIRST</b>, give <img src="http://pds1106.s3.amazonaws.com/images/pushy16.png" style="vertical-align: -1px"> a nickname</td>
                        </tr>

                        <!------- DOMAIN ------->

                        <tr height=50>
                          <td width=16% valign=top>&nbsp;</td>
                          <td width=33% valign=middle colspan=2  style="padding-left: -4px;">
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: middle;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-DOMAIN')"></a>
                            <input name="widget_domain" class=form_input style="width:166px;" value="" maxlength=60>
                          </td>
                          <td width=51% valign=middle><b>NEXT</b>, enter your website domain</td>
                        </tr>

                        <!------- CATEGORY ------->

                        <tr height=42 valign=top>
                          <td width=16% valign=top>&nbsp;</td>
                          <td width=4%  valign=top style="padding-top: 14px;">
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-CATEGORIES')"></a>
                          </td>
                          <td width=29% valign=top valign=right>
                            <SELECT name="widget_categories" size=9 multiple class="tahoma size14" style="margin-top: 11px; width:170px; background-color: FFFBF5;">
                              <?php
                                 foreach ($ProductCategories AS $cat => $ctitle)
                                   {
                                     echo "  <OPTION VALUE=\"$cat\">$ctitle</OPTION>\n";
                                   }
                              ?>
                            </SELECT>&nbsp;<br>&nbsp;
                          </td>
                          <td width=51% valign=top style="padding-top:11px;"><b>THEN</b>, select your audience's interest<br>
                              <div style="color:#CC0000; font-size:13px; line-height:15px; text-valign:bottom; padding: 10px 0 0 15px;">Select only <b>ONE</b> or <b>TWO</b> Categories
                                that best describe the content of your webite, which in turn would best describe the interest of your audience. This enables
                                <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> to produce the right type of ads for your website viewers.
                              </div><br>
                              <span style="color:#000000; font-size:14px; line-height:10px; text-valign:bottom;">(Press/hold <b>Ctrl Key</b> while making selections)</span><br>
                          </td>
                        </tr>
                      </table>

                    </td>
                  </tr>

                  <tr>
                    <td width=50% valign=top>

                      <table width=300 cellpadding=4 cellspacing=0 border=0 style="margin-top: 20px; border: 1px solid #FFCC00;" bgcolor=#FFEECC>

                        <tr height=10>
                          <td colspan=2>

                            <table width=100% border=0 cellpadding=0 cellspacing=0 border=0 >
                              <tr>
                                <td colspan=2 class="tahoma size18">
                                  <div style="position: absolute; width:400px; margin-top: -23px">
                                    <b>NOW, Configure <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px"></b>
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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-SIZE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_size" style="width:160px;background-color: FFFBF5;"><option value="null"> ------ Select ------ </option>
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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-POSTURE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_posture" style="width:160px;background-color: FFFBF5;" onChange=javascript:pushy_posture_changed(this.form,0)>
                              <option value="null"> ------ Select ------ </option>

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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-HOME')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_origin" style="width:160px;background-color: FFFBF5;"><option value="null"> ------ Select ------ </option>
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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-MOTION')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_motion" style="width:160px;background-color: FFFBF5;" onChange=javascript:pushy_motion_changed(this.form,0)><option value="null"> ------ Select ------ </option>
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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-TRANSITION')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_transition" style="width:160px;background-color: FFFBF5;"><option value="null"> ------ Select ------ </option>
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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-SPEED')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_speed" style="width:160px;background-color: FFFBF5;">
                               <option value="0" selected> Medium </option>
                               <option value="1"> Slow </option>
                               <option value="2"> Fast </option>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- WIGGLE ------->

                        <tr id="PUSHY_WIDGET_WIGGLE_0" height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Wiggle:
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-WIGGLE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_wiggle" style="width:100px;background-color: FFFBF5;">
                               <option value="0"> Off </option>
                               <option value="1" selected> On  </option>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- DELAY ------->

                        <tr id="PUSHY_WIDGET_DELAY_0" height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Delay:
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-DELAY')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_delay" style="width:100px;background-color: FFFBF5;">
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
                            <a href="#" onClick="return false" style="cursor:help;">
                            <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-PAUSE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_pause" style="width:100px;background-color: FFFBF5;">
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


                      <table width=100% cellpadding=4 cellspacing=0 border=0 style="margin-top:20px;">
                        <!--------------------------------------- DEMO/SUBMIT -------------------------------------------->
                        <tr height=80 valign=top>
                          <td bgcolor=#FFFFFF>
                            <input type=button value="PREVIEW PUSHY" class="bigbutton bold" style="width:143px; margin: 0 18px 0 -5px; color: #CC0000;" title="Preview PUSHY's Behavior" onClick=javascript:pushy_preview(this.form)>
                            <input id="Button_Get_Pushy" type=button value="GET PUSHY" class="bigbutton bold" style="width:137px;  color: #333333;" title="Puts PUSHY on Your Website" onClick=javascript:pushy_createWidget(this.form)>
                            <span id="Get_Pushy_Busy" style="width:90px; height:40px; vertical-align: middle; text-align:center; display:none">&nbsp;&nbsp;&nbsp;&nbsp;<img src="http://pds1106.s3.amazonaws.com/images/wait.gif"></span>
                          </td>
                        </tr>
                      </table>

                    </td>

                   <td width=50%  valign=top>
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
  </form>
