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
function VerifyForm(theForm)
  {
    theForm.CREDIT_AMOUNT.value=striplt(theForm.CREDIT_AMOUNT.value);
    if (theForm.CREDIT_AMOUNT.value.length==0)
      {
        alert("Please indicate the amount being credited - MUST BE DOLLARS AND CENTS  XXX.YY");
        theForm.CREDIT_AMOUNT.focus();
        return false;
      }
    if (!(isMoney(theForm.CREDIT_AMOUNT.value)))
      {
        alert("Credit Amount must in Dollars And Cents - XXX.YY");
        theForm.CREDIT_AMOUNT.focus();
        return false;
      }


//  rbox=getRadioChecked(theForm,"CREDIT_TYPE");
//  if (rbox==null)
//    {
//      alert("The Type of Credit Being Issued Must be Indicated");
//      return false;
//    }

    theForm.description.value=striplt(theForm.description.value);
    if (theForm.description.value.length==0)
      {
        alert("Please provide a Reason For Issuing this Credit");
        theForm.description.focus();
        return false;
      }

    return true;
  }


function isMoney(aString)
  {
    var dp=0;
    var dpc=0;
    for (i=0; i<aString.length; i++)
      {
        if (aString.charAt(i) >= '0' && aString.charAt(i) <= '9')
          {
            if (dp>0) dpc++;
          }
        else
        if (aString.charAt(i) == '.' && dp==0)
          {
            dp=1;
          }
        else
          {
            return false;
          }
      }
    if (dp != 1 || dpc != 2)
       return false;
    return(true);
  }


function cancelAction()
  {
    // window.close();
    history.back();
  }

function getRadioChecked(theForm,eName)
  {
    for (var i = 0; i < theForm.elements.length; i++)
      {
        if (theForm.elements[i].type == "radio")
          {
            rbox=theForm.elements[i];
            if (rbox.name==eName && rbox.checked)
              return rbox;
          }
      }
    return null;
  }
</script>
<title>Credit</title>
</head>
<body bgcolor="#E0E0FF"><font face="Arial">
<form name="CREDIT_FORM" method="POST" action="index.php" onSubmit='return VerifyForm(this)'>
<input type="hidden" name="op" value="ISSUE-CREDIT">
<input type="hidden" name="nextop" value="<?php echo $nextop?>">
<input type="hidden" name="receiptid"  value="<?php echo $receiptid?>">
<input type="hidden" name="select_yymm" value="<?php echo $select_yymm?>">
 <table>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial">&nbsp</font></td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial">
         <small>You have asked to <font color="#CC0000"><b>CREDIT</b></font> the following charge. </small></td>
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
      <td width="20%" align="right"><font face="Arial"><small><b>Credit Amount:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%">$ <input type="text" name="CREDIT_AMOUNT" size="9" maxlength="9" value="<?php echo $CREDIT_AMOUNT?>"><font face="Arial"><small>&nbsp; &nbsp; MUST BE Dollars and Cents</small></font></td>
   </tr>


<?php
   if (FALSE)
     {
?>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial">
       <small>&nbsp;<br>Unless VeriSign Has already credited back this charge, You should ALWAYS indicate processing</small></font>
       <small>&nbsp;<br> to occur in both Receipts and Verisign.  If Verisign has already credited this charge, then select</small></font>
       <small>&nbsp;<br>RECEIPTS-ONLY<br></small></font>
      </font></td>
   </tr>
   <tr>
      <td width="20%" align="right"><font face="Arial"><small><b>Process This Credit:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%">
        <font face="Arial"><small><small>
            &nbsp;
            IN RECEIPTS ONLY
            <input type="radio" name="WHERE_PROCESSED" value="R">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            IN BOTH RECEIPTS AND IN PAYFLOW
            <input type="radio" name="WHERE_PROCESSED" value="RV" checked>
        </small></small></font>
      </td>
   </tr>
   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial"><small><small>&nbsp;</small></small></font></td>
   </tr>
   <tr>
      <td width="20%" align="right"><font face="Arial"><small><b>Type of Credit:&nbsp;&nbsp;</b></small></font></td>
      <td width="80%">
        <font face="Arial"><small><small>
            &nbsp;
            Charge Back
            <input type="radio" name="CREDIT_TYPE" value="3">
            &nbsp;&nbsp;&nbsp;
            Bad Debt
            <input type="radio" name="CREDIT_TYPE" value="4">
            &nbsp;&nbsp;&nbsp;
            Other
            <input type="radio" name="CREDIT_TYPE" value="1" checked>
            &nbsp;&nbsp;
        </small></small></font>
      </td>
   </tr>
<?php
     }
?>

   <tr>
      <td width="100%" align="left" colspan="2"><font face="Arial"><small><small>&nbsp;</small></small></font></td>
   </tr>

   <tr height="40" valign="bottom">
      <td width="20%" align="right"><font face="Arial"><small><b>Reason for Credit:&nbsp;&nbsp;</b><br><small>(INCLUDE Your Initials)&nbsp;&nbsp;</small></small></font></td>
      <td width="80%"><input type="text" name="description" size="40" maxlength="40" STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 11px;" value="<?php echo $description?>"></td>
   </tr>

   <tr height="40" valign="bottom">
      <td colspan=2><font face="Arial"><small>
         <br />
         <span style="width:500px">
         The Select Box below shows the member's Current Level.<br> <br>
         If the Member's Level is to be changed as a result of this Credit, you must indicate the Level
         that the member will be after this CREDIT is issued.  <br> <br>
         DO NOT Change this selection if there is to be no change in the Members User Level status
         as a result of this Credit Transaction.
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
