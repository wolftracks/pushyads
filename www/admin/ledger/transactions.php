<?php
$months=array("", "January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December");
$dateToday=getDateToday();
$timeNow=getTimeNow();
$StatusMessage = "";

if (!(isset($select_yymm)))
  {
    $mth  = 0;
    $year = 0;
    $date = "ALL";
  }
else
  {
    $year = (int) substr($select_yymm, 0, 4);
    $mth  = (int) substr($select_yymm, 5, 2);
    $date = "$months[$mth], $year";
  }


if ($command == "CREDIT")
  {

    $CREDIT_AMOUNT = $_REQUEST["CREDIT_AMOUNT"];
    $CREDIT_AMOUNT = number_format($CREDIT_AMOUNT,2,".","");

    $error=FALSE;
    $sql  = "SELECT * FROM receipts";
    $sql .= " WHERE receiptid = '$receiptid'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<BR>\n",$sql);
    // printf("ERR: %s<BR>\n",mysql_error());

    if (($result) && $myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $transaction_id        = $myrow['transaction'];

        $payflow_url = PAYFLOW_BILLING_HOST;        // TEST or LIVE  -  depends on the payflow include

        $payflow_resp = payflow_credit($payflow_url, $transaction_id, $CREDIT_AMOUNT);
        // TESTING --- $payflow_resp["RESULT"] = 0;

        $fullname = addslashes($myrow["firstname"]." ".$myrow["lastname"]);

        $amount = $CREDIT_AMOUNT;

        $msg = "";
        $msg .= sprintf("DATE            : %s\n",getDateToday());
        $msg .= sprintf("TIME            : %s\n\n",getTimeNow());

        $msg .= sprintf("TRANSACTION     : CREDIT\n");
        $msg .= sprintf("TRANSACTION-ID  : $transaction_id\n\n");

        $msg .= sprintf("Member ID       : %s\n",$myrow["member_id"]);
        $msg .= sprintf("Member Name     : %s\n",$fullname);
        $msg .= sprintf("Purchase Amount : %s\n",$myrow["amount"]);
        $msg .= sprintf("Credit Amount   : %s\n",$amount);
        $msg .= "\n";

        $msg .= print_r($payflow_resp,TRUE)."\n\n";

        if ($payflow_resp["RESULT"] == 0)
          {
            $msg .= "CREDIT transaction succeeded\n";

            $member_id = $myrow["member_id"];
            $memberRecord = getMemberInfo($db,$member_id);

            if (is_array($memberRecord))
              {
                $user_level   = $memberRecord["user_level"];
                if (isset($_REQUEST["target_level"]) &&
                    $_REQUEST["target_level"] < "$user_level")
                  {
                     $request_date = getDateToday();
                     $target_date  = $request_date;

                     $sql  = "DELETE FROM userlevel_change_requests ";
                     $sql .= " WHERE member_id = '$member_id'";
                     $result = mysql_query($sql,$db);

                     $sql  = "INSERT into userlevel_change_requests set ";
                     $sql .= " member_id    = '$member_id', ";
                     $sql .= " target_level = '$target_level', ";
                     $sql .= " request_date = '$request_date', ";
                     $sql .= " target_date  = '$target_date'";
                     $result = mysql_query($sql,$db);
                  }
              }

            //printf("SQL: %s<br>\n",$sql);
            //printf("ERR: %s<br>\n",mysql_error());
          }
        else
           $msg .= "CREDIT transaction failed\n";

        send_mail_direct("PushyAds", EMAIL_TEAM, "Ledger", EMAIL_NOREPLY,   "CREDIT TRANSACTION - $fullname", $msg);

        if ($payflow_resp["RESULT"] == 0)
           {
              if ($amount > 0)
                $amount = -1 * ($amount);

              $orig_transaction      = $transaction_id;
              $orig_receiptid        = $receiptid;
              $orig_yymmdd           = $myrow["yymmdd"];
              $ref_commission_amount = $myrow["ref_commission_amount"];


              $transaction_id = $payflow_resp["PNREF"];  // new transaction

              $tm=time();
              $timestamp = formatDateTime($tm, TRUE);
              $yymmdd    = substr($timestamp,0,10);
              $yymm      = substr($timestamp,0,7);

              $sql  = "INSERT into receipts set";
              $sql .= " txtype = $TXTYPE_CREDIT,";

              $sql .= " src                   ='".$myrow['src']."',";
              $sql .= " receiptid             ='CR-".$myrow['receiptid']."',";
              $sql .= " member_id             ='".$myrow['member_id']."',";
              $sql .= " ts_order              ='".time()."',";
              $sql .= " yymmdd                ='".$yymmdd."',";
              $sql .= " yymm                  ='".$yymm."',";
              $sql .= " amount                ='".number_format($amount,2,".","")."',";
              $sql .= " refid                 ='".addslashes(stripslashes($myrow['refid']))."',";
              $sql .= " firstname             ='".addslashes(stripslashes($myrow['firstname']))."',";
              $sql .= " lastname              ='".addslashes(stripslashes($myrow['lastname']))."',";
              $sql .= " paymentmethod         ='".addslashes(stripslashes($myrow['paymentmethod']))."',";
              $sql .= " authorizationcode     ='".$myrow['authorizationcode']."',";
              $sql .= " transaction           ='".$transaction_id."',";
              $sql .= " orig_yymmdd           ='".$orig_yymmdd."',";
              $sql .= " orig_receiptid        ='".$orig_receiptid."',";
              $sql .= " orig_transaction      ='".$orig_transaction."',";
              $sql .= " ref_commission_amount ='".$myrow['ref_commission_amount']."',";
              $sql .= " order_type            ='".$myrow['order_type']."',";
              $sql .= " product_title         ='".$myrow['product_title']."',";
              $sql .= " product_description   ='".addslashes(striplt($description))."'";

              $result = mysql_query($sql,$db);

              // printf("SQL: %s<BR>\n",$sql);
              // printf("ERR: %s<BR>\n",mysql_error());

              if ($result)
                 $count=mysql_affected_rows();
              else
                {
                  printf("---- ERROR ---- COPY/PASTE THIS PAGE INTO AN EMAIL AND SEND TO TIM -- <br>\n");

                  printf("SQL: %s<BR>\n",$sql);
                  printf("ERR: %s<BR>\n",mysql_error());

                  exit;
                }
              if ($count==0)
                {
                  $StatusMessage = "ERROR - transaction not found: $receiptid";
                }
              else
              if ($count==1)
                {
                  $StatusMessage = "Transaction Credited";

                  /** MARK ORIGINAL ORDER TRANSACTION (txtype=0) AS "RETURNED" **/
                  $sql  = "UPDATE receipts SET  returned=1  WHERE receiptid = '$receiptid'";
                  mysql_query($sql,$db);

                }
              else
                {
                  $StatusMessage = "ERROR - MORE THAN ONE TRANSACTION WAS CREDITED!";
                }
           }
         else
           {
              $StatusMessage = "ERROR - CREDIT transaction failed:<br>&nbsp;".print_r($payflow_result,TRUE)."<br>\n";
           }
      }
    else
      {
        $error=TRUE;
        $StatusMessage = "Open Failed";
      }
  }
