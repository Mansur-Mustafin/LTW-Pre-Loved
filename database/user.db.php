<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/User.php');
require_once(__DIR__.'/../database/QueryBuilder.php');
require_once(__DIR__.'/../database/connection.db.php');

function createUser(PDO $db, User $user) : int
{
    $sql = "INSERT INTO Users (username, password, email, phonenumber, image_path, banned, admin_flag, address) 
            VALUES (:username, :password, :email, :phonenumber, :image_path, :banned, :admin_flag, :address)";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
    $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
    $stmt->bindParam(':phonenumber', $user->phonenumber, PDO::PARAM_STR);
    $stmt->bindParam(':image_path', $user->image_path, PDO::PARAM_STR);
    $stmt->bindParam(':banned', $user->banned, PDO::PARAM_BOOL);
    $stmt->bindParam(':admin_flag', $user->admin_flag, PDO::PARAM_BOOL);
    $stmt->bindParam(':address', $user->address, PDO::PARAM_STR);

    $stmt->execute();

    return intval($db->lastInsertId());
}

function updateUser(PDO $db, User $user): bool 
{
    $sql = "UPDATE Users SET 
                username = :username, 
                password = :password, 
                email = :email, 
                phonenumber = :phonenumber, 
                image_path = :image_path, 
                banned = :banned, 
                admin_flag = :admin_flag, 
                address = :address 
            WHERE id = :id";

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
    $stmt->bindValue(':password', $user->password, PDO::PARAM_STR);
    $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
    $stmt->bindValue(':phonenumber', $user->phonenumber, PDO::PARAM_STR);
    $stmt->bindValue(':image_path', $user->image_path, PDO::PARAM_STR);
    $stmt->bindValue(':banned', $user->banned, PDO::PARAM_INT);
    $stmt->bindValue(':admin_flag', $user->admin_flag, PDO::PARAM_INT);
    $stmt->bindValue(':address', $user->address, PDO::PARAM_STR);

    return $stmt->execute();
}

function existUser(PDO $db, string $username, string $email): bool 
{
    $qb = new QueryBuilder("User");

    $qb->select()
        ->from("Users")
        ->where(['username', '=', $username], 'OR')
        ->where(['email', '=', $email]);

    return isset($qb->all()[0]);
}

function getUser(PDO $db, string $text): ?User 
{   
    // text could be email or username they are both unique
    $qb = new QueryBuilder("User");

    $qb->select()
        ->from("Users")
        ->where(['username', '=', $text], 'OR')
        ->where(['email', '=', $text]);

    if(!$qb->all()) return null;
    return $qb->all()[0];
}

function searchUsers(PDO $db, string $keyword): ?array 
{
    $qb = new QueryBuilder("User");
    $qb->select()
        ->from("Users")
        ->where(['username', 'LIKE', '%'.$keyword.'%']);

    return $qb->all();
}

function getUserById(PDO $db, int $id): ?User 
{
    $qb = new QueryBuilder("User");

    $qb->select()
        ->from("Users")
        ->where(['id', '=', $id]);

    return $qb->all()[0];
}

function getAllUsers(PDO $db): array 
{
    $qb = new QueryBuilder("User");

    $qb->select()
        ->from("Users");

    return $qb->all();
}
