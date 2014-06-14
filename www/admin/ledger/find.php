<html>
<?php
 $MAX_RECORDS = 100;

 $dateToday=getDateToday();
 $timeNow=getTimeNow();

 $op_equal    = FALSE;
 $op_contains = FALSE;
 if (strlen($EQUAL)>0)
   $op_equal    = TRUE;
 else
 if (strlen($CONTAINS)>0)
   $op_contains = TRUE;
?>

<head>

<style>
.unknown  { background-color:#E0FFE0;}
.online   { background-color:#E0E0FF;}
.renewal  { background-color:#F2F2F2;}
.return   { background-color:#FFE0E0;}
</style>

<script LANGUAGE="JavaScript">
 function goBack()
  {
    history.go(-1);
  }
 function goToFind()
  {
    document.location.href="index.php?op=FIND";
  }
</script>

<title>Search</title>
</head>

<body>

<table border="0" cellPadding="4" cellSpacing="0" width="90%">
<tbody>
  <tr>
    <td width="25%"><p align="center"><font color="#0000a0" face="Arial"><strong>Search Receipts</strong></font></td>
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

<div align="left">
<form method="POST" action="">
</form>
</div>

<?php
   $StatusMessage="";
   if ((isset($searchby)) && (strlen($searchby) > 0) &&
       (isset($searchterm)) && (strlen($searchterm) > 0))
     {
       $count=0;
       $searchterm = strtolower($searchterm);
       if ($searchby == "receiptid" && startsWith($searchterm,"r-"))
           $searchterm = substr($searchterm,2);

       $totShares=0;
       $totAmount=0;
       $totSharesPaid=0;
       $totAmountPaid=0;
       $totSharesOwing=0;
       $totAmountOwing=0;

       $sql  = "SELECT * FROM receipts";
       if ($op_equal)
         {
           $sql .= " WHERE $searchby='$searchterm'";
           if ($searchby=="lastname" && strlen($searchterm2)>0)
             $sql .= "  AND  firstname='$searchterm2'";
         }
       else
       if ($op_contains)
         {
           $sql .= " WHERE $searchby LIKE '%$searchterm%'";
           if ($searchby=="lastname" && strlen($searchterm2)>0)
             $sql .= "  AND  firstname='$searchterm2'";
         }
       $sql .= " order by ts_order ASC";
       $sql .= " LIMIT $MAX_RECORDS";
       $result = mysql_query($sql,$db);
       // printf("SQL:%s<br>\n",$sql);



       if (($result) && (mysql_num_rows($result) > 0))
         {
           $numrows = mysql_num_rows($result);
?>
           <table border="0" cellspacing="0" cellpadding="0" width="99%">
           <tr>
             <td align="left" width="60%">
               <input type="button" name="Return" value="  Return  " STYLE="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"  onClick="goToFind()">
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
           <br>
<?php
           if ($numrows >= $MAX_RECORDS)
             {
               echo "<font face=\"Arial\" color=\"#CC0000\"><b>MAX RESULT REACHED = $MAX_RECORDS</b></font><br>\n";
             }
?>

            <table border="0" width="95%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="10%" height="32" align="left"><font color="#0000A0" face="Arial"><u><small><strong>Date</strong></small></u></font></td>
                <td width="1%"  align="center">&nbsp; </td>
                <td width="5%"  align="left"  ><font color="#0000A0" face="Arial"><u><strong><small>Type</small></strong></u></font></td>
                <td width="1%"  align="center">&nbsp; </td>
                <td width="9%"  align="left"  ><font color="#0000A0" face="Arial"><u><strong><small>Method</small></strong></u></font></td>
                <td width="1%"  align="center">&nbsp; </td>
                <td width="8%"  align="right" ><font color="#0000A0" face="Arial"><u><strong><small>Amount</small></strong></u></font></td>
                <td width="3%"  align="center">&nbsp; </td>
                <td width="30%" align="left"  ><font color="#0000A0" face="Arial"><u><strong><small>Customer Name</small></strong></u></font></td>
              </tr>

<?php
           $myrow  = mysql_fetch_array($result);
           while($myrow)
             {
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

               $amount = number_format($myrow["amount"],2,".","");
?>
               <tr class="<?php echo $cls?>">
                 <td width="10%" align="left"   height="21"><p align="left"><font face="Arial"><small><a href="index.php?op=RECEIPT&receiptid=<?php echo $myrow['receiptid']?>&select_yymm=2000-08"><?php echo $myrow['yymmdd']?></a></small></font></td>
                 <td width="1%"  align="center" height="21">&nbsp; </td>
                 <td width="5%"  align="left"   height="21"><font face="Arial" color="#CC0000"><small><small><?php echo $tx?></small></small></font></td>
                 <td width="1%"  align="center" height="21">&nbsp; </td>
                 <td width="9%"  align="left"   height="21"><font face="Arial"><small><?php echo $paymethod?></small></font></td>
                 <td width="1%"  align="center" height="21">&nbsp; </td>
                 <td width="8%"  align="right"  height="21"><font face="Arial"><small>$ <?php echo $amount?>&nbsp;</small></font></td>
                 <td width="3%"  align="center" height="21">&nbsp; </td>
                 <td width="30%" align="left"   height="21"><font face="Arial"><small><?php echo $myrow['firstname']." ".$myrow['lastname']?></small></font></td>
               </tr>

<?php
               $myrow = mysql_fetch_array($result);
             }
           mysql_free_result($result);
           echo "</table></body></html>\n";
           exit;
         }
       else
         {

           if ($op_equal)
             $op_msg = "Equals";
           else
           if ($op_contains)
             $op_msg = "Contains";
           echo "<font face=\"Arial\"><font color=\"#0000CC\"><strong>$searchby $op_msg </strong></font>$searchterm &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><font color=\"#CC0000\"> Receipt Not Found </font></strong></font>\n";
           echo "</body></html>\n";
           exit;
         }
     }
?>


<form method="POST" action="index.php">
<input type="hidden" name="op" value="FIND">
<input type="hidden" name="searchby" value="member_id">
  <table border="0" width="720" cellpadding="0" cellspacing="0">
    <tr>
      <td width="25%" align="right">
        <font face="Arial"><font color="#0000A0"><strong>Member ID:&nbsp;&nbsp;&nbsp;</strong></font>
      </td>
      <td width="40%" align="left">
         <input type="text" name="searchterm" size="35">
      </td>
      <td width="10%" align="left">
         <input name=EQUAL    type="submit" value=" Equal " name="Submit">
      </td>
      <td width="20%" align="left">
         <input name=CONTAINS type="submit" value=" Contains " name="Submit">
      </td>
    </tr>
  </table>
</form>

<br>

<form method="POST" action="index.php">
<input type="hidden" name="op" value="FIND">
<input type="hidden" name="searchby" value="lastname">
  <table border="0" width="720" cellpadding="0" cellspacing="0">
    <tr>
      <td width="25%" align="right">
        <font face="Arial"><font color="#0000A0"><strong>Last Name:&nbsp;&nbsp;&nbsp;</strong></font>
      </td>
      <td width="40%" align="left">
         <input type="text" name="searchterm" size="35">
      </td>
      <td width="10%" align="left">
         <input name=EQUAL    type="submit" value=" Equal " name="Submit">
      </td>
      <td width="20%" align="left">
         <input name=CONTAINS type="submit" value=" Contains " name="Submit">
      </td>
    </tr>
    <tr>
      <td width="25%" align="right">
        <font face="Arial"><font color="#0000A0"><strong>First Name:&nbsp;&nbsp;&nbsp;</strong></font>
      </td>
      <td width="40%" align="left">
         <input type="text" name="searchterm2" size="35">
      </td>
      <td width="30%" align="left" colspan=2>
         <font face='Arial'><small>(optional - considered if specified)</small></font>
      </td>
    </tr>
  </table>
</form>

<br>

<form method="POST" action="index.php">
<input type="hidden" name="op" value="FIND">
<input type="hidden" name="searchby" value="receiptid">
  <table border="0" width="720" cellpadding="0" cellspacing="0">
    <tr>
      <td width="25%" align="right">
        <font face="Arial"><font color="#0000A0"><strong>Receipt ID:&nbsp;&nbsp;&nbsp;</strong></font>
      </td>
      <td width="40%" align="left">
         <input type="text" name="searchterm" size="35">
      </td>
      <td width="10%" align="left">
         <input name=EQUAL    type="submit" value=" Equal " name="Submit">
      </td>
      <td width="20%" align="left">
         <input name=CONTAINS type="submit" value=" Contains " name="Submit">
      </td>
    </tr>
  </table>
</form>

<br>

<form method="POST" action="index.php">
<input type="hidden" name="op" value="FIND">
<input type="hidden" name="searchby" value="transaction">
  <table border="0" width="720" cellpadding="0" cellspacing="0">
    <tr>
      <td width="25%" align="right">
        <font face="Arial"><font color="#0000A0"><strong>Paypal Transaction:&nbsp;&nbsp;&nbsp;</strong></font>
      </td>
      <td width="40%" align="left">
         <input type="text" name="searchterm" size="35">
      </td>
      <td width="10%" align="left">
         <input name=EQUAL    type="submit" value=" Equal " name="Submit">
      </td>
      <td width="20%" align="left">
         <input name=CONTAINS type="submit" value=" Contains " name="Submit">
      </td>
    </tr>
  </table>
</form>

<br>

<div align="left">
<form method="POST" action="">
   <input type="button" value=" Return " name="Back" onclick="goBack()">
</form>
</div>

</body>
</html>
