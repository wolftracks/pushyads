<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db=getPushyDatabaseConnection();

class Registry {
  public $date           = '';
  public $signups        = 0;
  public $confirmations  = 0;
  public $registrations  = 0;

  function __construct($date) {
    $this->date=$date;
  }
}

$dateToday = getDateTodayAsArray();


$thisMonth = $dateToday;
$thisMonth["day"]=1;
$calData = calendar($thisMonth);
$thisMonth_dim = $calData["DaysInMonth"];
$thisMonth_dow = $calData["DayOfWeek"];


$lastMonth = calStepMonths(-1,$dateToday);
$lastMonth["day"]=1;
$calData = calendar($lastMonth);
$lastMonth_dim = $calData["DaysInMonth"];
$lastMonth_dow = $calData["DayOfWeek"];

$last_month = dateArrayToString($lastMonth);
$date_today = dateArrayToString($dateToday);

$registry=array();

$sql  = "SELECT record_created, confirmed, registered from member";
$sql .= " WHERE record_created>='$last_month'";
$sql .= " AND   record_created<='$date_today'";
$sql .= " ORDER BY record_created";
$result = mysql_query($sql,$db);

if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $record_created = $myrow["record_created"];
        $confirmed      = ($myrow["confirmed"]>0);
        $registered     = ($myrow["registered"]>0);

        if (!isset($registry[$record_created]))
            $registry[$record_created] = new Registry($record_created);

        $registry[$record_created]->signups++;
        if ($confirmed)
          $registry[$record_created]->confirmations++;
        if ($registered)
          $registry[$record_created]->registrations++;
      }
  }





$dateArray = $lastMonth;
$dow       = $lastMonth_dow;
$dim       = $lastMonth_dim;

$last_lines=array();
for ($i=0; $i<$dim; $i++)
  {
    $dayName  = substr($day_names[$dow],0,3);
    $date     = dateArrayToString($dateArray);

    $last_lines[]=sdump($date,$dayName,$registry);

    $dateArray = calStepDays(1,$dateArray);
    $dow++;
    if ($dow==7) $dow=0;
  }


$dateArray = $thisMonth;
$dow       = $thisMonth_dow;
//$dim       = $thisMonth_dim;
$dim       = $dateToday["day"];

$this_lines=array();
for ($i=0; $i<$dim; $i++)
  {
    $dayName  = substr($day_names[$dow],0,3);
    $date     = dateArrayToString($dateArray);

    $this_lines[]=sdump($date,$dayName,$registry);

    $dateArray = calStepDays(1,$dateArray);
    $dow++;
    if ($dow==7) $dow=0;
  }


function sdump($date,$dayName,&$registry)
  {

    if (isset($registry[$date]))
      {
        $signups       = $registry[$date]->signups;
        $confirmations = $registry[$date]->confirmations;
        $registrations = $registry[$date]->registrations;
      }
    else
      {
        $signups       = 0;
        $confirmations = 0;
        $registrations = 0;
      }

    return sprintf("%-3s   %-10s   %10d   %10d   %10d   %12d   %12d\n",
                    $dayName,
                    $date,
                    $signups,
                    $confirmations,
                    $registrations,
                    ($signups-$confirmations),
                    ($confirmations-$registrations));

  }






printf("<PRE>\n");

printf("%-3s   %-10s   %10s   %10s   %10s   %12s   %12s\n",
   "Day",
   "Date",
   "Signed Up",
   "Confirmed",
   "Registered",
   "!Confirmed",
   "!Registered");
for ($j=count($this_lines)-1; $j>=0; $j--)
  {
    echo $this_lines[$j];
  }

printf("\n\n---\n\n");

printf("%-3s   %-10s   %10s   %10s   %10s   %12s   %12s\n",
   "Day",
   "Date",
   "Signed Up",
   "Confirmed",
   "Registered",
   "!Confirmed",
   "!Registered");
for ($j=count($last_lines)-1; $j>=0; $j--)
  {
    echo $last_lines[$j];
  }

printf("</PRE>\n");
?>
