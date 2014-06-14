<?php
$product_id="";
$product_name="";
$product_title="";
$product_description="";
$product_categories="";
$product_url="";

if (isset($_REQUEST["myown_product_name"]))
  $product_name = striplt($_REQUEST["myown_product_name"]);
if (isset($_REQUEST["myown_product_title"]))
  $product_title = striplt($_REQUEST["myown_product_title"]);
if (isset($_REQUEST["myown_product_description"]))
  $product_description  = striplt($_REQUEST["myown_product_description"]);

// if (isset($_REQUEST["myown_product_categories"]))
//   $product_categories  = striplt($_REQUEST["myown_product_categories"]);
if (isset($_REQUEST["product_categories"]))
   $product_categories  = striplt($_REQUEST["product_categories"]);

if (isset($_REQUEST["myown_product_url"]))
  $product_url  = striplt($_REQUEST["myown_product_url"]);



$member_id =  $memberRecord["member_id"];
$user_level = $memberRecord["user_level"];

$ERROR = FALSE;

if (strlen($product_name) > 0        &&
    strlen($product_title) > 0       &&
    strlen($product_description) > 0 &&
    strlen($product_categories)    > 0 &&
    strlen($product_url)         > 0 &&
    $REQUEST_METHOD == "POST" )
  {
    $product_name  = upperCaseWords($product_name);
    $product_title = upperCaseWords($product_title);

    $sql  = "INSERT into product set ";
    $sql .= " product_submit_date='".getDateToday()."', ";
    $sql .= " product_owner='$member_id', ";
    $sql .= " org_product_owner='$member_id', ";
    $sql .= " product_name='".addslashes($product_name)."', ";
    $sql .= " product_title='".addslashes($product_title)."', ";
    $sql .= " product_description='".addslashes($product_description)."', ";
    $sql .= " product_categories='$product_categories' ";
    $result = mysql_query($sql,$db);
    if ($result && (mysql_affected_rows()==1))
      {
        $product_id = mysql_insert_id($db);

        if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP)
          {

          }
        else
          {
            $UPDATE=FALSE;
            $upload_image_variable = "myown_product_image";

            include_once("pushy_image_uploaded.inc");
          }

        if ($memberRecord["user_level"] == $PUSHY_LEVEL_VIP || ($IMAGE_UPLOADED))
          {
            // printf("<PRE>");
            // print_r($IMAGE_INFO);
            // printf("</PRE>");

            // Very Last thing We Want to Do is create an Ad Record for this Product
            $sql  = "INSERT into ads set ";
            $sql .= " member_id='$member_id', ";
            $sql .= " reseller_listing=0, ";
            $sql .= " product_id='$product_id', ";
            $sql .= " product_url='$product_url', ";
            $sql .= " pushy_network=0, ";
            $sql .= " elite_bar=0, ";
            $sql .= " elite_pool=0, ";
            $sql .= " product_list=0, ";
            $sql .= " date_created='".getDateToday()."', ";
            $sql .= " last_modified='".getDateToday()."'";
            $result = mysql_query($sql,$db);
            if ($result && (mysql_affected_rows()==1))
              {
                 // OK - First try to Update whats in Product_Pending - Should never be the case
                 $sql  = "UPDATE product_pending set ";
                 $sql .= " disposition=0, ";                                     // 0= NEW PRODUCT   1= PRODUCT UPDATE
                 $sql .= " ts_submitted='".time()."', ";
                 $sql .= " product_name='".addslashes($product_name)."', ";
                 $sql .= " product_title='".addslashes($product_title)."', ";
                 $sql .= " product_description='".addslashes($product_description)."', ";
                 $sql .= " product_categories='$product_categories',";
                 $sql .= " WHERE replaces_product_id='$product_id'";
                 $res=mysql_query($sql,$db);
                 if ($result && (mysql_affected_rows()==1))
                   {
                     // OK-DONE ... Not sure Why its there
                   }
                 else
                   {
                     $sql  = "INSERT into product_pending set ";
                     $sql .= " disposition=0, ";                                  // 0= NEW PRODUCT   1= PRODUCT UPDATE
                     $sql .= " ts_submitted='".time()."', ";
                     $sql .= " product_owner='$member_id', ";
                     $sql .= " product_name='".addslashes($product_name)."', ";
                     $sql .= " product_title='".addslashes($product_title)."', ";
                     $sql .= " product_description='".addslashes($product_description)."', ";
                     $sql .= " product_categories='$product_categories',";
                     $sql .= " replaces_product_id='$product_id' ";
                     mysql_query($sql,$db);
                   }

                 $messageFile = MESSAGE_DIRECTORY."/ads/own_product_submission.txt";
                 $vars["firstname"]       = getMemberFirstName($memberRecord);
                 $vars["submit_date"]     = getDateToday();
                 $vars["product_name"]    = $product_name;

                 $fullname = getMemberFullName($memberRecord);
                 $email    = strtolower($memberRecord["email"]);

                 sendMessageFile($messageFile, $fullname, $email, $vars);

                 sendMessageFile($messageFile, "Product Submission", EMAIL_TEAM, $vars);

              }
            else
              {
                deleteProduct($db,$product_id);

                $ERROR=TRUE;
                $ERROR_MESSAGE = "Failed";
              }
          }
        else
          {

            deleteProduct($db,$product_id);
            $ERROR=TRUE;

            // $UPLOAD_FAILURE and ERROR_MESSAGE was SET by Image Uploader
            if ($UPLOAD_FAILURE != 0 && strlen($ERROR_MESSAGE)==0)
              {
                $ERROR_MESSAGE = "Image File Upload Failure ($UPLOAD_FAILURE) ";
              }
          }
      }
    else
      {
        $ERROR=TRUE;
        $ERROR_MESSAGE = "Unable to Add Product";  // Should Never Happen
      }
  }
else
  {
    // internal only - shouldn't happen in production
    $ERROR=TRUE;
    $ERROR_MESSAGE = "Invalid Request";
  }

echo "<script type=\"text/javascript\">                         \n";
echo "  var theResult = {};                                     \n";
echo "  theResult.success=". ($ERROR?"false":"true") .";        \n";
echo "  theResult.disposition=0;                                \n";
echo "  theResult.user_level=". $user_level          .";        \n";
echo "  theResult.message='". $ERROR_MESSAGE ."';               \n";
echo "  theResult.ApprovalRequired=true;                        \n";
echo "  window.parent.myads_new_product_submission_status(theResult);\n";
echo "</script>                                                 \n";

// if ($ERROR)
//  {
//    echo "FAIL:$ERROR_MESSAGE";
//  }
// else
//  {
//    echo "SUCCESS:$product_id";
//  }

exit;
?>
