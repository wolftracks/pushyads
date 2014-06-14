<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$db = getPushyDatabaseConnection();


function weekTotals($dayTotals)
  {
    $wtotal=0;
    for ($j=0; $j<=6; $j++)
      {
        $wtotal += $dayTotals[$j];
      }
    return $wtotal;
  }

function fiveWeekTotalHitsFromRow($row)
  {
    $hits = 0;
    for ($i=1; $i<=5; $i++)
      {
        for ($j=0; $j<=6; $j++)
          {
            $hits += $row["w".$i."_h".$j];
          }
      }
    return $hits;
  }


function fiveWeekTotalClicksFromRow($row)
  {
    $clicks = 0;
    for ($i=1; $i<=5; $i++)
      {
        for ($j=0; $j<=6; $j++)
          {
            $clicks += $row["w".$i."_c".$j];
          }
      }
    return $clicks;
  }


function getTotals($weeks)
  {
    $clicks = 0;
    for ($i=1; $i<=5; $i++)
      {
        for ($j=0; $j<=6; $j++)
          {
            $clicks += $row["w".$i."_c".$j];
          }
      }
    return $clicks;
  }



function hitsFromRow($row,$week)
  {
    $hits = array(0,0,0,0,0,0,0);
    for ($j=0; $j<=6; $j++)
      {
        $hits[$j] = $row["w".$week."_h".$j];
      }
    return $hits;
  }

function clicksFromRow($row,$week)
  {
    $clicks = array(0,0,0,0,0,0,0);
    for ($j=0; $j<=6; $j++)
      {
        $clicks[$j] = $row["w".$week."_c".$j];
      }
    return $clicks;
  }


function tracker_get_week_counts($db,$member_id,$table,$tracker_id,$userkey="")
  {
     $sql  = "SELECT * from ".$table;
     $sql .= " WHERE member_id='$member_id'";
     $sql .= " AND   tracker_id='$tracker_id'";
     if (strlen($userkey)>0)
        $sql .= " AND   userkey='$user_key'";
     $sql .= " ORDER BY tracker_id";
     $result=mysql_query($sql,$db);
     if (FALSE)
      {
        printf("SQL: %s<br>\n",$sql);
        printf("ERR: %s<br>\n",mysql_error());
      }

     $weeks=array();
     $week_start_dates = tracker_dates();
     if ($result)
       {
         while ($myrow = mysql_fetch_array($result,MYSQL_ASSOC))
           {
             for ($week=1; $week<=5; $week++)
               {
                 $hits   = hitsFromRow($myrow,$week);
                 $clicks = clicksFromRow($myrow,$week);
                 $weeks[$week] = array("start_date"     => $week_start_dates[$week],
                                       "hits"           => $hits,
                                       "clicks"         => $clicks,
                                       "weekHits"       => weekTotals($hits),
                                       "weekClicks"     => weekTotals($clicks),
                                      );
               }
           }
       }
     return $weeks;
  }


function tracker_sum_category($db)
  {
    $categories = array();

    $sql  = "SELECT ";
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_h0), sum(w".$week."_h1), sum(w".$week."_h2), sum(w".$week."_h3), sum(w".$week."_h4), sum(w".$week."_h5), sum(w".$week."_h6), ";
      }
    for ($week=1; $week<=5; $week++)
      {
        $sql .= "  sum(w".$week."_c0), sum(w".$week."_c1), sum(w".$week."_c2), sum(w".$week."_c3), sum(w".$week."_c4), sum(w".$week."_c5), sum(w".$week."_c6), ";
      }
    $sql .= "  userkey ";
    $sql .= "  FROM tracker_pushy_category";
    $sql .= "  GROUP BY userkey";
    $result=mysql_query($sql,$db);

    if (TRUE)
     {
       printf("SQL: %s<br>\n",$sql);
       printf("ERR: %s<br>\n",mysql_error());

     }

    if ($result)
      {
        while ($myrow = mysql_fetch_array($result,MYSQL_NUM))
          {
            $j=0;
            for ($i=0; $i<35; $i++, $j++)
              {
                $hits[$i]   = $myrow[$j];
              }
            for ($i=0; $i<35; $i++, $j++)
              {
                $clicks[$i] = $myrow[$j];
              }

            $category    = $myrow[70];

            $categories[$category] = array("hits"=>$hits, "clicks"=>$clicks);

          }
      }
    return $categories;
  }


$categories = tracker_sum_category($db);
print_r($categories);
exit;

$weekCounts = tracker_get_week_counts($db,'paw1200',TRACKER_PUSHY_ADS,'1235');
print_r($weekCounts);


exit;

?>
