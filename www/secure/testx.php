<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");


$anniversaryTarget = dateToArray("2009-12-31");
printf("Anniversary Target: %s\n",dateArrayToString($anniversaryTarget));
printf("Next PaymentDue:    %s\n\n",dateArrayToString(getNextPaymentDue($anniversaryTarget)));
for ($i=0; $i<=12; $i++)
  {
    $anniversaryTarget = getNextAnniversaryTarget($anniversaryTarget);
    printf("Anniversary Target: %s\n",dateArrayToString($anniversaryTarget));
    printf("Next PaymentDue:    %s\n\n",dateArrayToString(getNextPaymentDue($anniversaryTarget)));
  }


function getNextPaymentDue($anniversaryTarget)
 {
   $nextPaymentDue = $anniversaryTarget;
   $calData = calendar($nextPaymentDue);
   $dim     = $calData["DaysInMonth"];

   if ($nextPaymentDue["day"] > $dim)
     {
       $nextPaymentDue = calStepMonths(1,$nextPaymentDue);
       $nextPaymentDue["day"]=1;
     }

   return($nextPaymentDue);
 }

function getNextAnniversaryTarget($anniversaryTarget)
 {
              // Anniversary Dates Do NOT have to be Valid Dates  e.g. 2/31/2009
              // They represent the Target from which the NextPaymentDue Date is then calculated.
              // The Next Payment Due Date will be an Actual Valid Calendar Date based on the target Anniversary.
   return calStepMonths(1,$anniversaryTarget);
 }

?>
