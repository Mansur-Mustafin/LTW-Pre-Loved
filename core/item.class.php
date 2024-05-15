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
        public ?string $username = null,
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

    public function getImagesArray(): array
    {
        return json_decode($this->images, true);
    }

    public function getMainItemImage(): string
    {
        return $this->getImagesArray()[0];
    }
}
