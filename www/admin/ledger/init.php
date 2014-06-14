<?php
if (strlen($PGAGENT) > 0)
  {
    setcookie("PGAGENT",$PGAGENT,time()+94608000,"/","",0);
  }
include ("index.php");
exit;
?>
