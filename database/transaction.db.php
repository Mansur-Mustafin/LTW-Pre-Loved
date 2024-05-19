<?php

declare(strict_types=1);


function getTransaction(PDO $db, int $itemId) 
{
    $sql = "SELECT * FROM Transactions WHERE item_id = :item_id";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);

    $stmt->execute();

    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction) {
        return $transaction;
    } else {
        return null; 
    }
}

function completeCheckout(PDO $db, int $userId, array $items)
{
    $itemsIds = array_column($items, 'id');
    $itemsIdsString = implode(', ', $itemsIds);

    $stmt = $db->prepare("DELETE FROM Wishlist WHERE user_id = ? AND item_id in ({$itemsIdsString})");
    $stmt->execute([$userId]);

    $stmt = $db->prepare("DELETE FROM ShoppingCart WHERE item_id in ({$itemsIdsString})");
    $stmt->execute();

    $values = [];
    $params = [];

    foreach ($items as $item) {
        $values[] = "(?, ?, ?, ?)";
        array_push($params, $item->user_id, $userId, $item->id, time());
    }

    $valuesString = implode(', ', $values);

    $stmt = $db->prepare("INSERT INTO Transactions (seller_id, buyer_id, item_id, created_at) VALUES {$valuesString};");
    $stmt->execute($params);

    $stmt = $db->prepare("DELETE FROM ItemTags WHERE item_id IN ({$itemsIdsString})");
    $stmt->execute();

    $stmt = $db->prepare("SELECT id FROM Tags WHERE name = 'Sold'");
    $stmt->execute();
    $soldTagId = $stmt->fetchColumn();

    $values = [];
    $params = [];

    foreach ($items as $item) {
        $values[] = "(?, ?)";
        array_push($params, $item->id, $soldTagId);
    }

    $valuesString = implode(', ', $values);

    $stmt = $db->prepare("INSERT INTO ItemTags (item_id, tag_id) VALUES {$valuesString};");
    $stmt->execute($params);
}

// TODO: rewrite this with QueryBuilder
function isItemSold(PDO $db, int $itemId) {
    
    $sql = "SELECT COUNT(*) FROM Transactions WHERE item_id = :itemId";
    $stmt = $db->prepare($sql);
    $stmt->execute([':itemId' => $itemId]);
    $count = $stmt->fetchColumn();

    return $count > 0; 
}
