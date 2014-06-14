<?php
$RESPONSE["result"]=0;
$RESPONSE["message"]="";

$product_id="";
$ad_id="";

if ( isset($_REQUEST["product_id"]) && isNumeric($_REQUEST["product_id"])  &&
     isset($_REQUEST["ad_id"])      && isNumeric($_REQUEST["ad_id"]))
  {
     $product_id = $_REQUEST["product_id"];
     $ad_id = $_REQUEST["ad_id"];
  }

$member_id =  $memberRecord["member_id"];

if (strlen($product_id)          > 0 &&
    strlen($ad_id)               > 0 &&
    is_array($productRecord=getProductInfo($db,$product_id,$member_id)) &&
    $REQUEST_METHOD == "POST" )
  {
    $existing_product_name        = stripslashes($productRecord["product_name"]);
    $existing_product_title       = stripslashes($productRecord["product_title"]);
    $existing_product_description = stripslashes($productRecord["product_description"]);
    $existing_product_categories  = stripslashes($productRecord["product_categories"]);

    // OK - First try to Update whats in Product_Pending - Could be that it is already there awaiting Approval - Just set the existing_products_requested bit
    $sql  = "UPDATE product_pending set ";
    $sql .= " existing_products_requested=1, ";
    $sql .= " ts_submitted='".time()."' ";
    $sql .= " WHERE replaces_product_id='$product_id'";
    $res=mysql_query($sql,$db);
    if ($result && (mysql_affected_rows()==1))
      {
        // OK-DONE ... It must be still be there waiting for initial approval
      }
    else
      {       // Disposition 6 is what Keeps it off of the PENDING ADS list
              // An AD Update can change disposition back to 1 - And should because An Ad Update alwayys moves an AD to Pending
              // And we are still OK because we have 'existing_products_requested' set - which is NOT Reset by an AD Update
        $sql  = "INSERT into product_pending set ";
        $sql .= " disposition=6,";                                  // 0= NEW PRODUCT   1= PRODUCT UPDATE   6=Request to Add to Existing Products List
        $sql .= " existing_products_requested=1, ";
        $sql .= " ts_submitted='".time()."', ";
        $sql .= " product_owner='$member_id', ";
        $sql .= " product_name='".addslashes($existing_product_name)."', ";
        $sql .= " product_title='".addslashes($existing_product_title)."', ";
        $sql .= " product_description='".addslashes($existing_product_description)."', ";
        $sql .= " product_categories='$existing_product_categories',";
        $sql .= " replaces_product_id='$product_id' ";
        $result = mysql_query($sql,$db);
        if ($result && (mysql_affected_rows()==1))
          {
            // OK
            $response= new stdClass();
            $response->success   = "TRUE";
            sendJSONResponse(0, $response, null);
            exit;
          }
        else
          {
            $ERROR=TRUE;
            $ERROR_MESSAGE = "Request Failed";
          }
      }
  }
else
  {
    // internal only - shouldn't happen in production
    $ERROR=TRUE;
    $ERROR_MESSAGE = "Invalid Request";
  }

//--- Failed
$response=new stdClass();
$response->success   = "FALSE";
sendJSONResponse(201, $response, "Unable to Process Request");
exit;
exit;
?>
