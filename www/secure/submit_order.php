<?php

define("PAYPAL_API_DEBUG" , "FALSE");

  //-----
  // Rules for Running
  //   PayFlow is in TEST MODE
  //      REMOTE: Charge is Submitted to PayFlow Test System with Test Credit Card Number
  //      LOCAL:  See PAYFLOW_TEST_OPTION Below
  //   PayFlow is in LIVE MODE
  //      REMOTE: Charge is Submitted to PayFlow Live System                     --- NORMAL PRODUCTION SCENARIO ---
  //      LOCAL:  Charge is NOT Submitted to PayFlow Live System  - Transacaction Will Fail (Denied)

  //-----
  // LOCAL_PAYFLOW_TEST_OPTION  is ONLY CONSIDERED when PayFlow is in TEST MODE  (include pushy_payflow-test.inc)  AND  You are Running LOCALLY
  //    0 = Submit Transaction to PAYFLOW TEST SYSTEM
  //    1 = Bypass PAYFLOW TEST SYSTEM - Do Not Submit  -  Just SUCCEED (Approved)
  //    2 = Bypass PAYFLOW TEST SYSTEM - Do Not Submit  -  Just FAIL    (Denied)

$LOCAL_PAYFLOW_TEST_OPTION = 0;

include_once("pushy_constants.inc");

include_once("pushy_payflow.inc");

include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");
include_once("pushy_sendmail.inc");
include_once("pushy_imagestore.inc");
include_once("fraud.php");

//----  Payment Form Hidden Fields --------
$mid            = $_REQUEST["mid"];
$sid            = $_REQUEST["sid"];   // Not Required - Can Order without signing in

$orderAmount    = $_REQUEST["orderAmount"];
$orderType      = $_REQUEST["orderType"];
$orderLevel     = $_REQUEST["orderLevel"];
$description    = $_REQUEST["description"];
$banner_image   = $_REQUEST["banner_image"];

$proration = 0;
if (isset($_REQUEST["proration"]))
   $proration = $_REQUEST["proration"];

$level_name = "VIP";
if ($orderLevel == $PUSHY_LEVEL_PRO)
   $level_name = "PRO";
else
if ($orderLevel == $PUSHY_LEVEL_ELITE)
   $level_name = "ELITE";


//----  Payment Form Input Fields --------
$cc_holdername  = $_REQUEST["cc_holdername"];
$receipt_email  = $_REQUEST["receipt_email"];
$cc_address     = $_REQUEST["cc_address"];
$cc_zip         = $_REQUEST["cc_zip"];
$cc_number      = $_REQUEST["cc_number"];
$cc_expmm       = $_REQUEST["cc_expmm"];
$cc_expyyyy     = $_REQUEST["cc_expyyyy"];
$cc_cvv2        = $_REQUEST["cc_cvv2"];
$cc_expmmyyyy   = $cc_expmm."-".$cc_expyyyy;


$amount = "".$orderAmount;
if (!(is_integer(strpos($amount,"."))))
  $amount .= ".00";

$dateToday = getDateToday();
$timeNow   = getTimeNow()." MST";
$dateTime  = $dateToday." ".$timeNow;

$db=getPushyDatabaseConnection();

//------------------------------------------------------------------------------------------------------

if (strlen($mid) > 0  && is_array($memberRecord=getMemberInfo($db,$mid)))
  {
    $member_id    = $mid;
    $firstname    = stripslashes($memberRecord["firstname"]);
    $lastname     = stripslashes($memberRecord["lastname"]);
    $fullname     = $firstname." ".$lastname;
    $phone        = stripslashes($memberRecord["phone"]);
    $email        = stripslashes($memberRecord["email"]);
    $refid        = stripslashes($memberRecord["refid"]);
    $user_level   = $memberRecord["user_level"];
    $current_user_level = $memberRecord["user_level"];
    $address1     = stripslashes($memberRecord["address1"]);
    $address2     = stripslashes($memberRecord["address2"]);
    $city         = stripslashes($memberRecord["city"]);
    $state        = stripslashes($memberRecord["state"]);
    $zip          = stripslashes($memberRecord["zip"]);
    $country      = stripslashes($memberRecord["country"]);

    $invoice      = substr(strtolower($firstname),0,1).substr(strtolower($lastname),0,1).getmicroseconds();
    $receiptid    = strtolower($mid)."-".getmicroseconds();

          // probably hit the BACK Button after a successfile order
          // and are trying again - first order took - tell them so
    if ($orderLevel <= $user_level)
      {
        $x_response_code        = 0;
        $x_response_reason_text = $UserLevels[$user_level];
        include("duplicate_order.php");
        exit;
      }
  }
