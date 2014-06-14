<?php

$adselection = array();
$ads = array();

$xpl_pending=0;
$xpl_name='';
$xpl_title='';
$existing_products_requested = 0;


//--------- See if this Member Currently has an Approved Product List Ad -------
$member_id = $memberRecord["member_id"];
$current_product_list_ad="";

$sql  = "SELECT * from ads ";
$sql .= " WHERE member_id='$member_id'";
$sql .= " AND   product_list!='0'";
$result = mysql_query($sql,$db);
if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
  {
    $current_product_list_ad_id = $myrow["ad_id"];
  }



if (isset($_REQUEST["product_list_ad"]) && isset($_REQUEST["product_list_affiliate_url"]))
  {
    $product_list_ad_id         = $_REQUEST["product_list_ad"];
    $product_list_affiliate_url = strtolower(striplt($_REQUEST["product_list_affiliate_url"]));
    $product_id="";

    $sql  = "SELECT existing_products_requested, product_id, affiliate_signup_url from ads ";
    $sql .= " WHERE ad_id='$product_list_ad_id'";
    $sql .= " AND   member_id='$member_id'";
    $result = mysql_query($sql,$db);
    if ($result && (mysql_num_rows($result)>0) && ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)))
      {
        $existing_products_requested = $myrow["existing_products_requested"];
        $affiliate_signup_url        = strtolower(striplt($myrow["affiliate_signup_url"]));
        $product_id                  = $myrow["product_id"];

        //----------------------------------------------------------------------------------------
        // This Block is Only Needed if we want the Client Alert Message for Ad Placement Updates
        // To mention the REVIEW/DELAY all the while the XPL Ad is "out for Review"
        // Rather than the First time that the Condition is raised that "Puts in to the Review Process"
        //----------------------------------------------------------------------------------------
        $sql  = "SELECT * from product_pending ";
        $sql .= " WHERE replaces_product_id='$product_id'";
        $res=mysql_query($sql,$db);
        if ($res && (mysql_num_rows($res)>0))
          {
            $productRecord=getProductInfo($db,$product_id,$member_id);
            if (is_array($productRecord))
              {
                $xpl_pending=1;
                $xpl_name  = stripslashes($productRecord["product_name"]);
                $xpl_title = stripslashes($productRecord["product_title"]);
              }
          }
        //----------------------------------------------------------------------
      }


    if (($existing_products_requested==0 || ($product_list_affiliate_url!= $affiliate_signup_url)) && isNumeric($product_id) && is_array($productRecord=getProductInfo($db,$product_id,$member_id)))
      {  // This Ad Has Not been Previous Approved for the Existing Products List
        $existing_product_name        = stripslashes($productRecord["product_name"]);
        $existing_product_title       = stripslashes($productRecord["product_title"]);
        $existing_product_description = stripslashes($productRecord["product_description"]);
        $existing_product_categories  = stripslashes($productRecord["product_categories"]);


        $xpl_pending=1;
        $xpl_name=$existing_product_name;
        $xpl_title=$existing_product_title;


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
                  // An AD Update can change disposition back to 1 - And should because An Ad Update always moves an AD to Pending
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
              }
            else
              {
                $ERROR=TRUE;
                $ERROR_MESSAGE = "Request Failed";
              }
          }
      }


    $sql  = "UPDATE ads set ";
    $sql .= " affiliate_signup_url='$product_list_affiliate_url', ";
    $sql .= " existing_products_requested=1, ";
    $sql .= " last_modified='".getDateToday()."'";
    $sql .= " WHERE ad_id='$product_list_ad_id'";
    $sql .= " AND   member_id='$mid'";
    $result = mysql_query($sql,$db);

    // printf("SQL: %s<br>\n",$sql);
    // printf("ERR: %s<br>\n",mysql_error());

    if ($result)
      {
        // OK
        if ($existing_products_requested==0 && strlen($existing_product_name)>0)  // First Request to Have this Ad Approved for Existing Product List
          {
             $messageFile = MESSAGE_DIRECTORY."/ads/own_product_existing_placement.txt";
             $vars["firstname"]       = getMemberFirstName($memberRecord);
             $vars["submit_date"]     = getDateToday();
             $vars["aff_signup_url"]  = $product_list_affiliate_url; // the one that came in on the request
             $vars["product_name"]    = $existing_product_name;

             $fullname = getMemberFullName($memberRecord);
             $email    = strtolower($memberRecord["email"]);
             sendMessageFile($messageFile, $fullname, $email, $vars);

             sendMessageFile($messageFile, "Exist Products List Requested", EMAIL_TEAM, $vars);
          }
      }
    else
      {
        $ERROR=TRUE;
        $ERROR_MESSAGE = "Affiliate Signup URL Update Failed";
      }
  }


