<?php
$REMOTE_ADDR    = $_SERVER["REMOTE_ADDR"];
$QUERY_STRING   = $_SERVER["QUERY_STRING"];
$REQUEST_URI    = $_SERVER["REQUEST_URI"];
$HTTP_HOST      = $_SERVER["HTTP_HOST"];

if (isset($_SERVER["HTTP_X_METHOD_OVERRIDE"]))
  {
    $REQUEST_METHOD = $_SERVER["HTTP_X_METHOD_OVERRIDE"];
    $_SERVER["REQUEST_METHOD"] = $REQUEST_METHOD;
  }
else
  $REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];

$CURRENT_DIRECTORY   = getcwd();
$THIS_FILE_DIRECTORY = dirname(__FILE__);

list($_uri_,$_qs_) = explode("?",$REQUEST_URI);
if (!isset($_qs_)) $_qs_="";

define("TRACE",FALSE);

 //-------- printf("\n\n\n%s\n\n\n",print_r($_SERVER,TRUE));

if (TRACE)
  {
    printf("<br>\n== REWRITE ==== REQUEST RECEIVED : %s ===============<br>\n", $REQUEST_URI);
  }

$PAREF = "";
$URI_ELEMENT_COUNT=0;
$_uri_tokens_ = explode("/",$_uri_);
for ($i=0; $i<count($_uri_tokens_); $i++)
  {
    if (strlen(trim($_uri_tokens_[$i]))>0)
      {
        $URI_ELEMENTS[$URI_ELEMENT_COUNT] = trim($_uri_tokens_[$i]);
        if (TRACE)
          {
            printf("<br> [%d] ... %s<br>\n",$URI_ELEMENT_COUNT,$URI_ELEMENTS[$URI_ELEMENT_COUNT]);
          }
        $URI_ELEMENT_COUNT++;
      }
  }

unset($i);
unset($_uri_);
unset($_qs_);
unset($_uri_tokens_);

$DEFAULT_TAB="";
if ($URI_ELEMENT_COUNT == 1)
  {
    $PAREF=$URI_ELEMENTS[0];
  }
else
if ($URI_ELEMENT_COUNT == 2 && $URI_ELEMENTS[0]=="tab")
  {
    $DEFAULT_TAB=$URI_ELEMENTS[1];
  }
else
if ($URI_ELEMENT_COUNT > 0)
  {
    header("HTTP/1.1 404 NOT FOUND");
    exit;
  }

if (TRACE)
  {
     printf("FILE-DIRECTORY    = %s\n", $THIS_FILE_DIRECTORY);
     printf("REMOTE_ADDR       = %s\n", $REMOTE_ADDR    );
     printf("QUERY_STRING      = %s\n", $QUERY_STRING   );
     printf("REQUEST_URI       = %s\n", $REQUEST_URI    );
     printf("HTTP_HOST         = %s\n", $HTTP_HOST      );
     printf("REQUEST_METHOD    = %s\n", $REQUEST_METHOD );
     printf("SERVICE_NAME      = %s\n", $SERVICE_NAME);
     printf("SERVICE_ROOT      = %s\n", $SERVICE_ROOT);

     printf("URI_ELEMENT_COUNT = %d\n",$URI_ELEMENT_COUNT);
     for ($i=0; $i<$URI_ELEMENT_COUNT; $i++)
       printf("... [%d]  %s\n",$i,$URI_ELEMENTS[$i]);
  }

include("main.php");
exit;
?>
