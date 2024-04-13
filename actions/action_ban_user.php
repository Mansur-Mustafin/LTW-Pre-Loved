<?php 
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../core/user.class.php');

$db = getDatabaseConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user = getUser($db,$_POST['username']);
    $user->banned = 1;
    updateUser($db,$user);
}


header('Location: ../pages/admin.php?value=users');
exit;