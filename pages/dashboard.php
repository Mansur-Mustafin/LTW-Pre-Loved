<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/dashboard.tpl.php');

$db = getDatabaseConnection();

$user = getUser($db,$session->getName());

$userItems = getItemsUser($session->getId());
$userTransactions = getBoughtItems($session->getId());
$earnings = 0;

foreach($userTransactions as $transaction) {
    $earnings += $transaction->price;
}

drawHeader($session,"Dashboard");
drawDashboard($userItems,$userTransactions,$earnings);
drawFooter();