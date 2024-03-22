<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
// If user input link
if ($session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}

require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');

$db = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirmPassword = $_POST['confirm_password'] ?? null;
    $address = $_POST['address'] ?? null;
    $phonenumber = $_POST['phonenumber'] ?? null;

    // TODO Basic validation
    if (!$email || !$username || !$password || $password !== $confirmPassword) {
        $session->addMessage('error', 'Failed input values');
        die(header('Location: ../pages/login.php'));
    }

    if (existUser($db, $username, $email)){
        $session->addMessage('error', 'User already exist');
        die(header('Location: ../pages/login.php'));
    }

    $password = get_hash_password($password);
    $user = new User($username, $password, $email, null, $phonenumber, null, 0, 0, $address);
    $userId = createUser($db, $user);
    $session->setId($userId);
    $session->setName($username);

    header('Location: ../pages/profile.php');
}
