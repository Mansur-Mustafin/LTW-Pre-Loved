<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/transaction.db.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

$userId = $session->getId();

$items_in_cart = itemsInCart($db, $session->getId());
$items_in_wishlist = itemsInWishlist($db, $session->getId());

$items = getAllItemsFromId($db, $items_in_cart);

completeCheckout($db, $userId, $items);

header('Location: /pages/profile.php?action=transactions');
