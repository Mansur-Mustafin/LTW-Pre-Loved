<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/QueryBuilder.php');

function getBoughtItems(PDO $db, int $user_id) : array
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->join("Transactions", "Items.id = Transactions.item_id")
        ->where(["Transactions.buyer_id", "=", $user_id]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($item->id);
        }
    }

    return $result;
}

function getItemsUser(PDO $db, int $user_id): array 
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Items.user_id", "=", $user_id]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($item->id);
        }
    }

    return $result;
}

function getAllItems(PDO $db, int $limit, int $offset, ?int $uid): array 
{
    $qb = new QueryBuilder("Item");
    $nullValue = null;

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->join("Transactions", "Items.id = Transactions.item_id")
        ->where(["Transactions.id", "is", $nullValue]);

    if ($uid !== null) {
        $qb->where(["Items.user_id", "!=", $uid]);
    }

    $qb ->order("Items.priority")
        ->order("Items.created_at")
        ->limit($limit)
        ->offset($offset);
    
    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($item->id);
        }
    }

    return $result;
}

function searchItems(PDO $db, string $keyword) : array
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Items.title", "LIKE", '%' . $keyword . '%']);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($item->id);
        }
    }

    return $result;
}

function getItem(PDO $db, int $itemId): ?Item 
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Items.id", "=", $itemId]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        $result[0]->tags = getTagsForItem($itemId);
    } 
    return $result[0];
}

function getBoughtItem(PDO $db, int $userId, int $itemId): ?Item
{
    $qb = new QueryBuilder("Item");
    
    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->join("Transactions", "Items.id = Transactions.item_id")
        ->where(["Transactions.buyer_id", "=", $userId])
        ->where(["Items.id", "=", $itemId]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        $result[0]->tags = getTagsForItem($itemId);
    } 
    return $result[0];
}

function getTagsForItem(int $itemId): array 
{
    $qb = new QueryBuilder();

    $qb->select("Tags.name")
        ->from("Tags")
        ->join("ItemTags", "ItemTags.tag_id = Tags.id")
        ->where(["ItemTags.item_id", "=", $itemId]);

    $result = $qb->all();
    return array_column($result, "name");
}

function itemsInCart(PDO $db, ?int $uid): array 
{
    if (!isset($uid)) return [];
    
    $qb = new QueryBuilder();
    $qb->select("item_id")
        ->from("ShoppingCart")
        ->where(["user_id", "=", $uid]);
    
    $result = $qb->all();
    return array_column($result, "item_id");
}

function itemsInWishlist(PDO $db, ?int $uid): array 
{
    if (!isset($uid)) return [];
    
    $qb = new QueryBuilder();
    $qb->select("item_id")
        ->from("Wishlist")
        ->where(["user_id", "=", $uid]);
    
    $result = $qb->all();
    return array_column($result, "item_id");
}

function groupByUser(array $items): array
{
    $itemsGroups = [];

    foreach ($items as $item) {
        $itemsGroups[$item->username][] = $item;
    }

    return $itemsGroups;
}

function getAllItemsFromId(PDO $db, array $items_ids): array 
{
    if (empty($items_ids)) {
        return [];
    }
    $placeholders = implode(',', array_fill(0, count($items_ids), '?'));

    $qb = new QueryBuilder("Item");
    $db = getDatabaseConnection();

    $qb->setupItemSelect()
        ->select("Users.username AS username")
        ->from("Items")
        ->setupItemJoins()
        ->join("Users", "Items.user_id = Users.id");

    [$query, $bindParams] = $qb->getQuery();
    $query = $query . "
        WHERE Items.id IN ($placeholders)
        ORDER BY Items.priority DESC, Items.created_at DESC;
    ";

    $stmt = $qb->createCommand($db, $query, $bindParams);
    
    foreach ($items_ids as $index => $itemId) {
        $stmt->bindValue(($index + 1), $itemId, PDO::PARAM_INT);
    }
    
    $result = $stmt->execute()->fetchAll(PDO::FETCH_ASSOC);

    $result = $qb->convertToModels($result);
    
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($item->id);
        }
    }

    return $result;
}

function toggleCartItem(PDO $db, int $userId, int $itemId): void 
{
    $qb = new QueryBuilder();

    $result = $qb->select()
        ->from("ShoppingCart")
        ->where(["user_id", "=", $userId])
        ->where(["item_id", "=", $itemId])
        ->all();
 
    if (!empty($result)) {
        removeFromCart($db, $userId, $itemId);
    } else {
        addToCart($db, $userId, $itemId);
    }
}

function toggleWishlistItem(PDO $db, int $userId, int $itemId): void 
{
    $qb = new QueryBuilder();

    $result = $qb->select()
        ->from("Wishlist")
        ->where(["user_id", "=", $userId])
        ->where(["item_id", "=", $itemId])
        ->all();
 
    if (!empty($result)) {
        removeFromWishlist($db, $userId, $itemId);
    } else {
        addToWishlist($db, $userId, $itemId);
    }
}

function addToCart(PDO $db, int $userId, int $itemId): void 
{
    $stmt = $db->prepare("INSERT INTO ShoppingCart (user_id, item_id) VALUES (?, ?)");
    $stmt->execute([$userId, $itemId]);
}

function removeFromCart(PDO $db, int $userId, int $itemId): void 
{
    $stmt = $db->prepare("DELETE FROM ShoppingCart WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
}

function addToWishlist(PDO $db, int $userId, int $itemId): void 
{
    $stmt = $db->prepare("INSERT INTO Wishlist (user_id, item_id) VALUES (?, ?)");
    $stmt->execute([$userId, $itemId]);
}

function removeFromWishlist(PDO $db, int $userId, int $itemId): void 
{
    $stmt = $db->prepare("DELETE FROM Wishlist WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
}

function deleteItemById(PDO $db, int $itemId): void
{
    $stmt = $db->prepare("DELETE FROM Items WHERE id = ?");
    $stmt->execute([$itemId]);
}

