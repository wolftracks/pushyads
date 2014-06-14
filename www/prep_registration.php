<?php
include("initialize.php");
include_once("pushy_jsontools.inc");

if (isset($_REQUEST["mid"]))
 {
   $db=getPushyDatabaseConnection();

   $mid=$_REQUEST["mid"];

   $sql  = "SELECT last_submission FROM  member";
   $sql .= " WHERE confirmed=0 AND registered=0 AND member_id='$mid'";
   $result = mysql_query($sql,$db);
   if ($result && ($myrow = mysql_fetch_array($result)))
     {
       $last_submission = $myrow["last_submission"];

          // $tm = time() -  3600;  // time: 1 hours ago
          // $tm = time() -  7200;  // time: 2 hours ago
          // $tm = time() - 10800;  // time: 3 hours ago
       $tm = time() - 43200;   // time: 12 hours ago

       if ($last_submission < $tm)
         {
           $sql  = "UPDATE member set ";
           $sql .= " last_submission='".time()."'";
           $sql .= " WHERE member_id='$mid'";
           mysql_query($sql,$db);

           $response= new stdClass();
           $response->success   = "TRUE";
           $response->tm        = $tm;
           $response->last_submission = $last_submission;
           sendJSONResponse(0, $response, null);
           exit;
         }
     }
 }

$response=new stdClass();
$response->success   = "FALSE";
sendJSONResponse(299, $response, null);
exit;
?>
