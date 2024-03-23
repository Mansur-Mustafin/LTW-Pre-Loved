<?php

declare(strict_types=1);

class Item {
    
    const DEFAULT_IMAGE_PATH = "../assets/img/default_item.svg";

    public int $id;
    public ?string $brand;
    public ?string $model;
    public ?string $description;
    public ?string $title;
    public ?string $images;
    public float $price;
    public int $tradable;
    public int $priority;
    public int $user_id;
    public string $created_at;
    public ?string $condition;
    public ?string $category;
    public ?string $size;

    public function __construct(
        ?int $id = null,
        ?string $brand = null,
        ?string $model = null,
        ?string $description = null,
        ?string $title = null,
        string $images = null,
        float $price,
        int $tradable = 0,
        int $priority = 1,
        int $user_id,
        string $created_at = '',
        ?string $condition = null,
        ?string $category = null,
        ?string $size = null
    ) {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->description = $description ?? '';
        $this->title = $title ?? '';
        $this->images = $images ?? [self::DEFAULT_IMAGE_PATH];
        $this->price = $price;
        $this->tradable = $tradable;
        $this->priority = $priority;
        $this->user_id = $user_id;
        $this->created_at = $created_at ?: date('Y-m-d H:i:s');
        $this->condition = $condition;
        $this->category = $category;
        $this->size = $size;
    }

    public function getImagesArray() {
        return json_decode($this->images, true);
    }
}

