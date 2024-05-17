<?php

require_once "/home/mansur/Desktop/LTW/project/database/connection.db.php";
require_once "/home/mansur/Desktop/LTW/project/core/user.class.php";
require_once "/home/mansur/Desktop/LTW/project/core/item.class.php";
require_once "/home/mansur/Desktop/LTW/project/database/QueryBuilder.php";

$db = getDatabaseConnection();
$userId = 1;
$itemId = 2;

function getTagsForItem(PDO $db, int $itemId): array 
{
    $qb = new QueryBuilder();

    $qb->select("Tags.name")
        ->from("Tags")
        ->join("ItemTags", "ItemTags.tag_id = Tags.id")
        ->where(["ItemTags.item_id", "=", $itemId]);

    $result = $qb->all();
    return array_column($result, "name");
}

$checkStmt = $db->prepare("SELECT * FROM ShoppingCart WHERE user_id = ? AND item_id = ?");
$checkStmt->execute([$userId, $itemId]);
$exists = $checkStmt->fetch();

// -----------------------------------------------

$qb = new QueryBuilder();

$result = $qb->select()
    ->from("ShoppingCart")
    ->where(["user_id", "=", $userId])
    ->where(["item_id", "=", $itemId])
    ->all();
$result = !empty($result);

// -----------------------------------------------


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test DB functions</title>
</head>
<body>
    <h1>Test DB Functions</h1>
    Real:
    <br>
    <?= var_dump($exists); ?> <br><br><br>
    getQuery(): <br>
    <?= print_r(""); ?> <br><br><br>
    Result:<br>
    <?= var_dump($result); ?> <br><br><br>
</body>
</html>
