<?php
$sid=$_REQUEST["sid"];
$mid=$_REQUEST["mid"];

if (isset($_REQUEST["tp"]))
 $tp=$_REQUEST["tp"];

$PAGE=$tp.".php";

if (file_exists($PAGE))
  {
    $_REQUEST["p"]=1;
    include($PAGE);
    exit;
  }

printf("Page Not Found: %s<br>\n",$tp);

exit;
?>
