<?php
$dateRegistered     = dateToArray($memberRecord["date_registered"]);
$date_registered    = dateAsText($dateRegistered);

$lastPaymentDate    = dateToArray($memberRecord["last_payment_date"]);
$last_payment_date  = dateAsText($lastPaymentDate);

$nextPaymentDue     = dateToArray($memberRecord["next_payment_due"]);
$next_payment_due   = dateAsText($nextPaymentDue);

$currentLevelDate   = dateToArray($memberRecord["current_level_date"]);
$current_level_date = dateAsText($currentLevelDate);

$receipt            = lastPurchase($db, $memberRecord);
// var_dump($receipt);
if ($receipt)
  {
    $dateTimeArray      = getDateTimeFromSecondsAsArray($receipt["ts_order"]);
    $dateLastPurchase   = dateAsText($dateTimeArray);
    $timeLastPurchase   = timeAsText($dateTimeArray);
    $lastPurchaseDate   = $dateLastPurchase." ".$timeLastPurchase;
    $lastPurchaseAmount = (int) $receipt["amount"];
  }
else
  {
    $lastPurchaseDate   = $dateLastPurchase." ".$timeLastPurchase;
  }


//---- number_format((float)$myrow["amount"],2,".","")

$user_level = $memberRecord["user_level"];
$user_level_name = strtoupper($UserLevels[$user_level]);

$nextPurchaseAmount = (int) $MonthlyFees[$user_level];


function lastPurchase($db, $memberRecord)
 {
   $sql  = "SELECT * from receipts";
   $sql .= " WHERE member_id  = '".$memberRecord["member_id"]."'";
   $sql .= " AND   txtype=0";
   $sql .= " ORDER BY ts_order DESC LIMIT 1";
   $result=mysql_query($sql,$db);

   // printf("[lastPurchase]<br>\n");
   // printf("SQL: %s<br>\n",$sql);
   // printf("ERR: %s<br>\n",mysql_error());
   // printf("ROWS: %s<br><br>\n",mysql_num_rows($result));

   if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
     {
       mysql_free_result($result);
       return($myrow);
     }
   if ($result)
     mysql_free_result($result);
   return FALSE;
 }


function purchaseHistory($db, $memberRecord)
 {
   $purchases=array();
   $sql  = "SELECT * from receipts";
   $sql .= " WHERE member_id  = '".$memberRecord["member_id"]."'";
   $sql .= " AND   txtype=0";
   $sql .= " ORDER BY ts_order";
   $result=mysql_query($sql,$db);

   // printf("[purchaseHistory]<br>\n");
   // printf("SQL: %s<br>\n",$sql);
   // printf("ERR: %s<br>\n",mysql_error());
   // printf("ROWS: %s<br><br>\n",mysql_num_rows($result));

   if ($result && (mysql_num_rows($result)>0))
     {
       while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC))
         {
           $purchases[]=$myrow;
         }
     }

   // var_dump($purchases);
   return $purchases;
 }


$timeNow   = timeAsText($dateArray);
?>

<div align=right style="margin: -41px 0 0 640px;">
  <a href=javascript:openVideo('http://pds1106.s3.amazonaws.com/video/int/reports-status.flv') title="Video Help"><img src="http://pds1106.s3.amazonaws.com/images/video-anim2.gif"></a>
</div>

