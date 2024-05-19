<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');

$db = getDatabaseConnection();

$user = getUser($db, $session->getName() ?? '');    // TODO search by id?
if(!$user || !$user->admin_flag) {
    echo json_encode(['error'=>'not Admin User']);
} else {
    echo json_encode([$session->getName(),$session->getId(),$session->isLoggedIn(),$session->isAdmin()]);
}
