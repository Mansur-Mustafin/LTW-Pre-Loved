<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../components/pdf.php');

$db = getDatabaseConnection();

$userId = isset($_GET['id']) == null ? $session->getId() : intval($_GET['id']);

$item = getBoughtItem($db, $userId, intval($_GET['item-id']));

if (empty($item)) {
    throw new Exception('Item not found', 404);
}

generatePdfBill($item);