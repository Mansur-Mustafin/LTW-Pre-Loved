<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/QueryBuilder.php');
require_once(__DIR__.'/../database/filter.db.php');

function getBoughtItems(int $user_id) : array
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

function getItemsUser(int $user_id): array
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

function getAllItems(int $limit, int $offset, ?int $uid): array
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

function searchItems(string $keyword) : array
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

function getItem(int $itemId): ?Item
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

function getBoughtItem(int $userId, int $itemId): ?Item
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

function itemsInCart(?int $uid): array
{
    if (!isset($uid)) return [];
    
    $qb = new QueryBuilder();
    $qb->select("item_id")
        ->from("ShoppingCart")
        ->where(["user_id", "=", $uid]);
    
    $result = $qb->all();
    return array_column($result, "item_id");
}

function itemsInWishlist(?int $uid): array
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

function getAllItemsFromId(array $items_ids): array
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
    
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

function addItem(Session $session, PDO $db, Item $item): bool {
    $categoryId = getElementId($item->category, 'Categories', $db);
    if($categoryId === -1){
        $session->addMessage('error', 'Invalid category id.');
        header('Location: ../pages/add_item.php');
        return false;
    }
    $sizeId = getElementId($item->size, 'Size', $db);
    if($sizeId === -1){
        $session->addMessage('error', 'Invalid size id.');
        header('Location: ../pages/add_item.php');
        return false;
    }
    $conditionId = getElementId($item->condition, 'Condition', $db);
    if($conditionId === -1){
        $session->addMessage('error', 'Invalid condition id.');
        header('Location: ../pages/add_item.php');
        return false;
    }
    $brandId = getElementId($item->brand, 'Brands', $db);
    if($brandId === -1){
        $sql = "INSERT INTO Brands (name) VALUES (:brand)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':brand', $item->brand);
        $stmt->execute();
        $brandId = $db->lastInsertId();
    }
    $modelId = getModelId($item->model, $db);
    if($modelId === -1){
        $sql = "INSERT INTO Models (brand_id, name) VALUES (:brand_id, :model)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':brand_id', $brandId);
        $stmt->bindParam(':model', $item->model);
        $stmt->execute();
        $modelId = $db->lastInsertId();
    }
    $sql = "INSERT INTO Items (title, description, images, price, tradable, user_id, category_id, size_id, condition_id, model_id, created_at)
            VALUES (:title, :description, :images, :price, :tradable, :user_id, :category_id, :size_id, :condition_id, :model_id, :created_at)";
    $stmt = $db->prepare($sql);
    
    $stmt->bindParam(':title', $item->title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $item->description, PDO::PARAM_STR);
    $stmt->bindParam(':images', $item->images, PDO::PARAM_STR);
    $stmt->bindParam(':price', $item->price, PDO::PARAM_STR);
    $stmt->bindParam(':tradable', $item->tradable, PDO::PARAM_BOOL);
    $stmt->bindParam(':user_id', $item->user_id, PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindParam(':size_id', $sizeId, PDO::PARAM_INT);
    $stmt->bindParam(':condition_id', $conditionId, PDO::PARAM_INT);
    $stmt->bindParam(':model_id', $modelId, PDO::PARAM_INT);
    $stmt->bindParam(':created_at', $item->created_at, PDO::PARAM_STR);
    $stmt->execute();
    return true;
}

function updateItem(Session $session, PDO $db, Item $item, int $oldID): bool {
    $categoryId = getElementId($item->category, 'Categories', $db);
    if($categoryId === -1){
        $session->addMessage('error', 'Invalid category id.');
        header('Location: ../pages/add_item.php');
        return false;
    }
    $sizeId = getElementId($item->size, 'Size', $db);
    if($sizeId === -1){
        $session->addMessage('error', 'Invalid size id.');
        header('Location: ../pages/add_item.php');
        return false;
    }
    $conditionId = getElementId($item->condition, 'Condition', $db);
    if($conditionId === -1){
        $session->addMessage('error', 'Invalid condition id.');
        header('Location: ../pages/add_item.php');
        return false;
    }
    $brandId = getElementId($item->brand, 'Brands', $db);
    if($brandId === -1){
        $sql = "INSERT INTO Brands (name) VALUES (:brand)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':brand', $item->brand);
        $stmt->execute();
        $brandId = $db->lastInsertId();
    }
    $modelId = getModelId($item->model, $db);
    //var_dump($item->model);
    if($modelId === -1){
        $models = getEntitiesFromType("Models");
        $sql = "INSERT INTO Models (brand_id, name) VALUES (:brand_id, :model)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':brand_id', $brandId);
        $stmt->bindParam(':model', $item->model);
        $stmt->execute();
        $modelId = $db->lastInsertId();
    }
    $sql = "UPDATE Items SET
            title = :title,
            description = :description,
            images = :images,
            price = :price,
            tradable = :tradable,
            user_id = :user_id,
            category_id = :category_id,
            size_id = :size_id,
            condition_id = :condition_id,
            model_id = :model_id,
            created_at = :created_at
            WHERE id = :id";
    $stmt = $db->prepare($sql);
    
    $stmt->bindParam(':id',$oldID,PDO::PARAM_INT);
    $stmt->bindParam(':title', $item->title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $item->description, PDO::PARAM_STR);
    $stmt->bindParam(':images', $item->images, PDO::PARAM_STR);
    $stmt->bindParam(':price', $item->price, PDO::PARAM_STR);
    $stmt->bindParam(':tradable', $item->tradable, PDO::PARAM_BOOL);
    $stmt->bindParam(':user_id', $item->user_id, PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindParam(':size_id', $sizeId, PDO::PARAM_INT);
    $stmt->bindParam(':condition_id', $conditionId, PDO::PARAM_INT);
    $stmt->bindParam(':model_id', $modelId, PDO::PARAM_INT);
    $stmt->bindParam(':created_at', $item->created_at, PDO::PARAM_STR);
    $stmt->execute();
    return true;
}
function addItemTags(Session $session, PDO $db) : bool{
    $itemId = (int)$db->lastInsertId();
    if (isset($item['item-tags'])) {
        $itemTags = $_POST['item-tags'];
    } else {
        $itemTags = [];
    }
    foreach($itemTags as $tag){
        $tagId = getElementId($tag, 'Tags', $db);
        if($tagId === -1){
            $session->addMessage('error', 'Invalid tag id.');
            header('Location: ../pages/profile.php');
            return false;
        }
        $insertStmt = $db->prepare("INSERT INTO ItemTags (item_id, tag_id) VALUES (?,?)");
        $insertStmt->execute([$itemId, $tagId]);
    }
    return true;
}
function getElementId(string $element, string $table, PDO $db) : int{
    $stmt = $db->prepare("SELECT id FROM $table WHERE name = ?");
    $stmt->execute([$element]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return (int)$row['id'];
    } else {
        return -1;
    }
}
function getModelId(string $model, PDO $db) : int{
    $stmt = $db->prepare("SELECT id FROM Models WHERE name = ?");
    $stmt->execute([$model]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return (int)$row['id'];
    } else {
        return -1;
    }
}
function getBrandFromModelId(string $model, PDO $db){
    $stmt = $db->prepare('SELECT Brands.id 
                          FROM Brands
                          JOIN Models ON Models.brand_id = Brands.id
                          WHERE Models.id = ?');
    $stmt->execute([$model]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
