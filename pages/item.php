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
$item_id = intval($_GET['item_id']);
$item = getItem($db, $item_id);


$in_cart = false;
$in_wishlist = false;

if($session->isLoggedIn() && $session->getId() != $item->user_id){
    $items_in_cart = itemsInCart($db, $session->getId());
    $items_in_wishlist = itemsInWishlist($db, $session->getId());

    $in_cart = in_array($item->id, $items_in_cart);
    $in_wishlist = in_array($item->id, $items_in_wishlist);
}



drawHeader($session, $item->title);
drawItemMain($item, $session, $in_cart, $in_wishlist);

drawFooter();

?>
