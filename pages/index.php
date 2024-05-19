<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/filter.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');

// show items
$pageIndex = isset($_GET['page']) ? (int)$_GET['page'] : 0;
if($pageIndex < 0){
    $pageIndex = 0;
}

$db = getDatabaseConnection();
$items = getAllItems(11, $pageIndex * 10, $session->getId());

$has_more_pages = false;

if (sizeof($items) > 10){
    $has_more_pages = true;
    array_pop($items);
}

$items_in_cart = itemsInCart($session->getId());
$items_in_wishlist = itemsInWishlist($session->getId());

// show filter
$categories = getEntitiesFromType("Categories");
$brands = getEntitiesFromType("Brands");
$size = getEntitiesFromType("Size");
$conditions = getEntitiesFromType("Condition");

drawHeader($session, 'Buy Now!');
drawFilter($session, $categories, $brands, $size, $conditions);
drawItems($items, $session, 'Find what you want to buy!',true,$items_in_cart, $items_in_wishlist, $pageIndex, $has_more_pages, 'main');
drawFooter();

