<?php
require("pushy_constants.inc");
require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_tree.inc");
require("pushy_sendmail.inc");

$TRACE=TRUE;

$db=getPushyDatabaseConnection();

set_time_limit(0);

$dateTodayAsArray  = getDateTodayAsArray();
$dateToday         = dateArrayToString($dateTodayAsArray);



$productsPending = array();
$servicePending  = array();

$sql  = "SELECT * FROM product_pending";
$sql .= " ORDER BY ts_submitted DESC";
$result = mysql_query($sql,$db);
if (($result) && ($pendingCount=mysql_num_rows($result))>0)
  {
    while ($myrow  = mysql_fetch_array($result))
      {
        $dtmSubmitted        = formatDateTime($myrow["ts_submitted"]);

        $disposition         = $myrow["disposition"];
        $existing_products_requested  = $myrow["existing_products_requested"];

        $disposition_display = "";

        if ($disposition <= 5)
          {
            if ($disposition == 0)
               $disposition_display = "NEW";
            else
            if ($disposition == 1)
               $disposition_display = "UPDATE";

            if ($existing_products_requested)
               $disposition_display .= " : XPL REQ";
          }
        else
          {
            if ($disposition == 6)
              {
                $existing_products_requested=1;
                $disposition_display = "XPL REQ";
              }
          }

        $product_id          = $myrow["replaces_product_id"];
        $member_id           = $myrow["product_owner"];
        $product_name        = stripslashes($myrow["product_name"]);
        $product_title       = stripslashes($myrow["product_title"]);
        $product_description = stripslashes($myrow["product_description"]);
        $product_categories  = stripslashes($myrow["product_categories"]);
        $product_url         = stripslashes($myrow["product_url"]);

        $memberRecord        = getMemberInfo($db,$member_id);
        $memberName          = stripslashes($memberRecord["firstname"])." ".stripslashes($memberRecord["lastname"]);
        $user_level          = $memberRecord["user_level"];
        $user_level_name     = $UserLevels[$user_level];

        $lineout = sprintf("%s   Member: %-20s   Type: %-10s   ProductName: %s\n",$dtmSubmitted, $memberName, $disposition_display, $product_name);
        $productsPending[] = $lineout;
      }
  }




$sql    = "SELECT * FROM service";
$sql   .= " WHERE ts_response=0";
$sql   .= " ORDER BY ts_request DESC";
$result = mysql_query($sql,$db);
if ($result)
  {
    while ($myrow = mysql_fetch_array($result))
      {
        $dtmSubmitted = formatDateTime($myrow["ts_request"]);
        $memberName   = stripslashes($myrow["firstname"])." ".stripslashes($myrow["lastname"]);
        $subject      = stripslashes($myrow["subject"]);

        $lineout = sprintf("%s   Member: %-20s   Subject: %s\n",$dtmSubmitted, $memberName, $subject);
        $servicePending[] = $lineout;
      }
  }


$msg="";
if (count($productsPending) > 0)
  {
    $msg .= "---------------------------------------------------------------------------------\n";
    $msg .= " ".count($productsPending)." Products Awaiting Approval\n";
    $msg .= "---------------------------------------------------------------------------------\n";
    for ($i=0; $i<count($productsPending); $i++)
      {
        $msg .= $productsPending[$i];
      }

    $msg .= "\n\n\n";
  }

if (count($servicePending) > 0)
  {
    $msg .= "---------------------------------------------------------------------------------\n";
    $msg .= " ".count($servicePending)." Service Messages Unanswered\n";
    $msg .= "---------------------------------------------------------------------------------\n";
    for ($i=0; $i<count($servicePending); $i++)
      {
        $msg .= $servicePending[$i];
      }

    $msg .= "\n\n\n";
  }

if (strlen($msg)>0)
  {
    //echo $msg;
    send_mail_direct("Pushy Admin", EMAIL_TEAM, "Pushy Notice", EMAIL_NOREPLY, "*** ADMINISTRATION ACTION REQUIRED ***", $msg);
  }

exit;
?>
