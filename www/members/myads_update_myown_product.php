<?php
$product_id="";
$ad_id="";
$product_name="";
$product_title="";
$product_description="";
$product_categories="";
$product_url="";

if ( isset($_REQUEST["product_id"]) && isNumeric($_REQUEST["product_id"])  &&
     isset($_REQUEST["ad_id"])      && isNumeric($_REQUEST["ad_id"]))
  {
     $product_id = $_REQUEST["product_id"];
     $ad_id = $_REQUEST["ad_id"];
  }
if (isset($_REQUEST["myown_product_name"]))
  $product_name = striplt($_REQUEST["myown_product_name"]);
if (isset($_REQUEST["myown_product_title"]))
  $product_title = striplt($_REQUEST["myown_product_title"]);
if (isset($_REQUEST["myown_product_description"]))
  $product_description  = striplt($_REQUEST["myown_product_description"]);

if (isset($_REQUEST["product_categories"]))
   $product_categories  = striplt($_REQUEST["product_categories"]);

if (isset($_REQUEST["myown_product_url"]))
  $product_url  = striplt($_REQUEST["myown_product_url"]);

$imageUploadSelected=false;
if (isset($_REQUEST["IMAGE_UPLOAD_OPTION"]) && $_REQUEST["IMAGE_UPLOAD_OPTION"]=="YES")
  $imageUploadSelected=true;


$ERROR = FALSE;

//----- DEBUG ONLY ----------
// $fp=fopen("a.1","w");
// $lineout= sprintf("CATEGORES: '%s'\n",$product_categories);
// fputs($fp,$lineout);
// fclose($fp);
//
// $product_id="";
//----- DEBUG ONLY ----------


$member_id   = $memberRecord["member_id"];
$system_user = $memberRecord["system"];
$user_level  = $memberRecord["user_level"];

$changes=array();
$requiresApproval = FALSE;

