<?php

# JSON-RPC module is required
# check 'Getting started with PHP' page in API documentation
require_once 'jsonRPCClient.php';

# your API key
# check 'How to get API key' page in API documentation
$api_key = 'ENTER_YOUR_API_KEY_HERE';

# API 2.x URL
$api_url = 'http://api2.getresponse.com';

# initialize JSON-RPC client
$client = new jsonRPCClient($api_url);

$result = NULL;

try {
    # call method 'get_campaign' and get result
    $conditions = Array(
        'campaign' => '7CT'
    );
    $result = $client->get_campaign($api_key, $conditions);
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
#         [7CT] => Array
#             (
#                 [from_email] => sample@emailadddress.com
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => product1_customers
#                 [from_name] => Sample Name
#                 [reply_to_email] => sample@emailadddress.com
#             )
#
#     )
#
print_r($result);

?>