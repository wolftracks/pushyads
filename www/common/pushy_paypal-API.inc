<?php

function payflow_submit($payflow_url, $tender, $inArray)
 {
   $TRACE = FALSE;

   $paypal_query_array = array(
       'USER'       => PAYFLOW_USERID,
       'VENDOR'     => PAYFLOW_VENDOR,
       'PARTNER'    => PAYFLOW_PARTNER,
       'PWD'        => PAYFLOW_PASSWORD,

       'TENDER'     => $tender,    // C - Direct Payment using credit card
       'TRXTYPE'    => 'S',        // A - Authorization, S - Sale

       'ACCT'       => $inArray["ACCT"],
       'CVV2'       => $inArray["CVV2"],
       'EXPDATE'    => $inArray["EXPDATE"],
       'AMT'        => $inArray["AMT"],
       'CURRENCY'   => 'USD',
       'INVNUM'     => $inArray["INVNUM"],

       'FIRSTNAME'  => $inArray["FIRSTNAME"],
       'LASTNAME'   => $inArray["LASTNAME"],
       'STREET'     => $inArray["STREET"],
       'CITY'       => $inArray["CITY"],
       'STATE'      => $inArray["STATE"],
       'ZIP'        => $inArray["ZIP"],
       'COUNTRY'    => $inArray["COUNTRY"],
       'PHONENUM'   => $inArray["PHONE"],
       'EMAIL'      => $inArray["EMAIL"],
       'CUSTIP'     => $inArray["CUSTIP"],
       'COMMENT1'   => PAYPAL_SEARCH_KEY,
       'COMMENT2'   => $inArray["COMMENT"],
       'VERBOSITY'  => 'MEDIUM',
   );

   // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
   // Bracketed numbers are length tags which allow you
   // to use the special characters of "&" and "=" in the value sent.
   $paypal_query = array();
   foreach ($paypal_query_array as $key => $value)
     {
       $paypal_query[]= $key.'['.strlen($value).']='.$value;
     }
   $paypal_query=implode('&', $paypal_query);

   // The $order_num field is storing our unique id that we'll use in the request id header.  By storing the id
   // in this manner, we are able to allowing reposting of the form without creating a duplicate transaction.
   $unique_id = $inArray["INVNUM"];

   // printf("%s\n","UniqueId=".$unique_id);
   // printf("%s\n","PayflowUrl=".$payflow_url);
   // flush();

   // Call the function to send data to PayPal and return the data into an Array.
   $nvpArray = fetch_data($unique_id, $payflow_url, $paypal_query);

   $nvpArray["PAYFLOW_COMMAND"] = $paypal_query;

   // printf("%s\n\n\n",print_r($nvpArray , TRUE));
   // flush();
   // exit;


   return $nvpArray;
 }



function payflow_credit($payflow_url, $transaction_id, $credit_amount="")
 {
   $TRACE = FALSE;

   if (IS_LOCAL && (FALSE))
     {
       $resp["RESULT"] = 0;
       $resp["PNREF"]  = "LOCAL-".time();
       return array("Transaction Credited Successfully", $resp);
     }

   $paypal_query_array = array(
       'USER'       => PAYFLOW_USERID,
       'VENDOR'     => PAYFLOW_VENDOR,
       'PARTNER'    => PAYFLOW_PARTNER,
       'PWD'        => PAYFLOW_PASSWORD,
       'TENDER'     => 'C',        // C - Credit Card
       'TRXTYPE'    => 'C',        // C - Credit  V=Void

       'AMT'        => $credit_amount,
       'ORIGID'     => $transaction_id
   );

   // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
   // Bracketed numbers are length tags which allow you
   // to use the special characters of "&" and "=" in the value sent.
   $paypal_query = array();
   foreach ($paypal_query_array as $key => $value)
     {
       $paypal_query[]= $key.'['.strlen($value).']='.$value;
     }
   $paypal_query=implode('&', $paypal_query);

   // printf("<PRE>%s\n</PRE>","CREDIT:");
   // printf("<PRE>%s\n</PRE>","SubmitUrl=".$submiturl);
   // printf("<PRE>%s\n</PRE>",$verisign_cmd);
   // flush();

   $unique_id = "CR-".getmicroseconds();

   // Call the function to send data to PayPal and return the data into an Array.
   $nvpArray = fetch_data($unique_id, $payflow_url, $paypal_query);

   $nvpArray["PAYFLOW_COMMAND"] = $paypal_query;

   return $nvpArray;
 }




