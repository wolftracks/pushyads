<?php
include_once("pushy_common.inc");
include_once("pushy_commonsql.inc");
include_once("pushy.inc");

include_once("jsonRPCClient.php");

$api_key = GET_RESPONSE_API_KEY;
$api_url = 'http://api2.getresponse.com';
$client = new jsonRPCClient($api_url);

$dateArray = getDateTodayAsArray();
$toDate    = dateArrayToString($dateArray);
$fromDate  = dateArrayToString( calStepDays(-2,$dateArray) );


printf("-- GetResponse Contact Info ---------\n");
printf("Dates: %s => %s\n", $fromDate,$toDate);
printf("-------------------------------------\n");


// ------  DEFINED in pushy.inc --------
// define ("CAMPAIGN_VIP",       "73q");
// define ("CAMPAIGN_PRO_ELITE", "md1");
// -------------------------------------


set_time_limit(0);

$db=getPushyDatabaseConnection();

$result = NULL;

try {
      # call method 'get_contacts' and get result
      $conditions = Array (
          'created_on' => Array ( 'FROM' => $fromDate, 'TO' => $toDate )
      );

      // $result = $client->get_contacts($api_key); // ALL CONTACTS

      $result = $client->get_contacts($api_key, $conditions);
    }
catch (Exception $e) {
      # check for communication and response errors
      # implement handling if needed
      # die($e->getMessage());
}

if (is_array($result))
  {
    foreach($result AS $key => $data)
      {
        $email     = $data["email"];
        $campaign  = $data["campaign"];
        $date_created = "";
        $temp      = explode(" ",$data["created_on"]);
        if (is_array($temp))
          $date_created = $temp[0];
        $fullname  = $data["name"];

        printf("--------------------\n");
        printf("  Key:       %s\n",$key);
        printf("  Name:      %s\n",$fullname);
        printf("  Campaign:  %s\n",$campaign);
        printf("  Email:     %s\n",$email);
        printf("  Created:   %s\n",$date_created);

        $sql  = "UPDATE member set ";
        $sql .= " getresponse_id       = '$key',";
        $sql .= " getresponse_created  = '$date_created',";
        $sql .= " getresponse_campaign = '$campaign'";
        $sql .= " WHERE email='$email' AND (getresponse_id = '' OR getresponse_campaign != '$campaign')";
        mysql_query($sql,$db);

        // printf("SQL: %s\n",$sql);
        // printf("ERR: %s\n",mysql_error());
        // printf("ROWS: %s\n",mysql_affected_rows());
      }
  }




$sql  = "SELECT member_id,getresponse_id,email,firstname,lastname from member ";
$sql .= " WHERE (user_level = '$PUSHY_LEVEL_PRO' OR user_level = '$PUSHY_LEVEL_ELITE') ";
$sql .= " AND  getresponse_id       != ''";
$sql .= " AND  getresponse_campaign != ''";
$sql .= " AND  getresponse_campaign != '".CAMPAIGN_PRO_ELITE."'";
$res=mysql_query($sql,$db);

// printf("\n\n======================================\n");
// printf("SQL: %s\n",$sql);
// printf("ERR: %s\n",mysql_error());

if ($res && mysql_num_rows($res)>0)
  {
    while ($myrow = mysql_fetch_array($res,MYSQL_ASSOC))
      {
         $member_id      = $myrow["member_id"];
         $getresponse_id = $myrow["getresponse_id"];
         $email          = $myrow["email"];
         $firstname      = stripslashes($myrow["firstname"]);
         $lastname       = stripslashes($myrow["lastname"]);

         printf("%-24s (%-8s : %-8s)  %s\n",$firstname." ".$lastname,$member_id,$getresponse_id,$email);

         $result=NULL;
         $error=FALSE;
         //------ Move to PRO_ELITE Campaign
         try {
                $conditions = Array(
                  'contact'  => $getresponse_id,
                  'campaign' => CAMPAIGN_PRO_ELITE
                );
                $result = $client->move_contact($api_key, $conditions);
         }
         catch (Exception $e) {
             # check for communication and response errors
             # implement handling if needed
             # die($e->getMessage());
           $error=TRUE;
           printf("GetResponse Error processing Move For ID(%s) to Campaign(%s) : %s\n",$getresponse_id, CAMPAIGN_PRO_ELITE, $e->getMessage() );
         }
         if (!$error)
           {
             $sql  = "UPDATE member set ";
             $sql .= " getresponse_campaign = '".CAMPAIGN_PRO_ELITE."'";
             $sql .= " WHERE member_id='$member_id'";
             mysql_query($sql,$db);
             // printf("SQL: %s\n",$sql);
             // printf("ERR: %s\n",mysql_error());
             // printf("ROWS: %s\n",mysql_affected_rows());
           }
      }
  }
?>
