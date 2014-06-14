<?php
 if (file_exists("c:\\windows") || file_exists("c:\\winnt"))
   {
     define("IS_LOCAL",  TRUE);
     define("SECURE_SERVER",           "http://pushy_secure");
     define("PUSHY_DOCROOT",            "/xampp/pushyads/www");

     define("LOCALHOST",               "pushyads.local");
     define("PUSHYWIDGETS",            "http://pushywidgets.local");

     define("CAPTCHA_PUBLIC_KEY",      "6LdF6wcAAAAAABqRyPFqjXNCZEXlV8hY9E3C__lC");
     define("CAPTCHA_PRIVATE_KEY",     "6LdF6wcAAAAAADkjdZYg5BMt-S7wEPNp_X4LbBrW");
   }
 else
   {
     define("IS_LOCAL",  FALSE);
     define("SECURE_SERVER",           "https://pushyads.securesites.net");
     define("PUSHY_DOCROOT",            "/home/pushyads/www");

     define("LOCALHOST",               "pushyads.com");
     define("PUSHYWIDGETS",            "http://pushywidgets.com");

     define("CAPTCHA_PUBLIC_KEY",      "6Lc_6wcAAAAAAKVG8iIlAu_t0m5-dH_STVkHm79M");
     define("CAPTCHA_PRIVATE_KEY",     "6Lc_6wcAAAAAAH3w2dq3nld9JDpmj5RSnOxhtvKk");
   }


 define("DOMAIN",  "http://".LOCALHOST);

 define("MAIL_SERVICE_HOST",       LOCALHOST);
 define("WEBSERVICE_DOMAIN",       LOCALHOST);
 define("AFFILIATE_DOMAIN",        LOCALHOST);
 define("REMOTE_SENDMAIL_HOST",    LOCALHOST);
 define("STATIC_FILE_SERVER",      LOCALHOST);

 define("SITE_NAME",  "PushyAds.com");


 define("GET_RESPONSE_API_KEY",  "1ad0fb75eed24b8b13d562009fed5923");
 define("GET_RESPONSE_API_URL",  "http://api2.getresponse.com");

 // $MAIN_PROGRAM_DIRECTORY = getcwd();   // probably not useful - I don't know who's including me
 // $THIS_FILE_DIRECTORY    = dirname(__FILE__);

 define("PRODUCT_IMAGE_DIRECTORY", "product_images");

 define("LOG_DIRECTORY",      PUSHY_DOCROOT."/logs");
 define("MESSAGE_DIRECTORY",  PUSHY_DOCROOT."/messages");
 define("AWARDS_DIRECTORY",   PUSHY_DOCROOT."/members/awards");

 define("SENDMAIL_SERVICE_URI",   "/webservices/sendmail_service.php");
 define("POSTOFFICE_SERVICE_URI", "/webservices/mailserver.php");

 define("REMOTE_SENDMAIL_URI",    "/remote_mail/mailer.php");

//================================ EMAIL ADDRESSES ==================================
 define("EMAIL_NOREPLY",          "noreply@pushyads.com");
 define("EMAIL_MARK",             "mark@pushyads.com");
 define("EMAIL_JILL",             "jill@pushyads.com");
 define("EMAIL_TIM",              "tim@pushyads.com");
 define("EMAIL_TEAM",             "t_e_a_m@pushyads.com");
 define("EMAIL_ORDERS",           "orders@pushyads.com");
 define("EMAIL_ACCOUNTANT",       "accountant@pushyads.com");

 //===================================================================================
 $REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
 $QUERY_STRING   = $_SERVER["QUERY_STRING"];
 $REQUEST_URI    = $_SERVER["REQUEST_URI"];
 $HTTP_HOST      = $_SERVER["HTTP_HOST"];
 $REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
 $DOCUMENT_ROOT  = $_SERVER["DOCUMENT_ROOT"];
 //===================================================================================
?>
