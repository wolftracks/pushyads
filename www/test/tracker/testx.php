<?php
 $DEBUG=FALSE;

 $INCLUDE_ROOT = TRUE;

 include_once("pushy_common.inc");
 include_once("pushy_commonsql.inc");
 include_once("pushy.inc");
 include_once("pushy_tree.inc");

 set_time_limit(0);
 $db = getPushyDatabaseConnection();

 $dates       = tracker_dates();
 $currentWeek = tracker_current_week();

 tracker_create_item($db,'tjw3','wtracker','x');
 tracker_create_item($db,'tjw3','wtracker','y');
 tracker_create_item($db,'tjw3','wtracker','z');
 exit;

if (FALSE)
  {
     mysql_query("LOCK TABLES wtracker",$db);

     $sql   = "UPDATE wtracker set ";
     $sql  .= " w5_0=0, w5_1=0, w5_2=0, w5_3=0, w5_4=0, w5_5=0, w5_0=0";
     $sql  .= " WHERE widget_key = 'c'  AND  dt_5 = '$weekStart'";
     mysql_query($sql,$db);

     mysql_query("UNLOCK TABLES",$db);


    for ($i=0; $i<1000; $i++)
      {
        $dow = rand(0,6);

        mysql_query("LOCK TABLES wtracker",$db);

        $sql = "UPDATE wtracker set w5_$dow = w5_$dow + 1 WHERE widget_key = 'c'";
        mysql_query($sql,$db);

        mysql_query("UNLOCK TABLES",$db);
      }

    for ($i=0; $i<800; $i++)
      {
        $dow = rand(0,6);

        mysql_query("LOCK TABLES wtracker",$db);

        $sql = "UPDATE wtracker set w4_$dow = w4_$dow + 1 WHERE widget_key = 'c'";
        mysql_query($sql,$db);

        mysql_query("UNLOCK TABLES",$db);
      }

    for ($i=0; $i<600; $i++)
      {
        $dow = rand(0,6);

        mysql_query("LOCK TABLES wtracker",$db);

        $sql = "UPDATE wtracker set w3_$dow = w3_$dow + 1 WHERE widget_key = 'c'";
        mysql_query($sql,$db);

        mysql_query("UNLOCK TABLES",$db);
      }

    for ($i=0; $i<400; $i++)
      {
        $dow = rand(0,6);

        mysql_query("LOCK TABLES wtracker",$db);

        $sql = "UPDATE wtracker set w2_$dow = w2_$dow + 1 WHERE widget_key = 'c'";
        mysql_query($sql,$db);

        mysql_query("UNLOCK TABLES",$db);
      }


    for ($i=0; $i<200; $i++)
      {
        $dow = rand(0,6);

        mysql_query("LOCK TABLES wtracker",$db);

        $sql = "UPDATE wtracker set w1_$dow = w1_$dow + 1 WHERE widget_key = 'c'";
        mysql_query($sql,$db);

        mysql_query("UNLOCK TABLES",$db);
      }
  }
else
  {

     $ucount=0;
     mysql_query("LOCK TABLES wtracker",$db);

     $sql   = "UPDATE wtracker set ";
     $sql  .= " dt_1=dt_2, w1_0=w2_0, w1_1=w2_1, w1_2=w2_2, w1_3=w2_3, w1_4=w2_4, w1_5=w2_5, w1_6=w2_6, ";
     $sql  .= " dt_2=dt_3, w2_0=w3_0, w2_1=w3_1, w2_2=w3_2, w2_3=w3_3, w2_4=w3_4, w2_5=w3_5, w2_6=w3_6, ";
     $sql  .= " dt_3=dt_4, w3_0=w4_0, w3_1=w4_1, w3_2=w4_2, w3_3=w4_3, w3_4=w4_4, w3_5=w4_5, w3_6=w4_6, ";
     $sql  .= " dt_4=dt_5, w4_0=w5_0, w4_1=w5_1, w4_2=w5_2, w4_3=w5_3, w4_4=w5_4, w4_5=w5_5, w4_6=w5_6, ";
     $sql  .= " dt_5='$weekStart', w5_0=0, w5_1=0, w5_2=0, w5_3=0, w5_4=0, w5_5=0, w5_6=0";
     $sql  .= " WHERE dt_5!='$weekStart'";

     $result=mysql_query($sql,$db);
     if ($result)
       $ucount=mysql_affected_rows();

     mysql_query("UNLOCK TABLES",$db);

     printf("Updates: %d\n",$ucount);

  }




