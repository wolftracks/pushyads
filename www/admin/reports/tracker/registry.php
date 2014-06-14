<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db=getPushyDatabaseConnection();


class Registry {
  public $date                = '';
  public $visits              = 0;
  public $signups             = 0;
  public $confirmed           = 0;
  public $registered          = 0;
  public $confirmed_same_day  = 0;
  public $registered_same_day = 0;
  public $orders              = 0;

  function __construct($date) {
    $this->date=$date;
  }
}

$weeks  = array();
$months = array();

printf("<PRE>\n");

printf("\n\n---------------------------------- DAILY ---------------------------------------\n\n");
$sql  = "SELECT * from member_registry order by date DESC";
$result = mysql_query($sql,$db);
if ($result)
  {
    printf("%-3s   %-10s   %10s   %10s   %12s   %12s   %12s   %12s   %12s   %12s   %10s\n",
                "Day",
                "Date",
                "Visits",
                "Signups",
                "CNF Same Day",
                "CNF Later",
                "CNF Total",
                "REG Same Day",
                "REG Later",
                "REG Total",
                "Orders");

    while ($myrow = mysql_fetch_array($result))
      {
        $yymm                =  $myrow["yymm"];
        $week_end            =  $myrow["week_end"];
        $date                =  $myrow["date"];
        $visits              =  $myrow["visits"];
        $signups             =  $myrow["signups"];
        $confirmed           =  $myrow["confirmed"];
        $registered          =  $myrow["registered"];
        $confirmed_same_day  =  $myrow["confirmed_same_day"];
        $registered_same_day =  $myrow["registered_same_day"];
        $orders              =  $myrow["orders"];

        $confirmed_later     =  $confirmed  - $confirmed_same_day;
        $registered_later    =  $registered - $registered_same_day;

        $dateArray= dateToArray($date);
        $calData  = calendar($dateArray);
        $day      = $dateArray["day"];
        $dow      = $calData["DayOfWeek"];
        $dim      = $calData["DaysInMonth"];
        $dayName  = getAbbrevDayName($dow);
        printf("%-3s   %-10s   %10s   %10s   %12s   %12s   %12s   %12s   %12s   %12s   %10s\n",
                    $dayName,
                    $date,
                    $visits,
                    $signups,
                    $confirmed_same_day,
                    $confirmed_later,
                    $confirmed,
                    $registered_same_day,
                    $registered_later,
                    $registered,
                    $orders);

        if (!isset($weeks[$week_end]))
          $weeks[$week_end]=new Registry($date);

        $weeks[$week_end]->visits              += $visits;
        $weeks[$week_end]->signups             += $signups;
        $weeks[$week_end]->confirmed           += $confirmed;
        $weeks[$week_end]->confirmed_same_day  += $confirmed_same_day;
        $weeks[$week_end]->registered          += $registered;
        $weeks[$week_end]->registered_same_day += $registered_same_day;
        $weeks[$week_end]->orders              += $orders;

        if (!isset($months[$yymm]))
          $months[$yymm]=new Registry($date);

        $months[$yymm]->visits                 += $visits;
        $months[$yymm]->signups                += $signups;
        $months[$yymm]->confirmed              += $confirmed;
        $months[$yymm]->confirmed_same_day     += $confirmed_same_day;
        $months[$yymm]->registered             += $registered;
        $months[$yymm]->registered_same_day    += $registered_same_day;
        $months[$yymm]->orders                 += $orders;
      }
  }


// print_r($weeks);
// print_r($months);


printf("\n\n---------------------------------- WEEKLY --------------------------------------\n\n");
printf("%-10s   %10s   %10s   %12s   %12s   %12s   %12s   %12s   %12s   %10s\n",
            "Week End",
            "Visits",
            "Signups",
            "CNF Same Day",
            "CNF Later",
            "CNF Total",
            "REG Same Day",
            "REG Later",
            "REG Total",
            "Orders");
krsort($weeks);
foreach($weeks AS $week_end=>$registry)
  {
    $confirmed_later  = $registry->confirmed  - $registry->confirmed_same_day;
    $registered_later = $registry->registered - $registry->registered_same_day;
    printf("%-10s   %10s   %10s   %12s   %12s   %12s   %12s   %12s   %12s   %10s\n",
                $week_end,
                $registry->visits,
                $registry->signups,
                $registry->confirmed_same_day,
                $confirmed_later,
                $registry->confirmed,
                $registry->registered_same_day,
                $registered_later,
                $registry->registered,
                $registry->orders);
  }



printf("\n\n---------------------------------- MONTHLY -------------------------------------\n\n");
printf("%-10s   %10s   %10s   %12s   %12s   %12s   %12s   %12s   %12s   %10s\n",
            "Month",
            "Visits",
            "Signups",
            "CNF Same Day",
            "CNF Later",
            "CNF Total",
            "REG Same Day",
            "REG Later",
            "REG Total",
            "Orders");
krsort($months);
foreach($months AS $yymm=>$registry)
  {
    $confirmed_later  = $registry->confirmed  - $registry->confirmed_same_day;
    $registered_later = $registry->registered - $registry->registered_same_day;
    printf("%-10s   %10s   %10s   %12s   %12s   %12s   %12s   %12s   %12s   %10s\n",
                $yymm,
                $registry->visits,
                $registry->signups,
                $registry->confirmed_same_day,
                $confirmed_later,
                $registry->confirmed,
                $registry->registered_same_day,
                $registered_later,
                $registry->registered,
                $registry->orders);
  }

printf("</PRE>\n");
?>
