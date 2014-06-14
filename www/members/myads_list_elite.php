<!------ MY PENDING AD LIST --------->
<?php
 if (count($pending)>0)
   {
?>
    <span class="size18 bold arial" style="line-height:38px">My Pending Ads</span>&nbsp;
       <a href="#" onClick="return false" style="cursor:help">
       <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: -3px;"  onmouseover="TagToTip('HELP-MYADS-PENDING')"></a>

    <table width=620 cellpadding=2 cellspacing=0 border=0  style="border: 1px  solid #E3B6B6; border-collapse: collapse;">
      <tr bgcolor="#FFEDED" height=35>
         <td width="40%" colspan=2 style="border: 1px  solid #E3B6B6;"  class="size14 bold verdana darkgreen">&nbsp;&nbsp; Product Name</td>
         <td width="38%"  style="border: 1px  solid #E3B6B6;" class="size14 bold verdana darkgreen">&nbsp;&nbsp; Product Title</td>
         <td width="22%"  style="border: 1px  solid #E3B6B6;" align=center class="size14 bold verdana darkgreen">Date Created</td>
      </tr>

<?php
     for ($i=0; $i<count($pending); $i++)
       {
          $ad = $pending[$i];
          $ad_id                       = $ad["ad_id"];
          $product_id                  = $ad["product_id"];
          $product_name                = $ad["product_name"];
          $product_title               = $ad["product_title"];
          $product_url                 = $ad["product_url"];
          $reseller_listing            = $ad["reseller_listing"];
          $affiliate_signup_url        = $ad["affiliate_signup_url"];

          $media_type                  = $ad["media_type"];
          $media_format                = $ad["media_format"];
          $media_width                 = $ad["media_width"];
          $media_height                = $ad["media_height"];
          $media_size                  = $ad["media_size"];
          $media_original_width        = $ad["media_original_width"];
          $media_original_height       = $ad["media_original_height"];
          $media_server                = $ad["media_server"];

          $product_description         = $ad["product_description"];
          $product_categories            = $ad["product_categories"];

          $date_created                = $ad["date_created"];
          $last_modified               = $ad["last_modified"];

          $pushy_network               = $ad["pushy_network"];
          $elite_bar                   = $ad["elite_bar"];
          $elite_pool                  = $ad["elite_pool"];
          $product_list                = $ad["product_list"];

          $product_list                = $ad["product_list"];
          $existing_products_requested = $ad["existing_products_requested"];

          $TARGET_HEIGHT=50;
          $TARGET_WIDTH =50;

          $dim="";
          $image_url="";
          if ($media_height > 0 && $media_width > 0)
            {
              list($scaled, $new_width, $new_height) = _scaled_ImageSize($media_original_width, $media_original_height, $TARGET_WIDTH, $TARGET_HEIGHT);
              $dim = "height=$new_height width=$new_width";
              $image_url  = $ad["image_url"]."?icache=".getmicroseconds();
            }
?>
       <tr height=48>
         <td width="40%" colspan=2 style="border: 1px  solid #E3B6B6;" >
           <table width=100% border=0 cellspacing=0 cellpadding=0>
             <tr>
               <td width="20%" align=center><img src="<?php echo $image_url?>" <?php echo $dim?>></td>
               <td width="80%" class="size14 tahoma">&nbsp;&nbsp; <?php echo $product_name?></td>
             </tr>
           </table>
         <td width="38%" style="border: 1px  solid #E3B6B6;"  class="size14 tahoma">&nbsp;&nbsp;&nbsp; <?php echo $product_title?></td>
         <td width="22%" align=center style="border: 1px  solid #E3B6B6;"  class="size14 tahoma"><?php echo $last_modified?> </td>
       </tr>


<?php
       }
?>
    </table>
    <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=615 height=31 style="margin-bottom: 30px;"></div>

<?php
   }
?>


