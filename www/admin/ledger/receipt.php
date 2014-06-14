<html>
<?php
$dateToday=getDateToday();
$timeNow=getTimeNow();
$lastmod=time();

$previously_credited = FALSE;
$sql  = "SELECT * FROM receipts";
$sql .= " WHERE receiptid = 'CR-".$receiptid."'";
$sql .= " OR    receiptid = 'VOID-".$receiptid."'";
$result = exec_query($sql,$db);
if (($result) && ($myrow = mysql_fetch_array($result)))
  {
    $previously_credited = TRUE;
  }

$StatusMessage = "";
$sql  = "SELECT * FROM receipts";
$sql .= " WHERE receiptid = '$receiptid'";
$result = exec_query($sql,$db);
if (($result) && ($myrow = mysql_fetch_array($result)))
  {
    $operation = "TRANSACTIONS";
    $txtype    = $myrow['txtype'];
  }
else
  {
    echo "<html><body><H3>Error - Open Failed</H3></body></html>";
    exit;
  }
?>
<head>

<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript" src="/admin/admin.js"></script>
<script language="JavaScript">
function ValidateForm(theForm)
  {
    if (theForm.command.value == "CREDIT")
      {
        document.RECEIPT.op.value = "CREDIT";
        document.RECEIPT.nextop.value = "<?php echo $operation?>";
        return true;
      }
    if (theForm.command.value == "VOID")
      {
        document.RECEIPT.op.value = "VOID";
        document.RECEIPT.nextop.value = "<?php echo $operation?>";
        return true;
      }
    if (theForm.command.value == "UPDATE")
      {
        document.RECEIPT.op.value = "UPDATE";
        document.RECEIPT.nextop.value = "<?php echo $operation?>";
        return true;
      }
    if (theForm.command.value == "DELETE")
      {
        if (confirm("Are You Sure you Want to DELETE this Transaction?"))
          {
            document.RECEIPT.op.value = "<?php echo $operation?>";
            return true;
          }
        return false;
      }
  }

function prepareTo(functionName)
  {
    if (functionName == "UPDATE")
      {
        document.RECEIPT.command.value = "UPDATE";
      }
    else
    if (functionName == "DELETE")
      {
        document.RECEIPT.command.value = "DELETE";
      }
    else
    if (functionName == "CREDIT")
      {
        document.RECEIPT.command.value = "CREDIT";
      }
    else
    if (functionName == "VOID")
      {
        document.RECEIPT.command.value = "VOID";
      }
  }

function goTo(functionName)
  {
    document.location.href="index.php?op="+functionName+"&select_yymm=<?php echo $select_yymm?>";
  }

function isNumeric(aString)
  {
    count=0;
    for (i=0; i<aString.length; i++)
      {
        if (aString.charAt(i) >= '0' && aString.charAt(i) <= '9')
          count++;
        else
          return false;
      }
    if (count > 0)
       return(true);
    return(false);
  }
</script>

<title>Receipts</title>
</head>

<body>

<table border="0" cellPadding="0" cellSpacing="0" width="800">
<tbody>
    <td width="25%"><p align="center"><font color="#0000a0" face="Arial"><strong>Receipt</strong></font>
    </td>
    <td width="50%"><p align="center"><font color="#ff0000" face="Arial"><big><strong><big><strong>PushyAds</strong></big></strong></big></font><br>&nbsp;</td>
    <td width="25%"><font face="Arial"><strong>
           <small><font color="#0000A0">DATE:</font>&nbsp;<font color="#000000"><?php echo $dateToday?></font></small><br>
           <small><font color="#0000A0">TIME:</font>&nbsp;&nbsp;&nbsp; <font color="#000000"><?php echo $timeNow?></font></small></strong></font>
    </td>
  </tr>
</tbody>
</table>
<br>

<form method="POST" name=RECEIPT action="index.php" onsubmit="return ValidateForm(this)">
  <input type="hidden" name="op" value="RECEIPT">
  <input type="hidden" name="nextop" value="">
  <input type="hidden" name="command" value="">
  <input type="hidden" name="receiptid" value="<?php echo $receiptid?>">
  <input type="hidden" name="select_yymm" value="<?php echo $select_yymm?>">
  <div align="left">
  <table border="0" cellpadding="0" cellspacing="0" width="500">

