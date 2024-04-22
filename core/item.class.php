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
    public int $created_at;
    public ?string $condition;
    public ?string $category;
    public ?string $size;
    public array $tags; 

    public function __construct(
        ?int $id = null,
        ?string $brand = null,
        ?string $model = null,
        ?string $description = null,
        ?string $title = null,
        ?string $images = null,
        float $price,
        int $tradable = 0,
        int $priority = 1,
        int $user_id,
        ?int $created_at = null,
        ?string $condition = null,
        ?string $category = null,
        ?string $size = null,
        ?array $tags = null
    ) {
        $this->id = $id;
        $this->brand = $brand ?? '';
        $this->model = $model ?? '';
        $this->description = $description ?? '';
        $this->title = $title ?? '';
        $this->images = $images ?? [self::DEFAULT_IMAGE_PATH];
        $this->price = $price;
        $this->tradable = $tradable;
        $this->priority = $priority;
        $this->user_id = $user_id;
        $this->created_at = $created_at ?? date();
        $this->condition = $condition ?? '';
        $this->category = $category ?? '';
        $this->size = $size ?? '';
        $this->tags = $tags ?? array();
    }

    public function getImagesArray() {
        return json_decode($this->images, true);
    }

    

    function getTimePassed() {
        $now = new DateTime();
        $eventTime = new DateTime();
        $eventTime->setTimestamp($this->created_at);
        $interval = $now->diff($eventTime);
    
        if ($interval->y > 0) {
            return $interval->y . ' year' . ($interval->y == 1 ? '' : 's') . ' ago';
        } elseif ($interval->m > 0) {
            return $interval->m . ' month' . ($interval->m == 1 ? '' : 's') . ' ago';
        } elseif ($interval->d > 0) {
            return $interval->d . ' day' . ($interval->d == 1 ? '' : 's') . ' ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hour' . ($interval->h == 1 ? '' : 's') . ' ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minute' . ($interval->i == 1 ? '' : 's') . ' ago';
        } else {
            return 'Just now';
        }
    }
    
}