else
  {
    $firstname = "";
    $lastname  = "";
    $email     = "";
    $user_level=0;
    $current_user_level = 0;

    if (isset($_COOKIE["PAREF"]) && strlen($_COOKIE["PAREF"]) > 0)
       $paref=$_COOKIE["PAREF"];
    else
       $paref=$_REQUEST["PAREF"];

    $refid="";
    if (is_array($affiliateRecord=getMemberInfoForAffiliate($db,$paref)))
      $refid=$affiliateRecord["member_id"];
    else
      $refid=$PUSHY_ROOT;

    if (isset($_REQUEST["firstname"]))
      $firstname=ucfirst_only($_REQUEST["firstname"]);
    if (isset($_REQUEST["lastname"]))
      $lastname=ucfirst_only($_REQUEST["lastname"]);
    if (isset($_REQUEST["email"]))
      $email=strtolower($_REQUEST["email"]);

    if (strlen($firstname)>0 && strlen($lastname)>0  && strlen($email)>0)
      {
        $member_id    = "";

        $member_id    = "nm-".rand(1.9).rand(1.9).rand(1.9).rand(1.9);

        $fullname     = $firstname." ".$lastname;
        $invoice      = substr(strtolower($firstname),0,1).substr(strtolower($lastname),0,1).getmicroseconds();
        $receiptid    = $member_id."-".getmicroseconds();
      }
    else
      {
        // Required Arguments missing
        printf("Required Arguments missing\n");

      }
  }

$userip=$_SERVER['REMOTE_ADDR'];


//------------------------------------------------------------------------------------------------------

































//--------------------------------------
// $FORCE_DECLINE=TRUE;
//--------------------------------------


//============================================================================================
if ($cc_number==MANUAL_ENTRY_KEY)
  {
    $cc_number=MANUAL_ENTRY_KEY;

    $payflow_resp["RESULT"]  = 0;
    $payflow_resp["AUTHCODE"]= "AUTH-00000001";
    $payflow_resp["PNREF"]   = "MAN-00000001";
    $payflow_resp["RESPMSG"] = "Your Charge has been Approved";
  }
