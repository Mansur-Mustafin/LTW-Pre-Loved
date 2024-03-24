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

if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['edit'] == 'save') {
    $user = getUser($db,$session->getName());
    if($_POST['new_username'] != '' && $_POST['new_username'] != $user->username) {
        updateUserParameter($db,$user->username,$_POST['new_username'],"username");
        $session->setName($_POST['new_username']);
        $user = getUser($db,$session->getName());
    }
    if($_POST['new_email'] != '' && $_POST['new_email'] != $user->email) {
        updateUserParameter($db,$user->username,$_POST['new_email'],"email");
    }
    if($_POST['new_phonenumber'] != '' && $_POST['new_phonenumber'] != $user->phonenumber) {
        updateUserParameter($db,$user->username,$_POST['new_phonenumber'],"phonenumber");
    }
    if($_POST['new_address'] != '' && $_POST['new_address'] != $user->address) {
        updateUserParameter($db,$user->username,$_POST['new_address'],"address");
    }
    if($_POST['new_password'] != '' && get_hash_password($_POST['new_password']) != $user->password) {
        updateUserParameter($db,$user->username,get_hash_password($_POST['new_password']),"password");
    }

    if(isset($_FILES['profile_img']['name'])) {
        $root_folder = '/data/profile_img/';
        $filename = get_hash_path($session->getName()).'.png';
        $save_path = $root_folder.htmlspecialchars($filename);
        
        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], __DIR__.'/..'.$save_path)) {
            var_dump($save_path);
            updateUserProfilePicture($db, $session->getName(), $save_path);
        }
        // TODO error handler!
    }
}

header('Location: ../pages/profile.php');
exit;