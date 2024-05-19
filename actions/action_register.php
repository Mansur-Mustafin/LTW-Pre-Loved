<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request(false);

if ($session->isLoggedIn()) die(header('Location: ../pages/main_page.php'));

require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__ . '/../utils/Validate.php');

$db = getDatabaseConnection();

if ($request->isPost()) {
    $email = $request->post('email') ?? null;
    $username = $request->post('username') ?? null;
    $password = $request->post('password') ?? null;
    $confirmPassword = $request->post('confirm_password') ?? null;
    $address = $request->post('address') ?? null;
    $phonenumber = $request->post('phonenumber') ?? null;

    $validator = Validate::in($request->getPostParams())
        ->required(['username', 'email', 'password', 'confirm_password'])
        ->match('username', '/^[^@]*$/')  // Username must not contain '@'
        ->length(['password'], 8, 100)   
        ->match('email', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');  // <mansur>@<gmail>.<com>

    if ($errors = $validator->getErrors()) {
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $session->addMessage('error', $message);
            }
        }
        header('Location: ../pages/login.php');
        die();
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
    exit;
}
