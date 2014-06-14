<?php


function buildWidgetKey($widget_configuration_key, $tracking_id)
  {
    return($widget_configuration_key."@".$tracking_id);
  }

function splitWidgetKey($widget_key)
  {
    $widget_configuration_key = "";
    $tracking_id = "";

    $pos=strpos($widget_key,"@");
    if (is_integer($pos))
      {
        $tracking_id=substr($widget_key,$pos+1);
        $widget_configuration_key=substr($widget_key,0,$pos);
      }
    else
      $widget_configuration_key=$widget_key;

    return( array("WidgetConfigurationKey" => $widget_configuration_key, "TrackingId" => $tracking_id) );
  }

function extractWidgetKey($widget_key)
  {
    $widget_configuration_key = "";
    $tracking_id = "";

    $pos=strpos($widget_key,"@");
    if (is_integer($pos))
      {
        $tracking_id=substr($widget_key,$pos+1);
        $widget_configuration_key=substr($widget_key,0,$pos);
      }
    else
      $widget_configuration_key=$widget_key;

    return $widget_configuration_key;
  }

function extractTrackingId($widget_key)
  {
    $widget_configuration_key = "";
    $tracking_id = "";

    $pos=strpos($widget_key,"@");
    if (is_integer($pos))
      {
        $tracking_id=substr($widget_key,$pos+1);
        $widget_configuration_key=substr($widget_key,0,$pos);
      }
    else
      $widget_configuration_key=$widget_key;

    return $tracking_id;
  }


function tracker_hit($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
    $ms=getmicroseconds();
    $tm=time();
    $today=formatDate($tm);
    $keydata=sprintf("H^%s^%s^%s^%s^%s^%s",$_SERVER["REMOTE_ADDR"],$table,$member_id,$widget_key,$ad_id,$userkey);
    $sql = "INSERT into tracker_keys set created='$ms', date_created='$today', keydata='$keydata'";
    $res=mysql_query($sql,$db);

   // printf("SQL: %s\n",$sql);
   // printf("ERR: %s\n",mysql_error());

    if ($res)
      {
        if (!tracker_item_exists($db,$table,$member_id,$widget_key,$ad_id,$userkey))
           tracker_create_item($db,$table,$member_id,$widget_key,$ad_id,$userkey);
        $dow=strftime("%w",$tm);
        $sql = "UPDATE $table set w5_h$dow = w5_h$dow + 1 WHERE member_id='$member_id' AND widget_key='$widget_key' AND ad_id='$ad_id' AND userkey='$userkey'";
        mysql_query($sql,$db);
        if (mysql_affected_rows()>0)
          {
            $fp=fopen(LOG_DIRECTORY."/traffic/hits-".$today.".txt", "a");
            $lineout=sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n",
                            $today,
                            formatTime($tm),
                            $_SERVER["REMOTE_ADDR"],
                            $table,$member_id,$widget_key,$ad_id,$userkey);
            fputs($fp,$lineout);
            fclose($fp);
          }
      }
  }


function tracker_click($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
    $ms=getmicroseconds();
    $tm=time();
    $today=formatDate($tm);
    $keydata=sprintf("C^%s^%s^%s^%s^%s^%s",$_SERVER["REMOTE_ADDR"],$table,$member_id,$widget_key,$ad_id,$userkey);
    $sql = "INSERT into tracker_keys set created='$ms', date_created='$today', keydata='$keydata'";
    $res=mysql_query($sql,$db);

   // printf("SQL: %s\n",$sql);
   // printf("ERR: %s\n",mysql_error());

    if ($res)
      {
        if (!tracker_item_exists($db,$table,$member_id,$widget_key,$ad_id,$userkey))
           tracker_create_item($db,$table,$member_id,$widget_key,$ad_id,$userkey);
        $dow=strftime("%w",$tm);
        $sql = "UPDATE $table set w5_c$dow = w5_c$dow + 1 WHERE member_id='$member_id' AND widget_key='$widget_key' AND ad_id='$ad_id' AND userkey='$userkey'";
        mysql_query($sql,$db);
        if (mysql_affected_rows()>0)
          {
            $fp=fopen(LOG_DIRECTORY."/traffic/clicks-".$today.".txt", "a");
            $lineout=sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n",
                            $today,
                            formatTime($tm),
                            $_SERVER["REMOTE_ADDR"],
                            $table,$member_id,$widget_key,$ad_id,$userkey);
            fputs($fp,$lineout);
            fclose($fp);
          }
      }
  }

