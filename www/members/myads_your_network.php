<?php
$myads      = array();
$myproducts = array();
$count=0;
$sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
$sql .= " WHERE ads.member_id='$mid' ";
$sql .= " ORDER BY product.product_title";
$result = mysql_query($sql,$db);
if ($result)
  {
    $count = mysql_num_rows($result);
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
?>

<DIV ID="AD_CREATION">

<?php
// printf("USER LEVEL=%d<br>\n",$memberRecord["user_level"]);
// printf("PUSHY_LEVEL_PRO=%d<br>\n",$PUSHY_LEVEL_PRO);
// printf("PUSHY_LEVEL_ELITE=%d<br>\n",$PUSHY_LEVEL_ELITE);
// printf("COUNT=%d<br>\n",$count);
// exit;

if ( ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP   && $count >= 1) ||
     ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO   && $count >= 1) ||
     ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE && $count >= 4) )
  {
    $pushy_user_level="";
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
      {
        $pushy_user_level="VIP";
        $todayAsArray=getDateTodayAsArray();
        $registeredDate = $memberRecord["date_registered"];
        $registerdArray = dateToArray($registeredDate);
        $daysUsed  = abs(dateDifference($registerdArray, $todayAsArray));

        // ---- TEST ONLY -----
        if ($daysUsed >= 30)
          $remaining = 0;
        else
          $remaining = 30 - $daysUsed;

    ?>

         <table border=0 cellpadding=12 cellspacing=0 style="border: 1px dotted #FF0000; margin-bottom: 20px;" bgcolor=#FFFFC7>
           <tr>
             <td class="red size14 verdana">

               Your membership entitles you to (<b>1</b>) product ad, <?php echo $firstname?>.
               To add another product ad, you must first remove an existing ad, or
               <a href=javascript:membership_plan()><b>upgrade</b></a> your account.
               (There are (<b><?php echo $remaining?></b>) days remaining in your Free Trial).

             </td>
           </tr>
         </table>

    <?php
      }
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
      {
        $pushy_user_level="PRO";
    ?>

         <table border=0 cellpadding=12 cellspacing=0 style="border: 1px dotted #FF0000; margin-bottom:20px" bgcolor=#FFFFC7>
           <tr>
             <td class="red size14 verdana">

               Your membership entitles you to (<b>1</b>) product ad, <?php echo $firstname?>.
               To add another product ad, you must first remove an existing ad, or
               <a href=javascript:membership_plan()><b>upgrade</b></a> your account.

             </td>
           </tr>
         </table>

    <?php
      }
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
      {
        $pushy_user_level="ELITE";
    ?>

         <table border=0 cellpadding=12 cellspacing=0 style="border: 1px dotted #FF0000; margin-bottom: 20px;" bgcolor=#FFFFC7>
           <tr>
             <td class="red size14 verdana">

               Your membership entitles you to (<b>4</b>) product ads, <?php echo $firstname?>.
               To add another product ad, you must first remove an existing ad.

             </td>
           </tr>
         </table>

    <?php
      }
  }
else

if ( ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP   && $count < 1) ||
     ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO   && $count < 1) ||
     ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE && $count < 4) )
  {
    $op = "Create";
    $pushy_user_level="";

    if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
      {
        $pushy_user_level="VIP";
        $todayAsArray=getDateTodayAsArray();
        $registeredDate = $memberRecord["date_registered"];
        $registerdArray = dateToArray($registeredDate);
        $daysUsed  = abs(dateDifference($registerdArray, $todayAsArray));
        $remaining = 30 - $daysUsed;

        // ---- TEST ONLY -----
        if ($remaining > 30)
          $remaining = ($remaining % 30) + 1;
    ?>

         <table border=0 cellpadding=12 cellspacing=0 style="border: 1px dotted #FF0000; margin-bottom: 20px;" bgcolor=#FFFFC7>
           <tr>
             <td class="red size14 verdana">

               Your membership entitles you to (<b>1</b>) product ad, <?php echo $firstname?>.
               You can add your product ad below. (There are (<b><?php echo $remaining?></b>)
               days remaining in your Free Trial).

             </td>
           </tr>
         </table>

    <?php
      }
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
      {
        $pushy_user_level="PRO";
    ?>

         <table border=0 cellpadding=12 cellspacing=0 style="border: 1px dotted #FF0000; margin-bottom: 20px;" bgcolor=#FFFFC7>
           <tr>
             <td class="red size14 verdana">

               Your membership entitles you to <b>1</b> product ad, <?php echo $firstname?>.
               You can add your product ad below.

             </td>
           </tr>
         </table>

    <?php
      }
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
      {
        $pushy_user_level="ELITE";
        $rem = 4-$count;
    ?>

         <table border=0 cellpadding=12 cellspacing=0 style="border: 1px dotted #FF0000; margin-bottom: 20px;" bgcolor=#FFFFC7>
           <tr>
             <td class="red size14 verdana">

               Your membership entitles you to (<b>4</b>) product ads, <?php echo $firstname?>.
               You can add your (<b><?php echo $rem?></b>) remaining product <?php echo (($rem==1)?"ad":"ads")?> below.

             </td>
           </tr>
         </table>

    <?php
      }
    ?>


    <DIV ID="CREATE_EXISTING_PRODUCT">
      <form name=CREATE_EXISTING_PRODUCT_FORM method=POST action="NULL">
      <input type=hidden name="mid" value="<?php echo $mid?>">
      <input type=hidden name="sid" value="<?php echo $sid?>">
      <input type=hidden name="op"  value="<?php echo $op?>">


        <table align=center border=0 cellspacing=0 cellpadding=0 style="margin-top: 25px;">
          <tr>
            <td>

              <div class=size20 style="position:absolute; margin: -18px 0 0 -272px; width: 600; height: 46px; padding-top: 25px">
                <img src="http://pds1106.s3.amazonaws.com/images/arrow_blue-lt.png" style="vertical-align: top;">
                <a href="#" onClick="return false" style="cursor:help">
                <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px; margin-right: 10px;"  onmouseover="TagToTip('HELP-MYADS-101')"></a>
                <b>I want <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px">  to advertise one of these:
                <img src="http://pds1106.s3.amazonaws.com/images/arrow_blue.png" style="vertical-align: top;"></td>
              </div>

            </td>
          </tr>
        </table>

      <table align=center width=100% class=gridb1 bgcolor=#FFF7E5 style="margin-top: 25px;">
        <tr height=90 bgcolor=#FFFFFF>
          <td colspan=2 style="background-image:url('http://pds1106.s3.amazonaws.com/images/bg-myads.gif'); background-repeat: repeat-x;">
            <p align=center style="padding: 10px 0; color: #000FFF;" class=size18>
              <input id=EXISTING_CREATE_EXISTING_PRODUCT_SELECTOR style="vertical-align: 2px;" type="radio" name="ProductSubmission" checked onClick=javascript:myads_setExistingProduct()>
                  <b>An Affiliate Offer</b> &nbsp;&nbsp;&nbsp;&nbsp;
              <input id=EXISTING_CREATE_MYOWN_PRODUCT_SELECTOR style="vertical-align: 2px;" type="radio" name="ProductSubmission" onClick=javascript:myads_setMyOwnProduct(this.form)>
                  <b>My Own Product/Service</b>
            </p>
          </td>
        </tr>

            <?php include("myads_existing_product_form.php");?>

      </table>

      <div align=center style="margin-bottom: 40px;">
        <img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width="615" height="31">
        <input id=SubmitExistingProduct type=button  class=bigbutton style="width:170px; height: 45px;" value="SUBMIT YOUR AD" onClick=javascript:myads_advertise_existing_product(this.form,"SubmitExistingProduct")>
      </div>

      </form>
    </DIV>
    <!-- ----------------------------- -->



    <!-- ----- OWN PRODUCT ----------- -->
    <DIV ID="CREATE_MYOWN_PRODUCT" style="display:none">
      <form name=CREATE_MYOWN_PRODUCT_FORM enctype="multipart/form-data" method=POST action="link.php" target=FRAME_MYADS_MYOWN_SUBMIT onSubmit="return myads_validateMyOwnProduct(this,'SubmitOwnProduct')">
      <input type=hidden name="mid" value="<?php echo $mid?>">
      <input type=hidden name="sid" value="<?php echo $sid?>">
      <input type=hidden name="op"  value="<?php echo $op?>">
      <input type=hidden name="product_categories" value="<?php echo $product_categories?>">
      <input type=hidden name="tp"  value="myads_submit_myown_product">
      <input type=hidden name="submitted" value="0">

      <input type=hidden name="pushy_user_level" value="<?php echo $pushy_user_level?>">

        <table align=center border=0 cellspacing=0 cellpadding=0  style="margin-top: 25px;">
          <tr>
            <td>

              <div class=size20 style="position:absolute; margin: -18px 0 0 -272px; width: 600; height: 46px; padding-top: 25px">
                <img src="http://pds1106.s3.amazonaws.com/images/arrow_blue-lt.png" style="vertical-align: top;">
                <a href="#" onClick="return false" style="cursor:help">
                <img src="http://pds1106.s3.amazonaws.com/images/question1.png" style="vertical-align: -2px; margin-right: 10px;"  onmouseover="TagToTip('HELP-MYADS-101')"></a>
                <b>I want <img src="http://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px"> to advertise one of these:
                <img src="http://pds1106.s3.amazonaws.com/images/arrow_blue.png" style="vertical-align: top;">
              </div>

            </td>

          </tr>
        </table>

      <table align=center width=100% class=gridb1 bgcolor=#FFF7E5 style="margin-top: 25px;">
        <tr height=90 bgcolor=#FFFFFF>
          <td colspan=2 style="background-image:url('http://pds1106.s3.amazonaws.com/images/bg-myads.gif'); background-repeat: repeat-x;">
            <p align=center style="padding: 10px 0; color: #000FFF;" class=size18>
            <input id=MYOWN_CREATE_EXISTING_PRODUCT_SELECTOR style="vertical-align: 2px;" type="radio" name="ProductSubmission"onClick=javascript:myads_setExistingProduct()>
                <b>An Affiliate Offer</b> &nbsp;&nbsp;&nbsp;&nbsp;
            <input id=MYOWN_CREATE_MYOWN_PRODUCT_SELECTOR style="vertical-align: 2px;" type="radio" name="ProductSubmission" checked  onClick=javascript:myads_setMyOwnProduct(this.form)>
                <b>My Own Product/Service<br>
            </p>
          </td>
        </tr>

        <?php include("myads_myown_product_form.php");?>

      </table>

      <center>
        <img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width="615" height="31">
      </center>

      <table align=center width=560 cellpadding=0 cellspacing=0 border=0 class="arial size14" style="margin-bottom: 40px; font-weight:bold;">
        <tr height=28>
          <td><a href=javascript:myads_preview(document.CREATE_MYOWN_PRODUCT_FORM)>
            <span id="PREVIEW_CREATE_1" class="arial size14" style="color:#CC0000; text-decoration:underline;">Preview Your Ad</span></a></td>
          <td align=right><a href=javascript:myads_preview(document.CREATE_MYOWN_PRODUCT_FORM)>
            <span id="PREVIEW_CREATE_2" class="arial size14" style="color:#CC0000; text-decoration:underline;">Preview Your Ad</span></a></td>
        </tr>
        <tr height=38><td colspan=2 align=center>
           <input id=SubmitOwnProduct type=submit class=bigbutton style="width: 170px; height: 45px;" value="SUBMIT YOUR AD">
        </td></tr>
      </table>

      </form>
    </DIV>
<?php
  }
