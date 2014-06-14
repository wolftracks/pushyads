<?php

//-------------------------------------------------------------------
// GET VIP Referrals Data from Member Table and set up Date Structure
//-------------------------------------------------------------------
function getVipReferrals($db,$member_id,$yyyy_mm)
  {
    global $PUSHY_LEVEL_VIP;
    global $PUSHY_LEVEL_PRO;
    global $PUSHY_LEVEL_ELITE;

    $targetMonthStartDate  = $yyyy_mm."-01";
    $targetMonthStartArray = dateToArray($targetMonthStartDate);

    $firstDate=$targetMonthStartArray;
    $calData   = calendar($firstDate);
    $dim       = $calData["DaysInMonth"];
    $dow       = $calData["DayOfWeek"];
    $startDate=$firstDate;
    if ($dow != 0)
      $startDate=calStepDays(-$dow,$firstDate);

    $startDate["dow"]=0;
    $firstDate["dow"]=$dow;

    $lastDate = calStepDays($dim-1,$targetMonthStartArray);
    $calData   = calendar($lastDate);
    $dow       = $calData["DayOfWeek"];
    $endDate  = $lastDate;
    if ($dow != 6)
      $endDate=calStepDays(6-$dow,$lastDate);

    $lastDate["dow"]=$dow;
    $endDate ["dow"]=6;

    //------------------------------------------------------------------
    // $startDate    =  First Date in Result       (Start Sunday)
    //   $firstDate  =  First Date in Target Month
    //   $lastDate   =  Last  Date in Target Month
    // $endDate      =  Last  Date in Result       (End   Saturday)
    //------------------------------------------------------------------
    //
    // print_r($startDate);  // First Date in Search Result (Start Sunday)
    // print_r($firstDate);  // First Date in Target Month
    //
    // print_r($lastDate);   // Last  Date in Target Month
    // print_r($endDate);    // Last  Date in Search Result (End   Saturday)
    //
    // printf("a %d\n", dateDifference($startDate, $firstDate) );
    // printf("b %d\n", dateDifference($firstDate, $lastDate)  );
    // printf("c %d\n", dateDifference($lastDate,  $endDate)   );
    // printf("d %d\n", dateDifference($startDate, $endDate)   );
    //-----------------------------------------------------------------


    $dateResults=array();
    $sql  = "SELECT date_registered, COUNT(*) from member ";
    $sql .= " WHERE registered>0";
    $sql .= " AND   system=0";
    $sql .= " AND   user_level='$PUSHY_LEVEL_VIP'";
    $sql .= " AND   member_disabled=0";
    $sql .= " AND   refid='$member_id'";
    $sql .= " AND   date_registered >= '".dateArrayToString($startDate)."'";
    $sql .= " AND   date_registered <= '".dateArrayToString($endDate)."'";
    $sql .= " GROUP BY date_registered";
    $result = mysql_query($sql,$db);

    //printf("SQL: %s\n",$sql);
    //printf("ERR: %s\n",mysql_error());

    if ($result && mysql_num_rows($result)>0)
      {
        while ($myrow=mysql_fetch_array($result,MYSQL_NUM))
          {
            $date  = $myrow[0];
            $count = $myrow[1];
            $dateResults[$date] = $count;
          }
      }

     // printf("\n------------------------------------------\n");
     // print_r($dateResults);
     // printf("\n------------------------------------------\n");

     $weeks = (dateDifference($startDate, $endDate)+1)/7;
     $vipResults=array();

     $weekArray  = array();
     $dateArray  = $startDate;
     $targetDate = $dateArray;
     unset($targetDate["julian"]);
     for ($i=0; $i<$weeks; $i++)
       {
         $weekResults=array();
         for ($j=0; $j<=6; $j++)
           {
             $targetDate["dow"] = $j;
             $current_date    = dateArrayToString($targetDate);
     //      $weekResults[$j] = array( "date"=>$targetDate,   "vip_referrals"=> (isset($dateResults[$current_date])?$dateResults[$current_date]:0) );
             $weekResults[$j] = array( "date"=>$targetDate, "sales" =>
                                          array(
                                                 $PUSHY_LEVEL_VIP      => array("count" =>  (isset($dateResults[$current_date])?$dateResults[$current_date]:0),
                                                                                "amount" => 0),
                                                 $PUSHY_LEVEL_PRO      => array("count" =>  0,
                                                                                "amount" => 0),
                                                 $PUSHY_LEVEL_ELITE    => array("count" =>  0,
                                                                                "amount" => 0),
                                               )
                                     );
             $targetDate=calStepDays(1,$targetDate);
             unset($targetDate["julian"]);
           }
         $weekArray[$i]=$weekResults;
         if (compareDates($targetDate, $endDate) == 0)  break;
       }
     $vipResults["StartPeriod"] = $startDate;
     $vipResults["StartMonth"]  = $firstDate;
     $vipResults["EndMonth"]    = $lastDate;
     $vipResults["EndPeriod"]   = $endDate;
     $vipResults["Weeks"]       = $weekArray;
     return $vipResults;
  }




