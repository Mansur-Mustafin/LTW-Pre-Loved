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
            size: $row['size_name']
        );
        $items[] = $item;
    }

    return $items;
}

function getAllItems(PDO $db, int $limit, int $offset): array {
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
            ORDER BY Items.priority DESC, Items.created_at DESC
            LIMIT :limit OFFSET :offset;";

    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    $items = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
            size: $row['size_name']
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
            size: $row['size_name']
        );
    }

    return null;
}
