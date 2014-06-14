<?php
require_once 'jsonRPCServer.php';
require_once 'Member.php';

$member = new Member();
jsonRPCServer::handle($member)
    or print 'no request';
?>
