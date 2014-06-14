<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db=getPushyDatabaseConnection();
?>

<html>
<head>
</head>
<body>

<br>

<PRE>

UNIQUE VISITOR DETAILS  -  Last 7 Days
-----------------------------------------------------------------

<?php
function show_hits($db,$date,$week,$dow)
  {
    global $day_names;
    $sql = "SELECT member_id, w".$week."_h".$dow." from tracker_affiliate_page";

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());

    $result=mysql_query($sql,$db);
    if ($result)
      {
        printf("\n\n---------  %s, %s ---------\n",$day_names[$dow],$date);
        $tot=0;
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $member_id = $myrow[0];
            $visits    = (int) $myrow[1];
            $tot+=$visits;
            if ($visits > 0)
              {
                $memberRecord = getMemberInfo($db,$member_id);
                $firstname = stripslashes($memberRecord["firstname"]);
                $lastname  = stripslashes($memberRecord["lastname"]);
                $fullname  = $firstname." ".$lastname;
                printf("%-10s  %-25s   %6d\n",$member_id,$fullname,$visits);
              }
          }
         printf("%-10s  %-25s   %6d\n","","----- TOTAL -----",$tot);
      }
  }

$dateArray=getDateTodayAsArray();
$calData=calendar($dateArray);
$dow = $calData["DayOfWeek"];

$week=5;
for ($i=0; $i<7; $i++)
  {
    $dt=dateArrayToString($dateArray);
    show_hits($db,$dt,$week,$dow);

    $dateArray=calStepDays(-1,$dateArray);
    $dow--;
    if ($dow < 0)
      {
        $dow=6;
        $week--;
      }
  }
?>


</PRE>
</body>
</html>
