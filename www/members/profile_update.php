<?php
//=== RETIRED =======  include("pushy_get_response.inc");

 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

 $email                 = strtolower(stripslashes($_REQUEST["email"]));
 $password              = striplt(strtolower($_REQUEST["password"]));
 $firstname             = ucfirst_only(stripslashes($_REQUEST["firstname"]));
 $lastname              = ucfirst_only(stripslashes($_REQUEST["lastname"]));
 $company_name          = stripslashes($_REQUEST["company_name"]);
 $address1              = stripslashes($_REQUEST["address1"]);
 $address2              = stripslashes($_REQUEST["address2"]);
 $city                  = stripslashes($_REQUEST["city"]);
 $state                 = stripslashes($_REQUEST["state"]);
 $zip                   = stripslashes($_REQUEST["zip"]);
 $country               = stripslashes($_REQUEST["country"]);
 $phone                 = stripslashes($_REQUEST["phone"]);
 $phone_ext             = stripslashes($_REQUEST["phone_ext"]);
 $taxid                 = stripslashes($_REQUEST["taxid"]);
 $payable_to            = stripslashes($_REQUEST["payable_to"]);
 $paypal_email          = stripslashes(strtolower(stripa($_REQUEST["paypal_email"])));


 $member_id    = $memberRecord["member_id"];
 $refid        = $memberRecord["refid"];
 $affiliate_id = $memberRecord["affiliate_id"];
 $is_update    = "false";


 $signin_email_changed=FALSE;
 if (strtolower($email) != strtolower($memberRecord["email"]))
   {
     $signin_email_changed=TRUE;
     $is_update="true";
     // They have to be RE-CONFIRMED
   }

 $sql  = "UPDATE member SET ";

 $sql .= " email            = '".addslashes(stripslashes($email))."', ";

 $sql .= " password         = '".addslashes(stripslashes($password))."', ";
 $sql .= " firstname        = '".addslashes(stripslashes($firstname))."', ";
 $sql .= " lastname         = '".addslashes(stripslashes($lastname))."', ";
 $sql .= " company_name     = '".addslashes(stripslashes($company_name))."', ";
 $sql .= " address1         = '".addslashes(stripslashes($address1))."', ";
 $sql .= " address2         = '".addslashes(stripslashes($address2))."', ";
 $sql .= " city             = '".addslashes(stripslashes($city))."', ";
 $sql .= " state            = '".addslashes(stripslashes($state))."', ";
 $sql .= " zip              = '".addslashes(stripslashes($zip))."', ";
 $sql .= " country          = '".addslashes(stripslashes($country))."', ";
 $sql .= " phone            = '".addslashes(stripslashes($phone))."', ";
 $sql .= " phone_ext        = '".addslashes(stripslashes($phone_ext))."', ";
 $sql .= " paypal_email     = '".addslashes(stripslashes($paypal_email))."', ";
 $sql .= " taxid            = '".addslashes(stripslashes($taxid))."', ";
 $sql .= " payable_to       = '".addslashes(stripslashes($payable_to))."'";
 $sql .= " WHERE member_id  = '$mid'";
 $result = exec_query($sql,$db);

 // printf("SQL: %s<br>\n",$sql)
 // printf("ERR: %s<br>\n",mysql_error());

 if (!$result)
   {
     $response=new stdClass();
     $response->success   = "FALSE";
     sendJSONResponse(201, $response, "Account Update Failed");
     //sendJSONResponse(201, $response, "SQL: $sql\n\n"."ERR:".mysql_error());
     exit;
   }


if (FALSE)
  {
    if ($is_update=="true")
      {
        if (getMemberIdFromEmail($db, $email))
          {
            $response=new stdClass();
            $response->success   = "FALSE";
            sendJSONResponse(201, $response, "Unable to Update the Email Address Entered\n\nEmail Address Already Exists\n");
            //sendJSONResponse(201, $response, "SQL: $sql\n\n"."ERR:".mysql_error());
            exit;
          }

        if (!IS_LOCAL)
          {
            //====================== WE NOW HAVE TO ADD THIS MEMBER TO THE EMAIL MARKETING SYSTEM =========================
            //
            // list($rc, $resp) = notifyGetResponse("Update",$member_id,$refid,$affiliate_id,"true",$firstname,$lastname,$email);
            //
            //
          }

        $response=new stdClass();
        $response->success   = "TRUE";
        $response->url       = "/awaiting_update_confirmation.php?mid=$mid&new_email=$email";
        sendJSONResponse(101, $response, null);
        exit;
      }
  }


$response= new stdClass();
$response->success   = "TRUE";
$response->email     = $email;
sendJSONResponse(0, $response, null);
eit;
?>
