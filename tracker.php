<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$dateArray=getDateTodayAsArray();
$calData=calendar($dateArray);
$dow=$calData["DayOfWeek"];


$db = getPushyDatabaseConnection();


// db_reset_all($db);
// db_count_all($db);
// db_list_all($db);


function getIp()
 {
   return rand(110,254).".".rand(110,254).".".rand(110,254).".".rand(110,254);
 }

//================================================================

$affiliates=array();

        $_SERVER["REMOTE_ADDR"]="213.56.204.65";

$sql  = "SELECT member_id,firstname,lastname,affiliate_id from member WHERE registered>0 AND system=0 AND user_level>0";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id    = $myrow["member_id"];
        $fullname     = stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"]);

        $affiliate_id = $myrow["affiliate_id"];
        $affiliates[] = $affiliate_id;

        printf("(%d) %-9s   %-9s  %s\n",count($affiliates),$affiliate_id,$member_id,$fullname);


        tracker_hit($db,TRACKER_AFFILIATE_PAGE,$member_id,'','','');

     }
  }
printf("--------------------------------\n");
db_list_all($db);
exit;





//================================================================



db_list_all($db);



function db_reset_all($db)
  {
    db_reset($db,"tracker_keys");
    db_reset($db,"widget_tracker");

    db_reset($db,"tracker_ad_category");
    db_reset($db,"tracker_affiliate_page");
    db_reset($db,"tracker_elite_bar");
    db_reset($db,"tracker_pushy_ads");
    db_reset($db,"tracker_pushy_ads_mysites");
    db_reset($db,"tracker_pushy_ads_referrals");
    db_reset($db,"tracker_pushy_widget");
    db_reset($db,"tracker_widget_category");
  }


function db_count_all($db)
  {
    db_count($db,"tracker_keys");
    db_count($db,"widget_tracker");

    db_count($db,"tracker_ad_category");
    db_count($db,"tracker_affiliate_page");
    db_count($db,"tracker_elite_bar");
    db_count($db,"tracker_pushy_ads");
    db_count($db,"tracker_pushy_ads_mysites");
    db_count($db,"tracker_pushy_ads_referrals");
    db_count($db,"tracker_pushy_widget");
    db_count($db,"tracker_widget_category");
  }


function db_list_all($db)
  {
    db_list($db,"tracker_keys");
//  db_list($db,"widget_tracker");

//  db_list($db,"tracker_ad_category");
    db_list($db,"tracker_affiliate_page");
//  db_list($db,"tracker_elite_bar");
//  db_list($db,"tracker_pushy_ads");
//  db_list($db,"tracker_pushy_ads_mysites");
//  db_list($db,"tracker_pushy_ads_referrals");
    db_list($db,"tracker_pushy_widget");
    db_list($db,"tracker_widget_category");
  }



function db_reset($db,$table)
  {
    $sql="DELETE FROM $table";
    mysql_query($sql,$db);
    printf(" ... Table Deleted    - %s\n",$table);
  }


function db_count($db,$table)
  {
    $count=0;
    $sql="SELECT COUNT(*) FROM $table";
    $result=mysql_query($sql,$db);
    if ($result)
      {
        $myrow=mysql_fetch_array($result);
        $count=$myrow[0];
      }
    printf(" ... Table Count (%d)  - %s\n",$count,$table);
  }



function db_list($db,$table)
  {
    GLOBAL $dow;
    $sql="SELECT * FROM $table";
    $result=mysql_query($sql,$db);
    if ($result)
      {
        printf(" ... Table LIST  (%d)  - %s\n",mysql_num_rows($result),$table);
        while ($myrow=mysql_fetch_array($result))
          {
            if ($table == "tracker_keys")
              {
                printf("     ==>  %s\n",
                                  $myrow["keydata"]);
              }
            else
              {
                printf("     ==>  %-16s   %-32s   %-8s   %-12s   [H: %d]  [C: %d]\n",
                                  $myrow["member_id"],
                                  $myrow["widget_key"],
                                  $myrow["ad_id"],
                                  $myrow["userkey"],
                                  $myrow["w5_h$dow"],
                                  $myrow["w5_c$dow"]);
              }
          }
      }
  }
?>
