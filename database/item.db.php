<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/item.class.php');

function getItemsUser(PDO $db, int $user_id): array {
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
            WHERE Items.user_id = :user_id;
            ";

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

function getAllItems(PDO $db, int $limit, int $offset, ?int $uid): array {
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
            LEFT JOIN Brands ON Models.brand_id = Brands.id";

    // Conditionally add WHERE clause if uid is not null
    if ($uid !== null) {
        $sql .= " WHERE Items.user_id != :uid";
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

function searchItems($db, $keyword) {
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
    $stmt->execute([$keyword . '%']);

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

function filterItemsbyCategory($db, $category) {
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
function filterItemsbyBrand($db, $brand) {
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
function filterItemsbySize($db, $size) {
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
function filterItemsbyCondition($db, $condition) {
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


function getItem(PDO $db, int $itemId): ?Item {
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

function getTagsForItem(PDO $db, int $itemId): array {
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

function itemsInCart(PDO $db, ?int $uid): array {
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

function itemsInWishlist(PDO $db, ?int $uid): array {
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


function getAllItemsFromId(PDO $db, array $items_ids): array {
    if (empty($items_ids)) {
        return [];
    }
    $placeholders = implode(',', array_fill(0, count($items_ids), '?'));

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
            tags: $tags
        );
        $items[] = $item;
    }

    return $items;
}

function toggleCartItem(PDO $db, int $userId, int $itemId): void {
    $checkStmt = $db->prepare("SELECT * FROM ShoppingCart WHERE user_id = ? AND item_id = ?");
    $checkStmt->execute([$userId, $itemId]);
    $exists = $checkStmt->fetch();

    if ($exists) {
        removeFromCart($db, $userId, $itemId);
    } else {
        addToCart($db, $userId, $itemId);
    }
}

function toggleWishlistItem(PDO $db, int $userId, int $itemId): void {
    $checkStmt = $db->prepare("SELECT * FROM Wishlist WHERE user_id = ? AND item_id = ?");
    $checkStmt->execute([$userId, $itemId]);
    $exists = $checkStmt->fetch();

    if ($exists) {
        removeFromWishlist($db, $userId, $itemId);
    } else {
        addToWishlist($db, $userId, $itemId);
    }
}

function addToCart(PDO $db, int $userId, int $itemId): void {
    $stmt = $db->prepare("INSERT INTO ShoppingCart (user_id, item_id) VALUES (?, ?)");
    $stmt->execute([$userId, $itemId]);
}

function removeFromCart(PDO $db, int $userId, int $itemId): void {
    $stmt = $db->prepare("DELETE FROM ShoppingCart WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
}

function addToWishlist(PDO $db, int $userId, int $itemId): void {
    $stmt = $db->prepare("INSERT INTO Wishlist (user_id, item_id) VALUES (?, ?)");
    $stmt->execute([$userId, $itemId]);
}

function removeFromWishlist(PDO $db, int $userId, int $itemId): void {
    $stmt = $db->prepare("DELETE FROM Wishlist WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
}

function deleteItembyId(PDO $db,int $itemId): void {
    $stmt = $db->prepare("DELETE FROM Items WHERE id = ?");
    $stmt->execute([$itemId]);
}
