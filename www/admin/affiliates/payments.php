<?php
 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_sendmail.inc");

 $db = getPushyDatabaseConnection();

 $today = getDateTodayAsArray();
 $date_today = getDateToday();

 if ((FALSE) && (isset($_REQUEST["PAY_PERIOD"]) && strlen($_REQUEST["PAY_PERIOD"])==10))
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
<title>Affiliate Payments</title>

<script type="text/javascript">
function monthChanged(theForm,selector)
 {
   window.location.href="index.php?PAY_PERIOD="+selector.value;
   // alert("index.php?PAY_PERIOD="+selector.value);
 }


function select_all(sw)
 {
   var list = document.getElementsByTagName("INPUT");
   for (var i=0; i<list.length; i++)
     {
       var el = list[i];
       if (el.type == "checkbox")
         {
           el.checked=sw;
         }
     }
 }


function query_selected()
 {
   var list = document.getElementsByTagName("INPUT");
   var count=0;
   for (var i=0; i<list.length; i++)
     {
       var el = list[i];
       if (el.type == "checkbox" && (el.checked))
         count++;
     }
   return count;
 }


function ValidateForm(theForm)
  {
    var count=query_selected();
    if (count==0)
      {
        alert("No Affiliates Selected For Payment");
        return false;
      }

    var msg  = "You have asked to Process Payments for "+count+" SELECTED Affiliates.\n \n";
        msg += "PLEASE BE CERTAIN THAT YOU HAVE LOOKED THIS LIST OVER CAREFULLY BEFORE CONTINUING!!\n \n";
        msg += "If you continue, You will be presented with a file that you should SAVE (NOT OPEN).\n";
        msg += "SAVE The File to A Special PayPal Submissions Folder. This File Should Be Kept indefinitely.\n\n";
        msg += "The Next Step is NON-REPEATABLE, so You Must Take Action on the Saved File and\n";
        msg += "Upload it to PAYPAL.  PuahyAds Records have already Marked these Payments as Completed.\n";
        msg += "If you CANNOT Successfully process the file or are notified of any exceptions during\n";
        msg += "processing, note them as they will have to be rectified manually.\n\n";
        msg += "\nPlease click OK to Continue  or  CANCEL to Ignore this Request\n \n";
    var resp = confirm(msg);
    if (resp)
      {
        return true;
      }
    return false;
  }


</script>

</head>

<body>

<form method="POST" name=F0 action="submit_batch_payment.php" onSubmit="return ValidateForm(this)">
<input type="hidden" name="PAY_PERIOD" value="<?php echo $pay_period?>">

<table class="smalltext" width="96%" border="0">
  <tr valign=top>
    <td align="center" width="30%">
      <br>
    </td>
    <td align=center width="40%">
       <span class="text bold darkred  size18">PushyAds</span><br>
       <span class="text bold darkblue size16">Affiliate Payments</span>
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
      <br>
    </td>
    <td width="40%">&nbsp;</td>
    <td align="right" width="30%">
      <br>
    </td>
  </tr>
</table>

<table class="smalltext" width="96%" border="0">
  <tr>
    <td width="100%" align=center>
       <input type=button class=button style="width:140px; margin:auto; text-align:center;" value="REFRESH"  onClick=javascript:window.location.reload();>
       &nbsp; &nbsp; &nbsp;
       <input type=button class=button style="width:140px; margin:auto; text-align:center;" value="EARNINGS" onClick=javascript:window.location.href="earnings.php">
    </td>
  </tr>
</table>

<br>


<table class="smalltext" height="500" width="100%" border="0">
  <tr valign="top">
    <td align="left" width="100%">

       <table align="center" border="1" cellpadding="2" cellspacing="0" width="98%">
         <tr bgcolor="#E6E6E6">
           <td bgcolor="#E6E6E6" align=left colspan=3>
                &nbsp;<a href=javascript:select_all(true)>Select All</a>
                &nbsp; &nbsp;  &nbsp;
                &nbsp;<a href=javascript:select_all(false)>Deselect All</a>
           </td>
           <td rowspan=2 align=left><b>Paypal Email</b></td>
           <td rowspan=2 align=right><b>Pro Earnings</b></td>
           <td rowspan=2 align=right><b>Elite Earnings</b></td>
           <td rowspan=2 align=right><b>Bonus</b></td>
           <td rowspan=2 align=right><b>Returns</b></td>
           <td rowspan=2 align=right><b>Net Earnings</b></td>
         </tr>

         <tr bgcolor="#E6E6E6">
           <td align=center width=3>&nbsp;</td>
           <td align=left><b>MemberId</b></td>
           <td align=left><b>Member Name</b></td>
         </tr>

         <?php

            $sql  = "SELECT * from earnings_semi_monthly JOIN member USING(member_id)";
            $sql .= " WHERE earnings_semi_monthly.yymmdd = '$pay_period'";
            $sql .= " AND   earnings_semi_monthly.date_paid=''";
            $sql .= " AND   earnings_semi_monthly.batch='0'";
            $sql .= " AND   earnings_semi_monthly.net_earnings>0";

            $sql .= " AND   member.system=0";
      //    $sql .= " AND   member.agreementOnFile>0";
            $sql .= " AND   member.taxid!=''";
            $sql .= " AND   member.payable_to!=''";
            $sql .= " AND   member.paypal_email LIKE '%@%'";

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

                    $payable->pro_earnings   += $earnings_pro;
                    $payable->elite_earnings += $earnings_elite;
                    $payable->bonus          += $bonus_amount;
                    $payable->returns        += $returns_amount;
                    if ($net_earnings > 0)
                       $payable->totals      += $net_earnings;
         ?>
                    <tr>
                      <td align=center width=3 bgcolor="#E6E6E6"><input name="B_<?php echo $member_id?>" class="tinytext" type="checkbox" value="YES" checked></td>
                      <td bgcolor="<?php echo $bgPayable?>">&nbsp;<?php echo $member_id?></td>
                      <td><?php echo $member_fullname?>&nbsp;</td>
                      <td><?php echo $paypal_email?></td>
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

          <tr height=35 bgcolor="<?php echo $BG_PAYABLE?>">
           <td bgcolor="#E6E6E6" align=left   colspan=3>
                &nbsp; &nbsp;
                <input type=submit style="width:260px" value=" SUBMIT  BATCH  PAYMENT ">
           </td>
           <td align=center><b> T O T A L S  &nbsp; --  &nbsp; P A Y A B L E</td>
           <td align=right><b><?php echo number_format($payable->pro_earnings,       2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->elite_earnings,     2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->bonus,              2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->returns,            2, '.', '')?></b></td>
           <td align=right><b><?php echo number_format($payable->totals,             2, '.', '')?></b></td>
          </tr>
       </table>

       <br>&nbsp;<br>
    </td>
  </tr>
</table>
</form>

</body>
</html>
