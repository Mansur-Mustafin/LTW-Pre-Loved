<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');
require_once(__DIR__ . '/../utils/Validate.php');

$session = new Session();
$request = new Request(false);

if ($session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../core/User.php');

$db = getDatabaseConnection();

if (!$request->isPost()) die(header('Location: ../pages/login.php'));

$validator = Validate::in($_POST)
    ->required(['email', 'password'])
    ->length(['password'], 8, 100); 

if ($errors = $validator->getErrors()) {
    foreach ($errors as $field => $messages) {
        foreach ($messages as $message) {
            $session->addMessage('error', $message);
        }
    }
    header('Location: ../pages/login.php');
    exit;
}    


// Add password verification
$user = getUser($db, $request->post('email'));

if (!$user){
    $session->addMessage('error', 'User not found');
    die(header('Location: ../pages/login.php'));
}

if(!is_same_password($request->post('password'), $user->password)){
    $session->addMessage('error', 'Wrong password');
    die(header('Location: ../pages/login.php'));
}

if($user->banned){
    $session->addMessage('error', 'This user was bunned');
    die(header('Location: ../pages/login.php')); 
}

$session->setId($user->id);
$session->setName($user->username);
$session->setAdmin((bool)$user->admin_flag);

// TODO change this later to admin page
if($user->admin_flag){
    header('Location: /');
}

header('Location: /');
exit;