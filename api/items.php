<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/item.db.php');

$db = getDatabaseConnection();

$items = searchItems($_GET['search']);

echo json_encode($items);
