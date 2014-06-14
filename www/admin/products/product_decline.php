<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_imagestore.inc");
include_once("pushy_jsontools.inc");

$db = getPushyDatabaseConnection();

$RESPONSE["result"]=0;
$RESPONSE["data"]="";
$RESPONSE["message"]="";

$member_id    = $_REQUEST["member_id"];
$product_id   = $_REQUEST["product_id"];
$ad_id        = $_REQUEST["ad_id"];
$disposition  = $_REQUEST["disposition"];
$reason       = $_REQUEST["reason"];
$existing_products_requested = $_REQUEST["existing_products_requested"];

$memberRecord = getMemberInfo($db,$member_id);

$pendingProduct=FALSE;
$sql  = "SELECT * FROM product_pending ";
$sql .= " WHERE product_owner='$member_id' AND replaces_product_id='$product_id'";
$result = mysql_query($sql,$db);
if (($result) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
  {
    $pendingProduct=$myrow;
  }

$sql  = "DELETE FROM product_pending ";
$sql .= " WHERE product_owner='$member_id' AND replaces_product_id='$product_id'";
$result = mysql_query($sql,$db);

// printf("SQL: %s<br>\n",$sql);
// printf("ERR: %s<br>\n",mysql_error());
// printf("AFFECTED ROWS: %d<br>\n",mysql_affected_rows());

if ($result && mysql_affected_rows()==1)
  {
     //----------------------------------------------------------- If Product Was NEW AD Submission -----------------
     if ($disposition==0)  // NEW PRODUCT - DELETE The Product and AD
       {
         deleteProduct($db, $product_id);

         //---- Now we can delete the ad
         $sql  = "DELETE FROM ads";
         $sql .= " WHERE member_id = '$member_id'";
         $sql .= " AND   ad_id= '$ad_id'";
         $sql .= " AND   product_id= '$product_id'";
         $result = mysql_query($sql,$db);

         //printf("SQL: %s<br>\n",$sql);
         //printf("ERR: %s<br>\n",mysql_error());

         if ($result && mysql_affected_rows()==1)
           {
             // OK
           }
         else
           {
             $RESPONSE["result"]=201;
             $RESPONSE["message"]="Product Decline Failed (2)";
           }
       }
     else
     if ($disposition==6 || ($existing_products_requested))
       {
         $sql  = "UPDATE ads set";
         $sql .= " existing_products_requested=0, ";   // Turn it Back Off for The XPL Requested State for This AD
         $sql .= " product_list=0 ";                   // Turn off the Existing Product List Selection for This AD
         $sql .= " WHERE member_id = '$member_id'";
         $sql .= " AND   ad_id= '$ad_id'";
         $sql .= " AND   product_id= '$product_id'";
         $result = mysql_query($sql,$db);

         // printf("SQL: %s<br>\n",$sql);
         // printf("ERR: %s<br>\n",mysql_error());
       }
  }
else
  {
    $RESPONSE["result"]=201;
    $RESPONSE["message"]="Product Decline Failed (1)";
  }

if ($RESPONSE["result"] == 0)
  {
    $RESPONSE["message"]=" Product Declined ";
  }


//----------------------------------------------------------- If Product Was NEW AD Submission -----------------
if ($disposition==0)
  {
    $product_name=stripslashes($pendingProduct["product_name"]);

    if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
       $messageFile = MESSAGE_DIRECTORY."/ads/own_product_decline_vip.txt";
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
       $messageFile = MESSAGE_DIRECTORY."/ads/own_product_decline_pro.txt";
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
       $messageFile = MESSAGE_DIRECTORY."/ads/own_product_decline_elite.txt";

    $vars["firstname"]       = getMemberFirstName($memberRecord);
    $vars["product_name"]    = $product_name;
    $vars["submit_date"]     = getDateToday();
    $vars["reason"]          = $reason;

    $fullname = getMemberFullName($memberRecord);
    $email    = strtolower($memberRecord["email"]);
    sendMessageFile($messageFile, $fullname, $email, $vars);
  }
else
//----------------------------------------------------------- If Product Was Existing Product List Request -----------------
if ($disposition==6 || ($existing_products_requested))
  {
    $product_name=stripslashes($pendingProduct["product_name"]);

    if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
       $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_decline_vip.txt";
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
       $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_decline_pro.txt";
    else
    if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
       $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_decline_elite.txt";

    $vars["firstname"]       = getMemberFirstName($memberRecord);
    $vars["product_name"]    = $product_name;
    $vars["submit_date"]     = getDateToday();
    $vars["reason"]          = $reason;

    $fullname = getMemberFullName($memberRecord);
    $email    = strtolower($memberRecord["email"]);
    sendMessageFile($messageFile, $fullname, $email, $vars);
  }


sendJSONResponse($RESPONSE["result"], $RESPONSE["data"], $RESPONSE["message"]);
exit;
?>
