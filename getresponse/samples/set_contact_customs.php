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
    # call method 'set_contact_customs' and get result
    $conditions = Array(
        'contact' => 'b92ef',
        'customs' => Array (
            Array (
                'name' => 'samle_name_1',
                'content' => 'New Value 1'
            ),
            Array (
                'name' => 'samle_name_2',
                'content' => null
            ),
            Array (
                'name' => 'samle_name_3',
                'content' => 'Sample Value 3'
            )
        )
    );
    $result = $client->set_contact_customs($api_key, $conditions);
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
#         [added] => 1
#         [deleted] => 1
#     )
#
print_r($result);

?>