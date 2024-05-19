<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  echo json_encode(['error'=> 'Permission denied']);
  die();
}

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/filter.db.php');
require_once(__DIR__.'/../database/user.db.php');


$db = getDatabaseConnection();

$user = getUser($db,$session->getName());
if(!$user->admin_flag) {
  echo json_encode(['error'=> 'Permission denied']); 
  die();
}

$user = getUser($db,$session->getName());
if(!$user->admin_flag) die(header('Location: /'));

$users = searchUsers($db,$_GET['search']);
echo json_encode($users);