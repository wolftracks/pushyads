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
    # call method 'get_message ' and get result
    $conditions = Array(
        'message' => 'BNJ9'
    );
    $result = $client->get_message ($api_key, $conditions);
}
catch (Exception $e) {
    # check for communication and response errors
    # implement handling if needed
    die($e->getMessage());
}

# display result
#
# Array
# (
#         [BNJ9] => Array
#             (
#                 [flags] => Array
#                     (
#                         [0] => clicktrack
#                         [1] => openrate
#                         [2] => google_analytics
#                     )
#
#                 [campaign] => 7CT
#                 [subject] => Sample Subject offer_1
#                 [created_on] => 2009-01-01 00:00:00
#                 [type] => newsletter
#                 [send_on] => 2009-01-01 00:00:00
#             )
#
# )
#
print_r($result);

?>