function payflow_void($payflow_url, $transaction_id)
 {
   $TRACE = FALSE;

   if (IS_LOCAL && (FALSE))
     {
       $resp["RESULT"] = 0;
       $resp["PNREF"]  = "LOCAL-".time();
       return array("Transaction Credited Successfully", $resp);
     }

   $paypal_query_array = array(
       'USER'       => PAYFLOW_USERID,
       'VENDOR'     => PAYFLOW_VENDOR,
       'PARTNER'    => PAYFLOW_PARTNER,
       'PWD'        => PAYFLOW_PASSWORD,
       'TENDER'     => 'C',        // C - Credit Card
       'TRXTYPE'    => 'V',        // C - Credit  V=Void

       'ORIGID'     => $transaction_id
   );

   // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
   // Bracketed numbers are length tags which allow you
   // to use the special characters of "&" and "=" in the value sent.
   $paypal_query = array();
   foreach ($paypal_query_array as $key => $value)
     {
       $paypal_query[]= $key.'['.strlen($value).']='.$value;
     }
   $paypal_query=implode('&', $paypal_query);

   // printf("<PRE>%s\n</PRE>","CREDIT:");
   // printf("<PRE>%s\n</PRE>","SubmitUrl=".$submiturl);
   // printf("<PRE>%s\n</PRE>",$verisign_cmd);
   // flush();

   $unique_id = "VOID-".getmicroseconds();

   // Call the function to send data to PayPal and return the data into an Array.
   $nvpArray = fetch_data($unique_id, $payflow_url, $paypal_query);

   $nvpArray["PAYFLOW_COMMAND"] = $paypal_query;

   return $nvpArray;
 }