function tracker_reset_item($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
     $currentWeek=tracker_current_week();
     $sql   = "UPDATE $table set ";
     $sql  .= " w5_h0=0, w5_h1=0, w5_h2=0, w5_h3=0, w5_h4=0, w5_h5=0, w5_h6=0, w5_c0=0, w5_c1=0, w5_c2=0, w5_c3=0, w5_c4=0, w5_c5=0, w5_c6=0";
     $sql  .= " WHERE member_id='$member_id' AND widget_key='$widget_key' AND ad_id='$ad_id' AND userkey='$userkey' AND  dt_5='$currentWeek'";
     mysql_query($sql,$db);
  }

function tracker_fold_table($db,$table)
  {
     $ucount=0;
     $currentWeek=tracker_current_week();
     mysql_query("LOCK TABLES $table",$db);
     $sql   = "UPDATE $table set ";
     $sql  .= " dt_1=dt_2, w1_h0=w2_h0, w1_h1=w2_h1, w1_h2=w2_h2, w1_h3=w2_h3, w1_h4=w2_h4, w1_h5=w2_h5, w1_h6=w2_h6, w1_c0=w2_c0, w1_c1=w2_c1, w1_c2=w2_c2, w1_c3=w2_c3, w1_c4=w2_c4, w1_c5=w2_c5, w1_c6=w2_c6,";
     $sql  .= " dt_2=dt_3, w2_h0=w3_h0, w2_h1=w3_h1, w2_h2=w3_h2, w2_h3=w3_h3, w2_h4=w3_h4, w2_h5=w3_h5, w2_h6=w3_h6, w2_c0=w3_c0, w2_c1=w3_c1, w2_c2=w3_c2, w2_c3=w3_c3, w2_c4=w3_c4, w2_c5=w3_c5, w2_c6=w3_c6,";
     $sql  .= " dt_3=dt_4, w3_h0=w4_h0, w3_h1=w4_h1, w3_h2=w4_h2, w3_h3=w4_h3, w3_h4=w4_h4, w3_h5=w4_h5, w3_h6=w4_h6, w3_c0=w4_c0, w3_c1=w4_c1, w3_c2=w4_c2, w3_c3=w4_c3, w3_c4=w4_c4, w3_c5=w4_c5, w3_c6=w4_c6,";
     $sql  .= " dt_4=dt_5, w4_h0=w5_h0, w4_h1=w5_h1, w4_h2=w5_h2, w4_h3=w5_h3, w4_h4=w5_h4, w4_h5=w5_h5, w4_h6=w5_h6, w4_c0=w5_c0, w4_c1=w5_c1, w4_c2=w5_c2, w4_c3=w5_c3, w4_c4=w5_c4, w4_c5=w5_c5, w4_c6=w5_c6,";
     $sql  .= " dt_5='$currentWeek', w5_h0=0, w5_h1=0, w5_h2=0, w5_h3=0, w5_h4=0, w5_h5=0, w5_h6=0, w5_c0=0, w5_c1=0, w5_c2=0, w5_c3=0, w5_c4=0, w5_c5=0, w5_c6=0";
     $sql  .= " WHERE dt_5!='$currentWeek'";
     $result=mysql_query($sql,$db);
     if ($result)
       $ucount=mysql_affected_rows();
     mysql_query("UNLOCK TABLES",$db);
     return $ucount;  // Rows affected
  }

function tracker_item_exists($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
     $sql   = "SELECT COUNT(*) from $table";
     $sql  .= " WHERE member_id='$member_id' AND widget_key='$widget_key' AND ad_id='$ad_id' AND userkey='$userkey'";
     $res=mysql_query($sql,$db);
     if ($result && ($myrow = mysql_fetch_array($result,MYSQL_NUM)) && ($myrow[0]==1))
        return TRUE;
     return FALSE;
  }

