<?php
$password            = trim(stripslashes($memberRecord["password"]));
$firstname           = trim(stripslashes($memberRecord["firstname"]));
$lastname            = trim(stripslashes($memberRecord["lastname"]));
$company_name        = trim(stripslashes($memberRecord["company_name"]));
$address1            = trim(stripslashes($memberRecord["address1"]));
$address2            = trim(stripslashes($memberRecord["address2"]));
$city                = trim(stripslashes($memberRecord["city"]));
$state               = trim(stripslashes($memberRecord["state"]));
$country             = trim(stripslashes($memberRecord["country"]));
$zip                 = trim(stripslashes($memberRecord["zip"]));
$email               = trim(stripslashes($memberRecord["email"]));
$phone               = trim($memberRecord["phone"]);
$phone_ext           = trim($memberRecord["phone_ext"]);
$payable_to          = trim(stripslashes($memberRecord["payable_to"]));
$taxid               = trim(stripslashes($memberRecord["taxid"]));
$paypal_email        = trim(stripslashes($memberRecord["paypal_email"]));

$user_level          = $memberRecord["user_level"];
$user_level_name     = $UserLevels[$user_level];
$date_registered     = $memberRecord["date_registered"];
$affiliate_id        = $memberRecord["affiliate_id"];
$affiliate_website   = DOMAIN."/".$affiliate_id;

if ($country == "") $country="USA";
?>

 <font size=5><b>Here's your Top Secret information <?php echo $firstname?></b></font>

<p class=largetext>Make sure you keep your information up to date! <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -1px">&#8482 starts getting an attitude when he can't communicate to you how much money, traffic, or targeted prospects he's sending your way. And believe me! You'll get an attitude too if you find out you missed out on a gob of money or targeted traffic because of it!</p>

<form name="MemberProfile" action="null">

<div align=center style="position: relative; width: 400px; height:28px; margin: 0 0 -36px 210px; padding-top: 3px;  background-color: #FFF8EB; border: 1px solid #FFDD99;" class="bold largetext">
  Your <img src="http://pds1106.s3.amazonaws.com/images/pushy14.png" style="vertical-align: -2px">&#8482 Membership Level: <b class=red><?php echo $user_level_name?></b></div>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="0"  class=bgborder style="margin-top: 20px; padding: 20px;" >
  <tr>
  <td width="100%">

