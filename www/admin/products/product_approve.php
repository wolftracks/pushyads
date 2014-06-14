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

$member_id  = $_REQUEST["member_id"];
if (!is_array($memberRecord=getMemberInfo($db,$member_id)))
  {
    $RESPONSE["result"]=202;
    $RESPONSE["message"]="Product Approval Failed";
  }
else
  {
    $ad_id                = $_REQUEST["ad_id"];
    $product_id           = $_REQUEST["product_id"];
    $product_name         = $_REQUEST["product_name"];
    $product_title        = $_REQUEST["product_title"];
    $product_description  = $_REQUEST["product_description"];
    $disposition          = $_REQUEST["disposition"];
    $existing_products_requested = $_REQUEST["existing_products_requested"];
    $affiliate_signup_url = "";
    if (isset($_REQUEST["affiliate_signup_url"]) && strlen($_REQUEST["affiliate_signup_url"])>7)
        $affiliate_signup_url = $_REQUEST["affiliate_signup_url"];

    $sql  = "UPDATE product set ";
    $sql .= " product_name='".addslashes($product_name)."', ";
    $sql .= " product_title='".addslashes($product_title)."', ";
    // $sql .= " product_categories='$product_categories', ";
    $sql .= " product_description='".addslashes($product_description)."'";
    $sql .= " WHERE product_id='$product_id'";
    $sql .= " AND   product_owner='$member_id'";
    $result = mysql_query($sql,$db);
    if ($result)
      {
        $sql  = "DELETE FROM product_pending ";
        $sql .= " WHERE product_owner='$member_id' AND replaces_product_id='$product_id'";
        $result = mysql_query($sql,$db);
      }
    else
      {
        $RESPONSE["result"]=201;
        $RESPONSE["message"]="Product Approval Failed";
      }

    if ($RESPONSE["result"] == 0)
      {        //--------------------------------------------- Approved -----------------------------------
        $RESPONSE["message"]=" Product Approved ";

        //----------------------------------------------------------- If Product Was NEW AD Submission -----------------
        if ($disposition==0)
          {
            if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
               $messageFile = MESSAGE_DIRECTORY."/ads/own_product_approval_vip.txt";
            else
            if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
               $messageFile = MESSAGE_DIRECTORY."/ads/own_product_approval_pro.txt";
            else
            if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
               $messageFile = MESSAGE_DIRECTORY."/ads/own_product_approval_elite.txt";

            $vars["firstname"]       = getMemberFirstName($memberRecord);
            $vars["product_name"]    = $product_name;
            $vars["submit_date"]     = getDateToday();

            $fullname = getMemberFullName($memberRecord);
            $email    = strtolower($memberRecord["email"]);
            sendMessageFile($messageFile, $fullname, $email, $vars);
          }
        else
        //----------------------------------------------------------- If Product Was Existing Product List Request -----------------
        if ($disposition==6 || ($existing_products_requested))
          {
            if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
               $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_approval_vip.txt";
            else
            if ($memberRecord["user_level"] == $PUSHY_LEVEL_PRO)
               $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_approval_pro.txt";
            else
            if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
               $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_approval_elite.txt";

            $vars["firstname"]       = getMemberFirstName($memberRecord);
            $vars["product_name"]    = $product_name;
            $vars["submit_date"]     = getDateToday();

            $fullname = getMemberFullName($memberRecord);
            $email    = strtolower($memberRecord["email"]);
            sendMessageFile($messageFile, $fullname, $email, $vars);
          }

        if (strlen($affiliate_signup_url) > 0)
          {
             $sql  = "UPDATE ads set affiliate_signup_url='$affiliate_signup_url' ";
             $sql .= " WHERE ad_id='$ad_id' ";
             $sql .= " AND   product_list!='0'";         // Won't Happen IF for any reason tjhis ad is No Longer the Members CURRENT Product List Ad
             $sql .= " AND   member_id='$member_id' ";
             mysql_query($sql,$db);
          }

        if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
          {
             //------------------------------------------------------------------------------------------------
             //
             // If No Pushy Ad selected - make this the Pushy Ad Selection By default
             //
             //------------------------------------------------------------------------------------------------

             $pushyAdSelected=FALSE;

             $sql  = "SELECT COUNT(*) from ads";
             $sql .= " WHERE ads.member_id='$member_id' ";
             $sql .= " AND   ads.pushy_network!=0 ";
             $result = mysql_query($sql,$db);
             if ($result)
               {
                 while ($myrow = mysql_fetch_array($result))
                   {
                     $count = (int) $myrow[0];
                     if ($count > 0)
                       $pushyAdSelected=TRUE;
                   }
               }
             if (!$pushyAdSelected)
               {
                 $sql  = "UPDATE ads set pushy_network=1 ";
                 $sql .= " WHERE ad_id='$ad_id' ";
                 $sql .= " AND   member_id='$member_id' ";
                 mysql_query($sql,$db);
               }
          }
      }
  }


sendJSONResponse($RESPONSE["result"], $RESPONSE["data"], $RESPONSE["message"]);
exit;
?>
