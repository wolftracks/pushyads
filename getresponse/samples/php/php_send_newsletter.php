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
    # call method 'send_newsletter' and get result
    $conditions = Array(
        'campaign' => '7CT',
        'subject' => 'Sample subject',
        'contents' => Array (
            'plain' => 'Sample plain content',
            'html'  => 'Sample html content'
        ),
        'flags' => Array ( 'clicktrack', 'openrate' ),
        'contacts' => Array( 'b92ef', '61d66', '06c65' )
    );
    $result = $client->send_newsletter($api_key, $conditions);
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