<div style="margin:20px 0 10px;" class="largetext darkred bold">Stuff you need to login with:</div>

    <table align=center width=500 class=text>
      <tr height=30>
        <td width="35%" align="right"><span class="required">*</span><b>Signin Email:&nbsp;</b></td>
        <td width="65%" align="left"><input class=form_input_small type="text" name="email" size="30" maxlength="50" value="<?php echo $email?>"></td>
      </tr>
      <tr>
        <td align="right"><span class="required">*</span><b>Password:&nbsp;</b></span></td>
        <td align="left"><input class=form_input_small type="text" name="password" size="20" maxlength="20" value="<?php echo $password?>"></td>
      </tr>

      <tr height=30>
        <td align="right"><b>Affiliate Website:&nbsp;</b></td>
        <td align="left"  bgcolor="#FFEECC" style="border: 1px dotted #FFCC00;">
          <span class=form_data_small>&nbsp;<b><a href="javascript:account_TestAffiliateSite('<?php echo $affiliate_website?>')"><?php echo $affiliate_website?></a></b></span></td>
      </tr>

      <tr>
        <td colspan=2 valign=middle>
          <div style="margin:40px 0 10px 0;" class="largetext darkred bold">Important stuff about you:</div>
        </td>
      </tr>

      <tr>
        <td align="right"><span class="required">*</span><b>First Name:&nbsp;</b></span></td>
        <td align="left"><input class=form_input_small type="text" name="firstname" size="30" maxlength="30" value="<?php echo $firstname?>"></td>
      </tr>
      <tr>
        <td align="right"  valign="middle"><span class="required">*</span><b>Last Name:&nbsp;</b></td>
        <td align="left"><input class=form_input_small type="text" name="lastname" size="30" maxlength="30" value="<?php echo $lastname?>"></td>
      </tr>
      <tr>
        <td align="right"><b>Company Name:&nbsp;</b></span></td>
        <td align="left"><input class=form_input_small type="text" name="company_name" size="30" maxlength="30" value="<?php echo $company_name?>" ></td>
      </tr>
      <tr>
        <td align="right"  valign="middle"><b>Phone:&nbsp;</b></td>
        <td align="left"   valign="middle">
           <input class=form_input_small type="text" name="phone" size="15" maxlength="25" value="<?php echo $phone?>">
           &nbsp;&nbsp;<span class=smalltext><b>Ext:</b></span>
           <input class=form_input_small type="text" name="phone_ext" size="3" maxlength="6" value="<?php echo $phone_ext?>">
        </td>
      </tr>
      <tr>
        <td align="right"  valign="middle"><b>Address 1:&nbsp;</b></td>
        <td align="left"   valign="middle"><input class=form_input_small type="text" name="address1" size="30" maxlength="30" value="<?php echo $address1?>"></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><b>Address 2:&nbsp;</b></td>
        <td align="left"  valign="middle"><input class=form_input_small type="text" name="address2" size="30" maxlength="30" value="<?php echo $address2?>"></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><b>City:&nbsp;</b></td>
        <td align="left"  valign="middle"><input class=form_input_small type="text" name="city" size="30" maxlength="30" value="<?php echo $city?>"></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><b>State/Province:&nbsp;</b></td>
        <td align="left"  valign="middle"><input class=form_input_small type="text" name="state" size="30" maxlength="25" value="<?php echo $state?>"></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><b>Zip/Postal:&nbsp;</b></td>
        <td align="left"  valign="middle"><input class=form_input_small type="text" name="zip" size="10" maxlength="10" value="<?php echo $zip?>"></td>
      </tr>
      <tr>
        <td align="right" valign="middle"><b>Country:&nbsp;</b></td>
        <?php
          $sel="";
          if (strlen($country) == 0) $sel="selected";
        ?>
        <td align="left"  valign="middle"><select class=form_input_small name="country"><option value="" <?php echo $sel?>> --- Select --- </option>
          <?php
            foreach ($countries AS $symbol => $country_name)
              {
                $sel="";
                if ($country==$symbol || $country==$country_name)
                  {
                    $sel="selected";
                  }
                echo "<option value=\"$country_name\" $sel>$country_name</option>\n";
              }
          ?>
        </td>
      </tr>

      <tr>
        <td colspan=2 valign=middle>
          <div style="margin:40px 0 10px 0;" class="largetext darkred bold">Important stuff we need so we can pay you Gobs of Money:</div>
        </td>
      </tr>

      <tr>
        <td align="right"><b>Tax ID:&nbsp;</b></td>
        <td align="left"><input class=form_input_small type="text" name="taxid" size="30" maxlength="30" value="<?php echo $taxid?>"></td>
      </tr>
      <tr>
        <td align="right"><b>Pay to:&nbsp;</b></td>
        <td align="left"><input class=form_input_small type="text" name="payable_to" size="30" maxlength="30" value="<?php echo $payable_to?>"></td>
      </tr>
      <tr>
        <td align="right"><b>PayPal Email:&nbsp;</b></td>
        <td align="left"><input class=form_input_small type="text" name="paypal_email" size="30" maxlength="60" value="<?php echo $paypal_email?>"></td>
      </tr>

      <tr>
        <td colspan=2 align=center height=80 valign=middle >
          <input type="button" style="width:100px;" class=bigbutton value="  UPDATE  " onClick=account_UpdateAccount(this.form)>
        </td>
      </tr>


      <tr>
        <td colspan=2 valign=middle>
          <div style="margin:40px 0 10px 0;" class="largetext darkred bold">Download Important Forms & Fax to us: <span class="largetext darkgray"> You must download,
            sign, and fax the Affiliate Agreement and W-9 (USA) or W-8 (Non-USA) to 971-925-7025 or 541-322-2195 before we can release your first affiliate payment</span></div>
        </td>
      </tr>

      <tr>
        <td colspan=2 valign=middle>
          <table width=100% border=0 cellspacing=0 cellpadding=0>
            <tr>
              <td width=30% align=center class="bold text">All Members<br>
                <a href=javascript:openPopupWindow('/members/bin/affiliate_agreement.pdf',0,0,700,650)>Affiliate Agreement</a><br>
                <a href=javascript:openPopupWindow('/members/bin/affiliate_agreement.pdf',0,0,700,650)><img src="http://pds1106.s3.amazonaws.com/images/pdf.jpg"></a>
              </td>

              <td width=40% align=center class="bold text">US Members<br>
                <a href=javascript:openPopupWindow('/members/bin/w-9.pdf',0,0,700,650)>W-9 Form</a><br>
                <a href=javascript:openPopupWindow('/members/bin/w-9.pdf',0,0,700,650)><img src="http://pds1106.s3.amazonaws.com/images/pdf.jpg"></a>
              </td>

              <td width=30% align=center class="bold text">Non US Members<br>
                <a href=javascript:openPopupWindow('/members/bin/w-8.pdf',0,0,700,650)>W-8 Form</a><br>
                <a href=javascript:openPopupWindow('/members/bin/w-8.pdf',0,0,700,650)><img src="http://pds1106.s3.amazonaws.com/images/pdf.jpg"></a>
              </td>

            </tr>
          </table>
        </td>
      </tr>

    </table>



  </td>
  </tr>
</table>

    <center>
      <img src="http://pds1106.s3.amazonaws.com/images/shadow.gif" width="500" height="31">
    </center>

</form>
