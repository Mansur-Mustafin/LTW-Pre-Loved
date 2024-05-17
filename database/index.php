<?php

require_once "/home/mansur/Desktop/LTW/project/database/connection.db.php";
require_once "/home/mansur/Desktop/LTW/project/core/user.class.php";
require_once "/home/mansur/Desktop/LTW/project/database/QueryBuilder.php";

$db = getDatabaseConnection();

function getAllUsers(PDO $db): array 
{
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


$qb = new QueryBuilder("User");
$qb->select()
    ->from("Users");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test DB functions</title>
</head>
<body>
    <h1>Test DB Functions</h1>
    Real:
    <br>
    <?= var_dump(getAllUsers($db)); ?> <br><br><br>
    getQuery(): <br>
    <?= print_r($qb->getQuery()); ?> <br><br><br>
    Query:<br>
    <?= var_dump($qb->all()); ?> <br><br><br>
</body>
</html>