function tracker_create_item($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
     $dates = tracker_dates();
     mysql_query("LOCK TABLES $table",$db);
     $sql   = "INSERT into $table set ";
     $sql  .= " member_id='$member_id',";
     $sql  .= " widget_key='$widget_key',";
     $sql  .= " ad_id='$ad_id',";
     $sql  .= " userkey='$userkey',";
     $sql  .= " dt_1='".$dates[1]."', w1_h0=0, w1_h1=0, w1_h2=0, w1_h3=0, w1_h4=0, w1_h5=0, w1_h6=0, w1_c0=0, w1_c1=0, w1_c2=0, w1_c3=0, w1_c4=0, w1_c5=0, w1_c6=0,";
     $sql  .= " dt_2='".$dates[2]."', w2_h0=0, w2_h1=0, w2_h2=0, w2_h3=0, w2_h4=0, w2_h5=0, w2_h6=0, w2_c0=0, w2_c1=0, w2_c2=0, w2_c3=0, w2_c4=0, w2_c5=0, w2_c6=0,";
     $sql  .= " dt_3='".$dates[3]."', w3_h0=0, w3_h1=0, w3_h2=0, w3_h3=0, w3_h4=0, w3_h5=0, w3_h6=0, w3_c0=0, w3_c1=0, w3_c2=0, w3_c3=0, w3_c4=0, w3_c5=0, w3_c6=0,";
     $sql  .= " dt_4='".$dates[4]."', w4_h0=0, w4_h1=0, w4_h2=0, w4_h3=0, w4_h4=0, w4_h5=0, w4_h6=0, w4_c0=0, w4_c1=0, w4_c2=0, w4_c3=0, w4_c4=0, w4_c5=0, w4_c6=0,";
     $sql  .= " dt_5='".$dates[5]."', w5_h0=0, w5_h1=0, w5_h2=0, w5_h3=0, w5_h4=0, w5_h5=0, w5_h6=0, w5_c0=0, w5_c1=0, w5_c2=0, w5_c3=0, w5_c4=0, w5_c5=0, w5_c6=0";
     $result=mysql_query($sql,$db);
     mysql_query("UNLOCK TABLES",$db);
     return ($result?TRUE:FALSE);
  }



 /*****
   a=Wed                      %a - abbreviated weekday name according to the current locale
   A=Wednesday                %A - full weekday name according to the current locale
   b=Apr                      %b - abbreviated month name according to the current locale
   B=April                    %B - full month name according to the current locale
   c=04/19/00 21:28:06        %c - preferred date and time representation for the current locale
   d=19                       %d - day of the month as a decimal number (range 00 to 31)
   H=21                       %H - hour as a decimal number using a 24-hour clock (range 00 to 23)
   I=09                       %I - hour as a decimal number using a 12-hour clock (range 01 to 12)
   j=110                      %j - day of the year as a decimal number (range 001 to 366)
   m=04                       %m - month as a decimal number (range 1 to 12)
   M=28                       %M - minute as a decimal number
   p=PM                       %p - either `am' or `pm' according to the given time value, or the corresponding strings for the current locale
   S=06                       %S - second as a decimal number
   U=16                       %U - week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week
   W=16                       %W - week number of the current year as a decimal number, starting with the first Monday as the first day of the first week
   w=3                        %w - day of the week as a decimal, Sunday being 0
   x=04/19/00                 %x - preferred date representation for the current locale without the time
   X=21:28:06                 %X - preferred time representation for the current locale without the date
   y=00                       %y - year as a decimal number without a century (range 00 to 99)
   Y=2000                     %Y - year as a decimal number including the century
   Z=Eastern Daylight Time    %Z - time zone or name or abbreviation
 *****/

function tracker_dates()
  {
    $dates=array();
    $dates[0]='';

    $tm=time();
    $dow=strftime("%w",$tm);
    if ($dow != 0)
       $tm -= (86400 * $dow);

    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $weekStart = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);

    $dates[5]=$weekStart;
    for ($i=4; $i>=1; $i--)
      {
         $tm -= (86400 * 7);
         $mm=strftime("%m",$tm);
         $dd=strftime("%d",$tm);
         $yy=strftime("%Y",$tm);
         $dates[$i] = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);
      }
     sort($dates);
     return $dates;
  }


function tracker_weeks()
  {
    $weeks=array();
    $dates=tracker_dates();
    for ($i=1; $i<=5; $i++)
      {
        $start_date = $dates[$i];
        $end_date   = dateArrayToString(calStepDays(6,dateToArray($start_date)));
        $weeks[$start_date]=$end_date;
      }
    return($weeks);
  }


