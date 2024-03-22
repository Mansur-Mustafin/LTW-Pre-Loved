<?php
declare(strict_types=1);

function get_hash_password(string $password) : string{
    return password_hash('road2vinte'.$password.'road2vinte', PASSWORD_DEFAULT);
}

function is_same_password(string $password, string $hash) : bool{
    return password_verify('road2vinte'.$password.'road2vinte', $hash);
}

function get_hash_path(string $path) : string{
    return hash('sha256', $path);
}
