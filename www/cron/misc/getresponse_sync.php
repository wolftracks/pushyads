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
$fromDate  = dateArrayToString( calStepDays(-20,$dateArray) );


printf("-- GetResponse Contact Info ---------\n");
printf("Dates: %s => %s\n", $fromDate,$toDate);
printf("-------------------------------------\n\n");

// ------  DEFINED in pushy.inc --------
// define ("CAMPAIGN_VIP",       "73q");
// define ("CAMPAIGN_PRO_ELITE", "md1");
// -------------------------------------


set_time_limit(0);

$db = getPushyDatabaseConnection();


$unconfirmed_members = array();

//-----------------------------------------------------------------------
$sql  = "SELECT firstname,lastname,member_id,email,record_created from member ";
$sql .= " WHERE confirmed=0";
$sql .= " AND   record_created >= '$fromDate'";
$sql .= " AND   record_created <= '$toDate' ";
$sql .= " ORDER BY record_created";
$result = mysql_query($sql,$db);
//------------------------------------------------------------------------
if (($result) && (($pcount=mysql_num_rows($result)) > 0))
  {
    while ($myrow=mysql_fetch_array($result,MYSQL_ASSOC))
      {
        $record_created = $myrow["record_created"];
        $member_id      = $myrow["member_id"];
        $email          = strtolower($myrow["email"]);
        $firstname      = stripslashes($myrow["firstname"]);
        $lastname       = stripslashes($myrow["lastname"]);
        $fullname       = getMemberFullName($myrow);

        $member = array(
                    "record_created"  => $record_created,
                    "email"           => $email,
                    "fullname"        => $fullname
                  );

        $unconfirmed_members[$member_id] = $member;
      }
  }











$result = NULL;

try {
      # call method 'get_contacts' and get result
      $conditions = Array (
          'origin'     => 'www',
          'created_on' => Array ( 'FROM' => $fromDate, 'TO' => $toDate )
      );

      // $result = $client->get_contacts($api_key); // ALL CONTACTS

      $result = $client->get_contacts($api_key, $conditions);
    }
catch (Exception $e) {
      # check for communication and response errors
      # implement handling if needed
      die($e->getMessage());
}



$contacts = array();

if (is_array($result))
  {
    printf("Contacts = %d\n\n\n",count($result));
    $inx=0;

    foreach($result AS $key => $data)
      {
        $email = strtolower($data["email"]);
        $data["email"] = $email;
        $data["getresponse_id"] = $key;
        $contacts[$email]=$data;

        $inx++;
        printf(" %d)  KEY=%s  DATA=%s\n",$inx,$key,print_r($data,true));


        //------------------------------------

        $campaign  = $data["campaign"];

        //-- We can do this conditionally or unconditionally
        //-- Just a minor Optimization Based on the Following Business Rule:
        //-------------
        //-- Current thinking is to move people into PRO_ELITE Campaign
        //-- But Never Move tham back to VIP (if they DownGrade)
        //-- Code Below works in either direction
        //-------------

        if ($campaign != CAMPAIGN_PRO_ELITE)         //--- Condition is completely optional
          {
             $email     = $data["email"];
             $date_created = "";
             $temp      = explode(" ",$data["created_on"]);
             if (is_array($temp))
               $date_created = $temp[0];
             $fullname  = $data["name"];

             // printf("--------------------\n");
             // printf("  Key:       %s\n",$key);
             // printf("  Name:      %s\n",$fullname);
             // printf("  Campaign:  %s\n",$campaign);
             // printf("  Email:     %s\n",$email);
             // printf("  Created:   %s\n",$date_created);

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
  }




$sql  = "SELECT member_id,getresponse_id,getresponse_campaign,email,firstname,lastname,user_level from member ";
$sql .= " WHERE (user_level = '$PUSHY_LEVEL_PRO' OR user_level = '$PUSHY_LEVEL_ELITE') ";
$sql .= " AND  getresponse_id       != ''";
$sql .= " AND  getresponse_campaign != ''";
$sql .= " AND  getresponse_campaign != '".CAMPAIGN_PRO_ELITE."'";
$res=mysql_query($sql,$db);

printf("\n\n======================================\n");
printf("SQL: %s\n",$sql);
printf("ERR: %s\n",mysql_error());
printf("ROWS: %d\n",mysql_num_rows($res));

if ($res && mysql_num_rows($res)>0)
  {
    printf("\n\n---- Moved to PRO_ELITE Campaign ----------\n");
    while ($myrow = mysql_fetch_array($res,MYSQL_ASSOC))
      {
         $member_id      = $myrow["member_id"];
         $getresponse_id = $myrow["getresponse_id"];
         $email          = $myrow["email"];
         $firstname      = stripslashes($myrow["firstname"]);
         $lastname       = stripslashes($myrow["lastname"]);
         $user_level     = $myrow["user_level"];


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
           // $error=TRUE;
           printf("GetResponse ERROR: Member(%s) %s %s  - %s\n",$member_id, $firstname, $lastname, $UserLevels[$user_level]);
           printf("   => Error processing Move For ID(%s) to Campaign(%s) : %s\n",$getresponse_id, CAMPAIGN_PRO_ELITE, $e->getMessage() );
           if (is_integer( strpos($e->getMessage(), "already exists")))
             {
               $error=FALSE;
               printf("   => Resolving Error - Update Locally\n");
             }
         }
         if (!$error)
           {
             $sql  = "UPDATE member set ";
             $sql .= " getresponse_campaign = '".CAMPAIGN_PRO_ELITE."'";
             $sql .= " WHERE member_id='$member_id'";
             mysql_query($sql,$db);
             printf("SQL: %s\n",$sql);
             printf("ERR: %s\n",mysql_error());
             printf("ROWS: %s\n",mysql_affected_rows());
           }
      }
  }


printf("\n\n--- Processing Unconfirmed Signups ---\n\n");

foreach($unconfirmed_members AS $member_id => $member_info)
  {
    $email=$member_info["email"];
    if (!isset($contacts[$email]))
      {
         $record_created = $member_info["record_created"];
         $fullname       = $member_info["fullname"];

         $cdata   = $contacts[$email];
         printf("*** Unconfirmed - Not In GetResponse:  %s   %-8s  %s    %s\n",$record_created,$member_id,$email,$fullname);
      }
  }


printf("\n\n");
foreach($unconfirmed_members AS $member_id => $member_info)
  {
    $email=$member_info["email"];
    if (isset($contacts[$email]))
      {
         $record_created = $member_info["record_created"];
         $fullname       = $member_info["fullname"];

         $cdata   = $contacts[$email];
         printf("*** Shows Confirmed In GetResponse:  %s   %-8s  %s    %s\n",$record_created,$member_id,$email,$fullname);

         $sql = "UPDATE member set ";
         $sql .= " confirmed='".time()."'";
         $sql .= " WHERE member_id='$member_id'";
         $result = mysql_query($sql,$db);
         if ($result)
           {
             printf("    --- Member Has Been Confirmed in PushyAds Database\n");
           }
      }
  }

?>
