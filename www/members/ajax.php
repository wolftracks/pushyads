<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");
include_once("pushy_tree.inc");
include_once("pushy_imagestore.inc");

$DEBUG=FALSE;

$mid=$_REQUEST["mid"];
$sid=$_REQUEST["sid"];

$db = getPushyDatabaseConnection();

$SIGNED_IN=FALSE;
$isAdminSession=FALSE;
$firstname  = "Friend";

// printf("<PRE>%s</PRE>\n",print_r($_REQUEST,TRUE));

if (strlen($mid) > 0 && strlen($sid) > 0)
  {
    list($rc, $isAdminSession) = getSession($db, $sid, $mid, FALSE);
    if ($rc==0)
      {
        $memberRecord=getMemberInfo($db,$mid);
        if (is_array($memberRecord) && strcasecmp($mid,$memberRecord["member_id"])==0)
          {
            $firstname    = $memberRecord["firstname"];
            $affiliate_id = $memberRecord["affiliate_id"];
            $SIGNED_IN=TRUE;
          }
      }
  }

if (TRUE)
  {
    if (!$SIGNED_IN)
      {
        header("HTTP/1.1 401 Unauthorized");
        exit;
      }
  }

$surl1="";
$surl2="";
if (strlen($mid) > 0 && strlen($sid) > 0)
  {
    $surl1 = "?"."mid=$mid&sid=$sid";
    $surl2 = "&"."mid=$mid&sid=$sid";
  }

$PAGE="index.php";
if (isset($_REQUEST["tp"]))
  {
    if (file_exists($_REQUEST["tp"].".php"))
      {
        $PAGE=$_REQUEST["tp"].".php";
      }
    else
    if (file_exists($_REQUEST["tp"]))
      {
        $PAGE=$_REQUEST["tp"];
      }
  }

include($PAGE);
exit;
?>
