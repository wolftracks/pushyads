<?php
ob_start();

// echo "<PRE>\n";
// print_r($_REQUEST);
// print_r($_REQUEST);
// print_r($memberRecord);
// echo "</PRE>\n";

if (isset($_REQUEST["report"]))
  $report=$_REQUEST["report"];
else
  $report="referrals";

include("reports_date_time".".inc");
include("reports_$report".".php");

$contents = ob_get_contents();
ob_end_clean();
if (is_integer(strpos($_SERVER["HTTP_ACCEPT"],"json")))
  {
    if (!isset($response))
      {
        $response= new stdClass();
        $response->success      = "TRUE";
      }
    $response->html  = $contents;
    sendJSONResponse(0, $response, null);
  }
else
  {
    echo $contents;
  }
exit;
?>
