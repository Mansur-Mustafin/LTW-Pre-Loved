<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

echo json_encode([$session->getName(),$session->getId(),$session->isLoggedIn(),$session->isAdmin()]);
