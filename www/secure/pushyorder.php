<?php
$firstname="";
$lastname="";
$email="";

$PRORATION=0;

if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
    $db=getPushyDatabaseConnection();
    if (is_array($memberRecord=getMemberInfo($db,$mid)))
      {
        $member_id=$memberRecord["member_id"];
        $firstname=stripslashes($memberRecord["firstname"]);
        $lastname =stripslashes($memberRecord["lastname"]);
        $email    =$memberRecord["email"];

        $cc_holdername = striplt($memberRecord["cc_holdername"]);
        if (strlen($cc_holdername)==0)
          {
            $cc_holdername=$firstname." ".$lastname;
          }
        $cc_number     = striplt($memberRecord["cc_number"]);
        $cc_address    = striplt($memberRecord["cc_address"]);
        if (strlen($cc_address)==0)
          {
            $cc_address=stripslashes($memberRecord["address1"]);
          }
        $cc_expmmyyyy  = striplt($memberRecord["cc_expmmyyyy"]);
        if (strlen($cc_expmmyyyy)==7) //-- MUST BE mm-yyyy
          {
            $cc_expmm   = substr($cc_expmmyyyy,0,2);
            $cc_expyyyy = substr($cc_expmmyyyy,3);
          }
        $cc_zip        = striplt($memberRecord["cc_zip"]);
        if (strlen($cc_zip)==0)
          {
            $cc_zip=stripslashes($memberRecord["zip"]);
          }
        $cc_cvv2       = striplt($memberRecord["cc_cvv2"]);
      }
    else
     {
       topLevelRedirect("/");
       exit;
     }
  }
else
  {
    topLevelRedirect("/");
    exit;
  }

$dateArray=getDateTodayAsArray();
$expiration_year_earliest = (int) $dateArray["year"];
$expiration_year_latest   = $expiration_year_earliest + 8;
?>

<html>
<title>PushyAds Secure Order Form</title>

<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="shortcut icon" href="https://pds1106.s3.amazonaws.com/images/favicon.ico" />
<LINK type=text/css rel=stylesheet href="/local-css/styles.css">
<script type="text/javascript" src="/local-js/jquery.js"></script>
<script type="text/javascript" src="/local-js/jquery.cookie.js"></script>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/local-js/jsutils.js"></script>

<script type="text/javascript">
<!---
var submitted=false;
var mid="<?php echo $mid?>";
var firstname="<?php echo $firstname?>";
var ExpirationYearEarliest="<?php echo $expiration_year_earliest?>";
var ExpirationYearLatest  ="<?php echo $expiration_year_latest?>";

function ValidateForm(theForm)
 {
   if (submitted)
     {
       alert("Already Submitted");
       return false;  // Only One Submission
     }

   theForm.cc_number.value       = getDigits(theForm.cc_number.value);
   theForm.cc_expmm.value        = getDigits(theForm.cc_expmm.value);
   theForm.cc_expyyyy.value      = getDigits(theForm.cc_expyyyy.value);
   theForm.cc_holdername.value   = striplt(theForm.cc_holdername.value);
   theForm.cc_address.value      = striplt(theForm.cc_address.value);
   theForm.cc_zip.value          = striplt(theForm.cc_zip.value);
   theForm.cc_cvv2.value         = striplt(theForm.cc_cvv2.value);
   theForm.receipt_email.value   = striplt(theForm.receipt_email.value);

   if (theForm.cc_holdername.value.length < 2)
     {
       alert("Please enter Card Holder's Name");
       theForm.cc_holdername.focus();
       return false;
     }
   if (theForm.receipt_email.value.length==0)
     {
       alert("Please enter Email address.\nReceipt will be sent to this address.\n");
       theForm.receipt_email.focus();
       return false;
     }
   if ((theForm.receipt_email.value.length > 0) && !isValidEmailAddress(theForm.receipt_email.value))
     {
       alert("The Email Address specified is not a Valid Email Address");
       theForm.receipt_email.focus();
       return false;
     }
   if (theForm.cc_address.value.length < 2)
     {
       alert("Please enter Card Holder Billing Address");
       theForm.cc_address.focus();
       return false;
     }
   if (theForm.cc_zip.value.length < 3)
     {
       alert("Please enter Card Holder Zip Code");
       theForm.cc_zip.focus();
       return false;
     }
   if (theForm.cc_number.value.length < 15  ||
       theForm.cc_number.value.length > 16)
     {
       alert("Please verify Credit Card Number\n(Expecting 15 or 16 Digits)");
       theForm.cc_number.focus();
       return false;
     }

   var firstDigit=theForm.cc_number.value.charAt(0);
   if (firstDigit != '3' &&
       firstDigit != '4' &&
       firstDigit != '5' &&
       firstDigit != '6' &&
       firstDigit != '9')
     {
       alert("Credit Card Number is Invalid - Please Re-Enter");
       theForm.cc_number.focus();
       return false;
     }
   if (theForm.cc_expmm.value.length != 2)
     {
       alert("Please enter 2-digit expiration month");
       theForm.cc_expmm.focus();
       return false;
     }
   if (theForm.cc_expmm.value == 0 ||
       theForm.cc_expmm.value > 12)
     {
       alert("Expiration Month is invalid (01-12)");
       theForm.cc_expmm.focus();
       return false;
     }
   if (theForm.cc_expyyyy.value.length != 4)
     {
       alert("Please enter 4-digit expiration year");
       theForm.cc_expyyyy.focus();
       return false;
     }
   if (theForm.cc_expyyyy.value < ExpirationYearEarliest ||
       theForm.cc_expyyyy.value > ExpirationYearLatest)
     {
       alert("Expiration Year is invalid");
       theForm.cc_expyyyy.focus();
       return false;
     }
   if (theForm.cc_cvv2.value.length < 3 || theForm.cc_cvv2.value.length > 4 || (!isNumeric(theForm.cc_cvv2.value)))
     {
       alert("Please enter the CVV2 code that is printed on your card");
       theForm.cc_cvv2.focus();
       return false;
     }

   if (!theForm.terms.checked)
     {
       alert("You must confirm that you have read and agree to the Terms Of Use.");
       theForm.terms.focus();
       return (false);
     }

   submitted=true;

   var elSubmitButton=document.getElementById("SUBMITBUTTON");
   var elPleaseWait  =document.getElementById("PLEASEWAIT");
   elSubmitButton.style.display="none";          // HIDE
   elPleaseWait.style.display="";                // SHOW

   return true;
 }
