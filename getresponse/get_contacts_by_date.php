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

# get all contacts on account

# try {
#     # call method 'get_contacts' and get result
#     $result = $client->get_contacts($api_key);
# }
# catch (Exception $e) {
#     # check for communication and response errors
#     # implement handling if needed
#     die($e->getMessage());
# }
#
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
#         [61d66] => Array
#             (
#                 [email] => sample2@emailadddress.com
#                 [campaign] => 7CT
#                 [ip] => 1.1.1.1
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => Sample Name
#                 [origin] => www
#                 [cycle_day] => 4
#             )
#
#         [06c65] => Array
#             (
#                 [email] => sample1@emailadddress.com
#                 [campaign] => 91k
#                 [ip] => 1.1.1.1
#                 [created_on] => 2009-01-01 00:00:00
#                 [name] => Sample Name
#                 [origin] => panel
#                 [cycle_day] =>
#             )
#
#     )
#
# print_r($result);

//
//      'name'  => Array ( 'CONTAINS' => 'Sample%' ),
//      'email' => Array ( 'MATCHES'  => 'sample' ),
//


$dateToday=getDateToday();

try {
    # call method 'get_contacts' and get result
    $conditions = Array (
        'created_on' => Array ( 'AT'  => $dateToday )
    );
    $result = $client->get_contacts($api_key, $conditions);
}
catch (Exception $e) {
    # check for communication and response errors
    # implement handling if needed
    die($e->getMessage());
}


//   # display result
//   #
//   #     Array
//   #     (
//   #         [b92ef] => Array
//   #             (
//   #                 [email] => sample1@emailadddress.com
//   #                 [campaign] => 7CT
//   #                 [ip] => 1.1.1.1
//   #                 [created_on] => 2009-01-01 00:00:00
//   #                 [name] => Sample Name
//   #                 [origin] => panel
//   #                 [cycle_day] => 2
//   #             )
//   #
//   #     )
//   #

print_r($result);

?>
