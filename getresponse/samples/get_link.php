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

try {
    # call method 'get_link' and get result
    $conditions = Array(
        'link' => '5e14'
    );
    $result = $client->get_link($api_key, $conditions);
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
#     )
#
print_r($result);

?>