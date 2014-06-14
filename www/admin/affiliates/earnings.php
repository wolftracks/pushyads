<?php
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_sendmail.inc");

 $db = getPushyDatabaseConnection();

 $today = getDateTodayAsArray();
 $date_today = getDateToday();

 if (isset($_REQUEST["PAY_PERIOD"]) && strlen($_REQUEST["PAY_PERIOD"])==10)
   {
     $period=dateToArray($_REQUEST["PAY_PERIOD"]);
     $date=dateArrayToString($period);
     if ($date >= $today)
       $period=$today;
   }
 else
   {
     $period=$today;
   }

 //print_r($period);

 $calData=calendar($period);
 $dim=$calData["DaysInMonth"];
 $date=dateArrayToString($period);
 if ($period["day"] < 15)
   {
     $period=calStepMonths(-1,$period);
     $calData=calendar($period);
     $dim=$calData["DaysInMonth"];
     $period["day"]=$dim;
   }
 else
 if ($period["day"] != $dim)
   {
     $period["day"]=15;
   }


 // printf("Today is: %s<br>\n",dateArrayToString($today));
 // printf("Last Pay Period Ended: %s<br>\n",dateArrayToString($period));

 $periodMonth = $period["month"];
 $periodDay   = $period["day"];
 $periodYear  = $period["year"];
 $calData=calendar($period);
 $periodDow   = $calData["DayOfWeek"];

 $pay_period  = dateArrayToString($period);

 $BG_PAYABLE    = "#E0FFE0";
 $BG_NONPAYABLE = "#FFE0E0";
 $BG_PAID       = "#FFFFCC";
 $BG_UNPAID     = "#E8F2FF";

 class Earnings {

   public $pro_earnings   = 0;
   public $elite_earnings = 0;
   public $bonus          = 0;
   public $returns        = 0;
   public $totals         = 0;

 }


 $payable     = new Earnings();
 $nonpayable  = new Earnings();
 $paid        = new Earnings();
 $unpaid      = new Earnings();


 $months=3;
 if ($today["year"] == 2010)
   {
     $months=$today["month"];
   }
 if ($months > 3) $months = 3;

 $payPeriods  = getPayPeriods($months);

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/admin/admin.css" />
<title>Earnings</title>

<script type="text/javascript">
function monthChanged(theForm,selector)
 {
   window.location.href="index.php?PAY_PERIOD="+selector.value;
   // alert("index.php?PAY_PERIOD="+selector.value);
 }
</script>

</head>

<body>
<form>
<table class="smalltext" width="96%" border="0">
  <tr valign=top>
    <td align="center" width="30%">
      <span class="arial bold size14" style="background:<?php echo $BG_PAYABLE?>;    width:200px;">&nbsp;&nbsp;Payable&nbsp;&nbsp;</span>
      <br>
      <span class="arial bold size14" style="background:<?php echo $BG_NONPAYABLE?>; width:200px;">&nbsp;&nbsp;NonPayable&nbsp;&nbsp;</span>
    </td>
    <td align=center width="40%">
       <span class="text bold darkred  size18">PushyAds</span><br>
       <span class="text bold darkblue size16">Affiliate Earnings</span>
    </td>
    <td align="right" width="30%" valign=top>
      <span class="arial bold size14">Date Today:&nbsp;</span>
      <span class="arial bold darkblue size14"><?php echo dateArrayToString($today)?></span><br>
      <span class="arial bold size14">Pay Period:&nbsp;</span>
      <span class="arial bold darkblue size14"><?php echo $pay_period?></span>
    </td>
  </tr>
  <tr valign=top>
    <td align="center" width="30%">
      <span class="arial bold size14" style="background:<?php echo $BG_PAID?>;     width:200px;">&nbsp;&nbsp;Paid&nbsp;&nbsp;</span>
      <br>
      <span class="arial bold size14" style="background:<?php echo $BG_UNPAID?>;   width:200px;">&nbsp;&nbsp;UnPaid&nbsp;&nbsp;</span>
    </td>
    <td width="40%">&nbsp;</td>
    <td align="right" width="30%">
      <br>
      <span class="arial bold darkred size14">Pay Period:&nbsp;</span>
      <select class="arial size12" style="width:120px" onchange=javascript:monthChanged(this.form,this)>
        <?php
           for ($j=0; $j<count($payPeriods); $j++)
             {
               $dt=$payPeriods[$j];
               $sel="";
               if ($dt==$pay_period)
                 $sel="selected";
               echo "<option value=\"$dt\" $sel>&nbsp;$dt&nbsp;</option>\n";
             }
        ?>
      </select>
    </td>
  </tr>
