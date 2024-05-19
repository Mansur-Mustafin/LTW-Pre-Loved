<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));


require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../core/user.class.php');

$db = getDatabaseConnection();

if ($request->isPost() && $request->post('edit-profile') !== null) {
    // TODO validate user email.
    $user = getUser($db, $session->getName());
    var_dump($user);

    if($request->post('edit-profile') == 'save'){
        if (!empty($request->post('new_username'))) {
            $old_image_path = $user->image_path;

            $user->username = $request->post('new_username');
            $root_folder = '/data/profile_img/';
            $filename = get_hash_path($user->username).'.png';
            $save_path = $root_folder.htmlspecialchars($filename);
            $user->image_path = $save_path;
            
            rename(__DIR__.'/..'.$old_image_path, __DIR__.'/..'.$save_path);
        }
        if (!empty($request->post('new_email'))) {
            $user->email = $request->post('new_email');
        }
        if (!empty($request->post('new_phonenumber'))) {
            $user->phonenumber = $request->post('new_phonenumber');
        }
        if (!empty($request->post('new_address'))) {
            $user->address = $request->post('new_address');
        }
        if(isset($_FILES['profile_img']['name'])){
            if (move_uploaded_file($_FILES['profile_img']['tmp_name'], __DIR__.'/..'.$user->image_path)) {
                $user->image_path = $save_path;
            }
        }
        updateUser($db, $user);
        $session->setName($user->username);
    }
    
    if($_POST['edit-profile'] == 'cancel'){
        header('Location: ../pages/profile.php');
        exit;
    }
}


if ($request->isPost() && $request->post('change-password') !== null) {

    $user = getUser($db, $session->getName());
    var_dump($user);

    if ($request->post('change-password') == 'save') {
        $new_password = $request->post('new_password');
        $new_password_conf = $request->post('new_password_confirmation');
        $old_password = $request->post('old_password');

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