<table width=680 valign=top cellspacing=0 cellpadding=0 style="border: 2px solid #FFCC00;">
  <tr>
    <td bgcolor="#FFFFFF">
      <table width=100% align=center valign=top cellspacing=15 cellpadding=0>
        <tr>
          <td class="largetext">

            Below is your membership status and account information. Make sure to keep it up to date so you don't
            lose out on any of your membership privileges.

            <br>&nbsp;<p class="darkred largetext"><b>Account Status</b></p>

            <table valign=top class="text" style="line-height: 20px; margin: -5px 40px 5px 40px;">
              <tr>
                <td>

                  <b>Member Since:</b> <?php echo $date_registered?>
                  <br><b>Member Level:</b> <?php echo $user_level_name?>, since <?php echo $current_level_date?>
                  <br><b>Last Purchase Date:</b> <?php echo $lastPurchaseDate?>
                  <br><b>Last Purchase Amount:</b> $<?php echo $lastPurchaseAmount?>
                  <br><b>Next Purchase Date:</b> <?php echo $next_payment_due?>
                  <br><b>Next Purchase Amount:</b> $<?php echo $nextPurchaseAmount?>

                  <p><b style="background-color: #FFEECC; border: 1px dotted #FFCC00; padding: 5px;">
                    &nbsp; Upgrade or downgrade your account
                    <a href=javascript:membership_plan()>HERE</a> &nbsp;</b></p>

                </td>
              </tr>
            </table>

          </td>
        </tr>
        <tr>
          <td>

            <div class="darkred largetext bold">Purchase History</div>

            <table width=600 valign=top border=0 cellspacing=0 cellpadding=0 class="text gridb1" style="margin: 15px 0 0 40px;">
              <tr bgcolor=#D0D6DF>
                <td width=32%><b>&nbsp; Date</b></td>
                <td width=18% align=center><b>Amount</b></td>
                <td width=50%><b>&nbsp; Description</b></td>
              </tr>

              <?php
                 $purchases = purchaseHistory($db, $memberRecord);
                 if (count($purchases)==0)
                   {
                     echo "<tr>\n";
                     echo "  <td colspan=3> No Purchase History</td>\n";
                     echo "</tr>\n";
                   }
                 else
                   {
                     for ($i=0; $i<count($purchases); $i++)
                       {
                         $purchase=$purchases[$i];
                         $date=dateAsText(getDateTimeFromSecondsAsArray($purchase['ts_order']));
                         $amount=$purchase['amount'];
                         $title =$purchase['product_title'];
                         $is_prorated = $purchase['is_prorated'];

                         echo "<tr>\n";
                         echo "  <td>&nbsp; $date</td>\n";
                         echo "  <td align=right style='padding-right: 28px;'>$ $amount</td>\n";
                         if ($is_prorated)
                           echo "  <td>&nbsp; $title (<a href=javascript:alert('Prorated') title=\"See proration details\" alt=\"See proration details\">prorated</a>)</td>\n";

                         else
                           echo "  <td>&nbsp; $title</td>\n";
                         echo "</tr>\n";
                       }
                   }
              ?>
            </table>

          </td>
        </tr>
        <tr>
          <td>

            <div class="darkred largetext bold" style="margin-top: 20px;">Purchase Information</div>

            <?php
               $ccno=$memberRecord['cc_number'];
               $cc_number='';
               $len=strlen($ccno);
               if ($len == 15)
                 {
                   $first = $len-4;
                   $cc_last4 = substr($ccno,$first);
                   $cc_number='xxxx-xxxx-xxxx-'.$cc_last4;
                 }
               else
               if ($len == 16)
                 {
                   $first = $len-4;
                   $cc_last4 = substr($ccno,$first);
                   $cc_number='xxxx-xxxx-xxxx-'.$cc_last4;
                 }
            ?>

            <form action="NULL">
            <input type="hidden" name="hold_cc_number" value="<?php echo $memberRecord['cc_number']?>">

            <table width=60% valign=top cellspacing=5 cellpadding=0 class="text" style="margin: 15px 0 10px 0;">
              <tr>
                <td align=right><b>Cardholder Name:</b>&nbsp;</td>
                <td align=left><input name="cc_holdername" type=input value="<?php echo $memberRecord['cc_holdername']?>"></td>
              </tr>

              <tr>
                <td align=right><b>Billing Address:</b>&nbsp;</td>
                <td align=left><input name="cc_address" type=input value="<?php echo $memberRecord['cc_address']?>"></td>
              </tr>

              <tr>
                <td align=right><b>Billing Zip/Postal:</b>&nbsp;</td>
                <td align=left><input name="cc_zip" type=input value="<?php echo $memberRecord['cc_zip']?>"></td>
              </tr>

              <!-- tr>
                <td align=right><b>Credit Card Type:</b>&nbsp;</td>
                <td align=left><input name="cc_cctype" type=input value="VISA"></td>
              </tr -->

              <tr>
                <td align=right><b>Credit Card#:</b>&nbsp;</td>
                <td align=left><input name="cc_number" onKeyPress=javascript:ccNumberPressed(this) type=input value="<?php echo $cc_number?>"></td>
              </tr>

              <tr>
                <td align=right><b>Expiration Date:</b>&nbsp;</td>
                <td align=left><input name="cc_expmmyyyy" type=input value="<?php echo $memberRecord['cc_expmmyyyy']?>"></td>
              </tr>

              <tr>
                <td align=right><b>Security Code:</b>&nbsp;</td>
                <td align=left><input name="cc_cvv2" type=input value="<?php echo $memberRecord['cc_cvv2']?>"></td>
              </tr>

              <tr height=40>
                <td align=right></td>
                <td align=left>
                   <input type="button" style="width:100px;" class=bigbutton value="  UPDATE  " onClick=status_UpdatePaymentInfo(this.form)>
                </td>
              </tr>
            </table>

            </form>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