// API functions and error handling
function fetch_data($unique_id, $submiturl, $data) {

    // get data ready for API
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Here's your custom headers; adjust appropriately for your setup:
    $headers[] = "Content-Type: text/namevalue"; //or text/xml if using XMLPay.
    $headers[] = "Content-Length : " . strlen ($data);  // Length of data to be passed
    // Here I set the server timeout value to 45, but notice below in the cURL section, I set the timeout
    // for cURL to 90 seconds.  You want to make sure the server timeout is less, then the connection.
    $headers[] = "X-VPS-Timeout: 45";
    $headers[] = "X-VPS-REQUEST-ID: $unique_id";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $submiturl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_HEADER, 1);                // tells curl to include headers in response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        // return into a variable

    if (PAYPAL_API_DEBUG == "TRUE")
      {
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);        // we want to be able to see request headers
      }

    curl_setopt($ch, CURLOPT_TIMEOUT, 90);              // times out after 90 secs
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);        // this line makes it work under https
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);        //adding POST data
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);       //verifies ssl certificate
    curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);       //forces closure of connection when done
    curl_setopt($ch, CURLOPT_POST, 1);                                  //data sent as POST

    $i=1;
    while ($i++ <= 3) {

        $result   = curl_exec($ch);
        $headers  = curl_getinfo($ch);

        if (PAYPAL_API_DEBUG == "TRUE")
          {
            $request_headers = curl_getinfo($ch,CURLINFO_HEADER_OUT);

            printf("<PRE>\n--------- Loop: %d ----------\n</PRE>",$i-1);
            printf("<PRE>\nREQUEST  DATA RECEIVED From Caller:\n%s\n</PRE>",$data);
            printf("<PRE>\nREQUEST  HEADERS:\n%s\n</PRE>",print_r($request_headers,TRUE));
            printf("<PRE>\nRESPONSE HEADERS:\n%s\n</PRE>",print_r($headers,TRUE));
            printf("<PRE>\nRESULT:\n%s\n</PRE>",print_r($result,TRUE));
            flush();
          }

        if ($headers['http_code'] != 200) {
            sleep(5);  // Let's wait 5 seconds to see if its a temporary network issue.
        }
        else if ($headers['http_code'] == 200) {
            // we got a good response, drop out of loop.
            break;
        }
    }
    // In this example I am looking for a 200 response from the server prior to continuing with
    // processing the order.  You can use this or other methods to validate a response from the
    // server and/or timeout issues due to network.
    if ($headers['http_code'] != 200) {
        // echo '<h2>General Error!</h2>';
        // echo '<h3>Unable to receive response from PayPal server.</h3><p>';
        // echo '<h4>Verify host URL of '.$submiturl.' and check for firewall/proxy issues.</h4>';
        curl_close($ch);

        $ppResponse = array(
           "RESULT"    => -1,
           "PNREF"     => "Communication Error",
           "RESPMSG"   => "Communication Error: HTTP STATUS='".$headers['http_code']."'  No Response or HTTP Failure - Verify host URL of ".$submiturl." and check for firewall/proxy issues. "
        );

        if (PAYPAL_API_DEBUG)
          {
            $ppResponse["REQUEST-HEADERS"]  = $request_headers;
            $ppResponse["RESPONSE-HEADERS"] = $headers;
          }

        return($ppResponse);
    }
    curl_close($ch);
    $result = strstr($result, "RESULT");
    // echo $result;
    // prepare responses into array
    $proArray = array();
    while(strlen($result)){
        // name
        $keypos= strpos($result,'=');
        $keyval = substr($result,0,$keypos);
        // value
        $valuepos = strpos($result,'&') ? strpos($result,'&'): strlen($result);
        $valval = substr($result,$keypos+1,$valuepos-$keypos-1);
        // decoding the respose
        $proArray[$keyval] = $valval;
        $result = substr($result,$valuepos+1,strlen($result));
    }

    // printf("<PRE>%s\n</PRE>",print_r($proArray,TRUE));
    // exit;

    return $proArray;
}




// API functions and error handling
function run_report($unique_id, $submiturl, $data) {

    // get data ready for API
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Here's your custom headers; adjust appropriately for your setup:
    $headers[] = "Content-Type: application/xml";
    $headers[] = "Content-Length : " . strlen ($data);  // Length of data to be passed
    // Here I set the server timeout value to 45, but notice below in the cURL section, I set the timeout
    // for cURL to 90 seconds.  You want to make sure the server timeout is less, then the connection.
    $headers[] = "X-VPS-Timeout: 45";
    $headers[] = "X-VPS-REQUEST-ID: $unique_id";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $submiturl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_HEADER, 1);                // tells curl to include headers in response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        // return into a variable

    if (PAYPAL_API_DEBUG == "TRUE")
      {
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);        // we want to be able to see request headers
      }

    curl_setopt($ch, CURLOPT_TIMEOUT, 90);              // times out after 90 secs
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);        // this line makes it work under https
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);        //adding POST data
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);       //verifies ssl certificate
    curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);       //forces closure of connection when done
    curl_setopt($ch, CURLOPT_POST, 1);                                  //data sent as POST

    $i=1;
    while ($i++ <= 3) {

        $result   = curl_exec($ch);
        $headers  = curl_getinfo($ch);

        if (PAYPAL_API_DEBUG == "TRUE")
          {
            $request_headers = curl_getinfo($ch,CURLINFO_HEADER_OUT);

            printf("<PRE>\n--------- Loop: %d ----------\n</PRE>",$i-1);
            printf("<PRE>\nREQUEST  DATA RECEIVED From Caller:\n%s\n</PRE>",$data);
            printf("<PRE>\nREQUEST  HEADERS:\n%s\n</PRE>",print_r($request_headers,TRUE));
            printf("<PRE>\nRESPONSE HEADERS:\n%s\n</PRE>",print_r($headers,TRUE));
            printf("<PRE>\nRESULT:\n%s\n</PRE>",print_r($result,TRUE));
            flush();
          }

        if ($headers['http_code'] != 200) {
            sleep(5);  // Let's wait 5 seconds to see if its a temporary network issue.
        }
        else if ($headers['http_code'] == 200) {
            // we got a good response, drop out of loop.
            break;
        }
    }
    // In this example I am looking for a 200 response from the server prior to continuing with
    // processing the order.  You can use this or other methods to validate a response from the
    // server and/or timeout issues due to network.
    if ($headers['http_code'] != 200) {
        // echo '<h2>General Error!</h2>';
        // echo '<h3>Unable to receive response from PayPal server.</h3><p>';
        // echo '<h4>Verify host URL of '.$submiturl.' and check for firewall/proxy issues.</h4>';
        curl_close($ch);

        $ppResponse = array(
           "RESULT"    => -1,
           "PNREF"     => "Communication Error",
           "RESPMSG"   => "Communication Error: HTTP STATUS='".$headers['http_code']."'  No Response or HTTP Failure - Verify host URL of ".$submiturl." and check for firewall/proxy issues. "
        );

        if (PAYPAL_API_DEBUG)
          {
            $ppResponse["REQUEST-HEADERS"]  = $request_headers;
            $ppResponse["RESPONSE-HEADERS"] = $headers;
          }

        return($ppResponse);
    }
    curl_close($ch);
    $result = strstr($result, "RESULT");
    // echo $result;
    // prepare responses into array
    $proArray = array();
    while(strlen($result)){
        // name
        $keypos= strpos($result,'=');
        $keyval = substr($result,0,$keypos);
        // value
        $valuepos = strpos($result,'&') ? strpos($result,'&'): strlen($result);
        $valval = substr($result,$keypos+1,$valuepos-$keypos-1);
        // decoding the respose
        $proArray[$keyval] = $valval;
        $result = substr($result,$valuepos+1,strlen($result));
    }

    // printf("<PRE>%s\n</PRE>",print_r($proArray,TRUE));
    // exit;

    return $proArray;
}



