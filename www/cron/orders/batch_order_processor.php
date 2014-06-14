<?php
define("PAYPAL_API_DEBUG" , "FALSE");

require("pushy_constants.inc");

require("pushy_payflow.inc");

require("pushy_common.inc");
require("pushy_commonsql.inc");
require("pushy.inc");
require("pushy_sendmail.inc");

$batch_description = "PushyAds Monthly Membership Fee";


//-------------------
//
// exit;
//
//-------------------


$COUNT=0;
$LIMIT=0;

if ($argc > 0)
  {
    // printf("ARGC=%d ARGV=%s\n",$argc,print_r($argv,TRUE));
    if (startsWith($argv[1],"LIMIT="))
      {
        $v=striplt(substr($argv[1],6));
        if (strlen($v)>0 && isNumeric($v))
          {
            $LIMIT=(int) $v;
          }
      }
  }

$dateToday = getDateToday();
$timeNow   = getTimeNow()." MST";


printf("Batch Order Processor Starting ... $dateToday $timeNow  .... LIMIT=%d\n\n",$LIMIT);
flush();

$TOTAL_AMOUNT_PROCESSED = 0;
$TOTAL_AMOUNT_DECLINED  = 0;

set_time_limit(0);

$db=getPushyDatabaseConnection();


// ---  INITIATE LOGS For this Batch Run ------------------------------------
$logtm=time();
$logdate=formatDate($logtm);
$logtime=formatTime($logtm);


// --- initalize attempts log for today's batch run
$logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-attempts.log";
$fp = fopen($logfname, "a");
$lineout  = "------------------ Batch Run: $logdate $logtm -----------------\n";
fputs($fp,$lineout);
fclose($fp);


// --- initalize success log for today's batch run
$logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-success.log";
$fp = fopen($logfname, "a");
$lineout  = "------------------ Batch Run: $logdate $logtm -----------------\n";
fputs($fp,$lineout);
fclose($fp);



// --- initalize failure log for today's batch run
$logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-failure.log";
$fp = fopen($logfname, "a");
$lineout  = "------------------ Batch Run: $logdate $logtm -----------------\n";
fputs($fp,$lineout);
fclose($fp);
// --------------------------------------------------------------------------



//--------------------------------------------------------------------------
// Process Any User DOWNGRADE REQUESTS scheduled and log them
// Both UPGRADE and DOWNGRADE Requests always attempt to DELETE any Pending
//   Level Change Requests (i.e. are Folded (last one wins)
//--------------------------------------------------------------------------

$sql  = "SELECT * from userlevel_change_requests";
$sql .= " WHERE target_date <= '$dateToday'";             // The date is Here (or Has Passed)
$result = mysql_query($sql,$db);
// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());
if (($result) && (($changeCount=mysql_num_rows($result)) > 0))
  {
    $logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-success.log";
    $fp = fopen($logfname, "a");
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $member_id   =$myrow["member_id"];
        $target_level=$myrow["target_level"];

        $current_level_date = getDateToday();

        $sql  = "UPDATE member set ";
        if ($target_level == $PUSHY_LEVEL_VIP)
          {
            // $sql .= " last_payment_date  = '',";
            $sql .= " next_payment_due   = '',";
            $sql .= " anniversary_target = '',";
          }
        $sql .= " current_level_date = '$current_level_date',";
        $sql .= " user_level='$target_level'";
        $sql .= " WHERE member_id='$member_id'";
        $res = mysql_query($sql,$db);

           // printf("SQL: %s\n",$sql);
           // printf("ERR: %s\n",mysql_error());

        $lineout  = sprintf("$logdate $logtime: Member Downgrade to  %s:   MemberId: $member_id\n",$UserLevels[$target_level]);
        echo $lineout;
        fputs($fp,$lineout);

        $sql  = "SELECT product_id,product_name from product ";
        $sql .= " WHERE product_owner='$member_id'";
        $res  = mysql_query($sql,$db);
        //  printf("SQL: %s\n",$sql);
        //  printf("ERR: %s\n",mysql_error());
        if (($res) && (($pcount=mysql_num_rows($res)) > 0))
          {
            while ($productRecord=mysql_fetch_array($res,MYSQL_ASSOC))
              {
                $product_id   = $productRecord["product_id"];
                $product_name = stripslashes($productRecord["product_name"]);

                //--- In a DownGrade - All Ads Are Deleted and Any Products that you OWN are - Regardless of the Traget Downgrade Level (VIP,PRO)
                //---     REASSIGNED to PUSHY if there are resellers (ads referencing it)
                //---     DELETED             if there are NO resellers (ads referencing it)

                // Must deal with the Product First Before Removing any Ads
                $action=retireProduct($db,$member_id,$product_id);
                if ($action==1)
                  {
                    $lineout  = sprintf("$logdate $logtime: --- Product DELETED    for Member '$member_id':  product_id($product_id) product_name($product_name)\n");
                    echo $lineout;
                    fputs($fp,$lineout);
                  }
                else
                  {
                    $lineout  = sprintf("$logdate $logtime: --- Product REASSIGNED for Member '$member_id':  product_id($product_id) product_name($product_name)\n");
                    echo $lineout;
                    fputs($fp,$lineout);
                  }
              }
          }

        $sql = "DELETE from ads where member_id='$member_id'";
        mysql_query($sql,$db);
      }
    fclose($fp);
  }

