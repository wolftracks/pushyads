<?php
ob_start();

// echo "<PRE>\n";
// print_r($_REQUEST);
// print_r($_REQUEST);
// print_r($memberRecord);
// echo "</PRE>\n";

if (isset($_REQUEST["tab"]))
  $tab=$_REQUEST["tab"];
else
  $tab="home";

include("tab_$tab".".php");

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
