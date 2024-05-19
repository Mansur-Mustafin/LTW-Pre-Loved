<?php

declare(strict_types=1);

require_once(__dir__.'/../utils/Session.php');
$session = new session();

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/item.db.php');

if(!$session->isLoggedIn()) {
    echo json_encode(['error'=> 'not logged in']);
    exit();
}

$db = getDatabaseConnection();

$transactions = getBoughtItems($session->getId()); 

echo json_encode($transactions);
