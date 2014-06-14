<?php
include_once("pushy_constants.inc");
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tree.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_imagestore.inc");

$TRACE=TRUE;

$db=getPushyDatabaseConnection();

set_time_limit(0);

$start_time=time();
printf("START: %s   %s\n", $start_time, formatDateTime($start_time,true));

//---------------------------------------------------------------------------------
//
// VIP FREE TRIAL EXPIRATION
//
//-----------------------------------------------------------------------------------
$dateTodayAsArray  = getDateTodayAsArray();
$dateToday         = dateArrayToString($dateTodayAsArray);

//---------------------- Any Upgrades immediately lose any Free Trial Remaining
$sql  = "UPDATE member set free_trial=0";
$sql .= " WHERE user_level='$PUSHY_LEVEL_PRO'";
$sql .= " OR    user_level='$PUSHY_LEVEL_ELITE'";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------


$dateArray=getDateTodayAsArray();
$dateArray=calStepDays(-30,$dateArray);
$date_30 = dateArrayToString($dateArray);


$sql  = "SELECT member_id,firstname,lastname,email from member ";
$sql .= " WHERE user_level='$PUSHY_LEVEL_VIP'";
$sql .= " AND   free_trial=1";
$sql .= " AND   registered>0";
$sql .= " AND   date_registered < '$date_30'";
$result = mysql_query($sql,$db);

printf("SQL: %s<br>\n",$sql);
printf("ERR: %s<br><br>\n",mysql_error());

if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $firstname = stripslashes($myrow["firstname"]);
        $lastname  = stripslashes($myrow["lastname"]);
        $member_id = $myrow["member_id"];
        $fullname  = getMemberFullName($myrow);

        $sql  = "UPDATE member set free_trial=0";
        $sql .= " WHERE member_id='$member_id'";
        mysql_query($sql,$db);

        $sql  = "SELECT product_id,product_name from product ";
        $sql .= " WHERE product_owner='$member_id'";
        $res  = mysql_query($sql,$db);
        if (($res) && (($pcount=mysql_num_rows($res)) > 0))
          {
            while ($productRecord=mysql_fetch_array($res,MYSQL_ASSOC))
              {
                $product_id   = $productRecord["product_id"];
                retireProduct($db,$member_id,$product_id);
              }
          }

        $sql = "DELETE from product_pending where product_owner='$member_id'";
        $res=mysql_query($sql,$db);

        $sql = "DELETE from ads where member_id='$member_id'";
        $res=mysql_query($sql,$db);

        for ($i=0; $i<count($tracker_tables); $i++)
          {
            $table = $tracker_tables[$i];
            $sql = "DELETE from $table where member_id='$member_id'";
            $res=mysql_query($sql,$db);
          }
      }
  }


//-----------------------------------------------------------------


$dateArray=getDateTodayAsArray();
$dateArray=calStepDays(-25,$dateArray);
$date_25 = dateArrayToString($dateArray);

$sql  = "SELECT member_id,firstname,lastname,email from member";
$sql .= " WHERE user_level='$PUSHY_LEVEL_VIP'";
$sql .= " AND   free_trial=1";
$sql .= " AND   registered>0";
$sql .= " AND   date_registered='$date_25'";
$result = mysql_query($sql,$db);

printf("SQL: %s<br>\n",$sql);
printf("ERR: %s<br><br>\n",mysql_error());

if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $firstname = stripslashes($myrow["firstname"]);
        $lastname  = stripslashes($myrow["lastname"]);
        $member_id = $myrow["member_id"];
        $email     = strtolower($myrow["email"]);
        $fullname  = getMemberFullName($myrow);

        $messageFile = MESSAGE_DIRECTORY."/trial/5-day-notice.txt";

        $vars=array();
        $vars["firstname"]  = $firstname;
        $vars["email"]      = $email;
        $vars["mid"]        = $member_id;

        sendMessageFile($messageFile, $fullname, $email, $vars);
      }
  }


