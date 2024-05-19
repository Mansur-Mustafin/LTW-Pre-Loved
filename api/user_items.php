<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();


require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../database/item.db.php');

$db = getDatabaseConnection();

$user = getUser($db, $session->getName());    // TODO search by id?
$items = getItemsUser($user->id);

echo json_encode($items);