<?php
    if ($txtype == 1)
      {
        echo "<tr><td colspan=\"5\"><font face=\"Arial\" color=\"#CC0000\"><b>CREDIT</b></font><br>&nbsp;</td></tr>";
      }
    else
    if ($txtype == 2)
      {
        echo "<tr><td colspan=\"5\"><font face=\"Arial\" color=\"#CC0000\"><b>VOIDED</b></font><br>&nbsp;</td></tr>";
      }
    else
    if ($txtype == 3)
      {
        echo "<tr><td colspan=\"5\"><font face=\"Arial\" color=\"#CC0000\"><b>Charge Back</b></font><br>&nbsp;</td></tr>";
      }
    else
    if ($txtype == 4)
      {
        echo "<tr><td colspan=\"5\"><font face=\"Arial\" color=\"#CC0000\"><b>Bad Debt</b></font><br>&nbsp;</td></tr>";
      }
?>

    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Receipt ID:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="75%"><font face="Arial" color="#0000CC"><b><small><?php echo $myrow["receiptid"]?></small></b></font></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Order Date:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><small><strong><font face="Arial" color="0000AA"><?php echo formatDateTime($myrow["ts_order"])?>&nbsp;</font></strong></small></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Member ID:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%">
        <small><strong><font face="Arial"><a href=javascript:openMember('<?php echo $myrow["member_id"]?>')><?php echo $myrow["member_id"]?></a></font></strong></small>
      </td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Ref ID:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><small><strong><font face="Arial" color="#CC0000"><a href=javascript:openMember('<?php echo $myrow["refid"]?>')><?php echo $myrow["refid"]?></a></font></strong></small></td>
    </tr>

    <tr><td colspan=3>&nbsp;</td></tr>

    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Name:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><?php echo stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"])?></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">User IP:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><small><font face="Arial"><?php echo $myrow['ipaddr']?></font></small></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Amount:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><?php echo number_format($myrow["amount"],2,".","")?></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Pay Method:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><?php echo $myrow["paymentmethod"]?></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Auth Code:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><?php echo $myrow["authorizationcode"]?></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Trans ID:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><?php echo $myrow["transaction"]?></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Ref Comm:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%">$<?php echo number_format($myrow["ref_commission_amount"],2,".","")?></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Product:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><font face="Arial"><small><?php echo $myrow["product_title"]?></small></font></td>
    </tr>
    <tr>
      <td width="20%" align="right"><p><small><strong><font face="Arial">Decription:</font></strong></small></td>
      <td width="4%"  align="right">&nbsp;</td>
      <td width="76%"><font face="Arial"><small><?php echo $myrow["product_description"]?></small></font></td>
    </tr>

  <?php
    if ($txtype > 0)
      {
  ?>
        <tr bgcolor="#F0F0F0">
          <td width="20%" align="right"><p><small><strong><font face="Arial">ORIG Charge:</font></strong></small></td>
          <td width="4%"  align="right">&nbsp;</td>
          <td width="76%"><?php echo $myrow["orig_yymmdd"]?></td>
        </tr>
        <tr bgcolor="#F0F0F0">
          <td width="20%" align="right"><p><small><strong><font face="Arial">ORIG Receipt:</font></strong></small></td>
          <td width="4%"  align="right">&nbsp;</td>
          <td width="76%"><?php echo $myrow["orig_receiptid"]?></td>
        </tr>
        <tr bgcolor="#F0F0F0">
          <td width="20%" align="right"><p><small><strong><font face="Arial">ORIG Trans:</font></strong></small></td>
          <td width="4%"  align="right">&nbsp;</td>
          <td width="76%"><?php echo $myrow["orig_transaction"]?></td>
        </tr>
  <?php
      }
  ?>

  </table>
  </div>
  <font face="Arial" color="CC0000"><b><small><br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $StatusMessage?> &nbsp;</small></b></font></br>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Delete" name="Delete" onClick='prepareTo("DELETE")'> &nbsp;&nbsp;&nbsp;
<?php
    if ($txtype == 0)
      {
        if (!$previously_credited)
          {
            echo "<input type=\"submit\" value=\" CREDIT \" name=\"Credit\" onClick='prepareTo(\"CREDIT\")'> &nbsp;&nbsp;&nbsp;";
            echo "<input type=\"submit\" value=\" VOID \" name=\"Void\" onClick='prepareTo(\"VOID\")'> &nbsp;&nbsp;&nbsp;";
          }
      }
?>
    <input type="button" value=" Find " name="Find" onClick="javascript:document.location.href='index.php?op=FIND'"> &nbsp;&nbsp;&nbsp;
    <input type="button" value="Return" name="Return" onClick="goTo('TRANSACTIONS')">
  </p>
</form>
</body>
</html>
