<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/filter.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');

// show items
$db = getDatabaseConnection();
$items = getAllItems($db, 20, 0, $session->getId());
$items_in_cart = itemsInCart($db, $session->getId());
$items_in_wishlist = itemsInWishlist($db, $session->getId());

// show filter
$categories = getEntitiesFromType($db,"Categories");
$brands = getEntitiesFromType($db,"Brands");
$size = getEntitiesFromType($db,"Size");
$conditions = getEntitiesFromType($db,"Condition");

drawHeader($session, 'All news');
drawFilter($categories, $brands, $size, $conditions);
drawItems($items, $session, 'Find what you want to buy!', $items_in_cart, $items_in_wishlist);
drawFooter();

?>