//-----------------------------------------------------------------


$dateArray=getDateTodayAsArray();
$dateArray=calStepDays(-27,$dateArray);
$date_27 = dateArrayToString($dateArray);

$sql  = "SELECT member_id,firstname,lastname,email from member";
$sql .= " WHERE user_level='$PUSHY_LEVEL_VIP'";
$sql .= " AND   free_trial=1";
$sql .= " AND   registered>0";
$sql .= " AND   date_registered='$date_27'";
$result = mysql_query($sql,$db);

printf("SQL: %s<br>\n",$sql);
printf("ERR: %s<br><br>\n",mysql_error());

if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $firstname = stripslashes($myrow["firstname"]);
        $lastname  = stripslashes($myrow["lastname"]);
        $member_id = $myrow["member_id"];
        $email     = strtolower($myrow["email"]);
        $fullname  = getMemberFullName($myrow);

        $messageFile = MESSAGE_DIRECTORY."/trial/3-day-notice.txt";

        $vars=array();
        $vars["firstname"]  = $firstname;
        $vars["email"]      = $email;
        $vars["mid"]        = $member_id;

        sendMessageFile($messageFile, $fullname, $email, $vars);
      }
  }


//-----------------------------------------------------------------


$dateArray=getDateTodayAsArray();
$dateArray=calStepDays(-29,$dateArray);
$date_29 = dateArrayToString($dateArray);

$sql  = "SELECT member_id,firstname,lastname,email from member";
$sql .= " WHERE user_level='$PUSHY_LEVEL_VIP'";
$sql .= " AND   free_trial=1";
$sql .= " AND   registered>0";
$sql .= " AND   date_registered='$date_29'";
$result = mysql_query($sql,$db);

printf("SQL: %s<br>\n",$sql);
printf("ERR: %s<br><br>\n",mysql_error());

if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $firstname = stripslashes($myrow["firstname"]);
        $lastname  = stripslashes($myrow["lastname"]);
        $member_id = $myrow["member_id"];
        $email     = strtolower($myrow["email"]);
        $fullname  = getMemberFullName($myrow);

        $messageFile = MESSAGE_DIRECTORY."/trial/1-day-notice.txt";

        $vars=array();
        $vars["firstname"]  = $firstname;
        $vars["email"]      = $email;
        $vars["mid"]        = $member_id;

        sendMessageFile($messageFile, $fullname, $email, $vars);
      }
  }


//-----------------------------------------------------------------






$dateTodayAsArray  = getDateTodayAsArray();
$dateToday         = dateArrayToString($dateTodayAsArray);
$confirmationRange = calStepDays(-8,$dateTodayAsArray);
$confirmation_date = dateArrayToString($confirmationRange);

//---------------------------------------------------------------------------------
//
// DELETE ALL TRACKER_KEYS  RECORDS FROM THE PREVIOUS DAY
//
//-----------------------------------------------------------------------------------
$sql  = "DELETE FROM tracker_keys WHERE date_created < '$dateToday'";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------



//---------------------------------------------------------------------------------
//
//
//
//-----------------------------------------------------------------------------------
$expirationDate    = calStepDays(-8,$dateTodayAsArray);
$expiration        = dateArrayToString($expirationDate);


