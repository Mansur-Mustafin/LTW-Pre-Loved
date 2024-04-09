<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/user.class.php');

function createUser(PDO $db, User $user) : int{
    $sql = "INSERT INTO Users (username, password, email, phonenumber, image_path, banned, admin_flag, address) 
            VALUES (:username, :password, :email, :phonenumber, :image_path, :banned, :admin_flag, :address)";

    $stmt = $db->prepare($sql);

    // Bind parameters to prevent SQL injection
    $stmt->bindParam(':username', $user->username);
    $stmt->bindParam(':password', $user->password);
    $stmt->bindParam(':email', $user->email);
    $stmt->bindParam(':phonenumber', $user->phonenumber);
    $stmt->bindParam(':image_path', $user->image_path);
    $stmt->bindParam(':banned', $user->banned, PDO::PARAM_BOOL);
    $stmt->bindParam(':admin_flag', $user->admin_flag, PDO::PARAM_BOOL);
    $stmt->bindParam(':address', $user->address);

    $stmt->execute();

    return intval($db->lastInsertId());
}

function existUser(PDO $db, string $username, string $email): bool {
    // text could be email or username they are both unique
    $sql = "SELECT 1 FROM Users WHERE username = :username OR email = :email";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

function getUser(PDO $db, string $text): ?User {
    // text could be email or username they are both unique
    $sql = "SELECT * FROM Users WHERE username = :text OR email = :text";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $stmt->execute();

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) return null;
    $user = new User(
        $userData['username'],
        $userData['password'],
        $userData['email'],
        $userData['id'],
        $userData['phonenumber'] ?? null,
        $userData['image_path'] ?? null,
        $userData['banned'] ?? 0,
        $userData['admin_flag'] ?? 0,
        $userData['address'] ?? null
    );
    
    return $user;
}

function updateUser(PDO $db, User $user): bool {
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

function getAllUsers(PDO $db): array {
    $sql = 'SELECT * FROM USERS';
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $users = [];
    while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $user = new User(
            id: $row['id'],
            username: $row['username'],
            email: $row['email'],
            password: $row['password'],
            phonenumber: $row['phonenumber'] ?? null,
            image_path: $row['image_path'] ?? null,
            banned: $row['banned'] ?? 0,
            admin_flag: $row['admin_flag'] ?? 0,
            address: $row['address'] ?? null,
        );
        $users[] = $user;
    }

    return $users;
}