else
  {
    $FRAUD_ALERT=FALSE;
    for ($i=0; $i<count($bad_cards); $i++)
      {
        list($first4, $last4) = explode("-",$bad_cards[$i]);
        if ( startsWith($cc_number, $first4)  &&  endsWith($cc_number, $last4) )
          {
            $FORCE_DECLINE=TRUE;
            $FRAUD_ALERT=TRUE;
            break;
          }
      }

    if (!$FORCE_DECLINE)
      {
        if (strlen($cc_number) < 15)
          $FORCE_DECLINE=TRUE;
        else
        if (!isNumeric($cc_number))
          $FORCE_DECLINE=TRUE;
        else
        if (substr($cc_number,0,1) != '3' &&
            substr($cc_number,0,1) != '4' &&
            substr($cc_number,0,1) != '5' &&
            substr($cc_number,0,1) != '6')
          $FORCE_DECLINE=TRUE;
        if (! (strlen($cc_cvv2) >= 3 && strlen($cc_cvv2) <= 4 && isNumeric($cc_cvv2)) )
          $FORCE_DECLINE=TRUE;
      }


    if ($FORCE_DECLINE)
      {
          sleep(4);  /* sleep 4 seconds */

          $payflow_resp["RESULT"]  = 12;
          $payflow_resp["AUTHCODE"]= "";
          $payflow_resp["PNREF"]   = "000000";
          $payflow_resp["RESPMSG"]="Transaction Declined (12)";

          $yy=sprintf("%02d", $cc_expyyyy % 2000);
          $mm=sprintf("%02d", $cc_expmm);

          if ($FRAUD_ALERT)
            $tmsg .= "FRAUD ALERT: An ORDER attempt has been attempted/denied using a Credit Card flagged for Fraud detection:\n\n";
          else
            $tmsg .= "SUSPICIOUS ORDER ATTEMPTED: Credit Card Audits Caught on Server instead of Client - Being Declined\n\n";

          $tmsg .= " Date Time          : $dateTime\n";
          $tmsg .= " Credit Card Number : $cc_number\n";
          $tmsg .= " Credit Card ExpDate: $cc_expyyyy-$cc_expmm\n";

          $tmsg .= " Purchase Amount    : $amount\n\n";
          $tmsg .= " Member ID          : $member_id\n";
          $tmsg .= " First Name         : $firstname\n";
          $tmsg .= " Last Name          : $lastname\n";
          $tmsg .= " Email              : $email\n";
          $tmsg .= " Phone              : $phone\n";

          send_mail_direct("ALERT", EMAIL_TIM,  "ORDER SYSTEM", EMAIL_NOREPLY,  "POSSIBLE FRAUD - PLEASE HANDLE AT ONCE", $tmsg);;
      }
    else
      {
          $payment_method = getPaymentMethod($cc_number);

          if (IS_LOCAL && (PAYFLOW_TEST_MODE=="FALSE"))      // On Local System and The PAYFLOW System is LIVE -  Do Not Submit - Fail !
            {
               $payflow_resp["RESULT"]  = 12;
               $payflow_resp["AUTHCODE"]= "";
               $payflow_resp["PNREF"]   = "000000";
               $payflow_resp["RESPMSG"]="Transaction Failed (1)";
            }
          else
            {
               //------------- PROCESS THE ORDER ---------------------------------------------

               $order_processed=FALSE;
               if (IS_LOCAL && (PAYFLOW_TEST_MODE=="TRUE"))       // On Local System and The PAYFLOW System is in TEST MODE
                 {
                   if ($LOCAL_PAYFLOW_TEST_OPTION == 1)          // OPTION 1 - Do NOT Submit to Payflow - Instead: FAIL!
                     {
                       $payflow_resp["RESULT"]  = 0;
                       $payflow_resp["AUTHCODE"]= "LOC-AUTH-00000001";
                       $payflow_resp["PNREF"]   = "LOC-00000001";
                       $payflow_resp["RESPMSG"] = "Your Charge has been Approved";
                       $order_processed=TRUE;
                     }
                   else
                   if ($LOCAL_PAYFLOW_TEST_OPTION == 2)          // OPTION 2 - Do NOT Submit to Payflow - Instead: PASS!
                     {
                       $payflow_resp["RESULT"]  = 12;
                       $payflow_resp["AUTHCODE"]= "";
                       $payflow_resp["PNREF"]   = "000000";
                       $payflow_resp["RESPMSG"]="Transaction Failed (1)";
                       $order_processed=TRUE;
                     }
                 }

               if (!($order_processed))
                 {
                   $cc_number=validateCard($cc_number);
                   $payment_method = getPaymentMethod($cc_number);

                   $payflow_args=array();
                   $tender="C";      //---- Credit Card
                   $yy=sprintf("%02d", $cc_expyyyy % 2000);
                   $mm=sprintf("%02d", $cc_expmm);
                   $payflow_args["AMT"]       = $amount;
                   $payflow_args["ACCT"]      = $cc_number;
                   $payflow_args["EXPDATE"]   = "$mm$yy";
                   $payflow_args["STREET"]    = $cc_address;
                   $payflow_args["ZIP"]       = $cc_zip;
                   $payflow_args["CVV2"]      = $cc_cvv2;
                   $payflow_args["INVNUM"]    = $receiptid;

                                     //--- Misc
                   $payflow_args["FIRSTNAME"] = $firstname;
                   $payflow_args["LASTNAME"]  = $lastname;
                   $payflow_args["CITY"]      = $city;
                   $payflow_args["STATE"]     = $state;
                   $payflow_args["COUNTRY"]   = $country;
                   $payflow_args["EMAIL"]     = $email;
                   $payflow_args["PHONE"]     = $phone;
                   $payflow_args["COMMENT"]   = 'Online:'.$member_id;
                   $payflow_args["CUSTIP"]    = $userip;


                           // ---  LOG ALL CC ATTEMPTS
                   $logtm=time();
                   $logdate=formatDate($logtm);
                   $logtime=formatTime($logtm);
                   $logfname = LOG_DIRECTORY."/orders/online/".substr($logdate,0,7)."-attempts.log";
                   $fp = fopen($logfname, "a");
                   $lineout  = "$logdate $logtime|$payment_method|$cc_number|$cc_expyyyy-$cc_expmm|$cc_cvv2|$member_id|$firstname|$lastname|$email|$cc_address|$cc_zip|$country|$phone|\n";
                   fputs($fp,$lineout);
                   fclose($fp);


                   $payflow_url = PAYFLOW_BILLING_HOST;        // TEST or LIVE  -  depends on the payflow include
                   $payflow_resp = payflow_submit($payflow_url, $tender, $payflow_args);
                 }
            }
      }
  }