//--- SEND EMAIL NOTICES TO NEW MEMBERS THAT HAVE NOT YET CONFIRMED -----
$sql  = "SELECT firstname,lastname,member_id,email,record_created from member ";
$sql .= " WHERE confirmed=0";
$sql .= " AND   record_created >= '$confirmation_date'";
$sql .= " AND   record_created <  '$dateToday' ";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------
if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {
    $first=TRUE;
    $inx=0;
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $record_created = $myrow["record_created"];
        $createDate     = dateToArray($record_created);
        $age = dateDifference($createDate,$dateTodayAsArray);

        if (!($age >= 1 && $age <= 8))
          {
            continue;
          }

        $messageFile = MESSAGE_DIRECTORY."/general/not_confirmed_".$age.".txt";

        $member_id      = $myrow["member_id"];
        $email          = strtolower($myrow["email"]);
        $firstname      = stripslashes($myrow["firstname"]);
        $lastname       = stripslashes($myrow["lastname"]);
        $fullname       = getMemberFullName($myrow);

        $vars=array();
        $vars["firstname"]  = $firstname;
        $vars["email"]      = $email;
        $vars["mid"]        = $member_id;

        sendMessageFile($messageFile, $fullname, $email, $vars);

        $inx++;
        printf("[%d] (C-%s)  Confirmation Notice sent to %s \n",$inx,time(),$fullname);
        if ($first)
          {
            $first=FALSE;
            sendMessageFile($messageFile, $fullname, "tim@pushyads.com", $vars);
          }
      }
  }



//--- SEND EMAIL NOTICES TO NEW MEMBERS THAT HAVE CONFIRMED BUT HAVE NOT COMPLETED REGISTRATION -----
$sql  = "SELECT firstname,lastname,member_id,refid,email,record_created,confirmed from member ";
$sql .= " WHERE confirmed>0 && registered=0";
$sql .= " AND   record_created >= '$expiration' ";
$sql .= " AND   record_created <  '$dateToday' ";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------

if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {

    $messageFile = MESSAGE_DIRECTORY."/general/not_registered.txt";
    $first=TRUE;
    $inx=0;
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id      = $myrow["member_id"];
        $refid          = $myrow["refid"];
        $email          = strtolower($myrow["email"]);
        $record_created = $myrow["record_created"];
        $confirmed      = $myrow["confirmed"];

        $firstname      = stripslashes($myrow["firstname"]);
        $lastname       = stripslashes($myrow["lastname"]);

        $fullname       = getMemberFullName($myrow);

        $createDate     = dateToArray($record_created);
        $calData        = calendar($createDate);
        $dow            = $calData["DayOfWeek"];

        $mm = $createDate["month"];
        $dd = $createDate["day"];
        $yy = $createDate["year"];

        $monthName=$month_names[$mm-1];
        $dayName=$day_names[$dow];

        $signupdate     = sprintf("%s, %s %d",$dayName,$monthName, $dd);

        $vars=array();

        $vars["firstname"]         = $firstname;
        $vars["email"]             = $email;
        $vars["signupdate"]        = $signupdate;
        $vars["confirmation_link"] = DOMAIN."/confirmed.php?mid=$member_id";
        $vars["mid"]               = $member_id;

        sendMessageFile($messageFile, $fullname, $email, $vars);

        $inx++;
        printf("[%d] (T-%s)  Registration Notice sent to %s \n",$inx,time(),$fullname);
        if ($first)
          {
            $first=FALSE;
            sendMessageFile($messageFile, $fullname, "tim@pushyads.com", $vars);
          }
      }
  }





//--- DELETE MEMBERS THAT HAVE NOT CONFIRMED AFTER A REASONABLE EXPIRATION PERIOD -----
$sql  = "DELETE from member ";
$sql .= " WHERE confirmed=0 ";
$sql .= " AND   record_created < '$confirmation_date' ";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------


//--- DELETE MEMBERS THAT HAVE CONFIRMED BUT HAVE NOT REGISTERED AFTER A REASONABLE EXPIRATION PERIOD -----
$sql  = "DELETE from member ";
$sql .= " WHERE confirmed>0 && registered=0";
$sql .= " AND   record_created < '$expiration' ";
$result = mysql_query($sql,$db);
//----------------------------------------------------------------------------




$end_time=time();
printf("END: %s   %s    ELAPSED:%d\n", $end_time, formatDateTime($end_time,true), ($end_time-$start_time));

exit;
?>
