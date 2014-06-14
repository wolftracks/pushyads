<!------- EXISTING PUSHY WIDGET ----->
  <form action=NULL>
  <input type="hidden" name="mid" value="<?php echo $mid?>">
  <input type="hidden" name="sid" value="<?php echo $sid?>">
  <input type="hidden" name="widget_key" value="<?php echo $WidgetKey?>">
  <input type="hidden" name="widget_name" value="<?php echo $WidgetName?>">
  <input type="hidden" name="widget_domain" value="<?php echo $WidgetDomain?>">
  <input type="hidden" name="option" value="Update">
    <table align=right width=98% cellspacing=2 cellpadding=2 class=bgborder bgcolor="#FFC757" style="margin: 4px 0 15px 0;">
      <tr valign=bottom height=70>
        <td>
          <table align=center width="100%" style="border:1px solid #999999;" cellspacing=0 cellpadding=10>
            <tr height=70 bgcolor="#FFFFFF">
              <td width=20% align=center valign=top>
                <table width=100% cellpadding=0 cellspacing=0 border=0 class="tahoma size14" style="border: 1px dotted #CC0000;">
                  <tr height=30 bgcolor="#FFFFA3">
                    <td width="50%" style="padding-left: 20px;"><b>Date Created:</b> <span  class=red><?php echo $DateCreated?></span></td>
                    <td width="50%" style="padding-right: 20px;" align=right><b>Last Modified:</b> <span id="LAST_MODIFIED_<?php echo $WidgetKey?>" class=red><?php echo $DateLastModified?></span></td>
                  </tr>
                </table>
                <table width=100% cellpadding=0 cellspacing=0 border=0 class="tahoma size14" style="border: 1px dotted #CC0000; margin: 5px 0 15px 0;">
                  <tr height=30 bgcolor="#FFE0E0">
                    <td width="70%" style="padding-left: 20px;"><b>Get <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px"> Code for your website
                      <img src="http://pds1106.s3.amazonaws.com/images/arrow-anim-rt_16.gif" style="vertical-align: bottom"></span></td>
                    <td width="30%" style="padding-right: 100px;" align=right><input name=GetCode id="GetCode" type=button value="GET CODE" onClick=javascript:pushy_getTheCode(this.form)></td>
                  </tr>
                </table>
                <br>

                <table width=100% border=0 cellpadding=0 cellspacing=0 border=0 >
                  <tr>
                    <td colspan=2>

                      <table width=100% align=left cellpadding=4 cellspacing=0 border=0 class="tahoma size18">


                        <!------- CATEGORY ------->

                        <tr valign=top>
                          <td width=15% valign=top><span id="widget-name-<?php echo $PUSHY_INDEX?>" style="display:none"><?php echo $WidgetName?></span>&nbsp;</td>
                          <td width=5% align=right valign=top style="padding-right: 5px;">
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-CATEGORIES')"></a>
                          </td>
                          <td width=29% valign=top align=left>
                            <?php
                               $categories=array();
                               $tarray=explode("~",$WidgetCategories);
                               for ($i=0; $i<count($tarray); $i++)
                                 {
                                   if (strlen($tarray[$i]) > 0)
                                     {
                                       $k=$tarray[$i];
                                       if (isset($ProductCategories[$k]))
                                         $categories[$k]=TRUE;
                                     }
                                 }
                            ?>
                            <SELECT name="widget_categories" size=9 multiple class="tahoma size14" style="width:170px; background-color: FFFBF5;">
                              <?php
                                 foreach ($ProductCategories AS $cat => $ctitle)
                                   {
                                     $sel="";
                                     if (isset($categories[$cat]))
                                       $sel="selected";
                                     echo "  <OPTION VALUE=\"$cat\" $sel>$ctitle</OPTION>\n";
                                   }
                              ?>
                            </SELECT>&nbsp;<br>&nbsp;
                          </td>
                          <td width=51% valign=top style="padding-top: 11px;">Edit your audience's interest<br>
                              <div style="color:#CC0000; font-size:13px; line-height:15px; text-valign:bottom; padding: 10px 0 0 15px;">Remember, only <b>ONE</b> or <b>TWO</b> 
                                Categories that best describe the content of your webite, which in turn would best describe the interest of your audience. This enables 
                                <img src="http://pds1106.s3.amazonaws.com/images/pushy12.png" style="vertical-align: -1px"> to produce the right type of ads for your website viewers.
                              </div><br>
                              <span style="color:#000000; font-size:14px; line-height:10px; text-valign:bottom;">(Press/hold  <b>Ctrl Key</b> while making selections)</span><br>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td width=50% valign=top>

                      <table width=300 cellpadding=4 cellspacing=0 border=0 style="margin-top: 15px; border: 1px solid #FFCC00;" bgcolor=#FFEECC>

                        <!------- SIZE ------->

                        <tr height=10>
                          <td colspan=2>

                            <table width=100% border=0 cellpadding=0 cellspacing=0 border=0 >
                              <tr>
                                <td colspan=2 class="tahoma size18">
                                  <div style="position: absolute; width:400px; margin-top: -23px">
                                    <b>Edit <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px">'s Behavior!</b>
                                    <img src="http://pds1106.s3.amazonaws.com/images/arrow_blue.png" style="vertical-align: top;">
                                  </div>
                                </td>
                              </tr>
                            </table>

                          </td>
                        </tr>

                        <tr height=6><td colspan=2></td></tr>

                        <tr id="PUSHY_WIDGET_SIZE_<?php echo $PUSHY_INDEX?>"  height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Size:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-SIZE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_size" style="width:160px;background-color: FFFBF5;">
                              <?php
                                 foreach($WIDGET_SCALE AS $widget_width => $widget_scale_element)
                                   {
                                     $widget_height = $widget_scale_element["height"];
                                     $sel="";
                                     if ($widget_width==$WidgetWidth)
                                       $sel="selected";
                                     $text = "W $widget_width &nbsp;x&nbsp;  H $widget_height  pixels";
                                     echo"  <option value=\"$widget_width\" $sel>".$text."</option>\n";
                                   }
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- POSTURE ------>

                        <tr id="PUSHY_WIDGET_POSTURE_<?php echo $PUSHY_INDEX?>"  height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Posture:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-POSTURE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_posture" style="width:160px;background-color: FFFBF5;" onChange=javascript:pushy_posture_changed(this.form,<?php echo $PUSHY_INDEX?>)>
                              <?php
                                 for ($i=0; $i<count($WIDGET_POSTURES); $i++)
                                   {
                                     $sel="";
                                     if ($i==$WidgetPosture)
                                       $sel="selected";
                                     echo"  <option value=\"$i\" $sel>".$WIDGET_POSTURES[$i]."</option>\n";
                                   }
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- HOME ------>


                        <tr id="PUSHY_WIDGET_ORIGIN_<?php echo $PUSHY_INDEX?>" style="display:<?php echo ($WidgetPosture==1)?'':'none';?>" height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Home:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-HOME')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_origin" style="width:160px;background-color: FFFBF5;">
                              <?php
                                 for ($i=0; $i<count($WIDGET_ORIGINS); $i++)
                                   {
                                     $sel="";
                                     if ($i==$WidgetOrigin)
                                       $sel="selected";
                                     echo"  <option value=\"$i\" $sel>".$WIDGET_ORIGINS[$i]."</option>\n";
                                   }
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- MOTION ------>

                        <tr id="PUSHY_WIDGET_MOTION_<?php echo $PUSHY_INDEX?>" style="display:<?php echo ($WidgetPosture==1)?'none':'';?>" height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Motion:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-MOTION')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_motion" style="width:160px;background-color: FFFBF5;" onChange=javascript:pushy_motion_changed(this.form,<?php echo $PUSHY_INDEX?>)>
                              <?php
                                 for ($i=0; $i<count($WIDGET_MOTIONS); $i++)
                                   {
                                     $sel="";
                                     if ($i==$WidgetMotion)
                                       $sel="selected";
                                     echo"  <option value=\"$i\" $sel>".$WIDGET_MOTIONS[$i]."</option>\n";
                                   }
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- TRANSITION ------>

                        <tr id="PUSHY_WIDGET_TRANSITION_<?php echo $PUSHY_INDEX?>"  height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Transition:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-TRANSITION')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_transition" style="width:160px;background-color: FFFBF5;">
                              <?php
                                 for ($i=0; $i<count($WIDGET_TRANSITIONS); $i++)
                                   {
                                     $sel="";
                                     if ($i==$WidgetTransition)
                                       $sel="selected";
                                     echo"  <option value=\"$i\" $sel>".$WIDGET_TRANSITIONS[$i]."</option>\n";
                                   }
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- SPEED ------->

                        <tr id="PUSHY_WIDGET_SPEED_<?php echo $PUSHY_INDEX?>" style="display:<?php echo ($WidgetPosture==1)?'none':'';?>"  height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Speed:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-SPEED')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_speed" style="width:160px;background-color: FFFBF5;">
                              <?php
                                 $sel="";
                                 if ($WidgetSpeed==0)
                                    $sel="selected";
                                 echo"  <option value=\"0\" $sel> Medium </option>\n";
                                 $sel="";
                                 if ($WidgetSpeed==1)
                                    $sel="selected";
                                 echo"  <option value=\"1\" $sel> Slow </option>\n";
                                 $sel="";
                                 if ($WidgetSpeed==2)
                                    $sel="selected";
                                 echo"  <option value=\"2\" $sel> Fast </option>\n";
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- WIGGLE ------->

                        <tr id="PUSHY_WIDGET_WIGGLE_<?php echo $PUSHY_INDEX?>"  height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Wiggle:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-WIGGLE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_wiggle" style="width:100px;background-color: FFFBF5;">
                              <?php
                                 $sel="";
                                 if ($WidgetWiggle==0)
                                    $sel="selected";
                                 echo"  <option value=\"0\" $sel> Off </option>\n";
                                 $sel="";
                                 if ($WidgetWiggle==1)
                                    $sel="selected";
                                 echo"  <option value=\"1\" $sel> On  </option>\n";
                              ?>
                            </SELECT>
                          </td>
                        </tr>

                        <!------- DELAY ------->

                        <tr id="PUSHY_WIDGET_DELAY_<?php echo $PUSHY_INDEX?>"  height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Delay:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-DELAY')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_delay" style="width:100px;background-color: FFFBF5;">
                              <?php
                                 // if ($WidgetDelay<2) $WidgetDelay=2;
                                 for ($i=0; $i<=10; $i++)
                                   {
                                     $sel="";
                                     if ($i==$WidgetDelay)
                                       $sel="selected";

                                     if ($i==0)
                                       $text = "No Delay ";
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

                        <tr id="PUSHY_WIDGET_PAUSE_<?php echo $PUSHY_INDEX?>" style="display:<?php echo ($WidgetPosture==1)?'none':'';?>" height=42 valign=middle>
                          <td align=right class="tahoma  bold size16">Pause:
                            <a href="#" onClick="return false" style="cursor:help;">
                             <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px;" onmouseover="TagToTip('HELP-PUSHY-PROPERTIES-PAUSE')"></a>
                          </td>
                          <td>
                            <SELECT name="widget_pause" style="width:100px;background-color: FFFBF5;">
                              <?php
                                 // if ($WidgetPause<2) $WidgetPause=2;
                                 for ($i=0; $i<=10; $i++)
                                   {
                                     $sel="";
                                     if ($i==$WidgetPause)
                                       $sel="selected";

                                     if ($i==0)
                                       $text = "No Pause ";
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
                            <input type=button value="PREVIEW PUSHY" class="bigbutton bold" style="width:143px; margin: 0 18px 0 -5px; color: #CC0000;" onClick=javascript:pushy_preview(this.form)>
                            <input type=button value="UPDATE PUSHY" class="bigbutton bold" style="width:137px;  color: #333333;" title="Updates PUSHY's Behavior on Your Website" onClick=javascript:pushy_updateWidget(this.form)>
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
