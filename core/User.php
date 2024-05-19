<?php

declare(strict_types=1);

class User 
{
    public const DEFAULT_USER_IMG = '../assets/img/account-icon.svg';

    public function __construct(
        public string $username,
        public string $password,
        public string $email,
        public ?int $id = null,
        public ?string $phonenumber = null,
        public ?string $image_path = null,
        public int $banned = 0,
        public int $admin_flag = 0,
        public ?string $address = null,
        public ?int $created_at = null
    ) {
        $this->ensureDefaultValues();
    }    

    public function hasDefaultImage(): bool
    {
        return $this->image_path == static::DEFAULT_USER_IMG;
    }

    public function ensureDefaultValues(): void
    {
        $this->image_path = $this->image_path ?? self::DEFAULT_USER_IMG;
        $this->created_at = $this->created_at ?? time();
        $this->phonenumber = $this->phonenumber ?? '';
        $this->address = $this->address ?? '';
    }
}