//-------------------------------------------------------------------
// GET SALES Information From Receipts  (Pro + Elite)
//-------------------------------------------------------------------
function getSalesData($db,$member_id,$yyyy_mm)
  {
    global $PUSHY_LEVEL_VIP;
    global $PUSHY_LEVEL_PRO;
    global $PUSHY_LEVEL_ELITE;

    $targetMonthStartDate  = $yyyy_mm."-01";
    $targetMonthStartArray = dateToArray($targetMonthStartDate);

    $firstDate=$targetMonthStartArray;
    $calData   = calendar($firstDate);
    $dim       = $calData["DaysInMonth"];
    $dow       = $calData["DayOfWeek"];
    $startDate=$firstDate;
    if ($dow != 0)
      $startDate=calStepDays(-$dow,$firstDate);

    $startDate["dow"]=0;
    $firstDate["dow"]=$dow;

    $lastDate = calStepDays($dim-1,$targetMonthStartArray);
    $calData   = calendar($lastDate);
    $dow       = $calData["DayOfWeek"];
    $endDate  = $lastDate;
    if ($dow != 6)
      $endDate=calStepDays(6-$dow,$lastDate);

    $lastDate["dow"]=$dow;
    $endDate ["dow"]=6;

    //------------------------------------------------------------------
    // $startDate    =  First Date in Result       (Start Sunday)
    //   $firstDate  =  First Date in Target Month
    //   $lastDate   =  Last  Date in Target Month
    // $endDate      =  Last  Date in Result       (End   Saturday)
    //------------------------------------------------------------------
    //
    // print_r($startDate);  // First Date in Search Result (Start Sunday)
    // print_r($firstDate);  // First Date in Target Month
    //
    // print_r($lastDate);   // Last  Date in Target Month
    // print_r($endDate);    // Last  Date in Search Result (End   Saturday)
    //
    // printf("a %d\n", dateDifference($startDate, $firstDate) );
    // printf("b %d\n", dateDifference($firstDate, $lastDate)  );
    // printf("c %d\n", dateDifference($lastDate,  $endDate)   );
    // printf("d %d\n", dateDifference($startDate, $endDate)   );
    //-----------------------------------------------------------------


    $salesArray = array();

    $sql  = "SELECT yymmdd, user_level, COUNT(*), SUM(amount) from receipts";
    $sql .= " WHERE txtype=0";
    $sql .= " AND   refid='$member_id'";
    $sql .= " AND   yymmdd >= '".dateArrayToString($startDate)."'";
    $sql .= " AND   yymmdd <= '".dateArrayToString($endDate)."'";
    $sql .= " GROUP BY yymmdd, user_level";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());

    if ($result)
      {
        printf("\n--\n");
        while ($myrow=mysql_fetch_array($result,MYSQL_NUM))
          {
            $date       = $myrow[0];
            $user_level = $myrow[1];
            $count      = $myrow[2];
            $sum        = $myrow[3];

            // printf("(%s)   %d  -  %d  -  %s\n",$date,$user_level,$count,$sum);

            if (isset($salesArray[$date]))
              $salesData=$salesArray[$date];
            else
              $salesData=array();

            $salesData[$user_level]   = array("count"=>$count, "amount"=>$sum);

            $salesArray[$date] = $salesData;
          }
      }

    return($salesArray);
  }
?>
