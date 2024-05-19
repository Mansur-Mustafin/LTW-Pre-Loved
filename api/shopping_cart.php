<?php

declare(strict_types=1);

require_once(__dir__.'/../utils/Session.php');
$session = new session();


require_once(__dir__.'/../core/Item.php');
require_once(__dir__.'/../database/connection.db.php');
require_once(__dir__.'/../database/item.db.php');

$db = getdatabaseconnection();

$items_in_cart = itemsInCart($session->getid());

echo $session->isLoggedIn()
    ? json_encode($items_in_cart)
    : json_encode(['error'=> 'Invalid Entity']);