//-->
</script>
</head>

<body class=secure_background topmargin=0>

<table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td valign=top>
      <DIV id="ORDERCONTENT">
      <table align=center valign=top width=950px border=0 cellpadding=0 cellspacing=0>
        <tr>
          <td align=center style="padding: 20px 0 0 0;"><img src="https://pds1106.s3.amazonaws.com/images/<?php echo $banner_image?>"></td>
          <td rowspan=3 align=right valign=top style="padding-top: 90px;"><img src="https://pds1106.s3.amazonaws.com/images/pushyman-sh.png">
          </td>
        </tr>
        <tr>
          <td valign=top align=center>
            <p class="tahoma size22 white" align=left style="margin: 20px 15px 30px 37px;">Great decision, <?php echo $firstname?>! You're on your way to
               making more friends, influencing more people, and becoming very famous throughout
               he <img src="https://pds1106.s3.amazonaws.com/images/pushy18.png" style="vertical-align: -1px" alt="PUSHY!">&#8482 network. Go ahead
               and fill out the form below to get started!
          </td>
        </tr>
        <tr>
          <td>
          <table align=left width=625 border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td width=40 height=34 valign=top background="https://pds1106.s3.amazonaws.com/images/shadow-top.png">&nbsp;</td>
              <td width=587 height=34 valign=bottom class=secure_boback></td>
            </tr>
            <tr>
              <td width=40 valign=top class=secure_cellleft>&nbsp;</td>
              <td width=587 valign=top>

              <!----------------------------------------- START CONTENT ------------------------------------->
              <table align=left width=587 bgcolor=#FFFFFF border=0 cellpadding=0 cellspacing=0>
                <tr>
                  <td valign=top>
                    <div style="margin: -3px 35px 25px 35px ;">

                    <?php
                      if (isset($_REQUEST["mid"]) && is_array($memberRecord=getMemberInfo($db,$_REQUEST["mid"])))
                        {
                          if ($orderLevel == $PUSHY_LEVEL_ELITE && $memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
                            {
                              $last_payment_date = $memberRecord["last_payment_date"];
                              $next_payment_due  = $memberRecord["next_payment_due"];

                              $lastPayment = dateToArray($last_payment_date);

                              $dateToday=getDateToday();

                              if ($next_payment_due > $dateToday)
                                {
                                  $dateArray  = getDateTodayAsArray();
                                  $paymentDue = dateToArray($next_payment_due);
                                  $days = dateDifference($dateArray, $paymentDue);
                                  if ($days > 0)
                                    {
                                      $eliteMonthlyFee = number_format($PUSHY_LEVEL_ELITE_MONTHLY_FEE, 2, ".", "");
                                      $dailyRatePro    = round($PUSHY_LEVEL_PRO_MONTHLY_FEE / 30, 2);
                                      $creditAmount    = number_format($dailyRatePro   * $days, 2, ".", "");

                                      if ($days > 30) $days = 30;
                                      if ($creditAmount > $PUSHY_LEVEL_PRO_MONTHLY_FEE) $creditAmount = number_format($PUSHY_LEVEL_PRO_MONTHLY_FEE,2,".","");


                                      $PRORATION=1;
                                      $orderAmount = number_format($PUSHY_LEVEL_ELITE_MONTHLY_FEE - $creditAmount, 2, ".", "");
                    ?>
                                      The normal monthly rate for Elite membership is $ <?php echo $eliteMonthlyFee?>.
                                      Because you are upgrading with <b><?php echo $days?> days</b> remaining in your current billing period,
                                      the amount you will be charged for the first month has been adjusted using the PRO Membership Daily rate of $<?php echo $dailyRatePro?>)<br>
                                      <br>
                                      <div align=right style="margin-right: 5px">
                                        <table width=440 cellpadding=4 cellspacing=0 class=gridb1>
                                          <tr>
                                             <td             align=left  class="arial size16 bold">Monthly Fee - ELITE Membership</td>
                                             <td             align=right class="arial size16">$ <?php echo $eliteMonthlyFee?></td>
                                          </tr>
                                          <tr>
                                             <td             align=left  class="arial size16">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;less <?php echo $days?> days of PRO membership @ $<?php echo $dailyRatePro?></td>
                                             <td             align=right class="arial size16">$ <?php echo $creditAmount?></td>
                                          </tr>
                                          <tr>
                                             <td             align=left  class="arial size16 bold">Amount of this One Time Upgrade Fee</td>
                                             <td             align=right class="arial size16">$ <?php echo $orderAmount?></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <br>
                    <?php
                                    }
                                }
                            }
                        }
                      else
                        {
                    ?>
                           After placing your order below, you will immediately gain access to all your free products, as well as get your Pushy ads seen throughout the network.
                    <?php
                        }
                    ?>

                    <div style="position:absolute; margin: -60px 0 0 -60px;"><img src="https://pds1106.s3.amazonaws.com/images/satisfaction_g.png" width=150></div>

                    <div align=right class="tahoma size18" style="margin-right: -20px;"><sup>$</sup><?php echo $orderAmount?> membership fee
                      <a href=javascript:openPopup('bin/ssl_root_compatibility.pdf',660,700) style="margin-left: 72px">
                      <img src="https://pds1106.s3.amazonaws.com/images/ssl.gif" style="vertical-align: middle;"></a>
                    </div>

                    <!-------------------------------- START ORDER FORM -------------------------------->
                    <table align=center width=510 cellspacing=3 cellpadding=3 border=1 bgcolor="#FFC44D" style="margin-top: 20px;" >
                      <tr>
                        <td style="border: 0px; background-color:#FFC44D;">
                        <form name="ORDERFORM" method="POST" action="submit_order.php" onSubmit="return ValidateForm(this)">
                          <input type="hidden" name="sid"           value="<?php echo $sid?>">
                          <input type="hidden" name="mid"           value="<?php echo $mid?>">
                          <input type="hidden" name="orderType"     value="<?php echo $orderType?>">
                          <input type="hidden" name="orderAmount"   value="<?php echo $orderAmount?>">
                          <input type="hidden" name="orderLevel"    value="<?php echo $orderLevel?>">
                          <input type="hidden" name="description"   value="<?php echo $description?>">
                          <input type="hidden" name="proration"     value="<?php echo $PRORATION?>">
                          <input type="hidden" name="banner_image"  value="<?php echo $banner_image?>">

                          <table style="border:1px solid #46587A;" width=100% cellpadding=0 cellspacing=0 class=largetext>
                            <tr bgcolor="#F7F7F7">
                              <td colspan=2 height=20></td>
                            </tr>

                            <tr height=28>
                              <td align=left colspan=2 bgcolor="#F7F7F7" style="padding: 0 0 20px 25px;" class="size18 tahoma"><b>Your Billing Information:</b>
                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Cardholder's Name:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <input class=input type="text" name="cc_holdername" maxlength="40" value="<?php echo $cc_holdername?>">
                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Email Address:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <input class=input type="text" name="receipt_email" maxlength="70" value="<?php echo $email?>">
                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Street Address:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <input class=input type="text" name="cc_address" maxlength="30" value="<?php echo $cc_address?>">
                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Zip/Postal Code:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <input class=input style="width: 105px;" type="text" name="cc_zip" size=16 maxlength="10" value="<?php echo $cc_zip?>">
                              </td>
                            </tr>

                            <!--
                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Country:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <select name="country" class=input>
                                   <option selected>- Select Country -</option>
                                   <option value="USA">United States</option>
                                   <option value="CAN">Canada</option>
                                   <option value="UK">United Kingdom</option>
                                   <option value="">Alphabetical</option>
                                 </select>
                              </td>
                            </tr>
                            -->

                            <tr height=28>
                              <td align=left colspan=2 bgcolor="#F7F7F7" style="padding: 40px 0 20px 25px;" class="size18 tahoma"><b>Your Credit Card Information:</b>
                                &nbsp;<img src="https://pds1106.s3.amazonaws.com/images/cc.jpg" style="vertical-align: -4px">
                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Card Number:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <input class=input type="text" name="cc_number" maxlength="16" value="<?php echo $cc_number?>">
                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Expiration Date:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;

                                <input class=input style="width: 30px;" type="text" name="cc_expmm" size="2" maxlength="2" value="<?php echo $cc_expmm?>"></input>
                                <span style="font-family: Tahoma,Arial,Helvetica; font-size:16px; font-weight: bold; color:#403C36; width:2px; height:25px; margin:0px; 0 0px 0;">/</span>
                                <input class=input style="width: 70px;" type="text" name="cc_expyyyy" size="4" maxlength="4" value="<?php echo $cc_expyyyy?>"></input>
                                <span class="arial size14 normal">&nbsp;&nbsp;mm/yyyy</span>

                              </td>
                            </tr>

                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <b>Security Code:&nbsp;</b></td>
                              <td width=55% class=signcol2>&nbsp;
                                 <input class=input style="width: 60px;" type="text" name="cc_cvv2" maxlength="4" value="<?php echo $cc_cvv2?>">
                              </td>
                            </tr>


                            <tr height=28>
                              <td width=45% class=signcol1>
                                 <input name="terms" type="checkbox">&nbsp;</td>
                              <td width=55% class=signcol2>&nbsp;&nbsp;
                                <b class=text>I agree to <a href=javascript:openPopup('/pop-terms.php',660,700)>Terms of Use</a>:&nbsp;&nbsp;</b>
                              </td>
                            </tr>


                            <tr bgcolor="#F7F7F7">
                              <td colspan=2 height=20></td>
                            </tr>

                            <tr  id="SUBMITBUTTON" valign=center height=81 bgcolor="#F7F7F7">
                              <td colspan=2 width="100%" align=center>
                                <!-- a href=javascript:submitOrder()><img src="https://pds1106.s3.amazonaws.com/images/order-now.gif" --></a>
                                <input type=image src="https://pds1106.s3.amazonaws.com/images/order-now.gif" width=350>
                              </td>
                            </tr>

                            <tr id="PLEASEWAIT" style="display:none" valign=center height=81 bgcolor="#F7F7F7">
                              <td colspan=2>
                                 <table width=80% align=center cellpadding=0 cellspacing=0 border=0>
                                   <tr height=81 valign=middle>
                                     <td width="50"  align="center"><img id="BUSY-1" src="https://pds1106.s3.amazonaws.com/images/busy_1.gif" width=20></td>
                                     <td width="300" align="center" class="size16 bold darkred Tahoma">Your Order is Being Processed<br>Please Wait</td>
                                     <td width="50"  align="center"><img id="BUSY-2" src="https://pds1106.s3.amazonaws.com/images/busy_1.gif" width=20></td>
                                   </tr>
                                 </table>
                              </td>
                            </tr>

                            <tr bgcolor="#F7F7F7">
                              <td width=100% colspan=2 height=80 valign=top>
                                <table width=90% align=center border=0 cellspacing=0 cellpadding=0 style="margin-top: 30px">
                                  <tr>
                                    <td><img src="https://pds1106.s3.amazonaws.com/images/payflow.png" height=90></td>
                                    <td align=center valign=top><img src="https://pds1106.s3.amazonaws.com/images/secure.jpg" style="margin-left: -18px"></td>
                                    <td align=right><img src="https://pds1106.s3.amazonaws.com/images/trusted.jpg" height=90></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>

                            <tr bgcolor="#F2F4F7">
                              <td colspan=2 height=20></td>
                            </tr>
                          </table>
                          </form>
                        </td>
                      </tr>
                    </table>

                      <div align=center><img src="https://pds1106.s3.amazonaws.com/images/shadow.gif" width=470 height=31></div>


                    <!-------------------------------- END ORDER FORM -------------------------------->
                    </div>
                  </td>
                </tr>
              </table>
              <!-------------------------------------- END CONTENT --------------------------------------->
            </td>
          </tr>
          <tr>
            <td width=40  height=38 background="https://pds1106.s3.amazonaws.com/images/shadow-crnr.png"></td>
            <td width=587 height=38>
              <table width=100% border=0 cellpadding=0 cellspacing=0>
                <tr>
                  <td width=547 height=38 valign=top class=secure_cellbottom></td>
                  <td width=40 height=38 valign=top align=right background="https://pds1106.s3.amazonaws.com/images/shadow-rt.png">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </DIV>

      </td>
    </tr>
  </table>

</body>
</html>