<!------ MY APPROVE AD LIST --------->
<?php
 if (count($active) > 0)
   {
?>
     <span class="size18 bold tahoma" style="line-height:38px">My Approved Ads</span>&nbsp;
       <a href="#" onClick="return false" style="cursor:help">
       <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: -3px;"  onmouseover="TagToTip('HELP-MYADS-APPROVED')"></a>

       <table width=620 cellpadding=2 cellspacing=0 border=0  style="border: 1px  solid #A3D4A1;">
         <tr>
            <td width="40%" colspan=2 class="size16 bold verdana darkgreen" style="padding-left: 9px;"><span style="border-bottom: 1px dotted #339933;">Product Name</span> </td>
            <td width="22%" class="size14 bold Verdana darkgreen"><span style="border-bottom: 1px dotted #339933;">Edit or Remove</span> </td>
            <td width="38%" class="size16 bold arial">
               <table width="100%" bgcolor="#F2FFF0" cellpadding=0 cellspacing=0 border=0 style="border: 1px solid #ADC7AD; border-collapse: collapse;">
                 <tr height=30>
                   <td align=center colspan=4 width="100%" class="size16 bold verdana darkgreen" style="border: 1px solid #ADC7AD;">Product Ad Placement</td>
                 </tr>
                 <tr>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none" title="Your ad will be seen on Pushy referral websites" style="text-decoration:none">Pushy Network</a></td>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none" title="Your product will be seen by Pushy members inside the Affiliate Offer drop down list" style="text-decoration:none">Affiliate Offers</a></td>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none" title="Your ad will be seen by Pushy members inside their backoffice" style="text-decoration:none">Elite<br>Bar</a></td>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none" title="Your ad will be seen throughout the entire Pushy Network" style="text-decoration:none">Elite Ad Pool</a></td>
                 </tr>
               </table>
            </td>
         </tr>


<?php
   for ($i=0; $i<count($active); $i++)
     {
        $ad = $active[$i];

        $ad_id                       = $ad["ad_id"];
        $product_id                  = $ad["product_id"];
        $product_name                = $ad["product_name"];
        $product_title               = $ad["product_title"];
        $product_url                 = $ad["product_url"];
        $reseller_listing            = $ad["reseller_listing"];
        $affiliate_signup_url        = striplt(strtolower($ad["affiliate_signup_url"]));
        if ($affiliate_signup_url=="")
            $affiliate_signup_url="http://";

        $media_type                  = $ad["media_type"];
        $media_format                = $ad["media_format"];
        $media_width                 = $ad["media_width"];
        $media_height                = $ad["media_height"];
        $media_size                  = $ad["media_size"];
        $media_original_width        = $ad["media_original_width"];
        $media_original_height       = $ad["media_original_height"];
        $media_server                = $ad["media_server"];

        $product_description         = $ad["product_description"];
        $product_categories            = $ad["product_categories"];

        $date_created                = $ad["date_created"];
        $last_modified               = $ad["last_modified"];

        $pushy_network               = $ad["pushy_network"];
        $elite_bar                   = $ad["elite_bar"];
        $elite_pool                  = $ad["elite_pool"];
        $product_list                = $ad["product_list"];
        $existing_products_requested = $ad["existing_products_requested"];

        $am_product_owner            = $ad["am_product_owner"];

        $product_active=false;
        $pushy_network_checked = "";
        if ($ad["pushy_network"])
          {
             $pushy_network_checked = "checked";
             $product_active=true;
          }

        $elite_bar_checked = "";
        if ($ad["elite_bar"])
          {
            $elite_bar_checked     = "checked";
            $product_active=true;
          }

        $elite_pool_checked = "";
        if ($ad["elite_pool"])
          {
            $elite_pool_checked    = "checked";
            $product_active=true;
          }

        $product_list_checked = "";
        if ($ad["product_list"])
          {
            $product_list_checked  = "checked";
            $product_active=true;
          }

        // if (!$am_product_owner)
        //   $product_list_checked  = "disabled";


        $TARGET_HEIGHT=50;
        $TARGET_WIDTH =50;

        $dim="";
        $image_url="";
        if ($media_height > 0 && $media_width > 0)
          {
            list($scaled, $new_width, $new_height) = _scaled_ImageSize($media_original_width, $media_original_height, $TARGET_WIDTH, $TARGET_HEIGHT);
            $dim = "height=$new_height width=$new_width";
            $image_url  = $ad["image_url"]."?icache=".getmicroseconds();
          }
?>
        <tr height=48>
          <td width="8%" align=center style="padding-left: 6px;"><img src="<?php echo $image_url?>" <?php echo $dim?>></td>
          <td width="34%" class="size14 bold arial">
            <div style="height: 24px; width:200px; border: 1px solid #ADC7AD; background-color: #F2FFF0; line-height: 25px; margin-top: -1px; padding-left: 4px">
            <span id="ProductName-<?php echo $i?>"> <?php echo $product_name?></span></div>
          </td>
          <td width="20%" align=right>
             <table width="100%"  bgcolor=#F2FFF0 cellpadding=0 cellspacing=0 border=0 style="border: 1px solid #ADC7AD; border-collapse: collapse;">
               <tr>
                 <td  height=25 align=right valign=middle class="size14 arial">
                   <span style="display:<?php echo ($product_active)?'none':''?>; color:#CC0000; font-weight:bold; font-size:12px;">INACTIVE &nbsp;&nbsp;&nbsp;</span>

                   <a href="#" onClick="return false" style="cursor:help">
                   <img src="http://pds1106.s3.amazonaws.com/images/edit.png" style="vertical-align: middle;" border=0 onmouseover="TagToTip('HELP-MYADS-EDIT')" ALT="Edit This Ad"   onClick=javascript:myads_edit_ad(document.MY_PRODUCT_ADS_FORM,<?php echo $i?>,'<?php echo $ad_id?>','<?php echo $product_id?>')></a> &nbsp;&nbsp;

                   <a href="#" onClick="return false" style="cursor:help">
                   <img src="http://pds1106.s3.amazonaws.com/images/remove.png" style="vertical-align: middle;" border=0 onmouseover="TagToTip('HELP-MYADS-REMOVE')" ALT="Remove This Ad" onClick=javascript:myads_remove_ad(document.MY_PRODUCT_ADS_FORM,<?php echo $i?>,'<?php echo $ad_id?>','<?php echo $product_id?>')></a>&nbsp;&nbsp;
                 </td>
               </tr>
             </table>
          </td>
          <td width="38%" class="size14 arial">
             <table width="100%" cellpadding=0 cellspacing=0 border=0 style="border: 1px solid #ADC7AD; border-collapse: collapse;">
               <tr height=25>
                 <td bgcolor="#D2FFD0" align=center width="25%" style="border: 1px solid #ADC7AD;">
                   <input id=pushy_network-<?php echo $i?>  name=pushy_network-<?php echo $ad_id?> type=checkbox <?php echo $pushy_network_checked?> style="width:18px; height:18px;" VALUE="YES" onClick=pushyNetworkClicked(<?php echo $i?>)></td>
                 <td bgcolor="#D2FFD0" align=center width="25%" style="border: 1px solid #ADC7AD;">
                   <?php
                     if ($am_product_owner)
                       {
                   ?>
                         <input id=product_list-<?php echo $i?>   name=product_list-<?php echo $ad_id?>  type=checkbox <?php echo $product_list_checked?>  style="width:18px; height:18px;" VALUE="YES" onClick=productListClicked(this.form,<?php echo $i?>,<?php echo count($active)?>)>
                   <?php
                       }
                     else
                       {
                   ?>
                         &nbsp;
                   <?php
                       }
                   ?>
                 </td>
                 <td bgcolor="#D2FFD0" align=center width="25%" style="border: 1px solid #ADC7AD;">
                   <input id=elite_bar-<?php echo $i?>  name=elite_bar-<?php echo $ad_id?> type=checkbox <?php echo $elite_bar_checked?> style="width:18px; height:18px;" VALUE="YES" onClick=eliteBarClicked(<?php echo $i?>)></td>
                 <td bgcolor="#D2FFD0" align=center width="25%" style="border: 1px solid #ADC7AD;">
                   <input id=elite_pool-<?php echo $i?> name=elite_pool-<?php echo $ad_id?>    type=checkbox <?php echo $elite_pool_checked?> style="width:18px; height:18px;" VALUE="YES" onClick=elitePoolClicked(<?php echo $i?>)></td>
               </tr>
             </table>
             <table border=0 cellspacing=0 cellpadding=0>
               <tr id="<?php echo "ProductList_AffiliateSignup_".$i?>" valign=middle style="display:<?php echo $product_list_checked?"":"none"?>;">
                 <td colspan=4 style="font: 11px arial;">
                   <div style="position:absolute; width:367px; height:22px; margin: 2px 0 0 -135px;">
                     <b class=red>Your Product's Affiliate Signup URL:</b>
                     <input id="<?php echo "ProductList_AffiliateSignup_URL_".$ad_id?>" type=text style="height: 19px; width: 152px; vertical-align: middle; font-size: 11px;" value="<?php echo $affiliate_signup_url?>">
                   </div>
                 </td>
               </tr>
             </table>
          </td>
        </tr>
<?php
       }
?>

      <tr><td height=12 colspan=4 bgcolor=#FFFFFF></td></tr>

    </table>
    <div align=center><img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width=615 height=31></div>

    <div align=center style="width:620px; margin-bottom: 20px;">
      <input type=button style="width: 210px; height: 45px" class=bigbutton value="UPDATE AD PLACEMENT" onClick=javascript:myads_update_ad_placement(this.form,<?php echo count($active)?>)>
    </div>

<?php
   }
?>
