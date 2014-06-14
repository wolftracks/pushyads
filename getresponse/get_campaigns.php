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

# get all campaigns on account

try {
    # call method 'get_campaigns' and get result
    $result = $client->get_campaigns($api_key);
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
#         [91k] => Array
#             (
#                 [from_email] => sample@emailadddress.com
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => product2_customers
#                 [from_name] => Sample Name
#                 [reply_to_email] => sample@emailadddress.com
#             )
#
#         [9Jy] => Array
#             (
#                 [from_email] => sample@emailadddress.com
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => product1_offers
#                 [from_name] => Sample Name
#                 [reply_to_email] => sample@emailadddress.com
#             )
#
#         [GSA] => Array
#             (
#                 [from_email] => sample@emailadddress.com
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => product2_offers
#                 [from_name] => Sample Name
#                 [reply_to_email] => sample@emailadddress.com
#             )
#
#     )
#
print_r($result);

# get campaigns with name condition

try {
    # call method 'get_campaigns' and get result
    $conditions = Array(
        'name' => Array( 'MATCHES' => 'customers' )
    );
    $result = $client->get_campaigns($api_key, $conditions);
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
#     [7CT] => Array
#         (
#             [from_email] => sample@emailadddress.com
#             [created_on] => 2009-01-01 00:00:00
#             [name] => product1_customers
#             [from_name] => Sample Name
#             [reply_to_email] => sample@emailadddress.com
#         )
#
#     [91k] => Array
#         (
#             [from_email] => sample@emailadddress.com
#             [created_on] => 2009-01-01 00:00:00
#             [name] => product2_customers
#             [from_name] => Sample Name
#             [reply_to_email] => sample@emailadddress.com
#         )
#
# )
#
print_r($result);

?>