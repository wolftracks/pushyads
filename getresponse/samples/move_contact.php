<?php   include("pushy_common.inc");

# JSON-RPC module is required
# check 'Getting started with PHP' page in API documentation
require_once 'jsonRPCClient.php';

# your API key
# check 'How to get API key' page in API documentation
$api_key = GET_RESPONSE_API_KEY;

# API 2.x URL
$api_url = 'http://api2.getresponse.com';

# initialize JSON-RPC client
$client = new jsonRPCClient($api_url);

$result = NULL;


/*---------------
 --- Campaigns ----

  Array
  (
      [73q] => Array
          (
              [from_email] => noreply@pushyads.com
              [created_on] => 2009-08-22 22:12:56
              [name] => pushy_ads
              [from_name] => PUSHY!
              [reply_to_email] => noreply@pushyads.com
          )

  )
  Array
  (
      [md1] => Array
          (
              [from_email] => noreply@pushyads.com
              [created_on] => 2009-11-18 13:14:03
              [name] => pro_elite_members
              [from_name] => PUSHY!
              [reply_to_email] => noreply@pushyads.com
          )

  )



 --- Contacts ----

    [pseFx] => Array
        (
            [email] => wolfy@webtribune.com
            [campaign] => 73q
            [ip] => 71.70.248.120
            [created_on] => 2009-09-17 21:19:18
            [name] => Kathy Wolf
            [origin] => www
            [cycle_day] =>
        )

    [psJ1O] => Array
        (
            [email] => ditto@webtribune.com
            [campaign] => 73q
            [ip] => 71.70.248.120
            [created_on] => 2009-08-25 13:49:08
            [name] => Tim Wolf
            [origin] => www
            [cycle_day] =>
        )

-----------------*/

try {
    # call method 'move_contact' and get result
    $conditions = Array(
        'contact'  => 'pseFx',
        'campaign' => 'md1'
    );
    $result = $client->move_contact($api_key, $conditions);
}
catch (Exception $e) {
    # check for communication and response errors
    # implement handling if needed
    die($e->getMessage());
}

# display result
#
#     Array
#     (
#         [updated] => 1
#     )
#
print_r($result);

?>
