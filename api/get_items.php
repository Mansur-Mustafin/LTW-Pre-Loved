<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();


require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/item.db.php');

$db = getDatabaseConnection();

$items = searchItems($db,$_GET['search']);
echo json_encode($items);
 
