<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");

$prs_db=FALSE;
$prs_included=TRUE;

$PRS_TRACE=FALSE;

function prs_getConnection()
  {
    global $prs_db;
    global $PRS_TRACE;
    if (!$prs_db)
      {
        $prs_db = getPRSDatabaseConnection();
      }
    return($prs_db);
  }


function prs_getDateTimeFromSecondsAsArray($tm)
  {
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    $sc=strftime("%S",$tm);
    $dtmArray["month"]  = (int) $mm;
    $dtmArray["day"]    = (int) $dd;
    $dtmArray["year"]   = (int) $yy;
    $dtmArray["hour"]   = (int) $hr;
    $dtmArray["minute"] = (int) $mn;
    $dtmArray["second"] = (int) $sc;
    return $dtmArray;
  }

function prs_formatDate($tm)
  {
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $date = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);
    return($date);
  }


function prs_formatTime($tm)
  {
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    $time = sprintf("%02d:%02d",$hr,$mn);
    return($time);
  }


function prs_formatDateTime($tm)
  {
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    $dateTime = sprintf("%04d-%02d-%02d %02d:%02d",$yy,$mm,$dd,$hr,$mn);
    return($dateTime);
  }


function prs_getProblemReport($id)
  {
    global $PRS_TRACE;
    $db = prs_getConnection();
    if ($db)
      {
        $sql="SELECT * from prs where id=$id";
        $result=mysql_query($sql,$db);
        if ($PRS_TRACE)
           {
              printf("prs_getProblemReport: SQL: %s<BR>\n",$sql);
              printf("prs_getProblemReport: ERR: %s<BR>\n",mysql_error());
           }
        if ($result && ($myrow=mysql_fetch_array($result)))
          {
            return $myrow;
          }
      }
    return FALSE;
  }


function prs_getProblemReportByKey($key)
  {
    global $PRS_TRACE;
    $db = prs_getConnection();
    if ($db)
      {
        $sql="SELECT * from prs where prs_key=$key";
        $result=mysql_query($sql,$db);
        if ($PRS_TRACE)
           {
              printf("prs_getProblemReport: SQL: %s<BR>\n",$sql);
              printf("prs_getProblemReport: ERR: %s<BR>\n",mysql_error());
           }
        if ($result && ($myrow=mysql_fetch_array($result)))
          {
            return $myrow;
          }
      }
    return FALSE;
  }


function prs_getProblemReportsWhere($where)
  {
    global $PRS_TRACE;
    $db = prs_getConnection();
    if ($db)
      {
        $sql="SELECT * from prs where $where";
        $result=mysql_query($sql,$db);
        if ($PRS_TRACE)
           {
              printf("prs_getProblemReportsWhere: SQL: %s<BR>\n",$sql);
              printf("prs_getProblemReportsWhere: ERR: %s<BR>\n",mysql_error());
           }
        if ($result)
          {
            $j=0;
            while ($myrow=mysql_fetch_array($result))
              {
                $resp[$j]=$myrow;
                if ($PRS_TRACE)
                   {
                      printf("prs_getProblemReportsWhere: %d) %s<BR>\n",$myrow["id"],$myrow["title"]);
                   }
                $j++;
              }
            if ($j==0)
              {
                return FALSE;
              }
            return $resp;
          }
      }
    return FALSE;
  }
?>
