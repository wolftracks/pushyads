<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_tracker.inc");

$config_file     =  "_pushy_config.js";
$config_file_min =  "_pushy_config-min.js";
$config_file_max =  "_pushy_config-max.js";

$fh = fopen($config_file, "rb");
$contents = fread($fh, filesize($config_file));
fclose($fh);

$fp = fopen($config_file_min, "w");
$len=strlen($contents);
for ($i=0; $i<$len; $i++)
  {
    $ch=substr($contents,$i,1);
    if ($ch == " "  ||
        $ch == "\r" ||
        $ch == "\n")
     { }
    else
    if ($ch == "~")
     fputs($fp," ");
    else
     fputs($fp,$ch);
  }
fputs($fp,"\n");
fclose($fp);



$fh = fopen($config_file, "rb");
$contents = fread($fh, filesize($config_file));
fclose($fh);

$fp = fopen($config_file_max, "w");
$len=strlen($contents);
for ($i=0; $i<$len; $i++)
  {
    $ch=substr($contents,$i,1);
//  if ($ch == " "  ||
//      $ch == "\r" ||
//      $ch == "\n")
//   { }
//  else
    if ($ch == "~")
     fputs($fp," ");
    else
     fputs($fp,$ch);
  }
fputs($fp,"\n");
fclose($fp);
?>
