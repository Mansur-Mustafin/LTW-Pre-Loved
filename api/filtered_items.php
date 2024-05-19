<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/QueryBuilder.php');


$qb = new QueryBuilder("Item");

$qb ->setupItemSelect()
    ->from("Items")
    ->setupItemJoins();


if($_GET['entity'] == 'category') {
    $qb->where(["Categories.name", "LIKE", $_GET['filter']]);
} else if($_GET['entity'] == 'brand') {
    $qb->where(["Brands.name", "LIKE", $_GET['filter']]);
} else if($_GET['entity'] == 'size') {
    $qb->where(["Size.name", "LIKE", $_GET['filter']]);
} else if($_GET['entity'] == 'condition') {
    $qb->where(["Condition.name", "LIKE", $_GET['filter']]);
} else {
    echo json_encode(['error'=> 'not valid search']);
}


$items = $qb->all();
if (isset($items) && count($items) > 0) {
    foreach ($items as $item) {
        $item->tags = getTagsForItem($item->id);
    }
}
echo json_encode($items);