</table>

<table class="smalltext" width="96%" border="0">
  <tr>
    <td width="100%" align=center>
       <input type=button class=button style="width:140px; margin:auto; text-align:center;" value="REFRESH" onClick=javascript:window.location.reload();>
       &nbsp; &nbsp; &nbsp;
       <input type=button class=button style="width:140px; margin:auto; text-align:center;" value="PAYMENTS" onClick=javascript:window.location.href="payments.php">
    </td>
  </tr>
</table>

<br>

<table class="smalltext" height="500" width="100%" border="0">
  <tr valign="top">
    <td align="left" width="100%">

       <table align="center" border="1" cellpadding="2" cellspacing="0" width="98%">
         <tr bgcolor="#E0E0E0">
           <td align=left><b>MemberId</b></td>
           <td align=left><b>Member Name</b></td>
           <td align=right><b>Pro Earnings</b></td>
           <td align=right><b>Elite Earnings</b></td>
           <td align=right><b>Bonus</b></td>
           <td align=right><b>Returns</b></td>
           <td align=right><b>Net Earnings</b></td>
         </tr>

         <?php

            $sql  = "SELECT * from earnings_semi_monthly JOIN member USING(member_id)";
            $sql .= " WHERE earnings_semi_monthly.yymmdd = '$pay_period'";
            $sql .= " AND   member.system=0";
            $sql .= " ORDER by member.lastname, member.firstname";
            $result=mysql_query($sql,$db);
            if ($result)
              {
                while ($myrow=mysql_fetch_array($result, MYSQL_ASSOC))
                  {
                    $member_id        = $myrow["member_id"];
                    $date_paid        = $myrow["date_paid"];

                    $sales_pro        = $myrow["sales_pro"];
                    $earnings_pro     = $myrow["earnings_pro"];
                    $sales_elite      = $myrow["sales_elite"];
                    $earnings_elite   = $myrow["earnings_elite"];
                    $bonus_count      = $myrow["bonus_count"];
                    $bonus_amount     = $myrow["bonus_amount"];
                    $returns_count    = $myrow["returns_count"];
                    $returns_amount   = $myrow["returns_amount"];
                    $net_earnings     = $myrow["net_earnings"];

                    $member_firstname = stripslashes($myrow["firstname"]);
                    $member_lastname  = stripslashes($myrow["lastname"]);
                    $member_fullname  = $member_firstname." ".$member_lastname;

                    $member_email     = $myrow["email"];

                    $paypal_email     = $myrow["paypal_email"];
                    $taxid            = $myrow["taxid"];
                    $payable_to       = $myrow["payable_to"];

                    $agreement_on_file = TRUE;

                    // If MEMBER RECORD AgreementOnFile  AND Pay_Pal email address, then PAYABLE

                    $isPaid=FALSE;
                    $isPayable=FALSE;

                    $bgPaid=$BG_UNPAID;
                    $bgPayable=$BG_NONPAYABLE;

                    if (is_integer(strpos($paypal_email,"@"))   &&
                       ($agreement_on_file)                     &&
                        strlen($payable_to) > 0                 &&
                        strlen($taxid)      > 0             )
                      {
                        $isPayable=TRUE;
                        $bgPayable=$BG_PAYABLE;
                      }
                    if (strlen($date_paid) == 10)
                      {
                        $isPaid=TRUE;
                        $bgPaid=$BG_PAID;
                      }


                    $member_earnings=0;
                    if ($net_earnings>0)
                      $member_earnings=$net_earnings;

                    if ($isPayable)
                      {
                        $payable->pro_earnings   += $earnings_pro;
                        $payable->elite_earnings += $earnings_elite;
                        $payable->bonus          += $bonus_amount;
                        $payable->returns        += $returns_amount;
                        $payable->totals         += $member_earnings;
                      }
                    else
                      {
                        $nonpayable->pro_earnings   += $earnings_pro;
                        $nonpayable->elite_earnings += $earnings_elite;
                        $nonpayable->bonus          += $bonus_amount;
                        $nonpayable->returns        += $returns_amount;
                        $nonpayable->totals         += $member_earnings;
                      }


                    if ($isPaid)
                      {
                        $paid->pro_earnings   += $earnings_pro;
                        $paid->elite_earnings += $earnings_elite;
                        $paid->bonus          += $bonus_amount;
                        $paid->returns        += $returns_amount;
                        $paid->totals         += $member_earnings;
                      }
                    else
                      {
                        $unpaid->pro_earnings   += $earnings_pro;
                        $unpaid->elite_earnings += $earnings_elite;
                        $unpaid->bonus          += $bonus_amount;
                        $unpaid->returns        += $returns_amount;
                        $unpaid->totals         += $member_earnings;
                      }



                    if ($net_earnings<=0) $bgPaid=$BG_PAID;

         ?>
                    <tr>
                      <td bgcolor="<?php echo $bgPayable?>"><?php echo $member_id?></td>
                      <!-- td><?php echo $member_fullname?>&nbsp;|<?php echo $payable_to?>|<?php echo $taxid?>|</td -->
                      <td><?php echo $member_fullname?></td>
                      <td align=right><?php echo number_format($earnings_pro,    2, '.', '')?></td>
                      <td align=right><?php echo number_format($earnings_elite,  2, '.', '')?></td>
                      <td align=right><?php echo number_format($bonus_amount,    2, '.', '')?></td>
                      <td align=right><?php echo number_format($returns_amount,  2, '.', '')?></td>
                      <td align=right bgcolor="<?php echo $bgPaid?>"><?php echo number_format($net_earnings, 2, '.', '')?></td>
                    </tr>
         <?php
                  }
              }
         ?>

          <tr bgcolor="<?php echo $BG_PAYABLE?>">
           <td align=center colspan=2><b> T O T A L S  &nbsp; --  &nbsp; P A Y A B L E</td>
           <td align=right><b><?php echo number_format($payable->pro_earnings,       2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->elite_earnings,     2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->bonus,              2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->returns,            2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->totals,             2, '.', '')?></b></td>
          </tr>


          <tr bgcolor="<?php echo $BG_NONPAYABLE?>">
           <td align=center colspan=2><b> T O T A L S  &nbsp; --  &nbsp; N O N P A Y A B L E</td>
           <td align=right><b><?php echo number_format($nonpayable->pro_earnings,    2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($nonpayable->elite_earnings,  2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($nonpayable->bonus,           2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($nonpayable->returns,         2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($nonpayable->totals,          2, '.', '')?></b></td>
          </tr>



          <tr bgcolor="<?php echo $BG_PAID?>">
           <td align=center colspan=2><b> T O T A L S  &nbsp; --  &nbsp; P A I D</td>
           <td align=right><b><?php echo number_format($paid->pro_earnings,          2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($paid->elite_earnings,        2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($paid->bonus,                 2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($paid->returns,               2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($paid->totals,                2, '.', '')?></b></td>
          </tr>

          <tr bgcolor="<?php echo $BG_UNPAID?>">
           <td align=center colspan=2><b> T O T A L S  &nbsp; --  &nbsp; U N P A I D</td>
           <td align=right><b><?php echo number_format($unpaid->pro_earnings,        2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($unpaid->elite_earnings,      2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($unpaid->bonus,               2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($unpaid->returns,             2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($unpaid->totals,              2, '.', '')?></b></td>
          </tr>
       </table>

       <br>&nbsp;<br>
    </td>
  </tr>
</table>
</form>

</body>
</html>
