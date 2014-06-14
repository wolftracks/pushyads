<?php


function dollar_format($amount)
 {
   $amount = round((float) $amount, 2);
   if ($amount < 0)
     return "-$".number_format(-$amount,2,".",",");
   return "$".number_format($amount,2,".",",");
 }

$a = "123.4567";
echo dollar_format($a)."\n";
$a = "123.4545";
echo dollar_format($a)."\n";
$a = "123.";
echo dollar_format($a)."\n";
$a = "123.0";
echo dollar_format($a)."\n";
$a = "123";
echo dollar_format($a)."\n";
$a = "123.515";
echo dollar_format($a)."\n";
$a = "";
echo dollar_format($a)."\n";
$a = "0";
echo dollar_format($a)."\n";

?>
