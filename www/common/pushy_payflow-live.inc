<?php

define("PAYFLOW_TEST_MODE" , "FALSE");     // Determines Which PayPal Host we are connecting to and whather "validateCard" will return TEST or ORIGINAL Card #


if ("".PAYPAL_API_DEBUG == "PAYPAL_API_DEBUG")
  {
                                           // THIS IS THE DEFAULT if Not Defined Previous to Including this File
                                           // DOES NOT EFFECT BEHAVIOR --- CONSOLE OUTPUT OPTIONS ONLY
    define("PAYPAL_API_DEBUG" , "FALSE");
  }

define("AMEX_TEST_CARD",        "378282246310005");
define("MASTERCARD_TEST_CARD",  "5555555555554444");
define("VISA_TEST_CARD",        "4111111111111111");


// ------- TEST CARD NUMBERS -------
// * American Express            378282246310005
//   American Express            371449635398431
//   American Express Corporate  378734493671000
//   Diners Club                 30569309025904
//   Diners Club                 38520000023237
//   JCB                         3530111333300000
//   JCB                         3566002020360505
// * MasterCard                  5555555555554444
//   MasterCard                  5105105105105100
// * Visa                        4111111111111111
//   Visa                        4111111111111112 ??
//   Visa                        4012888888881881


include_once("pushy_constants.inc");

define("MANUAL_ENTRY_KEY"  , "998468388561106");

if (PAYFLOW_TEST_MODE == "TRUE")
  {
    //----- TESTING
    define("PAYFLOW_BILLING_HOST"         , "https://pilot-payflowpro.paypal.com");
    define("PAYFLOW_REPORTS_DOMAIN"       , "payments-reports.paypal.com");
    define("PAYFLOW_REPORTS_APPLICATION"  , "/test-reportingengine");
    define("PAYFLOW_REPORTS_HOST"         , "https://".PAYFLOW_REPORTS_DOMAIN."/".PAYFLOW_REPORTS_APPLICATION);
  }
else
  {
    //----- PRODUCTION
    define("PAYFLOW_BILLING_HOST"         , "https://payflowpro.paypal.com");
    define("PAYFLOW_REPORTS_DOMAIN"       , "payments-reports.paypal.com");
    define("PAYFLOW_REPORTS_APPLICATION"  , "/reportingengine");
    define("PAYFLOW_REPORTS_HOST"         , "https://".PAYFLOW_REPORTS_DOMAIN."/".PAYFLOW_REPORTS_APPLICATION);
  }

define("PAYFLOW_PORT"     , 443);
define("PAYFLOW_VENDOR"   , "AutoProspector"); // or balank ??
define("PAYFLOW_PARTNER"  , "paypal");

define("PAYFLOW_USERID"   , "AutoProspector");
define("PAYFLOW_PASSWORD" , "dragon6002");

define("PAYFLOW_TIMEOUT"  , "30");

function validateCard($ccno)
  {
    if (PAYFLOW_TEST_MODE == "TRUE")
      {
        return(MASTERCARD_TEST_CARD);
      }
    return $ccno;
  }


//=========== API =========================

include_once("pushy_paypal-API.inc");

//=========================================
?>
