<?php
$RESPONSE["result"]=0;
$RESPONSE["message"]="";

$pushyAdSelected=TRUE;
if ($memberRecord["user_level"] == $PUSHY_LEVEL_ELITE)
  {
     //------------------------------------------------------------------------------------------------
     //
     // If No Pushy Ad selected - make this the Pushy Ad Selection By default
     //
     //------------------------------------------------------------------------------------------------
     $count = 0;
     $sql  = "SELECT COUNT(*) from ads";
     $sql .= " WHERE ads.member_id='$mid' ";
     $sql .= " AND   ads.pushy_network!=0 ";
     $result = mysql_query($sql,$db);
     if ($result)
       {
         while ($myrow = mysql_fetch_array($result))
           {
             $count = (int) $myrow[0];
           }
       }
     if ($count == 0)
       $pushyAdSelected=FALSE;
  }



$product_id  = $_REQUEST["product_id"];
$product_url = $_REQUEST["product_url"];

$member_id  = $memberRecord["member_id"];
$user_level = $memberRecord["user_level"];

$sql  = "INSERT into ads set ";
$sql .= " member_id='$member_id', ";
$sql .= " product_id='$product_id', ";
$sql .= " product_url='$product_url', ";
$sql .= " affiliate_signup_url='', ";
$sql .= " reseller_listing=1, ";
if (!$pushyAdSelected)
  $sql .= " pushy_network=1, ";
else
  $sql .= " pushy_network=0, ";
$sql .= " elite_bar=0, ";
$sql .= " elite_pool=0, ";
$sql .= " product_list=0, ";
$sql .= " date_created='".getDateToday()."', ";
$sql .= " last_modified='".getDateToday()."'";
$result = mysql_query($sql,$db);
if ($result && (mysql_affected_rows()==1))
  {
    $product_name=getProductName($db,$product_id);

    $messageFile = MESSAGE_DIRECTORY."/ads/existing_product_selected.txt";
    $vars["firstname"]       = getMemberFirstName($memberRecord);
    $vars["submit_date"]     = getDateToday();
    $vars["product_aff_url"] = $product_url;
    $vars["product_name"]    = $product_name;

    $fullname = getMemberFullName($memberRecord);
    $email    = strtolower($memberRecord["email"]);
    sendMessageFile($messageFile, $fullname, $email, $vars);

     // OK
    $response= new stdClass();
    $response->success   = "TRUE";
    $response->disposition = 1;
    $response->user_level = $user_level;
    sendJSONResponse(0, $response, null);
    exit;
  }

//--- Failed
$response=new stdClass();
$response->success   = "FALSE";
$response->disposition = 1;
$response->user_level = $user_level;
sendJSONResponse(201, $response, "Unable to Remove Product");
exit;
?>
