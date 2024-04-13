<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  die(header('Location: /'));
}

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/user.db.php');

$db = getDatabaseConnection();

$users = searchUsers($db,$_GET['search']);

echo json_encode($users);