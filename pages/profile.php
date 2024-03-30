<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');

$db = getDatabaseConnection();

$user = getUser($db, $session->getName());    // TODO search by id?
$items = getItemsUser($db, $user->id);
drawHeader($session);

if(isset($_POST['edit']) && $_POST['edit'] == 'profile') drawEditProfile($user); 
else if (isset($_POST['edit']) && $_POST['edit'] == 'password')  drawChangePassword($user); 
else drawProfile($user, $session); 

drawItems($items, $session, 'Your items to sell');
drawFooter();
?>
