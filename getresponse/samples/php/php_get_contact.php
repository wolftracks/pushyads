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
    # call method 'get_contact' and get result
    $conditions = Array(
        'contact' => 'b92ef'
    );
    $result = $client->get_contact($api_key, $conditions);
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
#         [b92ef] => Array
#             (
#                 [email] => sample1@emailadddress.com
#                 [campaign] => 7CT
#                 [ip] => 1.1.1.1
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => Sample Name
#                 [origin] => panel
#                 [cycle_day] => 2
#             )
#
#     )
#
print_r($result);

?>