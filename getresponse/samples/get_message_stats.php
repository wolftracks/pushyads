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
    # call method 'get_message_stats' and get result
    $conditions = Array(
        'message' => 'BNJ9'
    );
    $result = $client->get_message_stats($api_key, $conditions);
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
#         [2009-01-01] => Array
#             (
#                 [bounces_block_timeout] => 0
#                 [bounces_mailbox_full] => 96
#                 [complaints_unhandled] => 0
#                 [bounces_other_soft] => 24
#                 [opened] => 7442
#                 [bounces_user_unknown] => 12
#                 [sent] => 18274
#                 [bounces_block_content] => 22
#                 [complaints_handled] => 6
#                 [bounces_block_other] => 4
#                 [clicked] => 4923
#                 [bounces_other_hard] => 7
#             )
#
#         [2009-01-02] => Array
#             (
#                 [bounces_block_timeout] => 11
#                 [bounces_mailbox_full] => 2
#                 [complaints_unhandled] => 0
#                 [bounces_other_soft] => 4
#                 [opened] => 2177
#                 [bounces_user_unknown] => 0
#                 [sent] => 0
#                 [bounces_block_content] => 0
#                 [complaints_handled] => 2
#                 [bounces_block_other] => 1
#                 [clicked] => 1421
#                 [bounces_other_hard] => 1
#             )
#
#     )
#
print_r($result);

?>