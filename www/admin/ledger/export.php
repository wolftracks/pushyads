<html>
<head>
<title>Export Utility</title>
<meta name="GENERATOR" content="Microsoft FrontPage 3.0">
</head>

<body>

<table border="0" cellPadding="4" cellSpacing="0" width="90%">
<tbody>
  <tr>
    <td width="25%"><p align="center"><font color="#0000a0" face="Arial"><strong>Export
    Utility</strong></font></td>
    <td width="50%"><p align="center"><font color="#ff0000" face="Arial"><big><strong><big><strong>
    PushyAds</strong></big></strong></big></font><br>
    <font color="#000000" face="Arial"><small><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </small></small><br>
    <small><small>&nbsp;&nbsp;&nbsp;&nbsp; </small></small></font></td>
    <td width="25%"><font face="Arial">&nbsp;</td>
  </tr>
</tbody>
</table>
<br>
<br>
<br>

<?php
   $count=0;
   $sql  = "SELECT * from receipts";
   $sql .= " WHERE txtype <  $TXTYPE_PAYMENT";
   if (isset($ExportList) && strlen($ExportList) > 0)
     {
       $tarray=explode("&", $ExportList);
       $tc = count($tarray);
       for ($i=0; $i<$tc; $i++)
         {
           $yymm=$tarray[$i];
           if ($i==0)
             $sql .= " AND (yymm='$yymm'";
           else
             $sql .= " OR yymm='$yymm'";
         }
       if ($tc > 0)
          $sql .= ") ";
     }
   $sql .= " order by ts_order";

   // printf("SQL=%s<br>\n",$sql);
   // exit;

   $result = exec_query($sql,$db);
   if ($result)
     {
        // $fname = sprintf("export/export.%02d",$today["month"]);
        $fname = sprintf("export/export.txt");
        $fp=fopen($fname,"w");



//-----------------------------------------------------------------------------------
// 1)  Product type (PushyAds, BatchLeads, 30+ Day Leads, etc)
// 2)  Product division
//      * PushyAds
//         - USA/Canada
//         - USA/Canada/International
//         - UK
//         - Australia/NewZealand
//
// Company (PushyAds)
// Product (PushyAds, etc)
// Payment Type (Credit Card [Visa, MC, AMEX, etc], eCheck, M.O., etc)
// Date
//
//-----------------------------------------------------------------------------------


        $lineout=sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",
                         "DATE",
                         "BUSINESS",
                         "PRODUCT-FAMILY",
                         "PRODUCT-TYPE",
                         "PRODUCT",
                         "TXTYPE",
                         "UNITS-SOLD",
                         "TOTAL-PRICE",
                         "SOURCE",
                         "PAYMENTMETHOD",
                         "RECEIPT",
                         "INVOICE");
        fputs($fp, "$lineout\n");

        while (($myrow = mysql_fetch_array($result)))
          {
            $count++;
            $dtm= $myrow["ts_order"];
            list($date, $time) = split(" ", $dtm);
            $receipt     = $myrow["receiptid"];
            $invoice     = $myrow["invoice"];
            if ($myrow["txtype"] == 1)
              $txtype    = "CR";
            else
            if ($myrow["txtype"] == 2)
              $txtype    = "Void";
            else
            if ($myrow["txtype"] == 3)
              $txtype    = "CB";
            else
            if ($myrow["txtype"] == 4)
              $txtype    = "BD";
            else
              $txtype    = "";

            $paymethod   = $myrow["paymentmethod"];
            if ($paymethod=="American Express")
               $paymethod="Amex";
            else
            if ($paymethod=="MasterCard")
               $paymethod="MC";
            else
            if ($paymethod=="Discover")
               $paymethod="Disc";
            else
            if ($paymethod=="Visa")
               $paymethod="Visa";
            else
            if ($paymethod=="eCheck")
               $paymethod="Check";
            else
               $paymethod="Other";

            $units_sold  = $myrow["spots"];

            $source="PA";

            $business         = $myrow['business'];
            $product_category = $myrow['product_category'];
            $order_type       = $myrow['order_type'];
            $product_name     = $myrow['product_name'];
            $totalprice       = stripchr(number_format((float)$myrow["amount"],2,".",""), ",");

            $lineout=sprintf("\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%s,%s,\"%s\",\"%s\",\"R-%s\",\"I-%s\"",
                                     $date,
                                     $business,
                                     $product_category,
                                     $order_type,
                                     $product_name,
                                     $txtype,
                                     $units_sold,
                                     $totalprice,
                                     $source,
                                     $paymethod,
                                     $receipt,
                                     $invoice);

              //  echo "$lineout<br>\n";
            fputs($fp, "$lineout\n");
          }
        fclose($fp);
        mysql_free_result($result);
      }

   echo "<font face=\"Arial\"><strong><font color=\"#0000CC\">&nbsp;&nbsp;Receipts exported: </font>&nbsp;<font color=\"#CC0000\">$count</font></strong></font><br>&nbsp;<br>\n";

   echo "<table border=\"0\" cellPadding=\"2\" cellSpacing=\"2\" width=\"400\">\n";
   echo "<tr><td>\n";
   echo "<font face=\"Arial\" color=\"#CC0000\"><strong>&nbsp;<u>To download the receipts</u></strong></font>\n";
   echo "</td></tr>\n";
   echo "<tr><td>\n";
   echo "<font face=\"Arial\"><strong><small>&nbsp;&nbsp;1.&nbsp;&nbsp;Right-Mouse on the <font color=\"#0000CC\">DOWNLOAD</font> link below</small></strong></font>\n";
   echo "</td></tr>\n";
   echo "<tr><td>\n";
   echo "<font face=\"Arial\"><strong><small>&nbsp;&nbsp;2.&nbsp;&nbsp;Click 'Save Target As'&nbsp;&nbsp;(or 'Save Link As')</strong></font><br><br>\n";
   echo "</td></tr>\n";
   echo "</table>\n";

   echo "<strong>&nbsp;&nbsp;<a href=\"$fname\">DOWNLOAD</a></strong>\n";

?>



<br>&nbsp;<br>&nbsp;<br>
<form method="POST" action="">
  <input type="button" name="return" value=" Return " onClick="javascript:document.location='/admin/ledger'">
</form>

</body>
</html>