else
if ($command == "VOID")
  {
    $error=FALSE;
    $sql  = "SELECT * FROM receipts";
    $sql .= " WHERE receiptid = '$receiptid'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<BR>\n",$sql);
    // printf("ERR: %s<BR>\n",mysql_error());

    if (($result) && $myrow = mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $transaction_id        = $myrow['transaction'];


        $payflow_url = PAYFLOW_BILLING_HOST;        // TEST or LIVE  -  depends on the payflow include

        $payflow_resp = payflow_void($payflow_url, $transaction_id);
        // TESTING --- $payflow_resp["RESULT"] = 0;

        // var_dump($payflow_resp);
        // var_dump($_REQUEST);
        // var_dump($myrow);
        // exit;

        $fullname = addslashes($myrow["firstname"]." ".$myrow["lastname"]);
        $msg = "";
        $msg .= sprintf("DATE            : %s\n",getDateToday());
        $msg .= sprintf("TIME            : %s\n\n",getTimeNow());

        $msg .= sprintf("TRANSACTION     : VOID\n");
        $msg .= sprintf("TRANSACTION-ID  : $transaction_id\n\n");

        $msg .= sprintf("Member ID       : %s\n",$myrow["member_id"]);
        $msg .= sprintf("Member Name     : %s\n",$fullname);
        $msg .= sprintf("Purchase Amount : %d\n",number_format($myrow["amount"],2,".",""));
        $msg .= "\n";

        $msg .= print_r($payflow_resp,TRUE)."\n\n";

        if ($payflow_resp["RESULT"] == 0)
          {
            $msg .= "VOID transaction succeeded\n";

            $member_id = $myrow["member_id"];
            $memberRecord = getMemberInfo($db,$member_id);

            if (is_array($memberRecord))
              {
                $user_level   = $memberRecord["user_level"];
                if (isset($_REQUEST["target_level"]) &&
                    $_REQUEST["target_level"] < "$user_level")
                  {
                     $request_date = getDateToday();
                     $target_date  = $request_date;

                     $sql  = "DELETE FROM userlevel_change_requests ";
                     $sql .= " WHERE member_id = '$member_id'";
                     $result = mysql_query($sql,$db);

                     $sql  = "INSERT into userlevel_change_requests set ";
                     $sql .= " member_id    = '$member_id', ";
                     $sql .= " target_level = '$target_level', ";
                     $sql .= " request_date = '$request_date', ";
                     $sql .= " target_date  = '$target_date'";
                     $result = mysql_query($sql,$db);
                  }
              }

          }
        else
           $msg .= "VOID transaction failed\n";

        send_mail_direct("PushyAds", EMAIL_TEAM, "Ledger", EMAIL_NOREPLY,   "VOID TRANSACTION - $fullname", $msg);

        if ($payflow_resp["RESULT"] == 0)
           {
              $amount = $myrow['amount'];
              if ($amount > 0)
                $amount = -1 * ($amount);

              $orig_transaction      = $transaction_id;
              $orig_receiptid        = $receiptid;
              $orig_yymmdd           = $myrow["yymmdd"];
              $ref_commission_amount = $myrow["ref_commission_amount"];


              $transaction_id = $payflow_resp["PNREF"];  // new transaction

              $tm=time();
              $timestamp = formatDateTime($tm, TRUE);
              $yymmdd    = substr($timestamp,0,10);
              $yymm      = substr($timestamp,0,7);

              $sql  = "INSERT into receipts set";
              $sql .= " txtype = $TXTYPE_VOID,";

              $sql .= " src                   ='".$myrow['src']."',";
              $sql .= " receiptid             ='VOID-".$myrow['receiptid']."',";
              $sql .= " member_id             ='".$myrow['member_id']."',";
              $sql .= " ts_order              ='".time()."',";
              $sql .= " yymmdd                ='".$yymmdd."',";
              $sql .= " yymm                  ='".$yymm."',";
              $sql .= " amount                ='".number_format($amount,2,".","")."',";
              $sql .= " refid                 ='".addslashes(stripslashes($myrow['refid']))."',";
              $sql .= " firstname             ='".addslashes(stripslashes($myrow['firstname']))."',";
              $sql .= " lastname              ='".addslashes(stripslashes($myrow['lastname']))."',";
              $sql .= " paymentmethod         ='".addslashes(stripslashes($myrow['paymentmethod']))."',";
              $sql .= " authorizationcode     ='".$myrow['authorizationcode']."',";
              $sql .= " transaction           ='".$transaction_id."',";
              $sql .= " orig_yymmdd           ='".$orig_yymmdd."',";
              $sql .= " orig_receiptid        ='".$orig_receiptid."',";
              $sql .= " orig_transaction      ='".$orig_transaction."',";
              $sql .= " ref_commission_amount ='".$myrow['ref_commission_amount']."',";
              $sql .= " order_type            ='".$myrow['order_type']."',";
              $sql .= " product_title         ='".$myrow['product_title']."',";
              $sql .= " product_description   ='".addslashes(striplt($description))."'";
              $result = mysql_query($sql,$db);

              // printf("SQL: %s<BR>\n",$sql);
              // printf("ERR: %s<BR>\n",mysql_error());

              if ($result)
                 $count=mysql_affected_rows();
              else
                {
                  printf("---- ERROR ---- COPY/PASTE THIS PAGE INTO AN EMAIL AND SEND TO TIM -- <br>\n");

                  printf("SQL: %s<BR>\n",$sql);
                  printf("ERR: %s<BR>\n",mysql_error());

                  exit;
                }
              if ($count==0)
                {
                  $StatusMessage = "ERROR - transaction not found: $receiptid";
                }
              else
              if ($count==1)
                {
                  $StatusMessage = "Transaction Voided";

                  /** MARK ORIGINAL ORDER TRANSACTION (txtype=0) AS "RETURNED" **/
                  $sql  = "UPDATE receipts SET  returned=1  WHERE receiptid = '$receiptid'";
                  mysql_query($sql,$db);

                }
              else
                {
                  $StatusMessage = "ERROR - MORE THAN ONE TRANSACTION WAS VOIDED!";
                }
           }
         else
           {
              $StatusMessage = "ERROR - VOID transaction failed:<br>&nbsp;".print_r($payflow_result,TRUE)."<br>\n";
           }

      }
    else
      {
        $error=TRUE;
        $StatusMessage = "Open Failed";
      }
  }
