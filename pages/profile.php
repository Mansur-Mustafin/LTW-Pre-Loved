<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
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


// TODO: Is there better solution?
// Find all items that was sold, and not filter them?
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'sold') {
        $allItems = getItemsUser($user->id);
        $items = array_filter($allItems, function($item) {
            return in_array('Sold', $item->tags);
        });
        drawItems($items, $session, "Your Sold Items", $isCurrentUserPage, place: 'sold');
    } elseif ($_GET['action'] == 'transactions') {
        $items = getBoughtItems($user->id);
        drawItems($items, $session, "Your Transactions", $isCurrentUserPage, place: 'transactions');
    }
} else {
    $allItems = getItemsUser($user->id);
    $items = array_filter($allItems, function($item) {
        return !in_array('Sold', $item->tags);
    });
    drawItems($items, $session, 'Your items to sell', $isCurrentUserPage, place: 'profile');
}

drawFooter();
