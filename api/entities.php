<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    echo json_encode(['error'=> 'Permission denied']);
    die();
}

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/filter.db.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__ .'/../utils/validation.php');

$db = getDatabaseConnection();

$user = getUser($db,$session->getName());

echo is_valid_entity($_GET['search']) 
    ? json_encode(getEntitiesFromType($_GET['search']))
    : json_encode(['error'=> 'Invalid Entity']);
