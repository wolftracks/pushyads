<?php
 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

 $cc_holdername   = striplt(stripslashes($_REQUEST["cc_holdername"]));
 $cc_number       = getDigits($_REQUEST["cc_number"]);
 $cc_expmmyyyy    = striplt($_REQUEST["cc_expmmyyyy"]);
 $cc_address      = striplt(stripslashes($_REQUEST["cc_address"]));
 $cc_zip          = striplt(strtolower($_REQUEST["cc_zip"]));
 $cc_cvv2         = striplt(strtolower($_REQUEST["cc_cvv2"]));

 $sql  = "UPDATE member SET ";
 $sql .= " cc_holdername    = '".addslashes(stripslashes($cc_holdername))."', ";
 $sql .= " cc_number        = '".addslashes(stripslashes($cc_number    ))."', ";
 $sql .= " cc_expmmyyyy     = '".addslashes(stripslashes($cc_expmmyyyy ))."', ";
 $sql .= " cc_address       = '".addslashes(stripslashes($cc_address   ))."', ";
 $sql .= " cc_zip           = '".addslashes(stripslashes($cc_zip       ))."', ";
 $sql .= " cc_cvv2          = '".addslashes(stripslashes($cc_cvv2      ))."'  ";
 $sql .= " WHERE member_id  = '$mid'";
 $result = exec_query($sql,$db);

 // printf("SQL: %s<br>\n",$sql);
 // printf("ERR: %s<br>\n",mysql_error());

 if (!$result)
   {
     $response=new stdClass();
     $response->success   = "FALSE";
     sendJSONResponse(201, $response, "Payment Records Update Failed");
     //sendJSONResponse(201, $response, "SQL: $sql\n\n"."ERR:".mysql_error());
     exit;
   }

$response= new stdClass();
$response->success   = "TRUE";
sendJSONResponse(0, $response, null);
exit;
?>