if (strlen($product_id)          > 0 &&
    strlen($ad_id)               > 0 &&
    strlen($product_name)        > 0 &&
    strlen($product_title)       > 0 &&
    strlen($product_description) > 0 &&
    strlen($product_categories)  > 0 &&
    strlen($product_url)         > 0 &&
    is_array($productRecord=getProductInfo($db,$product_id,$member_id)) &&
    $REQUEST_METHOD == "POST" )
  {
    $product_name  = upperCaseWords($product_name);
    $product_title = upperCaseWords($product_title);

    $existing_product_name        = stripslashes($productRecord["product_name"]);
    $existing_product_title       = stripslashes($productRecord["product_title"]);
    $existing_product_description = stripslashes($productRecord["product_description"]);
    $existing_product_categories  = stripslashes($productRecord["product_categories"]);

    if ($existing_product_name  != $product_name)
      $changes["ProductName"]         = TRUE;
    if ($existing_product_title != $product_title)
      $changes["ProductTitle"]        = TRUE;
    if ($existing_product_description != $product_description)
      $changes["ProductDescription"]  = TRUE;

    // if ($existing_product_categories != $product_categories)
    //   $changes["ProductCategories"]   = TRUE;

    // echo "changes=".count($changes)."\n";



    //------

        if (count($changes)>0)    // May do something more elaborate later to decide whether these changes require Approval
          $requiresApproval = TRUE;

    //------




    if ($imageUploadSelected)
      {
        $UPDATE=TRUE;
        $upload_image_variable = "myown_product_image";
        include_once("pushy_image_uploaded.inc");

        if ($IMAGE_UPLOADED)
          {
            // printf("<PRE>");
            // print_r($IMAGE_INFO);
            // printf("</PRE>");

            $sql  = "UPDATE ads set ";
            $sql .= " product_url='$product_url', ";
            $sql .= " last_modified='".getDateToday()."'";
            $sql .= " WHERE product_id='$product_id'";
            $sql .= " AND   ad_id='$ad_id'";
            $sql .= " AND   member_id='$member_id'";
            $result = mysql_query($sql,$db);

            // printf("SQL: %s<br>\n",$sql);
            // printf("ERR: %s<br>\n",mysql_error());

            if ($result)
              {
                // OK
              }
            else
              {
                $ERROR=TRUE;
                $ERROR_MESSAGE = "Update Failed (1)";
              }
          }
        else
          {
            $ERROR=TRUE;
            if ($UPLOAD_FAILURE != 0 && strlen($ERROR_MESSAGE)==0)
              {
                $ERROR_MESSAGE = "Image File Upload Failure ($UPLOAD_FAILURE) ";
              }
          }
      }
    else
      {
         $sql  = "UPDATE ads set ";
         $sql .= " product_url='$product_url', ";
         $sql .= " last_modified='".getDateToday()."'";
         $sql .= " WHERE product_id='$product_id'";
         $sql .= " AND   ad_id='$ad_id'";
         $sql .= " AND   member_id='$member_id'";
         $result = mysql_query($sql,$db);

         // printf("SQL: %s<br>\n",$sql);
         // printf("ERR: %s<br>\n",mysql_error());

         if ($result)
           {
             // OK
           }
         else
           {
             $ERROR=TRUE;
             $ERROR_MESSAGE = "Update Failed (2)";
           }
      }


    if (!$ERROR)
      {
        if ($requiresApproval)
          {
             // OK - First try to Update whats in Product_Pending - Could be that it is already there  (Existing Products List Request)
             $sql  = "UPDATE product_pending set ";
             $sql .= " disposition=1, ";                                  // 0= NEW PRODUCT   1= PRODUCT UPDATE
             $sql .= " ts_submitted='".time()."', ";
             $sql .= " product_name='".addslashes($product_name)."', ";
             $sql .= " product_title='".addslashes($product_title)."', ";
             $sql .= " product_description='".addslashes($product_description)."', ";
             $sql .= " product_categories='$product_categories',";
             $sql .= " WHERE replaces_product_id='$product_id'";
             $res=mysql_query($sql,$db);
             if ($result && (mysql_affected_rows()==1))
               {
                 // OK-DONE ... It must be there for some reason like an Existing Products List Request
               }
             else
               {
                 $sql  = "INSERT into product_pending set ";
                 $sql .= " disposition=1, ";                                  // 0= NEW PRODUCT   1= PRODUCT UPDATE
                 $sql .= " ts_submitted='".time()."', ";
                 $sql .= " product_owner='$member_id', ";
                 $sql .= " product_name='".addslashes($product_name)."', ";
                 $sql .= " product_title='".addslashes($product_title)."', ";
                 $sql .= " product_description='".addslashes($product_description)."', ";
                 $sql .= " product_categories='$product_categories',";
                 $sql .= " replaces_product_id='$product_id' ";
                 $result = mysql_query($sql,$db);
                 if ($result && (mysql_affected_rows()==1))
                   {
                     // OK
                   }
                 else
                   {
                     $ERROR=TRUE;
                    $ERROR_MESSAGE = "Update Failed (2)";
                   }
               }

             if (!$ERROR)
               {
                 $messageFile = MESSAGE_DIRECTORY."/ads/own_product_submission.txt";
                 $vars["firstname"]       = getMemberFirstName($memberRecord);
                 $vars["submit_date"]     = getDateToday();
                 $vars["product_name"]    = $product_name;

                 sendMessageFile($messageFile, "Product Update", EMAIL_TEAM, $vars);
               }
          }
        else
          {
                 // Changes Did Not Require Product Approval
             $sql  = "UPDATE product set ";
             $sql .= " product_name='".addslashes($product_name)."', ";
             $sql .= " product_title='".addslashes($product_title)."', ";
             $sql .= " product_description='".addslashes($product_description)."', ";
             $sql .= " product_categories='$product_categories' ";
             $sql .= " WHERE product_id='$product_id'";
             $sql .= " AND   product_owner='$member_id'";
             $result = mysql_query($sql,$db);

             if ($result)
               {
                 // OK
               }
             else
               {
                 $ERROR=TRUE;
                $ERROR_MESSAGE = "Update Failed (3)";
               }
          }
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
echo "  theResult.disposition=1;                                \n";
echo "  theResult.user_level=". $user_level          .";        \n";
echo "  theResult.message='". $ERROR_MESSAGE ."';               \n";
echo "  theResult.ApprovalRequired=". ($requiresApproval?"true":"false") ."; \n";
echo "  window.parent.myads_update_product_submission_status(theResult);\n";
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
