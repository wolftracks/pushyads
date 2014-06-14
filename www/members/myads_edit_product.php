<?php
$myads      = array();
$myproducts = array();
$sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
$sql .= " WHERE ads.member_id='$mid' ";
$sql .= " ORDER BY product.product_title";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        $aid              = $myrow["ad_id"];
        $pid              = $myrow["product_id"];
        $reseller_listing = $myrow["reseller_listing"];

        $am_product_owner    = ((!$reseller_listing) && $myrow["product_owner"] == $mid)?1:0; // Am I the product Owner

        $myproducts[$pid] = ($am_product_owner==1);
        $myads[$aid]      = ($am_product_owner==1);
      }
  }

//----------------------------------------------------------------------------------------------------------------------

$ad_id      = $_REQUEST["ad_id"];
$product_id = $_REQUEST["product_id"];

$sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
$sql .= " WHERE ads.member_id='$mid' ";
$sql .= " AND   ads.ad_id='$ad_id' ";
$sql .= " AND   ads.product_id='$product_id'";
$result = mysql_query($sql,$db);
if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
  {
    //---- ADS
    $product_url          = $myrow["product_url"];
    $affiliate_signup_url = $myrow["affiliate_signup_url"];
    $pushy_network        = $myrow["pushy_network"];
    $elite_bar            = $myrow["elite_bar"];
    $elite_pool           = $myrow["elite_pool"];
    $product_list         = $myrow["product_list"];
    $date_created         = $myrow["date_created"];
    $last_modified        = $myrow["last_modified"];
    $reseller_listing     = $myrow["reseller_listing"];

    //---- PRODUCT
    $product_owner        = $myrow["product_owner"];
    $product_submit_date  = $myrow["product_submit_date"];
    $product_title        = stripslashes($myrow["product_title"]);
    $product_name         = stripslashes($myrow["product_name"]);
    $product_description  = stripslashes($myrow["product_description"]);
    $product_categories   = $myrow["product_categories"];

    $media_type           = $myrow["media_type"];
    $media_format         = $myrow["media_format"];
    $media_width          = $myrow["media_width"];
    $media_height         = $myrow["media_height"];
    $media_size           = $myrow["media_size"];
    $media_server         = $myrow["media_server"];

    $am_product_owner     = ((!$reseller_listing) && $myrow["product_owner"] == $mid)?1:0; // Am I the product Owner

    $image_url            = _get_MediaURL($product_id,$media_server,$media_format);

    if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
      {
        $pushy_user_level="VIP";
      }
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
      {
        $pushy_user_level="PRO";
      }
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
      {
        $pushy_user_level="ELITE";
      }

  }
else
  {
    exit;
  }
?>


<?php
  if (FALSE)
    {
      echo "<PRE>";
      print_r($myrow);
      echo "</PRE><br>\n\n";
    }
?>

<?php
$op="Update";
if ($reseller_listing)
  {
?>
    <!-- ---- UPDATE_EXISTING PRODUCT ------- -->
    <span class=spacer1>&nbsp;</span>
    <form name=UPDATE_EXISTING_PRODUCT_FORM method=POST action="NULL">
    <input type=hidden name="mid" value="<?php echo $mid?>">
    <input type=hidden name="sid" value="<?php echo $sid?>">
    <input type=hidden name="ad_id" value="<?php echo $ad_id?>">

    <!--- Not Sure these have to be there - Dangerous - Remove if not needed
    <input type=hidden name="product_id" value="<?php echo $product_id?>">
    --->

    <input type=hidden name="pushy_user_level" value="<?php echo $pushy_user_level?>">
    <input type=hidden name="save_product_id" value="<?php echo $product_id?>">
    <input type=hidden name="save_product_url" value="<?php echo $product_url?>">
    <input type=hidden name="op"  value="Update">

    <div align=center class="bold red size20" style="padding-bottom: 20px">EDIT Your Product Ad Here</div>

    <table align=center width=620 class=gridb1 style="background-color: #FFF7E5;">
      <?php  include("myads_existing_product_form.php"); ?>
    </table>

    <div align=center style="margin-bottom: 20px">
      <img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width="615" height="31">
      <input id="UpdateExistingProduct" type=button  class=bigbutton style="width: 100px;" value="UPDATE" onClick=javascript:myads_update_existing_product(this.form,"UpdateExistingProduct")>&nbsp;&nbsp;&nbsp;
      <input type=button  class=bigbutton style="width: 100px;" value="CANCEL" onClick=javascript:tabClicked('myads',true)>
    </div>

    </form>
<?php
  }
else
if ($am_product_owner)
  {
?>
    <span class=spacer1>&nbsp;</span>
    <form name=UPDATE_MYOWN_PRODUCT_FORM enctype="multipart/form-data" method=POST action="link.php" target=FRAME_MYADS_MYOWN_SUBMIT onSubmit="return myads_validateMyOwnProduct(this,'UpdateOwnProduct')">
    <input type=hidden name="mid" value="<?php echo $mid?>">
    <input type=hidden name="sid" value="<?php echo $sid?>">
    <input type=hidden name="ad_id" value="<?php echo $ad_id?>">
    <input type=hidden name="product_id" value="<?php echo $product_id?>">
    <input type=hidden name="product_list_enabled" value="<?php echo $product_list?>">
    <input type=hidden name="pushy_user_level" value="<?php echo $pushy_user_level?>">
    <input type=hidden name="product_categories" value="<?php echo $product_categories?>">
    <input type=hidden name="op"  value="Update">
    <input type=hidden name="tp"  value="myads_update_myown_product">


    <div align=center class="bold red size20" style="padding-bottom: 20px">EDIT Your Product Ad Here</div>

    <table align=center width=620 class=gridb1 style="background-color: #FFF7E5;">
      <?php include("myads_myown_product_form.php");?>
    </table>

    <center>
      <img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width="615" height="31">
    </center>

    <table align=center width=560 cellpadding=0 cellspacing=0 border=0 class="arial size14" style="margin-bottom: 20px; font-weight:bold;">
      <tr height=28>
        <td><a href=javascript:myads_preview(document.UPDATE_MYOWN_PRODUCT_FORM)>
          <span id="PREVIEW_UPDATE_1" class="arial size14" style="color:#CC0000; text-decoration:underline;">Preview Your Ad</span></a></td>
        <td align=right><a href=javascript:myads_preview(document.UPDATE_MYOWN_PRODUCT_FORM)>
          <span id="PREVIEW_UPDATE_2" class="arial size14" style="color:#CC0000; text-decoration:underline;">Preview Your Ad</span></a></td>
      </tr>
      <tr height=28><td colspan=2 align=center>
         <input id="UpdateOwnProduct" type=submit class=bigbutton style="width: 100px;" value="UPDATE"> &nbsp;&nbsp;&nbsp;
         <input type=button class=bigbutton style="width: 100px;" value="CANCEL" onClick=javascript:tabClicked('myads',true)>
      </td></tr>
    </table>

    </form>
<?php
  }
?>

