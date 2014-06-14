<?php
require("pushy_constants.inc");
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_tree.inc");
require("pushy_sendmail.inc");

$TRACE=TRUE;

set_time_limit(0);

$dateArray=getDateTodayAsArray();
$dateArray=calStepDays(-1,$dateArray);
$targetDate = dateArrayToString($dateArray);

// --- TEST AND/OR "RE-RUNS" ONLY --- $targetDate='2009-10-10';
// $targetDate='2009-10-18';
// ---

$db=getPushyDatabaseConnection();

//-------------------------------------------------------------------
// GET Sales And Commissions Earned From Membership Sales (Pro + Elite)
//-------------------------------------------------------------------
function _getDailySalesReceipts($db,$member_id,$yymmdd)
  {
    $salesData=array();
    $sql  = "SELECT user_level, COUNT(*), SUM(amount), SUM(ref_commission_amount) from receipts";
    $sql .= " WHERE txtype=0";
    $sql .= " AND   refid='$member_id'";
    $sql .= " AND   yymmdd = '".$yymmdd."'";
    $sql .= " GROUP BY user_level";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());

    if ($result)
      {
        while ($myrow=mysql_fetch_array($result,MYSQL_NUM))
          {
            $user_level         = $myrow[0];
            $count              = $myrow[1];
            $sales_amount       = $myrow[2];
            $commissions_amount = $myrow[3];
            $salesData[$user_level] = array("count"=>$count, "sales_amount"=>$sales_amount, "commissions_amount" => $commissions_amount);
          }
      }
    return($salesData);
  }


//-------------------------------------------------------------------
// GET RETURNS Information From Receipts: Credits, ChargeBacks, Voids
//-------------------------------------------------------------------
function _getDailyReturns($db,$member_id,$yymmdd)
  {
    $sql  = "SELECT COUNT(*), SUM(amount), SUM(ref_commission_amount) from receipts";
    $sql .= " WHERE txtype!=0";
    $sql .= " AND   amount<0";
    $sql .= " AND   refid='$member_id'";
    $sql .= " AND   yymmdd = '".$yymmdd."'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());

    if ($result)
      {
        if ($myrow=mysql_fetch_array($result,MYSQL_NUM))
          {
            $returns_count      = $myrow[0];
            $returns_amount     = $myrow[1];
            $commissions_amount = $myrow[2];

            if ($returns_count > 0 && $returns_amount < 0)
              {
                $returnsData = array("returns_count"=>$returns_count, "returns_amount"=>$returns_amount, "commissions_amount" => $commissions_amount);
                // printf("REFID=%s\n",$member_id);
                // print_r($returnsData);
                // exit;
                return $returnsData;
              }
          }
      }
    return FALSE;
  }



//-------------------------------------------------------------------
// GET and MERGE SALES Information From Receipts and Member Records
//-------------------------------------------------------------------
function getDailySalesData($db,$member_id,$yymmdd)
  {
    //-------------------------------------------------------
    // GET SALES Information From Receipts  (Pro + Elite)
    //-------------------------------------------------------
    $salesResults = _getDailySalesReceipts($db, $member_id, $yymmdd);

    //-------------------------------------------------------
    // GET RETURNS Information From Receipts: Credits, ChargeBacks, Voids
    //-------------------------------------------------------
    $returns      = _getDailyReturns($db, $member_id, $yymmdd);

    $results = array();
    $dataAvailable=FALSE;

      //--- Will Alsways Return an Array with Keys: SALES and RETURNS if One Or The Other Or BOTH Have Data
      //--- The 2 Items Themselves will Always be Arrays with Data appropriately Set to Zero if None existed

    if (count($salesResults)>0)
      {
        $results["SALES"]   = $salesResults;
        $results["RETURNS"] = FALSE;
        $dataAvailable=TRUE;
      }

    if (is_array($returns))
      {
        $results["RETURNS"] = $returns;
        if (!$dataAvailable)
          {
            $results["SALES"] = FALSE;
          }
        $dataAvailable=TRUE;
      }

    if ($dataAvailable)
      {
        return $results;
      }
    return FALSE;
  }




$count_activity=0;
$count_processed=0;




//------------ ALWAYS CLEAN THE SLATE For Target Date First ----------
$sql = "DELETE FROM earnings_daily WHERE yymmdd>='$targetDate'";
mysql_query($sql,$db);
//--------------------------------------------------------------------



//------------ PROCESS Sales Commissions for Target Date -------------
$sql  = "SELECT member_id,user_level from member";
$sql .= " WHERE registered>0";
$sql .= " AND   date_registered!=''";
$sql .= " AND   member_disabled=0";
// $sql .= " AND   system=0";
$sql .= " ORDER BY user_level DESC, member_id";   // !!!== TOP/DOWN ORDER is Critcally Important (Must Process Elites FIRST)
$memberResult = mysql_query($sql,$db);
  //  printf("SQL: %s\n",$sql);
  //  printf("ERR: %s\n",mysql_error());