//------- REMOVE ALL DOWNGRADE REQUESTS  --------------------------------------

$sql  = "DELETE from userlevel_change_requests";
$sql .= " WHERE target_date <= '$dateToday'";
$res = mysql_query($sql,$db);

//-----------------------------------------------------------------------------






//------------- TEST ONLY ----------------
//
// $FAIL_ON=0;          // 0=NO FORCE FAILURE
// if ($LIMIT > 0)
//   $FAIL_ON=rand(1,$LIMIT);
//
//------------- TEST ONLY ----------------




//--------------- BATCH ORDER PROCESSING -------------------------------------

$sql  = "SELECT * from member";
$sql .= " WHERE registered>0";
$sql .= " AND   user_level>0";
$sql .= " AND   system=0";
$sql .= " AND   member_disabled=0";
$sql .= " AND   (next_payment_due = ''  OR  next_payment_due <= '$dateToday')";

$memberResult = mysql_query($sql,$db);

   // printf("SQL: %s\n",$sql);
   // printf("ERR: %s\n",mysql_error());
   // printf("Rows: %d\n",mysql_num_rows($memberResult));


                //-------------------------------------------------------------------- TURNED OFF BATCH PROCESSOR


if (   (TRUE)   &&  ($memberResult) && (($rowCount=mysql_num_rows($memberResult)) > 0))


  {

    printf("BATCH PROCESSOR:   Total Records Available for Processing = %d ...\n",$rowCount);

    while ($memberRecord=mysql_fetch_array($memberResult,MYSQL_ASSOC))
      {
        $COUNT++;

        $member_id            = $memberRecord["member_id"];
        $refid                = $memberRecord["refid"];
        $user_level           = $memberRecord["user_level"];

        $firstname            = stripslashes($memberRecord["firstname"]);
        $lastname             = stripslashes($memberRecord["lastname"]);
        $fullname             = $firstname." ".$lastname;

        $city                 = stripslashes($memberRecord["city"]);
        $state                = stripslashes($memberRecord["state"]);
        $country              = stripslashes($memberRecord["country"]);
        $email                = strtolower(stripslashes($memberRecord["email"]));
        $phone                = $memberRecord["phone"];

        $cc_holdername        = stripslashes($memberRecord["cc_holdername"]);
        $cc_address           = stripslashes($memberRecord["cc_address"]);
        $cc_number            = $memberRecord["cc_number"];
        $cc_expmmyyyy         = $memberRecord["cc_expmmyyyy"];
        $cc_zip               = $memberRecord["cc_zip"];
        $cc_cvv2              = $memberRecord["cc_cvv2"];

        $cc_expmm             = (int) substr($cc_expmmyyyy,0,2);
        $cc_expyyyy           = (int) substr($cc_expmmyyyy,3);

        $last_payment_date    = $memberRecord["last_payment_date"];
        $next_payment_due     = $memberRecord["next_payment_due"];


        if (strlen($next_payment_due) != 10)
          {
            $msg  = "CRITICAL ERROR - Invalid Next Payment Due Date for Paid Subscription Level\n";
            $msg .= "  \n";
            $msg .= "Date-Time             = ".getDateTime()."\n";
            $msg .= "Member ID             = $member_id\n";
            $msg .= "FirstName             = $firstname\n";
            $msg .= "LastName              = $lastname\n";
            $msg .= "Email                 = $email\n";

            $msg .= "\nCurrent Member Level  = ".$memberRecord["user_level"]."\n";
            $msg .= "Last Payment Date     = '$last_payment_date'\n";
            $msg .= "Next Payment Due      = '$next_payment_due'\n";

            send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);

            continue;
          }

        if (strlen($cc_number) < 15 || strlen($cc_expmmyyyy) != 7)
          {
            $msg  = "CRITICAL ERROR - Invalid Credit Card or Credit Card Exiration On File for Paid Subscription Member\n";
            $msg .= "  \n";
            $msg .= "Date-Time             = ".getDateTime()."\n";
            $msg .= "Member ID             = $member_id\n";
            $msg .= "FirstName             = $firstname\n";
            $msg .= "LastName              = $lastname\n";
            $msg .= "Email                 = $email\n";

            $msg .= "\nCurrent Member Level  = ".$memberRecord["user_level"]."\n";
            $msg .= "Credit Card Number    = '$cc_number'\n";
            $msg .= "CC Expiration mm-yyyy = '$cc_expmmyyyy'\n";
            $msg .= "Last Payment Date     = '$last_payment_date'\n";
            $msg .= "Next Payment Due      = '$next_payment_due'\n";

            send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);

            continue;
          }


        $cc_number=validateCard($cc_number);



        // TESTONLY - FORCE FAILURE
        //
        // if ($FAIL_ON > 0 && ($COUNT==$FAIL_ON))
        //   {
        //     $cc_number=substr($cc_number,0,13);
        //   }


        $amount = $MonthlyFees[$user_level];
        $payment_method = getPaymentMethod($cc_number);

        $receiptid = strtolower($member_id)."-".getmicroseconds();

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
        $payflow_args["COMMENT"]   = 'Batch:'.$member_id;
        $payflow_args["CUSTIP"]    = '0.0.0.0';

                // ---  LOG ALL CC ATTEMPTS
        $logtm=time();
        $logdate=formatDate($logtm);
        $logtime=formatTime($logtm);
        $logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-attempts.log";
        $fp = fopen($logfname, "a");
        $lineout  = "$logdate $logtime|". "LAST:$last_payment_date|NEXT:$next_payment_due" ."|$payment_method|$cc_number|$cc_expyyyy-$cc_expmm|$cc_cvv2|$member_id|$firstname|$lastname|$email|$cc_address|$cc_zip|$country|$phone|\n";
        fputs($fp,$lineout);
        fclose($fp);


        $payflow_url = PAYFLOW_BILLING_HOST;        // TEST or LIVE depends on the payflow include
        $payflow_resp = payflow_submit($payflow_url, $tender, $payflow_args);

        //--------------------------------------- TESTING ONLY - When you want to comment out the actual payflow call and test everything else
        // $payflow_resp["RESULT"]  = 21;
        // $payflow_resp["AUTHCODE"]= "xxxx";
        // $payflow_resp["PNREF"]   = "zzzz";
        //------------------------------------------------------------------------------------------------------------------------------------


        if ($payflow_resp["RESULT"]==0)
          {
            $TOTAL_AMOUNT_PROCESSED += $amount;

            printf("---------- Batch Transaction Succeeded: AMOUNT(%s)  $firstname $lastname ($member_id)   Result: %s\n",$amount,$payflow_resp["RESULT"]);

            if (isset($payflow_resp["AUTHCODE"]))
               $x_auth_code  = $payflow_resp["AUTHCODE"];
            else
               $x_auth_code  = $payflow_resp["HOSTCODE"];
            $x_avs_code      = $payflow_resp["AVSADDR"];
            $x_trans_id      = $payflow_resp["PNREF"];


            //==================================================================================================================================================================

            $lastPaymentDate   = getDateTodayAsArray();


            // printf("MEMBER-RECORD: Last=%s  Next=%s  Anniv=%s\n",
            //                                       $memberRecord["last_payment_date"],
            //                                       $memberRecord["next_payment_due"],
            //                                       $memberRecord["anniversary_target"]);


                                                                                            // No Prior Anniversary - or you are coming in as NonPaid Subscription Level (VIP)
            if ($memberRecord["anniversary_target"]=="")                                    //   then Your New Anniversary TARGET is Based on Today's Date
              {
                $anniversaryTarget = $lastPaymentDate;
                // printf("(1) Anniversary Target:  %s\n",print_r($anniversaryTarget,TRUE));
              }
            else
              {
                $anniversaryTarget = dateToArray($memberRecord["anniversary_target"]);         // Otherwise, Your Anniversary Target is Advanced by One Month, but Not Altered.
                // printf("(2) Anniversary Target:  %s\n",print_r($anniversaryTarget,TRUE));
              }
                                                                                            // Advance Anniversary Target One Month
            $anniversaryTarget  = getNextAnniversaryTarget($anniversaryTarget);             // Anniversary Target represents Next Target upon which the Next Payment Due will be Based
            $nextPaymentDue     = getNextPaymentDue($anniversaryTarget);                    // Compute Next Payment Due based on the new Anniversary Target


            $last_payment_date  = dateArrayToString($lastPaymentDate);
            $next_payment_due   = dateArrayToString($nextPaymentDue);
            $anniversary_target = dateArrayToString($anniversaryTarget);



               printf("Last=%s  Next=%s  Anniv=%s\n",$last_payment_date,
                                                     $next_payment_due,
                                                     $anniversary_target);
            //
            // exit;

            //==================================================================================================================================================================



                // ---  Success ----
            $logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-success.log";
            $fp = fopen($logfname, "a");
            $lineout  = "$logdate $logtime|". "LAST:$last_payment_date|NEXT:$next_payment_due|TRN:$x_trans_id" ."|$payment_method|$cc_number|$cc_expyyyy-$cc_expmm|$cc_cvv2|$member_id|$firstname|$lastname|$email|$cc_address|$cc_zip|$country|$phone|\n";
            fputs($fp,$lineout);
            fclose($fp);

            //-------------------- MEMBER PAYMENT UPDATE --------------------------------------
            $affected_rows=0;
            $sql  = "UPDATE member set ";
            $sql .= " last_payment_date  = '$last_payment_date',";
            $sql .= " next_payment_due   = '$next_payment_due',";
            $sql .= " anniversary_target = '$anniversary_target'";
            $sql .= " WHERE member_id='$member_id'";
            $res = mysql_query($sql,$db);
            if (($res) && ($affected_rows = mysql_affected_rows())==1)
              {
                //-- OK --
              }
            else
              {
                $msg  = "CRITICAL ERROR - Unable to Update Member PAYMENT Status following Order\n";
                $msg .= "  \n";
                $msg .= "Date-Time             = ".getDateTime()."\n";
                $msg .= "Member ID             = $member_id\n";
                $msg .= "Amount                = $amount\n";
                $msg .= "FirstName             = $firstname\n";
                $msg .= "LastName              = $lastname\n";
                $msg .= "Email                 = $email\n";

                $msg .= "\nCurrent Member Level  = ".$memberRecord["user_level"]."\n";

                $msg .= sprintf("\nSQL: %s\n",$sql);
                $msg .= sprintf("ERR: %s\n",mysql_error());
                $msg .= sprintf("\nRESULT: %s\n",(($result)?"TRUE":"FALSE"));
                $msg .= sprintf("ROWS AFFECTED: %d\n",$affected_rows);

                send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);
              }
            //---------------------------------------------------------------------------------


            $paymentInfo = array(
              "ORDER_TYPE"          =>  ORDER_TYPE_RENEWAL,
              "PRODUCT_TITLE"       =>  "Membership Renewal",
              "PRODUCT_DESCRIPTION" =>  $batch_description." - ".$UserLevels[$user_level],
              "COMMAND"             =>  $payflow_resp["PAYFLOW_COMMAND"],
              "AMOUNT"              =>  $amount,
              "METHOD"              =>  $payment_method,
              "RECEIPT_ID"          =>  $receiptid,
              "RECEIPT_EMAIL"       =>  $email,
              "USERIP"              =>  "0.0.0.0",
              "PRORATION"           =>  0,
              "SEND-RECEIPT"        =>  true
            );

            $rc = process_receipt($db, "BATCH", $memberRecord, $payflow_args, $payflow_resp, $paymentInfo);

          }
        else
          {
            $x_response_code        = $payflow_resp["RESULT"];
            $x_response_reason_text = "FAILURE CODE($x_response_code) - ".$payflow_resp["RESPMSG"];

            $TOTAL_AMOUNT_DECLINED += $amount;

            printf("*-*-*-*-*- Batch Transaction Failed:    AMOUNT(%s)  $firstname $lastname ($member_id)   Result: %s\n",$amount,$payflow_resp["RESULT"]);
            print_r($payflow_args);
            print_r($payflow_resp);
            printf("\n");


                // ---  Failure ----
            $logfname = LOG_DIRECTORY."/orders/batch/".substr($logdate,0,7)."-failure.log";
            $fp = fopen($logfname, "a");
            $lineout  = "$logdate $logtime|". "LAST:$last_payment_date|NEXT:$next_payment_due" ."|$payment_method|$cc_number|$cc_expyyyy-$cc_expmm|$cc_cvv2|$member_id|$firstname|$lastname|$email|$cc_address|$cc_zip|$country|$phone|\n";
            fputs($fp,$lineout);
            fclose($fp);


            $paymentDue = dateToArray($memberRecord["next_payment_due"]);
            $days = dateDifference($paymentDue, getDateTodayAsArray());

            if ($days >= $PUSHY_GRACE_PERIOD)
              {
                //-------------------- MEMBER DOWNGRADE NOTICE -----------------------------------
                $affected_rows=0;
                $sql  = "UPDATE member set ";
                $sql .= " next_payment_due  = '', user_level=0";
                $sql .= " WHERE member_id='$member_id'";
                $res = mysql_query($sql,$db);
                if (($res) && ($affected_rows = mysql_affected_rows())==1)
                  {
                    $msg  = "Member Downgraded to VIP due to Failure To Make Payment within Grace Period: ".$PUSHY_GRACE_PERIOD." days.\n";
                    $msg .= "  \n";
                    $msg .= "Payment Due Date: ".$memberRecord["next_payment_due"]."\n";
                    $msg .= "Elapsed Days:     ".$days."\n";
                    $msg .= "  \n";
                    $msg .= "Date-Time             = ".getDateTime()."\n";
                    $msg .= "Member ID             = $member_id\n";
                    $msg .= "Amount                = $amount\n";
                    $msg .= "FirstName             = $firstname\n";
                    $msg .= "LastName              = $lastname\n";
                    $msg .= "Email                 = $email\n";

                    $msg .= "\nPrevious Member Level  = ".$memberRecord["user_level"]."\n";

                    $msg .= sprintf("\nSQL: %s\n",$sql);
                    $msg .= sprintf("ERR: %s\n",mysql_error());
                    $msg .= sprintf("\nRESULT: %s\n",(($result)?"TRUE":"FALSE"));
                    $msg .= sprintf("ROWS AFFECTED: %d\n",$affected_rows);

                    send_mail_direct("Tim Wolf", EMAIL_TEAM, SITE_NAME, EMAIL_NOREPLY, "Member Downgraded to VIP for Non-Payment", $msg);
                  }
                else
                  {
                    $msg  = "CRITICAL ERROR - Unable to DownGrade Member Due to NonPayment\n";
                    $msg .= "  \n";
                    $msg .= "Date-Time             = ".getDateTime()."\n";
                    $msg .= "Member ID             = $member_id\n";
                    $msg .= "FirstName             = $firstname\n";
                    $msg .= "LastName              = $lastname\n";
                    $msg .= "Email                 = $email\n";

                    $msg .= "\nPrevious Member Level  = ".$memberRecord["user_level"]."\n";
                    $msg .= "\nCurrent Member Level  = ".$memberRecord["user_level"]."\n";

                    $msg .= sprintf("\nSQL: %s\n",$sql);
                    $msg .= sprintf("ERR: %s\n",mysql_error());
                    $msg .= sprintf("\nRESULT: %s\n",(($result)?"TRUE":"FALSE"));
                    $msg .= sprintf("ROWS AFFECTED: %d\n",$affected_rows);

                    send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);
                  }
                //---------------------------------------------------------------------------------
              }
            else
              {
                 $anniversaryTarget = dateToArray($memberRecord["anniversary_target"]);
                 $lastAttempt       = calStepDays(5,$anniversaryTarget);

                 $lastAttemptMonth  = $lastAttempt["month"];
                 $lastAttemptMonthName = $month_names[$lastAttemptMonth - 1];
                 $lastAttemptDay    = $lastAttempt["day"];
                 $lastAttemptYear   = $lastAttempt["year"];

                 $anniversaryMonth  = $anniversaryTarget["month"];
                 $anniversaryMonthName = $month_names[$anniversaryMonth - 1];
                 $anniversaryDay    = $anniversaryTarget["day"];
                 $anniversaryYear   = $anniversaryTarget["year"];

                 $text_date_anniversary  = sprintf("%s %d, %s",$anniversaryMonthName,$anniversaryDay,$anniversaryYear);
                 $text_date_last_attempt = sprintf("%s %d, %s",$lastAttemptMonthName,$lastAttemptDay,$lastAttemptYear);

                 $messageFile = MESSAGE_DIRECTORY."/order/batch-order-failed.txt";

                 $ccard = $cc_number;

                 $s1=strrev($ccard);
                 $len=strlen($s1);
                 $s2="";
                 $j=0;
                 for ($i=0; $i<$len; $i++)
                   {
                     $ch=substr($s1,$i,1);
                     if ($ch >= "0" && $ch <= "9")
                       {
                          if ($j>=4)
                            $s2 .= "x";
                          else
                            {
                              $s2 .= $ch;
                            }
                          $j++;
                       }
                     else
                       $s2 .= $ch;
                   }

                 $ccard=strrev($s2);

                 $ccexp = $cc_expmm."/".$cc_expyyyy;

                 $vars=array();
                 $vars["date"]         = getDateToday();
                 $vars["time"]         = getTimeNow();
                 $vars["firstname"]    = $firstname;
                 $vars["email"]        = $email;
                 $vars["product"]      = $batch_description." - ".$UserLevels[$user_level];
                 $vars["ccard"]        = $ccard;
                 $vars["ccexp"]        = $ccexp;
                 $vars["reason"]       = $x_response_reason_text;
                 $vars["amount"]       = $amount;
                 $vars["due_date"]     = $text_date_anniversary;
                 $vars["last_attempt"] = $text_date_last_attempt;

                 sendMessageFile($messageFile, $fullname, $email, $vars);

                 sendMessageFile($messageFile, $fullname, EMAIL_TEAM, $vars);

              }
          }

        if ($LIMIT>0 && ($COUNT==$LIMIT))
          {
            break;
          }
      }
  }



$dateToday = getDateToday();
$timeNow   = getTimeNow()." MST";
printf("\n\nBatch Order Processor Stopping ... $dateToday $timeNow  .... PROCESSED=%d\n\n",$COUNT);
printf("    Total Amount Processed:  %s\n",$TOTAL_AMOUNT_PROCESSED);
printf("    Total Amount Declined:   %s\n",$TOTAL_AMOUNT_DECLINED);
flush();
?>
