<?php
require_once('JSON.php');

function sendJSONResponse($status,$data,$message="")
 {
    $response = array(
                       "status"   =>  $status,
                       "data"     =>  $data,
                       "message"  =>  $message
                     );

    $json   = new services_JSON();
    $result = $json->encode($response);

    header("Content-Type: text/json");
    echo $result;
 }
?>
