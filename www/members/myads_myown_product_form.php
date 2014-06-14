  <?php $MAX_DESCRIPTION_LENGTH=65; $TEXT_WIDTH=128; ?>
  <tr>
    <td align="right" valign="middle" width=180><b>Product Name:</b>
       <a href="#" onClick="return false" style="cursor:help">
      <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-107')"></a>
    </td>
    <td>
      <input class=form_input style="background-color: #FFFDF7; text-transform:capitalize" type="text" name="myown_product_name" size="25" maxlength="20" value="<?php echo ($op=="Update")?$product_name:""?>">
        20 characters max
    </td>
  </tr>
  <tr>
    <td align="right" valign="middle"><b>Product Title:</b>
       <a href="#" onClick="return false" style="cursor:help">
      <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-102')"></a>
    </td>
    <td>
      <input class=form_input type="text" name="myown_product_title" size="25" maxlength="20" style="text-transform:capitalize; background-color: #FFFDF7"  value="<?php echo ($op=="Update")?$product_title:""?>" onblur=javascript:myads_preview_refresh(this.form) onfocus=javascript:myads_preview_refresh(this.form) onclick=javascript:myads_textFieldClicked(this.form) onkeyup=javascript:myads_preview_refresh(this.form)>
      20 characters max
    </td>
  </tr>
  <tr height=60>
    <td align="right" valign="top"><b>Product Description:</b>
       <a href="#" onClick="return false" style="cursor:help">
       <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-104')"><br>&nbsp;<br></a>
       <div style="margin-top: -8px;">
       <input class=smalltext tabindex=-1 type="text" name="counter" maxlength="2" size="1" value=<?php echo $MAX_DESCRIPTION_LENGTH?> onblur="myads_textCounter(this.form,this.form.counter,'Product Description',this,<?php echo $MAX_DESCRIPTION_LENGTH?>);">
         characters remaining&nbsp;</div>
    </td>
    <td valign=middle><div style="float:right; padding: 11px 10px 0 0; z-index:1"><?php echo $MAX_DESCRIPTION_LENGTH-5?>-<?php echo $MAX_DESCRIPTION_LENGTH?> characters max <br> <span class=size12>(Preview will wrap words)</span></div>
      &nbsp;<textarea class="description_input" name="myown_product_description" rows=2
        onkeyup="myads_textCounter(this.form,this,'Product Description',this.form.counter,<?php echo $MAX_DESCRIPTION_LENGTH?>);" onclick=javascript:myads_textFieldClicked(this.form)  onblur="myads_textCounter(this.form,this,'Product Description',this.form.counter,<?php echo $MAX_DESCRIPTION_LENGTH?>);"><?php echo ($op=="Update")?$product_description:""?></textarea>
    </td>
  </tr>

  <tr>
    <td align="right" valign="top"><b>Product Categories:</b>
       <a href="#" onClick="return false" style="cursor:help">
      <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-105')"></a>

        <div align=left style="margin: 25px 0 0 0; width: 175px; font-size: 12px; color: #FF0000">
          <p align=center class="bold darkred">* Select 1 - 7 Categories *</p>
          <p style="margin-top: -7px">The <b>narrower</b> your description (fewer categories), the more targeted your traffic will be, but in smaller quantities. </p>
          <p style="margin-top: -7px">The <b>broader</b> your description (more categories) the less targeted your traffic will be, but in greater quantities.</p>
        </div>

    </td>
    <td>
      <div style="float: right; margin-top: 75px">Press/hold <b>Ctrl Key</b><br>while making selections
        <!----------- p style="margin-top: 60px;">
          <a tabindex=-1 href="javascript:openPopup('/members/popup.php?tp=definition-categories&sid=<?php echo $sid?>&mid=<?php echo $mid?>',670,400,true)">View Definitions</a>
        </p ----------->
      </div>
      <?php
         $categories=array();
         $tarray=explode("~",$product_categories);
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
      &nbsp;<SELECT NAME="myown_product_categories"  class="textform darkgreen" size=10 multiple style="background-color: #FFFDF7; width:260px;">
        <?php
           asort($ProductCategories);
           foreach ($ProductCategories AS $cat => $ctitle)
             {
               $sel="";
               if ($op=="Update" && isset($categories[$cat]))
                 $sel="selected";
               echo "  <OPTION VALUE=\"$cat\" $sel>$ctitle</OPTION>\n";
             }
        ?>
      </SELECT>
    </td
  </tr>
  <tr>
    <td align="right" valign="middle"><b>Product Sales URL:</b>
       <a href="#" onClick="return false" style="cursor:help">
       <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-103')"></a>
    </td>
    <td valign=middle>
      <?php
         $purl="http://";
         if ($op=="Update" && strlen($product_url) >= 7  && substr($product_url,0,7) == "http://")
           $purl=$product_url;
      ?>
      <input class="form_input" style="background-color: #FFFDF7; width:327px;" type="text" name="myown_product_url" maxlength="100" value="<?php echo $purl?>"><input type=button tabindex=-1 style="vertical-align: 1px;" value="Test URL" onClick=myads_TestSalesURL(this.form,this.form.myown_product_url)>
    </td>
  </tr>

  <?php
    if ($pushy_user_level != "VIP")
      {
        if ($op == "Update")
          {
  ?>
            <tr height=40>
              <td align="right" valign="middle"><b>New Product Image:</b>
       <a href="#" onClick="return false" style="cursor:help">
                <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-106')"></a>
              </td>
              <td valign="middle" style="color: #000FFF;">
                <input name=IMAGE_UPLOAD_OPTION style="vertical-align: -2px;" type="radio" value="NO" checked onClick=javascript:myads_hide("UPDATE_PRODUCT_IMAGE")>
                  No &nbsp;&nbsp;&nbsp;
                <input name=IMAGE_UPLOAD_OPTION style="vertical-align: -2px;" type="radio" value="YES" onClick=javascript:myads_show("UPDATE_PRODUCT_IMAGE")>
                  Yes
              </td>
            </tr>
            <tr id="UPDATE_PRODUCT_IMAGE" style="display:none">
              <td align="right" valign="middle" style="border-top: 1px solid #FFFFFF;"><b>Upload Image:</b>
                 <a href="#" onClick="return false" style="cursor:help">
                 <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-106')"></a>
              </td>
              <td>
                <input class=form_input type="file" name="myown_product_image" maxlength="" value="" size=50 style="font-size:14px; background-color: #FFFDF7;">
              </td>
            </tr>
      <?php
          }
        else
          {
      ?>
            <tr>
              <td align="right" valign="middle"><b>Product Image:</b>
                 <a href="#" onClick="return false" style="cursor:help">
                 <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: middle;"  onmouseover="TagToTip('HELP-MYADS-106')"></a>
              </td>
              <td>
                <input class=form_input type="file" name="myown_product_image" maxlength="" value="" size=50 style="font-size:14px; background-color: #FFFDF7;">
              </td>
            </tr>
  <?php
          }
     }
  ?>
