<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');

$db = getDatabaseConnection();

$items_in_cart = itemsInCart($session->getId());
$items_in_wishlist = itemsInWishlist($session->getId());
$isCart = true;

$items = getAllItemsFromId($items_in_cart);
$itemsGroups = groupByUser($items);

drawHeader($session, 'Shopping Cart ' . $session->getName());

include __DIR__ . '/../templates/total-cart.tpl.php';

drawItemsGroups($itemsGroups, $session, 'Time to buy!',true, $items_in_cart, $items_in_wishlist, place: 'shopcard');
drawFooter();

