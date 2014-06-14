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
    # call method 'get_contact_geoip' and get result
    $conditions = Array(
        'contact' => 'b92ef'
    );
    $result = $client->get_contact_geoip($api_key, $conditions);
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
#         [country] => Poland
#         [longitude] => 18.6667
#         [city] => Gdansk
#         [postal_code] =>
#         [latitude] => 54.35
#         [country_code] => PL
#         [region] => 82
#         [dma_code] => 0
#     )
#
print_r($result);

?>