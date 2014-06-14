<?php
include("pushy_common.inc");
include("pushy_commonsql.inc");
include("pushy.inc");

$batch_id              = $_REQUEST["batch_id"];
$submission_file_name  = $_REQUEST["submission_file_name"];

if (strlen($batch_id) >= 10 && strlen($submission_file_name) >= 20)
  {
    $submission_file=LOG_DIRECTORY."/paypal/submissions/".$submission_file_name;
    if (!file_exists($submission_file))
      {
        printf("File Not Found\n");
        exit;
      }
  }
else
  {
    printf("Parameter Error\n");
    exit;
  }


// header("Content-Type: application/force-download\n");
header("Content-Type: application/octetstream\n");
header("Content-Transfer-Encoding: Binary\n");
header("Accept-Ranges: bytes\n");
header("Content-Disposition: attachment; filename=$submission_file_name");
$fh=fopen($submission_file,"r");
while (!feof($fh))
  {
    $buffer = fgets($fh, 4096);
    echo $buffer;
  }
fclose($fh);
?>