//============================================================================================

















if ($payflow_resp["RESULT"]==0)
  {
    // ---  Success ----

    // We have just taken someone's money - Make Certain there are No DownGrade Requests Pending
    // Any Prior Request to Downgrade is now irrelevant

    $sql  = "DELETE from userlevel_change_requests";
    $sql .= " WHERE member_id = '$member_id'";
    $res = mysql_query($sql,$db);


    if (isset($payflow_resp["AUTHCODE"]))
       $x_auth_code  = $payflow_resp["AUTHCODE"];
    else
       $x_auth_code  = $payflow_resp["HOSTCODE"];
    $x_avs_code      = $payflow_resp["AVSADDR"];
    $x_trans_id      = $payflow_resp["PNREF"];



    //==================================================================================================================================================================

    $lastPaymentDate   = getDateTodayAsArray();
                                                                                    // No Prior Anniversary - or you are coming in as NonPaid Subscription Level (VIP)
    if ($memberRecord["anniversary_target"]=="" || $user_level == $PUSHY_LEVEL_VIP) //   then Your New Anniversary TARGET is Based on Today's Date
      $anniversaryTarget = $lastPaymentDate;
    else
      $anniversaryTarget = dateToArray($memberRecord["anniversary_target"]);        // Otherwise, Your Anniversary Target is Advanced by One Month, but Not Altered.

                                                                                    // Advance Anniversary Target One Month
    $anniversaryTarget = getNextAnniversaryTarget($anniversaryTarget);              // Anniversary Target represents Next Target upon which the Next Payment Due will be Based
    $nextPaymentDue    = getNextPaymentDue($anniversaryTarget);                     // Compute Next Payment Due based on the new Anniversary Target


    $last_payment_date  = dateArrayToString($lastPaymentDate);
    $next_payment_due   = dateArrayToString($nextPaymentDue);
    $anniversary_target = dateArrayToString($anniversaryTarget);

    $current_level_date = $memberRecord["current_level_date"];
    if ($orderLevel != $user_level)  // Upgraded to This Level (First Purchase or subsequent Upgrade)
      {
        $current_level_date = $last_payment_date;
      }

    //==================================================================================================================================================================

        // ---  Success ----
    $logtm=time();
    $logdate=formatDate($logtm);
    $logtime=formatTime($logtm);
    $logfname = LOG_DIRECTORY."/orders/online/".substr($logdate,0,7)."-success.log";
    $fp = fopen($logfname, "a");
    $lineout  = "$logdate $logtime|". "LAST:$last_payment_date|NEXT:$next_payment_due|TRN:$x_trans_id" ."|$payment_method|$cc_number|$cc_expyyyy-$cc_expmm|$cc_cvv2|$member_id|$firstname|$lastname|$email|$cc_address|$cc_zip|$country|$phone|\n";
    fputs($fp,$lineout);
    fclose($fp);

    if (strlen($mid) > 0 && is_array($memberRecord))
      {
         // WE JUST TOOK THEIR MONEY - IF ITS A NEW MEMBER, then ASSIGN THEM A SYSTEM PASSWORD TO ENSURE THEY HAVE ONE
         // They Will still Have the Opportunity to Create their Own, but this allows the "Forgot Password" to send them the System Assigned
         // Password If they don't complete the Create Password Form.

         if ( strlen($sid) == 0 &&
              strlen($memberRecord["password"]) == 0 )
           {
             $today = getDateToday();
             $chars="bcdfghjkmnprstvwxz123456789";
             $password="p-";  // SYSTEM-ASSIGNED Password
             for ($i=0;$i<8;$i++)
               {
                 $n=rand(0,strlen($chars)-1);
                 $password .= $chars[$n];
               }
             $sql  = "UPDATE member set password='$password', registered=".time().", date_registered='$today'";
             $sql .= " WHERE member_id='$member_id'";
             $res=mysql_query($sql,$db);
           }

        //-------------------- MEMBER LEVEL UPDATE -------------------------------- Set New User Level
        if ($orderLevel != $user_level)
          {
            $affected_rows=0;
            $sql  = "UPDATE member set ";
            $sql .= " user_level='$orderLevel'";
            $sql .= " WHERE member_id='$member_id'";
            $result = mysql_query($sql,$db);
            if (($result) && ($affected_rows = mysql_affected_rows())==1)
              {
                //-- OK
                if ($user_level == $PUSHY_LEVEL_VIP && ($orderLevel > $user_level))   //--- Upgrading FROM VIP -- Remove VIP Ad for this member if it exists
                  {

                    $sql  = "SELECT * from product ";
                    $sql .= " WHERE product_owner='$member_id'";
                    $res  = mysql_query($sql,$db);
                    if (($res) && (mysql_num_rows($res) > 0))
                      {
                        while ($productRecord=mysql_fetch_array($res,MYSQL_ASSOC))
                          {
                            $product_id = $productRecord["product_id"];
                            deleteProduct($db,$product_id);

                            $sql  = "DELETE from ads ";
                            $sql .= " WHERE member_id='$member_id'";
                            $sql .= " AND   product_id='$product_id'";
                            mysql_query($sql,$db);
                          }
                      }
                  }
              }
            else
              {
                $msg  = "ERROR - Unable to Update User Level following Order Upgrade/Downgrade\n";
                $msg .= "  \n";
                $msg .= "Date-Time             = ".getDateTime()."\n";
                $msg .= "Member ID             = $member_id\n";
                $msg .= "User Level (OLD)      = $user_level\n";
                $msg .= "User Level (NEW)      = $orderLevel\n";
                $msg .= "Amount                = $amount\n";
                $msg .= "FirstName             = $firstname\n";
                $msg .= "LastName              = $lastname\n";
                $msg .= "Email                 = $email\n";

                $msg .= sprintf("\nSQL: %s\n",$sql);
                $msg .= sprintf("ERR: %s\n",mysql_error());
                $msg .= sprintf("\nRESULT: %s\n",(($result)?"TRUE":"FALSE"));
                $msg .= sprintf("ROWS AFFECTED: %d\n",$affected_rows);

                send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);
              }
            $user_level = $orderLevel;
            $memberRecord["user_level"] = $user_level;
          }
        //----------------------------------------------------------------------------




        //--------------------- MEMBER PAYMENT UPDATE --------------------------------------
        $affected_rows=0;
        $sql  = "UPDATE member set ";
        $sql .= " current_level_date = '$current_level_date',";
        $sql .= " last_payment_date  = '$last_payment_date',";
        $sql .= " next_payment_due   = '$next_payment_due',";
        $sql .= " anniversary_target = '$anniversary_target'";
        $sql .= " WHERE member_id='$member_id'";
        $res = mysql_query($sql,$db);
        if ($res)
          {
            //-- OK --
          }
        else
          {
            $msg  = "CRITICAL ERROR - Unable to Update Member PAYMENT Status following Online Order\n";
            $msg .= "  \n";
            $msg .= "Date-Time             = ".getDateTime()."\n";
            $msg .= "Member ID             = $member_id\n";
            $msg .= "Amount                = $amount\n";
            $msg .= "FirstName             = $firstname\n";
            $msg .= "LastName              = $lastname\n";
            $msg .= "Email                 = $email\n";

            $msg .= "\nCurrent Member Level  = ".$user_level."\n";

            $msg .= sprintf("\nSQL: %s\n",$sql);
            $msg .= sprintf("ERR: %s\n",mysql_error());
            $msg .= sprintf("\nRESULT: %s\n",(($result)?"TRUE":"FALSE"));
            $msg .= sprintf("ROWS AFFECTED: %d\n",$affected_rows);

            send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);
          }
        //---------------------------------------------------------------------------------
      }


    $title = "Unknown";
    if ($orderType == ORDER_TYPE_INITIAL)
       $title = "Membership Fee";
    else
    if ($orderType == ORDER_TYPE_UPGRADE)
       $title = "Membership Upgrade";

    $paymentInfo = array(
      "ORDER_TYPE"          =>  $orderType,
      "PRODUCT_TITLE"       =>  $title,
      "PRODUCT_DESCRIPTION" =>  $description." - ".$UserLevels[$user_level],
      "COMMAND"             =>  $payflow_resp["PAYFLOW_COMMAND"],
      "AMOUNT"              =>  $amount,
      "METHOD"              =>  $payment_method,
      "RECEIPT_ID"          =>  $receiptid,
      "RECEIPT_EMAIL"       =>  $email,
      "USERIP"              =>  $userip,
      "PRORATION"           =>  $proration,
      "SEND-RECEIPT"        =>  true
    );



    if (is_array($memberRecord) && $memberRecord["member_id"] == $member_id)
      {
        $rc = process_receipt($db, "ONLINE", $memberRecord, $payflow_args, $payflow_resp, $paymentInfo);
      }
    else
      {
        $tempDataRecord = array(
           "member_id"    => $member_id,
           "user_level"   => $user_level,
           "refid"        => $refid,
           "firstname"    => $firstname,
           "lastname"     => $lastname,
           "email"        => $email,
           "phone"        => $phone
        );

        $rc = process_receipt($db, "ONLINE", $tempDataRecord, $payflow_args, $payflow_resp, $paymentInfo);
      }

    if (is_array($memberRecord) && $memberRecord["member_id"] == $member_id)
      {
        if (strlen($member_record["cc_holdername"]) == 0 ||
            strlen($member_record["cc_number"])     == 0 ||
            strlen($member_record["cc_expmmyyyy"])  == 0 ||
            strlen($member_record["cc_cvv2"])       == 0 )
          {
             $sql  = "UPDATE member SET ";
             $sql .= " cc_holdername    = '".addslashes(stripslashes($cc_holdername))."', ";
             $sql .= " cc_number        = '".addslashes(stripslashes($cc_number    ))."', ";
             $sql .= " cc_expmmyyyy     = '".addslashes(stripslashes($cc_expmmyyyy ))."', ";
             $sql .= " cc_address       = '".addslashes(stripslashes($cc_address   ))."', ";
             $sql .= " cc_zip           = '".addslashes(stripslashes($cc_zip       ))."', ";
             $sql .= " cc_cvv2          = '".addslashes(stripslashes($cc_cvv2      ))."'  ";
             $sql .= " WHERE member_id  = '$member_id'";
             $result = mysql_query($sql,$db);
          }

      }


    if ($orderType == ORDER_TYPE_INITIAL && $orderLevel==$PUSHY_LEVEL_VIP)
      {                      // here for completeness - VIP is not a PAID LEVEL
        giveMemberAward($db,$member_id,"101");
        giveMemberAward($db,$member_id,"201");
      }
    else
    if ($orderType == ORDER_TYPE_INITIAL && $orderLevel==$PUSHY_LEVEL_PRO)
      {
        giveMemberAward($db,$member_id,"102");
        giveMemberAward($db,$member_id,"200");
        giveMemberAward($db,$member_id,"201");
        giveMemberAward($db,$member_id,"202");
      }
    else
    if ($orderType == ORDER_TYPE_INITIAL && $orderLevel==$PUSHY_LEVEL_ELITE)
      {
        giveMemberAward($db,$member_id,"103");
        giveMemberAward($db,$member_id,"200");
        giveMemberAward($db,$member_id,"201");
        giveMemberAward($db,$member_id,"202");
        giveMemberAward($db,$member_id,"203");
      }


    else
    if ($orderType == ORDER_TYPE_UPGRADE && $orderLevel==$PUSHY_LEVEL_PRO)
      {
        giveMemberAward($db,$member_id,"104");
        giveMemberAward($db,$member_id,"200");
        giveMemberAward($db,$member_id,"204");
      }
    else
    if ($orderType == ORDER_TYPE_UPGRADE && $orderLevel==$PUSHY_LEVEL_ELITE)
      {
        giveMemberAward($db,$member_id,"105");
        giveMemberAward($db,$member_id,"200");
        giveMemberAward($db,$member_id,"204");
        giveMemberAward($db,$member_id,"205");
      }


    //--- Signed in ?
    if (strlen($mid) > 0 && strlen($sid) > 0)
      {
        list($rc, $isAdminSession) = getSession($db, $sid, $mid, FALSE);
        if ($rc==0)
          {
            $memberRecord=getMemberInfo($db,$mid);
            if (is_array($memberRecord) && strcasecmp($mid,$memberRecord["member_id"])==0)
              {
                $user_level=$memberRecord["user_level"];
                $x_response_code        = 0;
                $x_response_reason_text = $UserLevels[$user_level];
                include("success.php");
                exit;
              }
          }
      }


    topLevelRedirect(DOMAIN."/thankyou.php?mid=$mid"); // Login screen will deal with possibility that this person is already registered (shouldn't happen);
  }
