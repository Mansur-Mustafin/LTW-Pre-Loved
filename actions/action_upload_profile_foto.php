<?php
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  die(header('Location: /'));
}

require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__.'/../database/user.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../core/user.class.php');

$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_img'])) {

  $root_folder = '/data/profile_img/';
  $filename = get_hash_path($session->getName()).'.png';
  $save_path = $root_folder.htmlspecialchars($filename);

  if (move_uploaded_file($_FILES['profile_img']['tmp_name'], __DIR__.'/..'.$save_path)) {
    var_dump($save_path);
    updateUserProfilePicture($db, $session->getName(), $save_path);
  }
  // TODO error handler!
}

header('Location: ../pages/profile.php');
exit;