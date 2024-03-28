<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');

$db = getDatabaseConnection();
$items = getAllItems($db, 20, 0, $session->getId());
$items_in_cart = itemsInCart($db, $session->getId());
$items_in_wishlist = itemsInWishlist($db, $session->getId());

drawHeader($session, 'All news');
drawFilter();
drawItems($items, $session, 'Find what you want to buy!', $items_in_cart, $items_in_wishlist);
drawFooter();

?>
