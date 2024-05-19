<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));


require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/Validate.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../core/User.php');

$db = getDatabaseConnection();

// case of profile
if ($request->isPost() && $request->post('edit-profile') !== null) {
    // TODO validate user email.
    $user = getUser($db, $session->getName());

    if($request->post('edit-profile') == 'save'){

        $validator = Validate::in($request->getPostParams())
            ->required(['new_username', 'new_email'])
            ->match('new_username', '/^[^@]*$/')
            ->match('new_email', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');
        
        if ($errors = $validator->getErrors()) {
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $session->addMessage('error', $message);
                }
            }
            header('Location: ../pages/login.php');
            die();
        }

        if (!empty($request->post('new_username'))) {
            $hasDefaultImage = $user->hasDefaultImage();
            $updateFoto = !empty($_FILES['profile_img']['name']);
            
            var_dump($hasDefaultImage);
            var_dump($updateFoto);
            
            $used_usernames = array_map(function (User $user) {
                return $user->username;
            },getAllUsers($db));

            if ($hasDefaultImage && !$updateFoto) {
                if(!in_array($request->post('new_username'),$used_usernames)){
                    $user->username = $request->post('new_username');
                }
                var_dump("line 53");
            } else {
                var_dump("line 55");
                $old_image_path = $user->image_path;

                if(!in_array($request->post('new_username'),$used_usernames)){
                    $user->username = $request->post('new_username');
                }
                $root_folder = '/data/profile_img/';
                $filename = get_hash_path($user->username).'.png';
                $save_path = $root_folder.htmlspecialchars($filename);
                $user->image_path = $save_path;
                
                rename(__DIR__.'/..'.$old_image_path, __DIR__.'/..'.$save_path);
            }
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
                var_dump("line 79");
                $user->image_path = $save_path;
            }
        }
        // die();
        updateUser($db, $user);
        $session->setName($user->username);
    }
    
    if($_POST['edit-profile'] == 'cancel'){
        header('Location: ../pages/profile.php');
        exit;
    }
}

// case of password
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
