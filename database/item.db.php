<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/QueryBuilder.php');

function getBoughtItems(PDO $db, int $user_id)
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
            $item->tags = getTagsForItem($db, $item->id);
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
            $item->tags = getTagsForItem($db, $item->id);
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
            $item->tags = getTagsForItem($db, $item->id);
        }
    }

    return $result;
}

function searchItems(PDO $db, string $keyword) 
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Items.title", "LIKE", '%' . $keyword . '%']);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($db, $item->id);
        }
    }

    return $result;
}

function filterItemsbyCategory($db, $category) 
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Categories.name", "LIKE", $category]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($db, $item->id);
        }
    }

    return $result;
}

function filterItemsbyBrand($db, $brand) 
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Brands.name", "LIKE", $brand]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($db, $item->id);
        }
    }

    return $result;
}

function filterItemsbySize($db, $size) 
{
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Size.name", "LIKE", $size]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($db, $item->id);
        }
    }

    return $result;
}

function filterItemsbyCondition($db, $condition) 
{   
    $qb = new QueryBuilder("Item");

    $qb ->setupItemSelect()
        ->from("Items")
        ->setupItemJoins()
        ->where(["Condition.name", "LIKE", $condition]);

    $result = $qb->all();
    if (isset($result) && count($result) > 0) {
        foreach ($result as $item) {
            $item->tags = getTagsForItem($db, $item->id);
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
        $result[0]->tags = getTagsForItem($db, $itemId);
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
        $result[0]->tags = getTagsForItem($db, $itemId);
    } 
    return $result[0];
}

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

    $sql = "SELECT 
            Items.*, 
            Users.username AS username, 
            Condition.name AS condition_name, 
            Models.name AS model_name,
            Categories.name AS category_name,
            Size.name AS size_name,
            Brands.name AS brand_name
            FROM Items
            LEFT JOIN Condition ON Items.condition_id = Condition.id
            LEFT JOIN Models ON Items.model_id = Models.id
            LEFT JOIN Categories ON Items.category_id = Categories.id
            LEFT JOIN Size ON Items.size_id = Size.id
            LEFT JOIN Brands ON Models.brand_id = Brands.id
            LEFT JOIN Users ON Items.user_id = Users.id
            WHERE Items.id IN ($placeholders)
            ORDER BY Items.priority DESC, Items.created_at DESC;";

    $stmt = $db->prepare($sql);

    foreach ($items_ids as $index => $itemId) {
        $stmt->bindValue(($index + 1), $itemId, PDO::PARAM_INT);
    }

    $stmt->execute();

    $items = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tags = getTagsForItem($db, $row['id']);

        $item = new Item(
            id: $row['id'],
            brand: $row['brand_name'],
            description: $row['description'],
            title: $row['title'],
            images: $row['images'],
            price: $row['price'],
            tradable: $row['tradable'],
            priority: $row['priority'],
            user_id: $row['user_id'],
            created_at: $row['created_at'],
            condition: $row['condition_name'],
            model: $row['model_name'],
            category: $row['category_name'],
            size: $row['size_name'],
            tags: $tags,
            username: $row['username'],
        );
        $items[] = $item;
    }

    return $items;
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

function completeCheckout(PDO $db, int $userId, array $items)
{
    $itemsIds = array_column($items, 'id');
    $itemsIdsString = implode(', ', $itemsIds);

    $stmt = $db->prepare("DELETE FROM Wishlist WHERE user_id = ? AND item_id in ({$itemsIdsString})");
    $stmt->execute([$userId]);

    $stmt = $db->prepare("DELETE FROM ShoppingCart WHERE user_id = ? AND item_id in ({$itemsIdsString})");
    $stmt->execute([$userId]);

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
