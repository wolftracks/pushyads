<?php
include("pushy_common.inc");
include("pushy_commonsql.inc");
include("pushy.inc");
include("pushy_sendmail.inc");
include("pushy_jsontools.inc");

$DEBUG=FALSE;

if (NOT_EXCLUDE)
  {
    v_printf("Method: $REQUEST_METHOD\n\n");
    if (is_array($_REQUEST) && count($_REQUEST) > 0)
      {
        v_printf("-------- REQUEST VARS -- (Get/Post/Cookie/Files) ---\n");
        while (list($key00, $value00) = each($_REQUEST))
          {
            v_printf("%s=%s\n",$key00,$value00);
          }
        v_printf("\n\n\n");
      }
  }


$db = getPushyDatabaseConnection();

$PAGE=$_REQUEST["tp"].".php";

v_printf("\n----ADMIN EXEC:  Page(%s)\n",$PAGE);

if (file_exists($PAGE))
  {
    include_once($PAGE);
    exit;
  }

sendJSONResponse(99, NULL, "Internal Error: Page Not Found: $tp");
exit;
?>
