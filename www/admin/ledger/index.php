<?php
include_once("pushy_constants.inc");
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");

include_once("pushy_payflow.inc");

include_once("../users.php");

if (strlen($PGAGENT)==0)
  {
    include("signin.php");
    exit;
  }

$db = getPushyDatabaseConnection();

if ($op == "MAIN")
  {
    include ("main.php");
    exit;
  }

if ($op == "FIND")
  {
    include ("find.php");
    exit;
  }

if ($op == "RECEIPT")
  {
    $op = "OPEN";
    include ("receipt.php");
    exit;
  }

if ($op == "EXPORT")
  {
    include ("export.php");
    exit;
  }

if ($op == "DAILYTOTALS")
  {
    include ("dailytotals.php");
    exit;
  }

if ($op == "TRANSACTIONS")
  {
    include ("transactions.php");
    exit;
  }

if ($op == "CREDIT")
  {
    include ("credit.php");
    exit;
  }

if ($op == "VOID")
  {
    include ("void.php");
    exit;
  }

if ($op == "UPDATE")
  {
    include ("receipt.php");
    exit;
  }


if ($op == "ISSUE-CREDIT")
  {
    $command="CREDIT";
    if ($nextop == "TRANSACTIONS")
      {
        include ("transactions.php");
      }
    exit;
  }

if ($op == "ISSUE-VOID")
  {
    $command="VOID";
    if ($nextop == "TRANSACTIONS")
      {
        include ("transactions.php");
      }
    exit;
  }

include ("main.php");
exit;
?>
