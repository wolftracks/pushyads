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
    # call method 'get_account_blacklist' and get result
    $result = $client->get_account_blacklist($api_key);
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
#         [@bad-domain.com] => 2009-01-01 00:00:00
#         [bad1@emailadddress.com] => 2009-01-01 00:00:00
#         [bad2@emailadddress.com] => 2009-01-01 00:00:00
#     )
#
print_r($result);

?>