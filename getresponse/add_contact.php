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

# add contact using required fields only

try {
    # call method 'add_contact' and get result
    $conditions = Array(
        'campaign' => '7CT',
        'name' => 'Sample Name',
        'email' => 'sample1@emailadddress.com'
    );
    $result = $client->add_contact($api_key, $conditions);
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
#         [added] => 1
#     )
#
print_r($result);

# add contact using all fields

try {
    # call method 'add_contact' and get result
    $conditions = Array(
        'campaign' => '7CT',
        'name' => 'Sample Name',
        'email' => 'sample2@emailadddress.com',
        'action' => 'update',
        'cycle_day' => '4',
        'ip' => '1.1.1.1',
        'customs' => Array(
            Array(
                'name'       => 'samle_name_1',
                'content'    => 'Sample Value 1'
            ),
            Array(
                'name'       => 'samle_name_2',
                'content'    => 'Sample Value 2'
            )
        )
    );
    $result = $client->add_contact($api_key, $conditions);
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
#         [added] => 1
#     )
#
print_r($result);

?>