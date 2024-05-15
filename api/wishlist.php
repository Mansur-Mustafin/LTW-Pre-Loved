<?php

declare(strict_types=1);

require_once(__dir__.'/../utils/session.php');
$session = new Session();


require_once(__dir__.'/../core/item.class.php');
require_once(__dir__.'/../database/connection.db.php');
require_once(__dir__.'/../database/item.db.php');

$db = getdatabaseconnection();

$items_in_wishlist = itemsInWishlist($db,$session->getid());

echo $session->isLoggedIn()
    ? json_encode($items_in_wishlist)
    : json_encode(['error'=> 'Invalid Entity']);