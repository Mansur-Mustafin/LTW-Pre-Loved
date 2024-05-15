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
require_once(__DIR__ . '/../components/pdf.php');

$db = getDatabaseConnection();

$user_id = isset($_GET['id']) == null ? $session->getId() : intval($_GET['id']);
$user = getUserById($db, $user_id);

drawHeader($session, $user->username);

$isCurrentUserPage = $user_id == $session->getId();

if(isset($_GET['action']) && $_GET['action'] == 'profile') drawEditProfile($user);
else if (isset($_GET['action']) && $_GET['action'] == 'password')  drawChangePassword($user);
else drawProfile($user, $session,$isCurrentUserPage);

if (isset($_GET['action']) && $_GET['action'] == 'transactions') {
    $items = getBoughtItems($db, $user->id);
    drawItems($items, $session, "Your Transactions", $isCurrentUserPage, [], [], 0, false, 'transactions');
} else {
    $items = getItemsUser($db, $user->id);
    drawItems($items, $session, 'Your items to sell',$isCurrentUserPage);
}

drawFooter();
