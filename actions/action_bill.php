<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/transaction.db.php');
require_once(__DIR__ . '/../components/pdf.php');

$db = getDatabaseConnection();

$userId = empty($request->get('id')) ? $session->getId() : intval($request->get('id'));
$itemId = intval($request->get('item-id'));

$item = getBoughtItem($userId, $itemId);
$transaction = getTransaction($db, $itemId);

if (empty($item)) {
    throw new Exception('Item not found', 404);
}

generatePdfBill($item, $transaction);