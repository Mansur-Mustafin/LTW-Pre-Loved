<?php
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if ($session->isLoggedIn()) {
  die(header('Location: /'));
}

require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');

$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(header('Location: ../pages/login.php'));
}

if (!is_valid_email($_POST['email'])) {
    $session->addMessage('error', 'Invalid email format');
    die(header('Location: ../pages/login.php'));
}

// Add password verification
$user = getUser($db, $_POST['email']);

if (!$user){
    $session->addMessage('error', 'User not found');
    die(header('Location: ../pages/login.php'));
}

if(!is_same_password($_POST['password'], $user->password)){
    $session->addMessage('error', 'Wrong password');
    die(header('Location: ../pages/login.php'));
}

$session->setId($user->id);
$session->setName($user->username);

// TODO change this later
if($user->admin_flag){
    header('Location: /');
}

header('Location: /');
exit;