else
if ($command == "DELETE")
  {
    $sql  = "DELETE FROM receipts";
    $sql .= " WHERE receiptid = '$receiptid'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result)
       $count=mysql_affected_rows();
    if ($count==0)
      {
        $StatusMessage = "ERROR - transaction not found: $receiptid";
      }
    else
    if ($count==1)
      {
        $StatusMessage = "Transaction Deleted";
      }
    else
      {
        $StatusMessage = "ERROR - MORE THAN ONE TRANSACTION WAS DELETED!";
      }

    // printf("((%s))<br>\n",$StatusMessage);
    // exit;
  }
?>
<html>
<head>
<meta http-equiv="Page-Enter" content="RevealTrans (Duration=.6,Transition=2)">

<style>
.unknown  { background-color:#E0FFE0;}
.online   { background-color:#E0E0FF;}
.renewal  { background-color:#F2F2F2;}
.return   { background-color:#FFE0E0;}
</style>

<script language="JavaScript">
<!--
  function goTo(functionName)
    {
      document.location.href="index.php?op="+functionName+"&select_yymm=<?php echo $select_yymm?>";
    }
  function sortBy(column)
    {
      document.RECEIPTS.SortBy.value=column;
    }
// -->
</script>
<title>Account Manager</title>
<meta name="GENERATOR" content="Microsoft FrontPage 3.0">
</head>

<body>

<table border="0" cellPadding="0" cellSpacing="0" width="99%">
<tbody>
  <tr>
    <td width="25%"><p align="center"><font color="#0000a0" face="Arial"><strong>Account
    Manager</strong></font>
    </td>
    <td width="50%"><p align="center"><font color="#ff0000" face="Arial"><big><strong><big><strong>
    PushyAds</strong></big></strong></big></font><br>
    <font color="#000000" face="Arial"><small><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </small></small><br>
    <small><small>&nbsp;&nbsp;&nbsp;&nbsp; </small></small></font></td>
    <td width="25%"><font face="Arial"><strong><small><font color="#0000A0">DATE:</font>
    &nbsp; <font color="#000000"><?php echo $dateToday?></font></small><br>
    <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo $timeNow?></font></small></strong></font></td>
  </tr>
</tbody>
</table>
<br>
<form method="POST" action="">
  <table border="0" cellspacing="0" cellpadding="0" width="99%">
  <tr>
    <td height="50" align="left" colspan="2" width="100%">
      <input type="button" name="Return" value="  Return  " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"  onClick="goTo('MAIN')">
    </td>
  </tr>
  <tr>
    <td align="left" width="60%">
      <strong><font color="#0000A0" face="Arial"><?php echo $type?> TRANSACTIONS &nbsp;&nbsp; <font color="#CC0000"><?php echo $date?></font></font></strong>
    </td>
    <td align="center" width="40%">
       <table width="100%" cellpadding=2 cellspacing=0 border=2>
         <tr>
            <td align="center" width="25%">
              <font face="Arial"><small><small><span style="font-weight:bold; color:#000099">LEGEND&nbsp;</span>:&nbsp;</small></small></font>
            </td>
            <td class=online  align="center" width="25%">
              <font face="Arial"><small><small>&nbsp;&nbsp;Online-Order&nbsp;&nbsp;</small></small></font>
           </td>
            <td class=renewal align="center" width="25%">
              <font face="Arial"><small><small>&nbsp;&nbsp;Batch-Renewal&nbsp;&nbsp;</small></small></font>
            </td>
            <td class=return  align="center" width="25%">
              <font face="Arial"><small><small>&nbsp;&nbsp;Return&nbsp;&nbsp;</small></small></font>
            </td>
         </tr>
       </table>
    </td>
  </tr>
  </table>
</form>

<?php
if (strlen($StatusMessage) > 0)
  {
?>
<br>
<table width="100%" bgcolor="#E8E8E8">
<tr>
  <td width="100%"><font face="Arial" size="3" color="#CC0000"><b><?php echo $StatusMessage?></b></font></td>
</tr>
</table>
<br>
<?php
  }
?>

<form name="RECEIPTS" action="index.php">
<input type="hidden" name="op" value="TRANSACTIONS">
<input type="hidden" name="select_yymm"  value="<?php echo $select_yymm?>">
<input type="hidden" name="SortBy"       value="<?php echo $SortBy?>">
<table border="0" width="98%" cellpadding="0" cellspacing="0">

  <tr>
    <td width="14%"><input type="submit" value="Date" onclick=sortBy("Date") STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"></td>
    <td width="8%"> <input type="submit" value="Type" onclick=sortBy("Type") STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"></td>
    <td width="14%" align="left"> <font color="#0000A0" face="Arial"><u><strong><small><small>Payment Method</small></small></strong></u></font></td>
    <td width="6%"  align="right"><font color="#0000A0" face="Arial"><u><strong><small><small>Amount</small></small></strong></u></font></td>
    <td width="2%"  align="left">&nbsp;</td>
    <td width="24%"><input type="submit" value="Customer Name" onclick=sortBy("Name") STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"></td>
    <td width="14%"><input type="submit" value="Product" onclick=sortBy("Product") STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"></td>
    <td width="10%" align="center"> <font color="#0000A0" face="Arial"><u><strong><small><small>UserLevel</small></small></strong></u></font></td>
  </tr>

<?php
    $sql  = "SELECT * FROM receipts";
    if (isset($select_yymm))
      {
        $sql .= " WHERE yymm = '$select_yymm'";
      }
    if ($SortBy=="Date")
      $sql .= " order by ts_order";
    else
    if ($SortBy=="Type")
      $sql .= " order by txtype DESC, ts_order ASC";
    else
    if ($SortBy=="PaymentMethod")
      $sql .= " order by paymentmethod, ts_order";
    else
    if ($SortBy=="Name")
      $sql .= " order by lastname, firstname, ts_order";
    else
    if ($SortBy=="Product")
      $sql .= " order by product_name, ts_order";
    else
      $sql .= " order by ts_order";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result)
      {
        $myrow    = mysql_fetch_array($result);
        while ($myrow)
          {
            $user_level=$myrow["user_level"];
            $user_level_name=$UserLevels[$user_level];

            $src = $myrow["src"];
            $txtype = $myrow["txtype"];
            $tx="&nbsp;";
            if ($txtype == $TXTYPE_CREDIT)
               $tx = "(CR)";
            else
            if ($txtype == $TXTYPE_VOID)
               $tx = "(Void)";
            $paymethod = $myrow["paymentmethod"];
            $cls="unknown";
            if ($src==1 && $txtype==0)
               $cls="online";
            else
            if ($src==1 && $txtype>0)
               $cls="return";
            else
            if ($src==2 && txtype==0)
               $cls="renewal";

?>
  <tr class="<?php echo $cls?>">
    <td width="14%" align="left"  ><p align="left"><font face="Arial"><small><a href="index.php?op=RECEIPT&receiptid=<?php echo $myrow['receiptid']?>&select_yymm=<?php echo $myrow['yymm']?>"><small><small><?php echo formatDateTime($myrow['ts_order'])?></small></small></a></font></td>
    <td width="8%"  align="left"  ><font face="Arial" color="#CC0000"><small><small><?php echo $tx?></small></small></font></td>
    <td width="14%" align="left"  ><font face="Arial"><small><small><?php echo $paymethod?></small></small></font></td>
    <td width="6%"  align="right" ><font face="Arial"><small><small>$ <?php echo $myrow['amount']?>&nbsp;</small></small></font></td>
    <td width="2%"  align="left">&nbsp;</td>
    <td width="24%" align="left"><font face="Arial"><small><small><?php echo $myrow['lastname'].", ".$myrow['firstname']?></small></small></font></td>
    <td width="14%" align="left"><font face="Arial"><small><small><?php echo $myrow['product_title']?></small></small></font></td>
    <td width="10%" align="center"><font face="Arial"><small><small><?php echo $user_level_name?></small></small></small></font></td>
  </tr>
<?php
            $myrow = mysql_fetch_array($result);
          }

        mysql_free_result($result);
      }
?>
</table>
</form>

<br>
<form method="POST" action="index.php">
  <input type="hidden" name="op" value="MAIN">
  <input type="hidden" name="select_yymm" value="<?php echo $select_yymm?>">
  <input type="submit" name="return" value=" Return ">
</form>

</body>
</html>