function getPaymentMethod($cc_number)
 {
   $payment_method   = "Other";
   $cc1=substr($cc_number,0,1);
   if ($cc1 == "3")
     $payment_method = "American Express";
   else
   if ($cc1 == "4")
     $payment_method = "Visa";
   else
   if ($cc1 == "5")
     $payment_method = "MasterCard";
   else
   if ($cc1 == "6")
     $payment_method = "Discover";

   return $payment_method;
 }




function process_receipt($db, $mode, $memberRecord, $payflow_args, $payflow_resp, $paymentInfo)
 {
   global $UserLevels;
   global $CommissionRates;

   $x_resp_code        = $payflow_resp["RESULT"];
   if (isset($payflow_resp["AUTHCODE"]))
      $x_auth_code     = $payflow_resp["AUTHCODE"];
   else
      $x_auth_code     = $payflow_resp["HOSTCODE"];
   $x_avs_code         = $payflow_resp["AVSADDR"];
   $x_trans_id         = $payflow_resp["PNREF"];

   $orderType          = $paymentInfo["ORDER_TYPE"];
   $productTitle       = $paymentInfo["PRODUCT_TITLE"];
   $productDescription = $paymentInfo["PRODUCT_DESCRIPTION"];

   $respCode           = $paymentInfo["RESPCODE"];
   $orderAmount        = $paymentInfo["AMOUNT"];
   $proration          = $paymentInfo["PRORATION"];
   $paymentMethod      = $paymentInfo["METHOD"];
   $sendToAddress      = $paymentInfo["RECEIPT_EMAIL"];
   $receiptid          = $paymentInfo["RECEIPT_ID"];
   $userip             = $paymentInfo["USERIP"];
   $payflow_command    = $paymentInfo["COMMAND"];

   $isBatch=FALSE;
   if (strcasecmp($mode,"BATCH")==0)
     $isBatch=TRUE;

   if ($isBatch)
      $responseFile = MESSAGE_DIRECTORY."/order/batch-order-processed.txt";
   else
      $responseFile = MESSAGE_DIRECTORY."/order/live-order-processed.txt";

   $member_id   = $memberRecord["member_id"];
   $refid       = $memberRecord["refid"];
   $firstname   = stripslashes($memberRecord["firstname"]);
   $lastname    = stripslashes($memberRecord["lastname"]);
   $email       = stripslashes($memberRecord["email"]);
   $phone       = stripslashes($memberRecord["phone"]);
   $user_level  = $memberRecord["user_level"];
   $user_level_name = $UserLevels[$user_level];

   $tm = time();
   $ms = getmicroseconds();
   $dateToday = formatDate($tm);
   $timeNow   = formatTime($tm)." MST";
   $dateTime  = $dateToday." ".$timeNow;

   //------------------------------------------------------- DEBUG ONLY ----------------------
   if (TRUE)
     {
       if ($user_level == 0)
         {
            $msg  = "CRITICAL ERROR - Trying to Insert a RECEIPT For VIP User - mode=$mode:\n";
            $msg .= "  \n";
            $msg .= print_r($memberRecord,TRUE)."\n\n";
            $msg .= print_r($payflow_args,TRUE)."\n\n";
            $msg .= print_r($payflow_resp,TRUE)."\n\n";
            $msg .= print_r($paymentInfo, TRUE)."\n\n";
            send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);
         }
     }
   //------------------------------------------------------- DEBUG ONLY ----------------------



   //=============================================== SEND CUSTOMER NOTIFICATION and RECEIPT ===========
   if (isset($paymentInfo["SEND-RECEIPT"]) && ($paymentInfo["SEND-RECEIPT"]))
     {
        $msgData   = loadFileAsArray($responseFile);
        $fromName  = "";
        $fromEmail = "";
        $subject   = "";
        $message   = "";
        $inMessage = FALSE;
        $cnt=0;
        $result=FALSE;
        if (is_array($msgData))
          {
            $message="";
            $numLines=count($msgData);
            for ($i=0; $i<$numLines; $i++)
              {
                if ($inMessage)
                  {
                    $message .= $msgData[$i]."\n";
                  }
                else
                  {
                    if ($msgData[$i] == "MESSAGE")
                      $inMessage = TRUE;
                    else
                      {
                        list($key,$value) = split("=",$msgData[$i]);
                        if ($key == "FROMNAME")
                          $fromName=$value;
                        if ($key == "FROMEMAIL")
                          $fromEmail=$value;
                        if ($key == "SUBJECT")
                          $subject=$value;
                      }
                  }
              }


            $toEmail = $sendToAddress;
            $toName  = $firstname." ".$lastname;

            $subject = str_replace("%firstname%",     $firstname,          $subject);
            $subject = str_replace("%lastname%",      $lastname,           $subject);

            $message = str_replace("%firstname%",     $firstname,          $message);
            $message = str_replace("%lastname%",      $lastname,           $message);
            $message = str_replace("%email%",         $sendToAddress,      $message);
            $message = str_replace("%date%",          $dateToday,          $message);
            $message = str_replace("%time%",          $timeNow,            $message);
            $message = str_replace("%product%",       $productDescription, $message);
            $message = str_replace("%invoice%",       $receiptid,          $message);
            $message = str_replace("%amount%",        $orderAmount,        $message);

            //---------------------
            // OVERRIDE SENDER INFO
            //---------------------
            $fromName  = SITE_NAME;
            $fromEmail = EMAIL_NOREPLY;

            $result = send_mail_direct($toName, $toEmail, $fromName, $fromEmail, $subject, $message);
          }
     }





   //=============================================== SEND INTERNAL NOTIFICATION ======
   $subject  = SITE_NAME." ORDER : $lastname, $firstname";
   $message  = "STATUS = 0\n";

   $message .= "Date-Time             = $dateTime\n";
   $message .= "Member ID             = $member_id\n";
   $message .= "Description           = $productDescription\n";
   $message .= "User Level            = $user_level_name\n";

   $message .= "Amount                = $orderAmount\n";
   $message .= "Payment               = $paymentMethod\n";
   $message .= "FirstName             = $firstname\n";
   $message .= "LastName              = $lastname\n";
   $message .= "Phone                 = $phone\n";
   $message .= "Email                 = $email\n";
   $message .= "User IP Address       = $userip\n\n";

   $message .= "Authorization Code    = $x_auth_code\n";
   $message .= "Transaction ID        = $x_trans_id\n";
   $message .= "Receipt ID (Invoice)  = $receiptid\n\n";

   $message .= "\n";
   if (isset($payflow_command) && strlen($payflow_command) > 0)
     {
       $message .= "\n";
       $message .= "PAYFLOW RESPCODE = ".$x_resp_code."\n";
       $message .= "PAYFLOW COMMAND  = ".$payflow_command."\n";
       $message  .= "\n";
     }

   $toName  =  SITE_NAME." ORDER STATUS";
   $fromName = SITE_NAME;
   $result = send_mail_direct($toName, EMAIL_ORDERS, $fromName, EMAIL_NOREPLY, $subject, $message);



   $ref_commission_rate=0;
   $ref_commission_amount=0;
   if (strlen($refid) > 0)
     {
       if (is_array($refRecord=getMemberInfo($db,$refid)))
         {
           $ref_user_level = $refRecord["user_level"];
           $ref_commission_rate   = $CommissionRates[$ref_user_level];
           $ref_commission_amount = round(($ref_commission_rate * $orderAmount),2);
         }
     }


   //=============================================== INSERT INTO RECEIPTS  ======
   $sql  = "INSERT into receipts set";
   $sql .= " txtype        = 0,";
   if ($isBatch)                                          // 1= LIVE From Member Site  2=BATCH
     $sql .= " src           = 2,";
   else
     $sql .= " src           = 1,";
   $sql .= " receiptid     = '$receiptid',";
   $sql .= " refid         = '$refid',";

   //----------------------------------------------------------

   $sql .= " order_type             = '$orderType',";
   $sql .= " product_title          = '$productTitle',";
   $sql .= " product_description    = '$productDescription',";
   $sql .= " amount                 = $orderAmount,";
   $sql .= " is_prorated            = $proration,";

   $sql .= " ts_order               = $tm,";
   $sql .= " yymmdd                 = '$dateToday',";
   $sql .= " yymm                   = '".substr($dateToday,0,7)."',";

   $sql .= " member_id              = '$member_id',";
   $sql .= " firstname              = '".addslashes($firstname)."',";
   $sql .= " lastname               = '".addslashes($lastname)."',";
   $sql .= " user_level             = '".$user_level."',";

   $sql .= " ref_commission_amount  = '".$ref_commission_amount."',";

   $sql .= " paymentmethod          = '$paymentMethod',";
   $sql .= " authorizationcode      = '$x_auth_code',";
   $sql .= " transaction            = '$x_trans_id',";
   $sql .= " ipaddr                 = '$userip'";

   $result = mysql_query($sql,$db);

   // printf("SQL: %s<br>\n",$sql);
   // printf("ERR: %s<br>\n",mysql_error());
   //exit;

   //------------------------------------------------------- DEBUG ONLY ----------------------
   if (TRUE)
     {
       if (mysql_errno($db) != 0)
         {
            $msg  = "CRITICAL ERROR - Receipt Insert Failed - mode=$mode:\n";
            $msg .= "  \n";
            $msg .= sprintf("SQL: %s\n",$sql);
            $msg .= sprintf("ERRNO: %s\n",mysql_errno($db));
            $msg .= sprintf("ERR: %s\n",mysql_error());
            $msg .= "  \n";
            $msg .= print_r($memberRecord,TRUE)."\n\n";
            $msg .= print_r($payflow_args,TRUE)."\n\n";
            $msg .= print_r($payflow_resp,TRUE)."\n\n";
            $msg .= print_r($paymentInfo, TRUE)."\n\n";
            send_mail_direct("Tim Wolf", EMAIL_TIM, SITE_NAME, EMAIL_NOREPLY, "Error - Action Required", $msg);
         }
     }
   //------------------------------------------------------- DEBUG ONLY ----------------------

   return(0);
 }
?>
