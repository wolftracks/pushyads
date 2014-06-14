<?php
require("pushy_constants.inc");
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_tree.inc");
require("pushy_sendmail.inc");

$TRACE=TRUE;

$db=getPushyDatabaseConnection();

set_time_limit(0);

$dateTodayAsArray  = getDateTodayAsArray();
$dateToday         = dateArrayToString($dateTodayAsArray);

$payPeriodAsArray  = getDateTodayAsArray();
if ($payPeriodAsArray["day"] <= 15)        // Treat any Day On or Before the 15th of the month as if we were running
  {                                        // on the 1st  of the month and process the last  Pay Period of the Prior Month
    $payPeriodAsArray["day"]      = 1;
    $endPayPeriodAsArray          = calStepDays(-1,$payPeriodAsArray);
    $endPayPeriod                 = dateArrayToString($endPayPeriodAsArray);
    $startPayPeriodAsArray        = $endPayPeriodAsArray;
    $startPayPeriodAsArray["day"] = 16;
    $startPayPeriod               = dateArrayToString($startPayPeriodAsArray);
  }
else                                       // Treat any Day On or After  the 16th of the month as if we were running
  {                                        // on the 16th of the month and process the first Pay Period of the Current Month
    $payPeriodAsArray["day"]      = 15;
    $endPayPeriodAsArray          = $payPeriodAsArray;
    $endPayPeriod                 = dateArrayToString($endPayPeriodAsArray);
    $startPayPeriodAsArray        = $endPayPeriodAsArray;
    $startPayPeriodAsArray["day"] = 1;
    $startPayPeriod               = dateArrayToString($startPayPeriodAsArray);
  }
printf("StartPayPeriod = %s   EndPayPeriod = %s\n",$startPayPeriod, $endPayPeriod);



//------------ ALWAYS CLEAN THE SLATE For End Payment Period Date ----
$sql = "DELETE FROM earnings_semi_monthly WHERE yymmdd='$endPayPeriod'";
mysql_query($sql,$db);
//--------------------------------------------------------------------

$count_processed=0;

$sql  = "SELECT member_id,user_level from member";
$sql .= " WHERE registered>0";
$sql .= " AND   date_registered!=''";
$sql .= " AND   member_disabled=0";
// $sql .= " AND   system=0";
$sql .= " ORDER BY user_level DESC, member_id";   // !!!== TOP/DOWN ORDER is Critcally Importaant (Must Process Elites FIRST)
$memberResult = mysql_query($sql,$db);
//   printf("SQL: %s\n",$sql);
//   printf("ERR: %s\n",mysql_error());
if (($memberResult) && mysql_num_rows($memberResult) > 0)
  {
    while ($memberRecord=mysql_fetch_array($memberResult,MYSQL_ASSOC))
      {
        $member_id     = $memberRecord["member_id"];
        $user_level    = $memberRecord["user_level"];

        $sql  = "SELECT ";
        $sql .= "  SUM(sales_pro),      ";
        $sql .= "  SUM(earnings_pro),   ";
        $sql .= "  SUM(sales_elite),    ";
        $sql .= "  SUM(earnings_elite), ";
        $sql .= "  SUM(bonus_count),    ";
        $sql .= "  SUM(bonus_amount),   ";
        $sql .= "  SUM(returns_count),  ";
        $sql .= "  SUM(returns_amount)  ";
        $sql .= " FROM earnings_daily   ";
        $sql .= " WHERE yymmdd>='$startPayPeriod' ";
        $sql .= " AND   yymmdd<='$endPayPeriod'   ";
        $sql .= " AND member_id='$member_id'      ";
        $result = mysql_query($sql,$db);

        // printf("SQL: %s\n",$sql);
        // printf("ERR: %s\n",mysql_error());

        if ($result && mysql_num_rows($result)>0 && ($myrow=mysql_fetch_array($result,MYSQL_NUM)))
          {
            $sales_count_pro     = $myrow[0];
            $earnings_pro        = $myrow[1];
            $sales_count_elite   = $myrow[2];
            $earnings_elite      = $myrow[3];
            $bonus_count         = $myrow[4];
            $bonus_amount        = $myrow[5];
            $returns_count       = $myrow[6];
            $returns_amount      = $myrow[7];

            if ($sales_count_pro    > 0   ||
                $earnings_pro       > 0   ||
                $sales_count_elite  > 0   ||
                $earnings_elite     > 0   ||
                $bonus_count        > 0   ||
                $bonus_amount       > 0   ||
                $returns_count      > 0   ||
                $returns_amount     > 0)
              {

                 $net_earnings = $earnings_pro + $earnings_elite + $bonus_amount - $returns_amount;

                 // ---  print_r($myrow);
                 $count_processed++;
                 $sql  = "INSERT INTO earnings_semi_monthly set";
                 $sql .= " yymmdd     = '$endPayPeriod', ";
                 $sql .= " member_id  = '$member_id',";
                 $sql .= " sales_pro      = '".$sales_count_pro."',";
                 $sql .= " earnings_pro   = '".$earnings_pro."',";
                 $sql .= " sales_elite    = '".$sales_count_elite."',";
                 $sql .= " earnings_elite = '".$earnings_elite."',";
                 $sql .= " bonus_count    = '".$bonus_count."',";
                 $sql .= " bonus_amount   = '".$bonus_amount."',";
                 $sql .= " returns_count  = '".$returns_count."',";
                 $sql .= " returns_amount = '".$returns_amount."',";
                 $sql .= " net_earnings   = '".$net_earnings."'";
                 mysql_query($sql,$db);
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

printf("Processing Complete ... Semi-Montly PayStubs Created: %d\n\n",$count_processed);


exit;
?>
