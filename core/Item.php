<?php

declare(strict_types=1);

class Item 
{
    public const DEFAULT_IMAGE_PATH = "../assets/img/default_item.svg";
    public array $imagesArray = [];

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
        $this->ensureDefaultValues();
        $this->imagesArray = isset($this->images) ?  $this->parseImages() : [self::DEFAULT_IMAGE_PATH];
    }

    public function __set($name, $value){}

    public function ensureDefaultValues(): void
    {
        $this->brand = $this->brand ?? '';
        $this->model = $this->model ?? '';
        $this->description = $this->description ?? '';
        $this->title = $this->title ?? '';
        $this->created_at = $this->created_at ?? time();
        $this->condition = $this->condition ?? '';
        $this->category = $this->category ?? '';
        $this->size = $this->size ?? '';
        $this->tags = $this->tags ?? [];
    }

    public function parseImages() : array 
    {
        return json_decode($this->images, true);
    }

    public function getImagesArray(): array
    {
        return $this->imagesArray;
    }

    public function getMainItemImage(): string
    {
        return $this->getImagesArray()[0];
    }
}
