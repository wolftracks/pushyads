<?php
 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

 $widget_key    = $_REQUEST["widget_key"];

 $sql  = "DELETE FROM widget ";
 $sql .= " WHERE member_id = '$mid'";
 $sql .= " AND   widget_key= '$widget_key'";
 $result = exec_query($sql,$db);

 //printf("SQL: %s<br>\n",$sql);
 //printf("ERR: %s<br>\n",mysql_error());

 if ($result && mysql_affected_rows()==1)
   {
     $response= new stdClass();
     $response->success   = "TRUE";
     sendJSONResponse(0, $response, null);
     exit;
   }

//--- Failed
$response=new stdClass();
$response->success   = "FALSE";
sendJSONResponse(201, $response, "Unable to Delete Widget");
exit;
?>
