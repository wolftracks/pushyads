<?php
$RESPONSE["result"]=0;
$RESPONSE["message"]="";

$ad_id       = $_REQUEST["ad_id"];
$product_id  = $_REQUEST["product_id"];
$product_url = $_REQUEST["product_url"];

$member_id  = $memberRecord["member_id"];
$user_level = $memberRecord["user_level"];

$sql  = "UPDATE ads set ";
$sql .= " product_id='$product_id', ";
$sql .= " product_url='$product_url', ";
$sql .= " last_modified='".getDateToday()."'";
$sql .= " WHERE ad_id='$ad_id'";
$sql .= " AND   member_id='$member_id'";
$result = mysql_query($sql,$db);

//printf("SQL: %s\n",$sql);
//printf("ERR: %s\n",mysql_error());


if ($result)
  {
     // OK
    $response= new stdClass();
    $response->success     = "TRUE";
    $response->disposition = 1;
    $response->user_level = $user_level;
    // $response->sql = $sql;
    // $response->err = mysql_error();
    sendJSONResponse(0, $response, null);
    exit;
  }

//--- Failed
$response=new stdClass();
$response->success   = "FALSE";
$response->disposition = 1;
$response->user_level = $user_level;
sendJSONResponse(201, $response, "Unable to Update Ad");
exit;
?>
