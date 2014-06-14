<?php
$firstname = stripslashes($memberRecord["firstname"]);
$lastname  = stripslashes($memberRecord["lastname"]);
$email     = stripslashes($memberRecord["email"]);

include_once("scaling.inc");
?>

<div align=right style="position:absolute; margin: -10px 0 0 640px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/tab_pushy.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim.gif"></a>
</div>

<font size=5><b>Configure your <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -2px">&#8482 Widget here <?php echo $firstname?></b></font>

<p class=largetext style="margin-bottom: 25px;">Make him dance, bounce, float, hover, or just stand still. Once you have configured him to look like you want him to, you can 
  EDIT him and never had to change the code on your site, once you place it inside your html. Preview him first, then copy and paste the snippet of code on your web page, and 
  you're ready for <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -2px">&#8482 to captivate your site visitors.</p>


<!----------------------------------------------------------- START EXPAND/COLLAPSE FORM ----------------------------------------------------------->
<?php
include_once("tips/pushy_properties.tips");
?>

<table width=710 align=center cellspacing=0 cellpadding=0 bgcolor="#FFEECC" class=bgborder>
  <tr>
    <td>
      <table width=700 align=center cellspacing=10 cellpadding=0 bgcolor="#FFEECC" style="margin: 10px 0 10px 0;">

        <tr height=32>
          <td width=550 colspan=2  bgcolor=#F2FFF0 style="border:1px solid #17B000; padding-left:10px;">
            <img src="http://pds1106.s3.amazonaws.com/images/config-p.png"  style="vertical-align: -6px;">&nbsp;
            <b class="tahoma size20">Create a NEW <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px"> Configuration</b></td>
          <td width=12% align=center valign="absmiddle">
            <input id='widget-button-0' style="vertical-align: -6px; width: 90px;" type="button" class=bigbutton value="OPEN" onClick=javascript:pushy_widgetClicked(0,'')>
          </td>
        </tr>

        <!------- NEW PUSHY WIDGET ----->
        <tr id="WidgetDetail-0" style="display:none;"><td colspan=3><div id="PushyDetail-0"></div></td></tr>

        <!------- EXISTING PUSHY WIDGETS ----->
     <?php
       $sql  = "SELECT * from widget ";
       $sql .= " WHERE member_id='$mid'";
       $sql .= " AND enabled>0";
       $sql .= " ORDER BY widget_name";
       $result = mysql_query($sql,$db);

       // printf("SQL:%s\n",$sql);
       // printf("ERR:%s\n",mysql_error());

       $PUSHY_INDEX=0;
       if ($result)
         {
           while ($widget = mysql_fetch_array($result,MYSQL_ASSOC))
             {

               $WidgetName       = $widget["widget_name"];
               $WidgetKey        = $widget["widget_key"];
               $WidgetDomain     = $widget["domain"];
               $PUSHY_INDEX++;
     ?>
           <tr height=32>
              <td width=440 style="border:1px solid #17B000; background-color:#F2FFF0; padding-left:11;" class="tahoma size16">
                <img src="http://pds1106.s3.amazonaws.com/images/edit-p.png" style="vertical-align: -9px;">&nbsp;
                <b><span id="<?php echo "pushy-widget-name-".$PUSHY_INDEX?>"><?php echo $WidgetName?></span></b> <span class=smalltext style="text-transform: lowercase;">(<?php echo $WidgetDomain?>)</span>
              </td>

              <td width=12% style="border:0px; background-color:#FFEECC;">
                 <input id="widget-button-<?php echo $PUSHY_INDEX?>" style="vertical-align: -6px; width: 90px;" type="button" class=bigbutton value="OPEN" onClick=javascript:pushy_widgetClicked(<?php echo $PUSHY_INDEX?>,'<?php echo $WidgetKey?>')>
              </td>
              <td width=12% align=center valign="absmiddle">
                 <form action=NULL>
                   <input type="hidden" name="mid" value="<?php echo $mid?>">
                   <input type="hidden" name="sid" value="<?php echo $sid?>">
                   <input type="hidden" name="widget_key" value="<?php echo $WidgetKey?>">
                   <input type="hidden" name="widget_name" value="<?php echo $WidgetName?>">
                   <input style="vertical-align: -6px; width: 90px;" type="button" class=bigbutton value="REMOVE" onClick=javascript:pushy_removeWidget(this.form)>
                 </form>
              </td>
            </tr>
            <tr id="WidgetDetail-<?php echo $PUSHY_INDEX?>" style="display:none;"><td colspan=3><div id="PushyDetail-<?php echo $PUSHY_INDEX?>"></div></td></tr>
     <?php
             }
         }
     ?>

      </table>
    </td>
  </tr>
</table>
<div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=670 height=31></div>

</body>
</html>
<?php
  $response= new stdClass();
  $response->success         = "TRUE";
  $response->ExistingWidgets = $PUSHY_INDEX;
?>
