<?php
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../core/user.class.php');

$db = getDatabaseConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit-profile'])) {
    // TODO validate user email.
    $user = getUser($db, $session->getName());
    var_dump($user);

    if($_POST['edit-profile'] == 'save'){
        if (!empty($_POST['new_username'])) {
            $user->username = $_POST['new_username'];
        }
        if (!empty($_POST['new_email'])) {
            $user->email = $_POST['new_email'];
        }
        if (!empty($_POST['new_phonenumber'])) {
            $user->phonenumber = $_POST['new_phonenumber'];
        }
        if (!empty($_POST['new_address'])) {
            $user->address = $_POST['new_address'];
        }
        if(isset($_FILES['profile_img']['name'])){
            $root_folder = '/data/profile_img/';
            $filename = get_hash_path($session->getName()).'.png';
            $save_path = $root_folder.htmlspecialchars($filename);
            if (move_uploaded_file($_FILES['profile_img']['tmp_name'], __DIR__.'/..'.$save_path)) {
                $user->image_path = $save_path;
            }
        }
    
        var_dump($user);
        $session->setName($user->username);
        updateUser($db, $user);
    }
    
    if($_POST['edit-profile'] == 'cancel'){
        header('Location: ../pages/profile.php');
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change-password'])) {

    $user = getUser($db, $session->getName());
    var_dump($user);

    if($_POST['change-password'] == 'save'){
        $new_password = $_POST['new_password'];
        $new_password_conf = $_POST['new_password_confirmation'];
        $old_password = $_POST['old_password'];

        if (empty($new_password) || empty($new_password_conf) || empty($old_password) ){
            die(header('Location: ../pages/profile.php'));
        }

        if ($new_password != $new_password_conf || !is_same_password($old_password, $user->password)){
            $session->addMessage('error', 'Passwords dont match :-(');
            die(header('Location: ../pages/profile.php'));
        }

        $user->password = get_hash_password($new_password);
        updateUser($db, $user);
    }
    
    if($_POST['change-password'] == 'cancel'){
        header('Location: ../pages/profile.php');
        exit;
    }
}


header('Location: ../pages/profile.php');
exit;
