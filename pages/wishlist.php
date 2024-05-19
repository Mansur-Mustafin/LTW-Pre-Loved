<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../templates/wishlist.tpl.php');

$db = getDatabaseConnection();

$items_in_cart = itemsInCart($session->getId());
$items_in_wishlist = itemsInWishlist($session->getId());

$items = getAllItemsFromId($items_in_wishlist);

drawHeader($session, 'Wishlist');
drawWishListForm($session);
drawItems($items, $session, 'Your Wishlist!', true, $items_in_cart, $items_in_wishlist, place: 'wishlist');
drawFooter();

