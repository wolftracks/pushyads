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


$week=5;
$dateArray    = getDateTodayAsArray();
for ($i=0; $i<10; $i++)
  {
    $ts_today     = timestampFromDateArray($dateArray);

    $targetArray  = calStepDays(-1,$dateArray);
    $ts_target    = timestampFromDateArray($targetArray);

    $calData=calendar($targetArray);
    $dow = $calData["DayOfWeek"];
    if ($i > 0  &&  ($dow == 6))
      {
        $week--;
      }

    $targetDate = dateArrayToString($targetArray);
    $targetDateSecondsFrom = $ts_target;   // Inclusive
    $targetDateSecondsTo   = $ts_today-1;  // Inclusive

    $registry = new Registry($targetDate);

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


    $yymm=substr($targetDate,0,7);

    $weekEndDate = $targetArray;
    if ($dow != 6)
      $weekEndDate=calStepDays(6-$dow, $weekEndDate);

    $week_end=dateArrayToString($weekEndDate);


    $sql = "SELECT * from member_registry WHERE date='$targetDate'";
    $result = mysql_query($sql,$db);
    if ($result && ($myrow = mysql_fetch_array($result)))
      {
        $yymm             = $myrow["yymm"];
        $confirmed_total  = $myrow["confirmed"];
        $registered_total = $myrow["registered"];

        $doUpdate=FALSE;
        if ($registry->confirmed > $confirmed_total)
          {
            $doUpdate=TRUE;

            printf("%s  CONFIRMED:  Old=%d  New=%d\n",$targetDate,$confirmed_total,$registry->confirmed);
          }
        if ($registry->registered > $registered_total)
          {
            $doUpdate=TRUE;

            printf("%s  REGISTERED: Old=%d  New=%d\n",$targetDate,$registered_total,$registry->registered);
          }

        if ($doUpdate)
          {
             $sql  = "UPDATE member_registry set";
             if ($registry->confirmed > $confirmed_total)
               {
                 $sql .= " confirmed = '". $registry->confirmed           . "',";
               }
             if ($registry->registered > $registered_total)
               {
                 $sql .= " registered = '". $registry->registered          . "',";
               }
             $sql .= " yymm = '$yymm' ";
             $sql .= " WHERE date = '$targetDate'";
             $result = mysql_query($sql,$db);

             printf("SQL: %s\n",$sql);
             printf("ERR: %s\n",mysql_error());

          }
      }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------

    $dateArray  = calStepDays(-1,$dateArray);
  }


exit;
?>
