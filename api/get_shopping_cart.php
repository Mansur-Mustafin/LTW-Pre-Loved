<?php

declare(strict_types=1);

require_once(__dir__.'/../utils/session.php');
$session = new session();

if (!$session->isloggedin()) {
  die(header('location: /'));
}

require_once(__dir__.'/../core/item.class.php');
require_once(__dir__.'/../database/connection.db.php');
require_once(__dir__.'/../database/item.db.php');

$db = getdatabaseconnection();

$items_in_cart = itemsInCart($db,$session->getid());

echo json_encode($items_in_cart);