else
  {

    // ---  Failure ----

    $logtm=time();
    $logdate=formatDate($logtm);
    $logtime=formatTime($logtm);
    $logfname = LOG_DIRECTORY."/orders/online/".substr($logdate,0,7)."-failure.log";
    $fp = fopen($logfname, "a");
    $lineout  = "$logdate $logtime|$payment_method|$cc_number|$cc_expyyyy-$cc_expmm|$cc_cvv2|$member_id|$firstname|$lastname|$email|$cc_address|$cc_zip|$country|$phone|\n";
    fputs($fp,$lineout);
    fclose($fp);


    $msg  = "Online Order Failure: \n";
    $msg .= "Date-Time             = ".getDateTime()."\n";
    $msg .= "Member ID             = $member_id\n";
    $msg .= "Amount                = $amount\n";
    $msg .= "FirstName             = $firstname\n";
    $msg .= "LastName              = $lastname\n";
    $msg .= "Email                 = $email\n";
    $msg .= "\n";
    $msg .= "\n";

    $msg .= sprintf("%s\n\n",print_r($payflow_args,TRUE));
    $msg .= sprintf("%s\n\n",print_r($payflow_resp,TRUE));

    send_mail_direct("ORDER FAILED", EMAIL_TIM,  "ORDER SYSTEM", EMAIL_NOREPLY,  "Order Failed", $msg);

    $x_response_code        = $payflow_resp["RESULT"];
    $x_response_reason_text = $payflow_resp["RESPMSG"];
    include("failure.php");
  }
?>