if (($memberResult) && mysql_num_rows($memberResult) > 0)
  {
    while ($memberRecord=mysql_fetch_array($memberResult,MYSQL_ASSOC))
      {
        $count_processed++;
        $member_id     = $memberRecord["member_id"];
        $user_level    = $memberRecord["user_level"];
        $salesResults  = getDailySalesData($db,$member_id,$targetDate);

        $salesRecords  = FALSE;
        $returns       = FALSE;
        if (is_array($salesResults) && is_array($salesResults["SALES"]))
          {
            $salesRecords = $salesResults["SALES"];
          }
        if (is_array($salesResults) && is_array($salesResults["RETURNS"]))
          {
            $returns      = $salesResults["RETURNS"];
          }

        if (is_array($salesRecords) || is_array($returns))
          {

            $sales_count_pro           = 0;
            $sales_amount_pro          = 0;
            $sales_count_elite         = 0;
            $sales_amount_elite        = 0;
            $earnings_pro              = 0;
            $earnings_elite            = 0;

            $returns_count             = 0;
            $returns_amount            = 0;

            if (is_array($salesRecords))
              {
                if (isset($salesRecords[$PUSHY_LEVEL_PRO]))
                  {
                    $sales_count_pro    = $salesRecords[$PUSHY_LEVEL_PRO]["count"];
                    $sales_amount_pro   = $salesRecords[$PUSHY_LEVEL_PRO]["sales_amount"];
                    $earnings_pro       = $salesRecords[$PUSHY_LEVEL_PRO]["commissions_amount"];
                  }
                if (isset($salesRecords[$PUSHY_LEVEL_ELITE]))
                  {
                    $sales_count_elite  = $salesRecords[$PUSHY_LEVEL_ELITE]["count"];
                    $sales_amount_elite = $salesRecords[$PUSHY_LEVEL_ELITE]["sales_amount"];
                    $earnings_elite     = $salesRecords[$PUSHY_LEVEL_ELITE]["commissions_amount"];
                  }
              }
            if (is_array($returns))
              {
                $returns_count   = $returns["returns_count"];
                $returns_amount  = $returns["commissions_amount"];
              }

            if (($sales_amount_pro + $sales_amount_elite > 0) || $returns_count != 0)
              {
                if ($sales_amount_pro + $sales_amount_elite > 0)
                  {
                     if ($user_level == $PUSHY_LEVEL_VIP)
                       {
                         $total_sales = $sales_amount_pro + $sales_amount_elite;
                         $bonus_amount = round($PUSHY_LEVEL_ELITE_BONUS_COMMISSION_RATE * $total_sales, 2);

                         // Find an Elite and pay the Bonus
                         $upline = tree_findFirstUplineLevel($db,$member_id, "EQ", $PUSHY_LEVEL_ELITE);
                         if (is_array($upline))
                           {
                             if ($TRACE)
                               {
                                 printf("   BONUS - ELITE UPLINE:%s\n",(is_array($upline)?"ID=".$upline["member_id"]." ".$upline["firstname"]." ".$upline["lastname"]."  UL=".$upline["user_level"]:"  - None -"));
                               }
                             $sql  = "UPDATE earnings_daily set bonus_count=bonus_count+1, bonus_amount=bonus_amount+'$bonus_amount' ";
                             $sql .= "  WHERE yymmdd='$targetDate' ";
                             $sql .= "  AND   member_id='".$upline["member_id"]."'";
                             mysql_query($sql,$db);
                             if ($TRACE)
                               {
                                 if (mysql_errno() != 0)
                                   {
                                     printf("   SQL:%s\n",$sql);
                                     printf("   ERR:%s\n",mysql_error());
                                     printf("   ROWS:%d\n\n",mysql_affected_rows());
                                   }
                               }
                             if (mysql_affected_rows()==0)
                               {  // This Elite Had No Direct Sales or Signups Today - Insert Record with Zero for Everything but Bonus Count and Amount
                                  $sql  = "INSERT INTO earnings_daily set";
                                  $sql .= " yymmdd = '$targetDate', ";
                                  $sql .= " member_id = '".$upline["member_id"]."',";
                                  $sql .= " bonus_count = 1, ";
                                  $sql .= " bonus_amount = '".number_format($bonus_amount,2,".","")."'";
                                  mysql_query($sql,$db);
                                   //      printf("      SQL:%s\n",$sql);
                                   //      printf("      ERR:%s\n",mysql_error());
                                  if ($TRACE)
                                    {
                                      if (mysql_errno() != 0)
                                        {
                                           printf("      SQL:%s\n",$sql);
                                           printf("      ERR:%s\n",mysql_error());
                                           printf("      ROWS:%d\n\n",mysql_affected_rows());
                                        }
                                    }
                               }
                           }

                       }
                  }


                //------------- CREATE THE DAILY RECORD HERE ------------------

                $count_activity++;
                if ($TRACE)
                  {
                    printf("(%2d:%2d)   UL=%d  %8s  SC_P=%3d  SA_P=%7s  SC_E=%3d  SA_E=%7s  EP=%7s  EE=%7s  RC=%d  RA=%s\n",
                            $count_processed,
                            $count_activity,
                            $user_level,
                            $member_id,
                            $sales_count_pro,
                            $sales_amount_pro,
                            $sales_count_elite,
                            $sales_amount_elite,
                            $earnings_pro,
                            $earnings_elite,
                            $returns_count,
                            $returns_amount
                          );
                  }

                $sql  = "INSERT INTO earnings_daily set";
                $sql .= " yymmdd = '$targetDate', ";
                $sql .= " member_id      = '$member_id',";
                $sql .= " sales_pro      = '".$sales_count_pro."',";
                $sql .= " earnings_pro   = '".number_format($earnings_pro,2,".","")."',";
                $sql .= " sales_elite    = '".$sales_count_elite."',";
                $sql .= " earnings_elite = '".number_format($earnings_elite,2,".","")."',";
                $sql .= " returns_count  = '".$returns_count."',";
                $sql .= " returns_amount = '".number_format($returns_amount,2,".","")."'";

                mysql_query($sql,$db);

                if (mysql_errno() != 0)
                  {
                    printf("SQL: %s\n",$sql);
                    printf("ERR: %s\n",mysql_error());
                    printf("ERRNO: %s\n",mysql_errno());
                  }
              }
          }
      }
  }
exit;
?>
