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


$registry = new Registry($targetDate);

$week=5;

//-------------------------------------------------------------

$sql = "SELECT sum(w".$week."_h".$dow.") from ".TRACKER_AFFILIATE_PAGE;
$result = mysql_query($sql,$db);

printf("%s\n",$sql);

if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $registry->visits=(int)$myrow[0];
  }

//-------------------------------------------------------------

$sql  = "SELECT COUNT(*) from member";
$sql .= " WHERE record_created='$targetDate'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $registry->signups=(int)$myrow[0];
  }


//-------------------------------------------------------------

$sql  = "SELECT COUNT(*) from receipts";
$sql .= " WHERE txtype=0 AND returned=0 AND yymmdd='$targetDate'";
$sql .= "  AND user_level>0 and order_type='initial'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $registry->orders=(int)$myrow[0];
  }


$sql  = "SELECT record_created, confirmed, registered, date_registered from member";
$sql .= " WHERE record_created='$targetDate'";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $record_created  = $myrow["record_created"];
        $confirmed       = $myrow["confirmed"];
        $date_confirmed  = formatDate($confirmed);
        $registered      = $myrow["registered"];
        $date_registered = $myrow["date_registered"];

        if ($confirmed)
          {
             $registry->confirmed++;
             if ($date_confirmed == $record_created)
                $registry->confirmed_same_day++;
          }
        if ($registered)
          {
             $registry->registered++;
             if ($date_registered == $record_created)
                $registry->registered_same_day++;
          }
      }
  }




$sql = "SELECT date from member_registry WHERE date='$targetDate'";
$result = mysql_query($sql,$db);
if ($result && ($myrow = mysql_fetch_array($result)))
  {
    $sql  = "UPDATE member_registry set";
    $sql .= " visits              = '". $registry->visits              . "',";
    $sql .= " signups             = '". $registry->signups             . "',";
    $sql .= " confirmed           = '". $registry->confirmed           . "',";
    $sql .= " confirmed_same_day  = '". $registry->confirmed_same_day  . "',";
    $sql .= " registered          = '". $registry->registered          . "',";
    $sql .= " registered_same_day = '". $registry->registered_same_day . "',";
    $sql .= " orders              = '". $registry->orders              . "'";
    $sql .= " WHERE date = '$targetDate'";
    $result = mysql_query($sql,$db);
  }
else
  {
    $sql  = "INSERT INTO member_registry set";
    $sql .= " date            = '". $targetDate       . "',";
    $sql .= " visits              = '". $registry->visits              . "',";
    $sql .= " signups             = '". $registry->signups             . "',";
    $sql .= " confirmed           = '". $registry->confirmed           . "',";
    $sql .= " confirmed_same_day  = '". $registry->confirmed_same_day  . "',";
    $sql .= " registered          = '". $registry->registered          . "',";
    $sql .= " registered_same_day = '". $registry->registered_same_day . "',";
    $sql .= " orders              = '". $registry->orders              . "'";
    $result = mysql_query($sql,$db);
  }

printf("SQL: %s\n",$sql);
printf("ERR: %s\n",mysql_error());

print_r($registry);


exit;
?>
