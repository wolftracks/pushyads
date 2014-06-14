<?php
 $receiptid = $_REQUEST["receiptid"];

 $sql  = "SELECT * from receipts";
 $sql .= " WHERE receiptid='$receiptid'";
 $result=mysql_query($sql,$db);
 if (($result) && ($myrow=mysql_fetch_array($result,MYSQL_ASSOC)))
   {
     $custname             = stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"]);
     $purchase_date        = $myrow["yymmdd"];
     $purchase_amount      = $myrow["amount"];
     $payment_method       = $myrow["paymentmethod"];
     $transaction          = $myrow["transaction"];

     $member_id            = $myrow["member_id"];
     $memberRecord         = getMemberInfo($db,$member_id);
     $user_level           = $memberRecord["user_level"];
     $user_level_name      = $UserLevels[$user_level];
   }
 else
   {
     printf("Receipt Not Found: '$receiptid'\n");
     exit;
   }

//  printf("SQL: %s<br>\n",$sql);
//  printf("ERR: %s<br>\n",mysql_error());


// var_dump($myrow);
// var_dump($_REQUEST);
?>
<html>
<head>
<script type="text/javascript" src="/local-js/common.js"></script>
<script type="text/javascript">
<!--
function VerifyForm(theForm)
  {
    theForm.description.value=striplt(theForm.description.value);
    if (theForm.description.value.length==0)
      {
        alert("Please provide a reason for voiding this transaction");
        theForm.description.focus();
        return false;
      }
    return true;
  }


function isMoney(aString)
  {
    $dp=0;
    $dpc=0;
    for (i=0; i<aString.length; i++)
      {
        if (aString.charAt(i) >= '0' && aString.charAt(i) <= '9')
          {
            if ($dp>0) $dpc++;
          }
        else
        if (aString.charAt(i) == '.' && $dp==0)
          { }
        else
          return false;
      }
    if ($dp != 1 || $dpc != 2)
       return false;
    return(true);
  }


function cancelAction()
  {
    // window.close();
    history.back();
  }
// -->
</script>
<title>Void</title>
</head>

<body bgcolor="#FFE0E0"><font face="Arial">
<form name="VOID_FORM" method="POST" action="index.php" onSubmit='return VerifyForm(this)'>
<input type="hidden" name="op" value="ISSUE-VOID">
<input type="hidden" name="nextop" value="<?php echo $nextop?>">
<input type="hidden" name="receiptid"  value="<?php echo $receiptid?>">
<input type="hidden" name="select_yymm" value="<?php echo $select_yymm?>">
 <table>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial">&nbsp</font></td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial"><small>You have asked to <font color="#CC0000"><b>VOID</b></font> the following charge.  If you choose to submit this transaction, <br>&nbsp;&nbsp; the Void will be processed <b>BOTH IN RECEIPTS AND IN PAYFLOW</b></small></font></td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><small>&nbsp;</small></td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial"><small>To Continue, click <b>SUBMIT</b></small></font></td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial"><small>To Cancel,&nbsp;&nbsp;&nbsp; click <b>CANCEL</b></small></font></td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><small>&nbsp;</small></td>
   </tr>
   <tr>
      <td width="20%" align="right"><font face="Arial"><small><b>Customer Name:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%"><font face="Arial" color="#000099"><small><b><?php echo $custname?></b></small></font></td>
   </tr>
   <tr>
      <td width="20%" align="right"><font face="Arial"><small><b>Purchase Date:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%"><font face="Arial" color="#000099"><small><b><?php echo $purchase_date?></b></small></font></td>
   </tr>
   <tr>
      <td width="20%" align="right"><font face="Arial"><small><b>Purchase Amount:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%"><font face="Arial" color="#000099"><small><b>$ <?php echo $purchase_amount?></b></small></font></td>
   </tr>
   <tr>
      <td width="20%" align="right"><font face="Arial"><small><b>Payment Method:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%"><font face="Arial" color="#000099"><small><b><?php echo $payment_method?></b></small></font></td>
   </tr>
   <tr height="40" valign="bottom">
      <td width="20%" align="right"><font face="Arial"><small><b>Reason for Void:&nbsp;&nbsp;</b><br><small>(Include Your Initials)&nbsp;&nbsp;</small></small></font></td>
      <td width="80%"><input type="text" name="description" size="40" maxlength="40" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 11px;" value="<?php echo $description?>"></td>
   </tr>


   <tr height="40" valign="bottom">
      <td colspan=2><font face="Arial"><small>
         <br />
         <span style="width:500px">
         The Select Box below shows the member's Current Level.<br> <br>
         If the Member's Level is to be changed as a result of this Void, you must indicate the Level
         that the member will be after this Void is issued.  <br> <br>
         DO NOT Change this selection if there is to be no change in the Members User Level status
         as a result of this Void Transaction.
         </span>
      </td>
   </tr>

   <tr height="40" valign="bottom">
      <td width="20%" align="right"><font face="Arial"><small><b>Member's NEW Level:&nbsp;&nbsp;</b></td>
      <td width="80%">
        <?php
           if ($user_level == $PUSHY_LEVEL_ELITE) $target_elite = "selected";
           else
           if ($user_level == $PUSHY_LEVEL_PRO)   $target_pro   = "selected";
           else
           if ($user_level == $PUSHY_LEVEL_VIP)   $target_vip   = "selected";
        ?>
        <SELECT name=target_level>
           <option value="0" <?php echo $target_vip?>  > VIP   </option>
           <option value="1" <?php echo $target_pro?>  > PRO   </option>
           <option value="2" <?php echo $target_elite?>> ELITE </option>
        </SELECT>
        &nbsp;<br />
      </td>
   </tr>


   <tr height="50" valign="bottom">
      <td width="100%" align="left" colspan="2">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" value="  Submit  ">&nbsp;&nbsp;&nbsp;
        <input type="button" value="  Cancel  " onClick=cancelAction()>
      </td>
   </tr>
 </table>
</form>

</body>
</html>