function tracker_reset_item($db,$member_id,$table,$tracker_id)
  {
     $currentWeek=tracker_current_week();
     mysql_query("LOCK TABLES $table",$db);
     $sql   = "UPDATE $table set ";
     $sql  .= " w5_0=0, w5_1=0, w5_2=0, w5_3=0, w5_4=0, w5_5=0, w5_0=0";
     $sql  .= " WHERE member_id='$member_id' AND tracker_id = '$tracker_id'  AND  dt_5='$currentWeek'";
     mysql_query($sql,$db);
     mysql_query("UNLOCK TABLES",$db);
  }


function tracker_fold_item($db,$member_id,$table,$tracker_id)
  {
     $ucount=0;
     $currentWeek=tracker_current_week();
     mysql_query("LOCK TABLES $table",$db);
     $sql   = "UPDATE $table set ";
     $sql  .= " dt_1=dt_2, w1_0=w2_0, w1_1=w2_1, w1_2=w2_2, w1_3=w2_3, w1_4=w2_4, w1_5=w2_5, w1_6=w2_6, ";
     $sql  .= " dt_2=dt_3, w2_0=w3_0, w2_1=w3_1, w2_2=w3_2, w2_3=w3_3, w2_4=w3_4, w2_5=w3_5, w2_6=w3_6, ";
     $sql  .= " dt_3=dt_4, w3_0=w4_0, w3_1=w4_1, w3_2=w4_2, w3_3=w4_3, w3_4=w4_4, w3_5=w4_5, w3_6=w4_6, ";
     $sql  .= " dt_4=dt_5, w4_0=w5_0, w4_1=w5_1, w4_2=w5_2, w4_3=w5_3, w4_4=w5_4, w4_5=w5_5, w4_6=w5_6, ";
     $sql  .= " dt_5='$currentWeek', w5_0=0, w5_1=0, w5_2=0, w5_3=0, w5_4=0, w5_5=0, w5_6=0";
     $sql  .= " WHERE member_id='$member_id' AND tracker_id = '$tracker_id' AND dt_5!='$currentWeek'";
     $result=mysql_query($sql,$db);
     if ($result)
       $ucount=mysql_affected_rows();
     mysql_query("UNLOCK TABLES",$db);
     return $ucount;  // Rows affected
  }


function tracker_create_item($db,$member_id,$table,$tracker_id)
  {
     $dates = tracker_dates();
     mysql_query("LOCK TABLES $table",$db);
     $sql   = "INSERT into $table set ";
     $sql  .= " member_id='$member_id',";
     $sql  .= " tracker_id='$tracker_id',";
     $sql  .= " dt_1='".$dates[1]."', w1_0=0, w1_1=0, w1_2=0, w1_3=0, w1_4=0, w1_5=0, w1_6=0,";
     $sql  .= " dt_2='".$dates[2]."', w2_0=0, w2_1=0, w2_2=0, w2_3=0, w2_4=0, w2_5=0, w2_6=0,";
     $sql  .= " dt_3='".$dates[3]."', w3_0=0, w3_1=0, w3_2=0, w3_3=0, w3_4=0, w3_5=0, w3_6=0,";
     $sql  .= " dt_4='".$dates[4]."', w4_0=0, w4_1=0, w4_2=0, w4_3=0, w4_4=0, w4_5=0, w4_6=0,";
     $sql  .= " dt_5='".$dates[5]."', w5_0=0, w5_1=0, w5_2=0, w5_3=0, w5_4=0, w5_5=0, w5_6=0";
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
exit;
?>
