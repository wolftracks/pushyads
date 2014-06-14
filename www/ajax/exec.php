<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_jsontools.inc");

$DEBUG=FALSE;

$sid=$_REQUEST["sid"];
$mid=$_REQUEST["mid"];

$db = getPushyDatabaseConnection();

$SIGNED_IN=FALSE;
$isAdminSession=FALSE;
$firstname  = "Friend";
if (strlen($mid) > 0 && strlen($sid) > 0)
  {
    list($rc, $isAdminSession) = getSession($db, $sid, $mid, FALSE);
    if ($rc==0)
      {
        $memberRecord=getMemberInfo($db,$mid);
        if (is_array($memberRecord) && strcasecmp($mid,$memberRecord["member_id"])==0)
          {
            $firstname  = $memberRecord["firstname"];
            $SIGNED_IN=TRUE;
          }
      }
  }

if (!$SIGNED_IN)
  {
    $sid = "";
    $mid = "";
  }

$surl1="";
$surl2="";
if (strlen($mid) > 0 && strlen($sid) > 0)
  {
    $surl1 = "?"."mid=$mid&sid=$sid";
    $surl2 = "&"."mid=$mid&sid=$sid";
  }


if (isset($_REQUEST["tp"]) && (strlen($_REQUEST["tp"])>0))
  {
    $tp=$_REQUEST["tp"];
    $PAGE=$_REQUEST["tp"].".php";
    if (file_exists($PAGE))
      {
        //--------------- INITIALIZED VARIABLES ------------------
        if ($SIGNED_IN)
          $firstname      = $memberRecord["firstname"];
        else
          $firstname      = "Friend";

        include_once($PAGE);
        exit;
      }
  }

sendJSONResponse(99, NULL, "Internal Error: Page Not Found: $tp");
exit;
?>
