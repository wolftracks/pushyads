<?php
include_once("pushy_common.inc");
include_once("jsonRPCClient.php");

$api_key = GET_RESPONSE_API_KEY;
$api_url = 'http://api2.getresponse.com';
$client = new jsonRPCClient($api_url);

$dateArray = getDateTodayAsArray();
$toDate    = dateArrayToString($dateArray);
$fromDate  = dateArrayToString( calStepDays(-2,$dateArray) );

printf("Dates: %s => %s\n", $fromDate,$toDate);


$result = NULL;

try {
      # call method 'get_contacts' and get result
      $conditions = Array (
          'created_on' => Array ( 'FROM' => $fromDate, 'TO' => $toDate )
      );
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
      }
  }

?>
