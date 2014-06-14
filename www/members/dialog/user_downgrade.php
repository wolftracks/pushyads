<?php
 $RESPONSE["result"]=0;
 $RESPONSE["message"]="";

 $target_level = $_REQUEST["target_level"];

 $request_date = getDateToday();

 $target_date  = $memberRecord["next_payment_due"];
 if ($target_date=='')
   $target_date=$request_date;

 $sql  = "DELETE FROM userlevel_change_requests ";
 $sql .= " WHERE member_id = '$mid'";
 $result = mysql_query($sql,$db);

 $sql  = "INSERT into userlevel_change_requests set ";
 $sql .= " member_id    = '$mid', ";
 $sql .= " target_level = '$target_level', ";
 $sql .= " request_date = '$request_date', ";
 $sql .= " target_date  = '$target_date'";
 $result = mysql_query($sql,$db);

 //printf("SQL: %s<br>\n",$sql);
 //printf("ERR: %s<br>\n",mysql_error());

 if ($result && mysql_affected_rows()==1)
   {
     $response= new stdClass();
     $response->success     = "TRUE";
     $response->target_date = $target_date;
     sendJSONResponse(0, $response, null);
     exit;
   }

//--- Failed
$response=new stdClass();
$response->success   = "FALSE";
sendJSONResponse(201, $response, "Unable to Process Downgrade Request");
exit;
?>
