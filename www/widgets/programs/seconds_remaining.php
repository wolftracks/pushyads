<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy_jsontools.inc");

// print_r($_REQUEST);
// exit;

$countdown=0;
if (isset($_REQUEST["mid"]))
  {
    $mid=$_REQUEST["mid"];
    $db=getPushyDatabaseConnection();
    if (is_array($memberRecord=getMemberInfo($db,$mid)))
      {
        $countdown=$memberRecord["countdown"];
        if (isset($_REQUEST["countdown"]) && isNumeric($_REQUEST["countdown"]))
          {
            $new_countdown=(int) $_REQUEST["countdown"];
            if ($new_countdown < $countdown)
              {
                $sql = "UPDATE member set countdown='$new_countdown' WHERE member_id='$mid' and countdown>'$new_countdown'";
                mysql_query($sql,$db);
                $countdown=$new_countdown;
              }
          }
      }
  }

$response= new stdClass();
$response->success          = "TRUE";
$response->secondsRemaining = $countdown;
sendJSONResponse(0, $response, null);
?>
