<?php

declare(strict_types=1);

class User {
    const DEFAULT_USER_IMG = '../assets/img/account-icon.svg';

    public ?int $id;
    public string $username;
    public string $password;
    public string $email;
    public ?string $phonenumber;
    public ?string $image_path;
    public ?int $banned;
    public int $admin_flag;
    public ?string $address;
    public ?int $created_at;

    public function __construct(string $username, 
                                string $password, 
                                string $email, 
                                ?int $id = null, 
                                ?string $phonenumber = null, 
                                ?string $image_path = null,
                                int $banned = 0, 
                                int $admin_flag = 0, 
                                ?string $address = null,
                                ?int $created_at = null) 
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phonenumber = $phonenumber ?? '';
        $this->image_path = $image_path ?? self::DEFAULT_USER_IMG;
        $this->banned = $banned;
        $this->admin_flag = $admin_flag;
        $this->address = $address ?? '';
        $this->created_at = $created_at ?? time();
    }
}
