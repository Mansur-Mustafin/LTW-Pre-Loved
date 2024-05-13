<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  die(header('Location: /'));
}

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/item.db.php');

$db = getDatabaseConnection();

if($_GET['entity'] == 'category') {
    $items = filterItemsbyCategory($db,$_GET['filter']);
    echo json_encode($items);
} else if($_GET['entity'] == 'brand') {
    $items = filterItemsbyBrand($db,$_GET['filter']);
    echo json_encode($items);
} else if($_GET['entity'] == 'size') {
    $items = filterItemsbySize($db,$_GET['filter']);
    echo json_encode($items);
} else if($_GET['entity'] == 'condition') {
    $items = filterItemsbyCondition($db,$_GET['filter']);
    echo json_encode($items);
} else {
    echo json_encode(['error'=> 'not valid search']);
}


