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

# get all links on account

try {
    # call method 'get_links' and get result
    $result = $client->get_links($api_key);
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
#         [5e14] => Array
#             (
#                 [clicks] => 6000
#                 [name] => My home page top link
#                 [url] => http://sample.web.page.com
#                 [message] => BNJ9
#             )
#
#         [5e16] => Array
#             (
#                 [clicks] => 300
#                 [name] => My home page bottom link
#                 [url] => http://sample.web.page.com
#                 [message] => BNJ9
#             )
#
#         [5e1T] => Array
#             (
#                 [clicks] => 44
#                 [name] => http://sample.web.page.com/product1
#                 [url] => http://sample.web.page.com/product1
#                 [message] => BNJ9
#             )
#
#         [5eAV] => Array
#             (
#                 [clicks] => 4
#                 [name] => My home page
#                 [url] => http://sample.web.page.com
#                 [message] => BNJm
#             )
#
#     )
#
print_r($result);

# get links with messages condition

try {
    # call method 'get_links' and get result
    $conditions = Array(
        'messages' => Array( 'BNJM' )
    );
    $result = $client->get_links($api_key, $conditions);
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
#         [5eAV] => Array
#             (
#                 [clicks] => 4
#                 [name] => My home page
#                 [url] => http://sample.web.page.com
#                 [message] => BNJm
#             )
#
#     )
#
print_r($result);

?>