if (strlen($current_product_list_ad_id) > 0)
  {
    if ($product_list_ad_id != $current_product_list_ad_id)
      {
        // No  Product List Ad  Selected or
        // New Product List Ad  is Different  than Previous Product List Ad
        //
        // --- UPDATE PREVIOUS PRODUCT_LIST_AD SELECTION  - set SIGNUP URL=""  AND  existing_products_requested=0
        // This will FORCE APPROVAL ON ALL  PRODUCT_LIST ADS selected  INCLUDING  AFFILIATE SIGNUP URL
        //

        $sql  = "UPDATE ads SET existing_products_requested=0, affiliate_signup_url='' ";
        $sql .= " WHERE member_id='$member_id'";
        $sql .= " AND   ad_id='$current_product_list_ad_id'";
        mysql_query($sql,$db);
      }
  }


while (list($k, $v) = each($_REQUEST))
  {
    if (substr($k,0,1) == "~")
      {
        // printf("%s=%s<br>\n",$k,$v);

        $tarray=explode("-",substr($k,1));
        if (count($tarray) == 2 && isNumeric($tarray[1]))
          {
            $sel=$tarray[0];
            $ad_id=$tarray[1];
            if (isset($ads[$ad_id]))
               $selection = $ads[$ad_id];
            else
               $selection=array();

            $selection[$sel] = (strcasecmp($v,"TRUE")==0);

            $ads[$ad_id] = $selection;
          }
      }
  }


// printf("---MID=%s  SID=%s  NumAds=%d\n",$mid,$sid,count($ads));
// print_r($ads);

if (count($ads) > 0)
  {
    foreach($ads AS $ad_id=>$selection)
      {
        $sql  = "UPDATE ads set ";
        foreach($selection AS $selector=>$bool)
          {
            if ($selector == "pushy_network")
              {
                 if ($bool)
                   $sql .= " pushy_network=1,";
                 else
                   $sql .= " pushy_network=0,";
              }
            else
            if ($selector == "elite_bar")
              {
                 if ($bool)
                   $sql .= " elite_bar=1,";
                 else
                   $sql .= " elite_bar=0,";
              }
            else
            if ($selector == "elite_pool")
              {
                 if ($bool)
                   $sql .= " elite_pool=1,";
                 else
                   $sql .= " elite_pool=0,";
              }
            else
            if ($selector == "product_list")
              {
                 if ($bool)
                   $sql .= " product_list=1,";
                 else
                   $sql .= " product_list=0,";
              }
          }
        $sql .= " ad_id='$ad_id'";

        $sql .= " WHERE member_id = '$mid'";
        $sql .= " AND   ad_id= '$ad_id'";
        $result = mysql_query($sql,$db);

        //printf("SQL: %s<br>\n",$sql);
      }
  }

$response= new stdClass();
$response->success   = "TRUE";
$response->product_list_ad_id          = $product_list_ad_id;
$response->product_list_affiliate_url  = $product_list_affiliate_url;
$response->existing_products_requested = $existing_products_requested;
$response->xpl_pending                 = $xpl_pending;   // existing_products request (if 1 - Tell Member there will be a delay)
$response->xpl_name                    = $xpl_name;      // existing_products name    (if xpl_pending: product_name)
$response->xpl_title                   = $xpl_title;     // existing_products title   (if xpl_pending: product_title)
sendJSONResponse(0, $response, null);
exit;
?>
