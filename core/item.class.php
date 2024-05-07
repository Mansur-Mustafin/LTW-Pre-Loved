<?php

declare(strict_types=1);

class Item 
{
    public const DEFAULT_IMAGE_PATH = "../assets/img/default_item.svg";

    public function __construct(
        public float $price,
        public int $user_id,
        public int $tradable = 0,
        public int $priority = 1,
        public ?int $id = null,
        public ?string $brand = null,
        public ?string $model = null,
        public ?string $description = null,
        public ?string $title = null,
        public ?string $images = null,
        public ?int $created_at = null,
        public ?string $condition = null,
        public ?string $category = null,
        public ?string $size = null,
        public ?array $tags = null,
    ) {
        $this->brand = $brand ?? '';
        $this->model = $model ?? '';
        $this->description = $description ?? '';
        $this->title = $title ?? '';
        $this->images = $images ?? [self::DEFAULT_IMAGE_PATH];
        $this->created_at = $created_at ?? time();
        $this->condition = $condition ?? '';
        $this->category = $category ?? '';
        $this->size = $size ?? '';
        $this->tags = $tags ?? array();
    }

    public function getImagesArray()
    {
        return json_decode($this->images, true);
    }

    function getTimePassed()
    {
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

