<!------ MY PENDING AD LIST --------->
<?php
 if (count($pending)>0)
   {
?>
    <span class="size18 bold arial" style="line-height:38px">My Pending Ads</span>&nbsp;
       <a href="#" onClick="return false" style="cursor:help">
       <img src="http://pds1106.s3.amazonaws.com/images/question1.png" border=0 style="vertical-align: -3px;"  onmouseover="TagToTip('HELP-MYADS-PENDING')"></a>

    <table width=620 cellpadding=2 cellspacing=0 border=0  style="border: 1px  solid #E3B6B6; border-collapse: collapse;">
      <tr bgcolor="#F2FFF0" height=35>
         <td width="40%" colspan=2 style="border: 1px  solid #E3B6B6;"  class="size14 bold verdana darkgreen">&nbsp;&nbsp; Product Name</td>
         <td width="38%"  style="border: 1px  solid #E3B6B6;" class="size14 bold verdana darkgreen">&nbsp;&nbsp; Product Title</td>
         <td width="22%"  style="border: 1px  solid #E3B6B6;" align=center class="size14 bold verdana darkgreen">Date Created</td>
      </tr>

<?php
   for ($i=0; $i<count($pending); $i++)
     {
       $ad = $pending[$i];
       $ad_id               = $ad["ad_id"];
       $product_id          = $ad["product_id"];
       $product_name        = $ad["product_name"];
       $product_title       = $ad["product_title"];
       $reseller_listing    = $ad["reseller_listing"];

       $media_type            = $ad["media_type"];
       $media_format          = $ad["media_format"];
       $media_width           = $ad["media_width"];
       $media_height          = $ad["media_height"];
       $media_size            = $ad["media_size"];
       $media_original_width  = $ad["media_original_width"];
       $media_original_height = $ad["media_original_height"];
       $media_server          = $ad["media_server"];

       $product_description = $ad["product_description"];
       $product_categories  = $ad["product_categories"];

       $date_created        = $ad["date_created"];
       $last_modified       = $ad["last_modified"];

       $TARGET_HEIGHT=50;
       $TARGET_WIDTH =50;

       if ($media_height > 0 && $media_width > 0 && ($reseller_listing))
         {
           list($scaled, $new_width, $new_height) = _scaled_ImageSize($media_original_width, $media_original_height, $TARGET_WIDTH, $TARGET_HEIGHT);
           $dim = "height=$new_height width=$new_width";
           $image_url  = $ad["image_url"];  //  "?icache=".getmicroseconds();
         }
       else
         {
           // $dim = "height=$TARGET_HEIGHT width=$TARGET_WIDTH";
           $dim = "height=$TARGET_HEIGHT";
           $image_url  = "http://pds1106.s3.amazonaws.com/images/no-image.gif";
         }

       $img="<img src=\"$image_url\" $dim>";
?>
       <tr height=48>
         <td width="40%" colspan=2 style="border: 1px  solid #E3B6B6;" >
           <table width=100% border=0 cellspacing=0 cellpadding=0>
             <tr>
               <td width="20%" align=center><img src="<?php echo $image_url?>" <?php echo $dim?> onmouseover="TagToTip('HELP-MYADS-NO-IMAGE')"></td>
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



<!------ MY APPROVED AD LIST --------->
<?php
 if (count($active) > 0)
   {
?>
     <span class="size18 bold arial" style="line-height:38px">My Approved Ads</span>
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
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none"  title="Your ad will be seen on Pushy referral websites">Pushy Network</a></td>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none"  title="As an ELITE member, your product would be seen by Pushy members inside the Affiliate Offer drop down list">Affiliate Offers</a></td>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none"  title="As an ELITE member, your ad would be seen by Pushy members inside their backoffice">Elite<br>Bar</a></td>
                   <td align=center width="25%" class="size13 arial" style="border: 1px solid #ADC7AD;">
                     <a href="#" onClick="return false" style="cursor:help;text-decoration:none"  title="As an ELITE member, your ad would be seen throughout the entire Pushy Network">Elite Ad Pool</a></td>
                 </tr>
               </table>
            </td>
         </tr>

<?php
   for ($i=0; $i<count($active); $i++)
     {
       $ad = $active[$i];

       $ad_id                 = $ad["ad_id"];
       $product_id            = $ad["product_id"];
       $product_name          = $ad["product_name"];
       $product_title         = $ad["product_title"];
       $reseller_listing      = $ad["reseller_listing"];
       $affiliate_signup_url  = $ad["affiliate_signup_url"];

       $media_type            = $ad["media_type"];
       $media_format          = $ad["media_format"];
       $media_width           = $ad["media_width"];
       $media_height          = $ad["media_height"];
       $media_size            = $ad["media_size"];
       $media_original_width  = $ad["media_original_width"];
       $media_original_height = $ad["media_original_height"];
       $media_server          = $ad["media_server"];

       $product_description   = $ad["product_description"];
       $product_categories    = $ad["product_categories"];

       $date_created          = $ad["date_created"];
       $last_modified         = $ad["last_modified"];

       $TARGET_HEIGHT=50;
       $TARGET_WIDTH =50;

       if ($media_height > 0 && $media_width > 0 && ($reseller_listing))
         {
           list($scaled, $new_width, $new_height) = _scaled_ImageSize($media_original_width, $media_original_height, $TARGET_WIDTH, $TARGET_HEIGHT);
           $dim = "height=$new_height width=$new_width";
           $image_url  = $ad["image_url"];  //  "?icache=".getmicroseconds();
         }
       else
         {
           // $dim = "height=$TARGET_HEIGHT width=$TARGET_WIDTH";
           $dim = "height=$TARGET_HEIGHT";
           $image_url  = "http://pds1106.s3.amazonaws.com/images/no-image.gif";
         }

       $img="<img src=\"$image_url\" $dim>";
?>

        <tr height=48>

          <td width="8%" align=center style="padding-left: 6px;"><a href=javascript:void(0) onmouseover="TagToTip('HELP-MYADS-NO-IMAGE')"><?php echo $img?></a></td>
          <td width="34%" class="size14 bold arial">
            <div style="height: 24px; width:200px; border: 1px solid #ADC7AD; background-color: #F2FFF0; line-height: 25px; margin-top: -1px; padding-left: 4px">
            <span id="ProductName-<?php echo $i?>"> <?php echo $product_name?></span></div>
          </td>

          <td width="20%" align=right>
             <table width="100%"  bgcolor=#F2FFF0 cellpadding=0 cellspacing=0 border=0 style="border: 1px solid #ADC7AD; border-collapse: collapse;">
               <tr>
                 <td  height=25 align=right valign=middle class="size14 arial">

                   <a href="#" onClick="return false" style="cursor:help">
                   <img src="http://pds1106.s3.amazonaws.com/images/edit.png" style="vertical-align: middle;" border=0 onmouseover="TagToTip('HELP-MYADS-EDIT')" ALT="Edit This Ad"   onClick=javascript:myads_edit_ad(document.MY_PRODUCT_ADS_FORM,<?php echo $i?>,'<?php echo $ad_id?>','<?php echo $product_id?>')></a> &nbsp;&nbsp;

                   <a href="#" onClick="return false" style="cursor:help">
                   <img src="http://pds1106.s3.amazonaws.com/images/remove.png" style="vertical-align: middle;" border=0 onmouseover="TagToTip('HELP-MYADS-REMOVE')" ALT="Remove This Ad" onClick=javascript:myads_remove_ad(document.MY_PRODUCT_ADS_FORM,<?php echo $i?>,'<?php echo $ad_id?>','<?php echo $product_id?>')></a>&nbsp;&nbsp;
                 </td>
               </tr>
             </table>
          </td>

          <td width="38%">
             <table width="100%" cellpadding=0 cellspacing=0 border=0>
               <tr height=36>
                 <td valign=middle width="100%">
                   <div style="position:absolute; float: left; max-width: 222px; max-height: 37; margin: -19px 0 0 -2px; "><img src="http://pds1106.s3.amazonaws.com/images/elite-adbar.gif"></div>
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

<?php
   }
?>
