<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/item.class.php');

function getBoughtItems(PDO $db, int $user_id)
{
    $sql = "SELECT 
            Items.*, 
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
            LEFT JOIN Transactions ON Items.id = Transactions.item_id
            WHERE Transactions.buyer_id = :user_id;";

    $stmt = $db->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function getItemsUser(PDO $db, int $user_id): array 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Items.user_id = :user_id;";

    $stmt = $db->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function getAllItems(PDO $db, int $limit, int $offset, ?int $uid): array 
{
    $sql = "SELECT 
            Items.*, 
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
            LEFT JOIN Transactions ON Items.id = Transactions.item_id
            WHERE Transactions.id is null";

    // Conditionally add WHERE clause if uid is null
    if ($uid !== null) {
        $sql .= " AND Items.user_id != :uid";
    }

    $sql .= " ORDER BY Items.priority DESC, Items.created_at DESC
              LIMIT :limit OFFSET :offset;";

    $stmt = $db->prepare($sql);

    if ($uid !== null) {
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function searchItems(PDO $db, string $keyword) 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Items.title LIKE ?";

    $stmt = $db->prepare($sql); 
    $stmt->execute(['%' . $keyword . '%']);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function filterItemsbyCategory($db, $category) 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Categories.name LIKE ?";

    $stmt = $db->prepare($sql); 
    $stmt->execute([$category . '%']);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function filterItemsbyBrand($db, $brand) 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Brands.name LIKE ?";

    $stmt = $db->prepare($sql); 
    $stmt->execute([$brand . '%']);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function filterItemsbySize($db, $size) 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Size.name LIKE ?";
    $stmt = $db->prepare($sql); 
    $stmt->execute([$size . '%']);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function filterItemsbyCondition($db, $condition) 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Condition.name LIKE ?";
    $stmt = $db->prepare($sql); 
    $stmt->execute([$condition . '%']);

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function getItem(PDO $db, int $itemId): ?Item 
{
    $sql = "SELECT 
            Items.*, 
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
            WHERE Items.id = :itemId;";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $tags = getTagsForItem($db, $row['id']);

        return new Item(
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
            tags: $tags
        );
    }

    return null;
}

function getBoughtItem(PDO $db, int $userId, int $itemId): ?Item
{
    $sql = "SELECT 
            Items.*, 
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
            LEFT JOIN Transactions ON Items.id = Transactions.item_id
            WHERE Transactions.buyer_id = :userId AND Items.id = :itemId;";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $tags = getTagsForItem($db, $row['id']);

        return new Item(
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
            tags: $tags
        );
    }

    return null;
}

function getTagsForItem(PDO $db, int $itemId): array 
{
    $tagSql = "SELECT Tags.name 
               FROM ItemTags 
               JOIN Tags ON ItemTags.tag_id = Tags.id 
               WHERE ItemTags.item_id = :itemId";

    $tagStmt = $db->prepare($tagSql);
    $tagStmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
    $tagStmt->execute();

    $tags = [];
    while ($tagRow = $tagStmt->fetch(PDO::FETCH_ASSOC)) {
        $tags[] = $tagRow['name'];
    }

    return $tags;
}

function itemsInCart(PDO $db, ?int $uid): array 
{
    if (!isset($uid)){
        return array();
    }

    $sql = "SELECT item_id
            FROM ShoppingCart
            WHERE user_id = :uid;";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $itemIds = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemIds[] = $row['item_id'];
    }

    return $itemIds;
}

function itemsInWishlist(PDO $db, ?int $uid): array 
{
    if (!isset($uid)){
        return array();
    }

    $sql = "SELECT item_id
            FROM Wishlist
            WHERE user_id = :uid;";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
    $stmt->execute();

    $itemIds = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemIds[] = $row['item_id'];
    }

    return $itemIds;
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
    $checkStmt = $db->prepare("SELECT * FROM ShoppingCart WHERE user_id = ? AND item_id = ?");
    $checkStmt->execute([$userId, $itemId]);
    $exists = $checkStmt->fetch();

    if ($exists) {
        removeFromCart($db, $userId, $itemId);
    } else {
        addToCart($db, $userId, $itemId);
    }
}

function toggleWishlistItem(PDO $db, int $userId, int $itemId): void 
{
    $checkStmt = $db->prepare("SELECT * FROM Wishlist WHERE user_id = ? AND item_id = ?");
    $checkStmt->execute([$userId, $itemId]);
    $exists = $checkStmt->fetch();

    if ($exists) {
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
    $modelId = getModelId($item->model, (int)$brandId, $db);
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

function addItemTags(Session $session, PDO $db) : bool{
    $itemId = (int)$db->lastInsertId();
    $itemTags = $_POST['item-tags'];
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
function getModelId(string $model, int $brand, PDO $db) : int{
    $stmt = $db->prepare("SELECT id FROM Models WHERE name = ? and brand_id = ?");
    $stmt->execute([$model, $brand]);
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
