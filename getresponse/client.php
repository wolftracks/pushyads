<?php
require_once 'jsonRPCClient.php';
require_once 'Member.php';

$member = new jsonRPCClient('http://test/getresponse/server.php');
// $member = new Member();

$memberInput = array("member" =>"tjw998468");

try {
    echo $member->getFirstName($memberInput);
} catch (Exception $e) {
    echo nl2br($e->getMessage()).'<br />'."\n";
}


?>