function tracker_current_week()
  {
    $tm=time();
    $dow=strftime("%w",$tm);
    if ($dow != 0)
       $tm -= (86400 * $dow);

    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $weekStart = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);
    return $weekStart;
  }



//---- Queries ------------------------------------------------------------------

function tracker_last_hit($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
    $keydata=sprintf("H^%s^%s^%s^%s^%s^%s",$_SERVER["REMOTE_ADDR"],$table,$member_id,$widget_key,$ad_id,$userkey);
    $sql = "SELECT created from tracker_keys WHERE keydata='$keydata'";
    $res=mysql_query($sql,$db);

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());

    if ($res && ($myrow=mysql_fetch_array($res)))
      {
        $lasthit = (int) ($myrow["created"] / 1000000);
        return $lasthit;  // In SECONDS
      }
    return 0;
  }

function tracker_last_click($db,$table,$member_id,$widget_key,$ad_id,$userkey="")
  {
    $keydata=sprintf("C^%s^%s^%s^%s^%s^%s",$_SERVER["REMOTE_ADDR"],$table,$member_id,$widget_key,$ad_id,$userkey);
    $sql = "SELECT created from tracker_keys WHERE keydata='$keydata'";
    $res=mysql_query($sql,$db);

    // printf("SQL: %s\n",$sql);
    // printf("ERR: %s\n",mysql_error());

    if ($res && ($myrow=mysql_fetch_array($res)))
      {
        $lastclick = (int) ($myrow["created"] / 1000000);
        return $lastclick;  // In SECONDS
      }
    return 0;
  }


function tracker_test_set_hit($db, $seconds, $table,$member_id,$widget_key,$ad_id,$userkey="")
  {
    $ts = $seconds * 1000000;
    $created_new  = getmicroseconds();
    $created_test = $created_new - $ts;

    $keydata=sprintf("H^%s^%s^%s^%s^%s^%s",$_SERVER["REMOTE_ADDR"],$table,$member_id,$widget_key,$ad_id,$userkey);
    $sql = "SELECT created from tracker_keys WHERE keydata='$keydata'";
    $res=mysql_query($sql,$db);
    if ($res && ($myrow=mysql_fetch_array($res)))
      {
        $sql = "UPDATE tracker_keys set created='$created_new' WHERE keydata='$keydata' AND created<$created_test";
        $res=mysql_query($sql,$db);
        if ($res && (mysql_affected_rows()==1))
          {
            return true;  // Exists - Time Expired
          }
        return false; // Exists - Time Not Expired
      }
    return true; // Not Exists
  }


function tracker_test_set_click($db, $seconds, $table,$member_id,$widget_key,$ad_id,$userkey="")
  {
    $ts = $seconds * 1000000;
    $created_new  = getmicroseconds();
    $created_test = $created_new - $ts;

    $keydata=sprintf("C^%s^%s^%s^%s^%s^%s",$_SERVER["REMOTE_ADDR"],$table,$member_id,$widget_key,$ad_id,$userkey);
    $sql = "SELECT created from tracker_keys WHERE keydata='$keydata'";
    $res=mysql_query($sql,$db);
    if ($res && ($myrow=mysql_fetch_array($res)))
      {
        $sql = "UPDATE tracker_keys set created='$created_new' WHERE keydata='$keydata' AND created<$created_test";
        $res=mysql_query($sql,$db);
        if ($res && (mysql_affected_rows()==1))
          {
            return true;  // Exists - Time Expired
          }
        return false; // Exists - Time Not Expired
      }
    return true; // Not Exists
  }



function getSystemWidgetKey($db)
  {
    $sql  = "SELECT widget_key from widget";
    $sql .= " WHERE member_id   = 'pushy'";
    $sql .= " AND   widget_name = 'PushyAds'";
    $sql .= " AND   domain      = 'pushyads.com'";
    $result=mysql_query($sql,$db);

    //printf("SQL:%s<br>\n",$sql);
    //printf("ERR:%s<br>\n",mysql_error());

    $system_widget_key="";
    if (($result) && ($myrow = mysql_fetch_array($result,MYSQL_NUM)))
      {
        $system_widget_key = $myrow[0];
      }

    return $system_widget_key;
  }
?>