?>
</DIV>


<!-- ---- UPDATE_EXISTING PRODUCT ------- -->
<DIV ID="PRODUCT_EDIT" style="display:none"></DIV>
<!-- ----------------------------- -->




<DIV id=MYADS_MY_ADS_LISTS>
<!-- ---- MY ADS LIST ------- -->
<?php
 $active=array();
 $pending=array();
 $j=0;

 $sql  = "SELECT * from ads LEFT JOIN product USING(product_id) ";
 $sql .= " WHERE ads.member_id='$mid' ";
 $sql .= "  ORDER BY product.product_name";
 $result = mysql_query($sql,$db);
 if ($result)
   {
     while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
       {
         //---- ADS
         $ad_id                       = $myrow["ad_id"];
         $product_id                  = $myrow["product_id"];
         $product_url                 = $myrow["product_url"];
         $affiliate_signup_url        = $myrow["affiliate_signup_url"];
         $pushy_network               = $myrow["pushy_network"];
         $elite_bar                   = $myrow["elite_bar"];
         $elite_pool                  = $myrow["elite_pool"];
         $product_list                = $myrow["product_list"];
         $date_created                = $myrow["date_created"];
         $last_modified               = $myrow["last_modified"];
         $reseller_listing            = $myrow["reseller_listing"];
         $existing_products_requested = $myrow["existing_products_requested"];

         $am_product_owner            = ((!$reseller_listing) && $myrow["product_owner"] == $mid)?1:0; // Am I the product Owner

         //---- PRODUCT
         $product_owner               = $myrow["product_owner"];
         $product_submit_date         = $myrow["product_submit_date"];
         $product_name                = stripslashes($myrow["product_name"]);
         $product_title               = stripslashes($myrow["product_title"]);
         $product_description         = stripslashes($myrow["product_description"]);
         $product_categories            = $myrow["product_categories"];

         $media_type                  = $myrow["media_type"];
         $media_format                = $myrow["media_format"];
         $media_width                 = $myrow["media_width"];
         $media_height                = $myrow["media_height"];
         $media_size                  = $myrow["media_size"];
         $media_original_width        = $myrow["media_original_width"];
         $media_original_height       = $myrow["media_original_height"];
         $media_server                = $myrow["media_server"];

         $image_url                   = _get_MediaURL($product_id,$media_server,$media_format);


         //--------------------------------------------------------------------------------
         // If the Product is PENDING - Get the NAME and TITLE As Submitted to PENDING
         //--------------------------------------------------------------------------------
         $sql  = "SELECT product_name, product_title from product_pending ";
         $sql .= " WHERE replaces_product_id='$product_id'";
         $sql .= " AND   product_owner='$mid'";
         $res  = mysql_query($sql,$db);
         if (($res) &&  ($pendingRow = mysql_fetch_array($res,MYSQL_ASSOC)))
           {
              $product_name  = stripslashes($pendingRow["product_name"]);
              $product_title = stripslashes($pendingRow["product_title"]);
           }
         //--------------------------------------------------------------------------------


         //-----------------------------------
         $ad = array();
         $ad["ad_id"]                 = $ad_id;
         $ad["reseller_listing"]      = $reseller_listing;
         $ad["am_product_owner"]      = $am_product_owner;
         $ad["product_id"]            = $product_id;
         $ad["product_url"]           = $product_url;
         $ad["affiliate_signup_url"]  = $affiliate_signup_url;
         $ad["product_name"]          = $product_name;
         $ad["product_title"]         = $product_title;
         $ad["product_description"]   = $product_description;
         $ad["product_categories"]      = $product_categories;
         $ad["date_created"]          = $date_created;
         $ad["last_modified"]         = $last_modified;
         $ad["image_url"]             = $image_url;

         $ad["media_type"]            = $media_type;
         $ad["media_format"]          = $media_format;
         $ad["media_width"]           = $media_width;
         $ad["media_height"]          = $media_height;
         $ad["media_size"]            = $media_size;
         $ad["media_original_width"]  = $media_original_width;
         $ad["media_original_height"] = $media_original_height;
         $ad["media_server"]          = $media_server;

         $ad["pushy_network"]         = $pushy_network;
         $ad["elite_bar"]             = $elite_bar;
         $ad["elite_pool"]            = $elite_pool;
         $ad["product_list"]          = $product_list;

         $ad["existing_products_requested"] = $existing_products_requested;


         if (isProductApprovalPending($db, $product_id))
           $pending[]=$ad;
         else
           $active[]=$ad;
      }
   }
//var_dump($pending);
//var_dump($active);
?>



<form name=MY_PRODUCT_ADS_FORM action=NULL>
<input type="hidden" name="mid" value="<?php echo $mid?>">
<input type="hidden" name="sid" value="<?php echo $sid?>">
<input type="hidden" name="pushy_user_level" value="<?php echo $pushy_user_level?>">
<?php
if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
  {
    include("myads_list_vip.php");
  }
else
if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
  {
    include("myads_list_pro.php");
  }
else
if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
  {
    include("myads_list_elite.php");
  }
?>
</form>

</DIV> <!-- END OF MYADS_MY_ADS_LISTS -->
