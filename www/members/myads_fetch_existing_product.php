<?php
if (isset($_REQUEST["product_id"]))
   $product_id    = $_REQUEST["product_id"];
if (isset($_REQUEST["operation"]))
   $operation     = $_REQUEST["operation"];

$sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
$sql .= " WHERE ads.product_id='$product_id'";
$sql .= " AND   ads.product_list!=0";                  // this has to stay - so don't look here if a problem arises
$sql .= " AND   ads.existing_products_requested!=0";   // this has to stay - so don't look here if a problem arises
$result = mysql_query($sql,$db);

//echo "<br>operation='$operation'<br>";

if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
  {
    // OK
  }
else
if ($operation == "Update")
  {                                                    // This ad owner is still here - but this product is no longer his Exist Products Selection
    $sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
    $sql .= " WHERE ads.product_id='$product_id'";
    $sql .= " AND   ads.member_id=product.product_owner";
    $result = mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        // OK
      }
    else
      {                                                  // Ad Owner doesn't seem to be around - just Grab the Product if it Exists
        $sql  = "SELECT * from product";
        $sql .= " WHERE product_id='$product_id'";
        $result = mysql_query($sql,$db);
        if ($result && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
          {
            // OK
          }
      }
  }
if (is_array($myrow))
  {
    //---- ADS
    $ad_owner             = $myrow["member_id"];
    $product_id           = $myrow["product_id"];
    $affiliate_signup_url = $myrow["affiliate_signup_url"];
    $product_owner_sales_url = $myrow["product_url"];

    //---- PRODUCT
    $product_owner        = $myrow["product_owner"];
    $product_name         = stripslashes($myrow["product_name"]);
    $product_title        = stripslashes($myrow["product_title"]);
    $product_description  = stripslashes($myrow["product_description"]);
    $product_categories   = $myrow["product_categories"];

    $media_type           = $myrow["media_type"];
    $media_format         = $myrow["media_format"];
    $media_width          = $myrow["media_width"];
    $media_height         = $myrow["media_height"];
    $media_size           = $myrow["media_size"];
    $media_server         = $myrow["media_server"];

    $image_url            = _get_MediaURL($product_id,$media_server,$media_format);

    if ($product_owner_sales_url=="http://" || strlen($product_owner_sales_url) < 12 || !(startsWith($product_owner_sales_url,"http://"))) // Product Link is not Active if URL is invalid
       $product_owner_sales_url="";
    if ($affiliate_signup_url=="http://" || strlen($affiliate_signup_url) < 12 || !(startsWith($affiliate_signup_url,"http://")))          // Affiliate Sales URL is not Active if URL is invalid
       $affiliate_signup_url="";

    if ($product_owner == $PUSHY_ROOT)        // This can happen when a Product is Reassigned to Pushy on a DownGrade or Remove
      {
        $product_owner_sales_url="";
        $affiliate_signup_url="";
      }
?>


    <div style="position:relative; float:left; width:180px; border:1px  solid #FFCC00;">
      <center>
        <span style="padding:6px;">
     <?php
       if (strlen($affiliate_signup_url) > 0)
         {
     ?>
           <span class="arial size12" style="color:#AA0000">If you are not an affiliate for this product, you MUST first sign up to get your Affiliate URL &nbsp; &nbsp;</span>
           <input type="button" onClick=javascript:openPopupWindow('<?php echo $affiliate_signup_url?>',0,0,680,620) class=button value=" Apply Now ">
     <?php
         }
       else
         {
     ?>
           <span class="arial size12" style="color:#AA0000"><b>IMPORTANT:</b> Read this message before you select a different product to advertise.</span>
       <a href="#" onClick="return false" style="cursor:help">

           <br><b  style="background-color: #FFF454">::::: <a href=javascript:void(0) onmouseover="TagToTip('HELP-MYADS-XPL-PRODUCT')">CLICK HERE</a> :::::</b>
     <?php
           //var_dump($sql);
           //var_dump($myrow);
         }
     ?>
        </span style="margin:6px;">
      </center>
    </div>

    <div style="position:relative; float:left; width:400px;">
      <table width="100%" height=40 cellpadding=0 cellspacing=0 border=0 style="border:0px  solid #ffffff;">
        <tr>

         <?php
           if (strlen($product_owner_sales_url) > 0)
             {
         ?>
                <td width="25%" align=center  style="border:0px  solid #ffffff;">
                  <a href=javascript:openPopupWindow('<?php echo $product_owner_sales_url?>',0,0,680,620)><img src="<?php echo $image_url?>" width=40></td>

                <td width="75%" style="font-family:Arial; font-size:16px; border:0px  solid #ffffff; padding-right: 50px"><a
                   href=javascript:openPopupWindow('<?php echo $product_owner_sales_url?>',0,0,680,620) style="text-decoration:none; style="color:#AA0000; font-weight:bold;">
                     <span style="color:#AA0000; font-weight:bold;"><?php echo $product_title?></span></a><br><?php echo $product_description?></td>
         <?php
             }
           else
             {
         ?>
                <td width="25%" align=center style="border:0px  solid #ffffff;"><img src="<?php echo $image_url?>" width=40></td>

                <td width="75%" style="font-family:Arial; font-size:16px; border:0px  solid #ffffff; padding-right: 50px">
                     <span style="color:#AA0000; font-weight:bold;"><?php echo $product_title?></span><br><?php echo $product_description?></td>
         <?php
             }
         ?>

        </tr>
      </table>
    </div>
<?php
  }
?>
