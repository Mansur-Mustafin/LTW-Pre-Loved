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
        public ?string $brand = '',
        public ?string $model = '',
        public ?string $description = '',
        public ?string $title = '',
        public ?string $images = null,
        public ?int $created_at = null,
        public ?string $condition = '',
        public ?string $category = '',
        public ?string $size = '',
        public ?array $tags = null,
    ) {
        $this->images = $images ?? [self::DEFAULT_IMAGE_PATH];
        $this->created_at = $created_at ?? time();
        $this->tags = $tags ?? [];
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
