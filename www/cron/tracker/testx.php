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
  public $confirmed_same_day  = 0;
  public $registered          = 0;
  public $registered_same_day = 0;
  public $orders              = 0;

  function __construct($date) {
    $this->date=$date;
  }
}


$dateArray    = getDateTodayAsArray();
$ts_today     = timestampFromDateArray($dateArray);

$targetArray  = calStepDays(-1,$dateArray);
$ts_target    = timestampFromDateArray($targetArray);

$calData=calendar($targetArray);
$dow = $calData["DayOfWeek"];

$targetDate   = dateArrayToString($targetArray);
$targetDateSecondsFrom = $ts_target;   // Inclusive
$targetDateSecondsTo   = $ts_today-1;  // Inclusive

$yymm=substr($targetDate,0,7);

$weekEndDate = $targetArray;
if ($dow != 6)
  $weekEndDate=calStepDays(6-$dow, $weekEndDate);

$week_end=dateArrayToString($weekEndDate);


echo $targetDate."\n\n";
echo $week_end."\n\n";


?>
