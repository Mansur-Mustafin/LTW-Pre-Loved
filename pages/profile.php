<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');

$db = getDatabaseConnection();

$items = array(1,2,3,4,5,6);

$user = getUser($db, $session->getName());    // TODO search by id?

drawHeader($session);
drawProfile($user);
drawItems($items, $session, 'Your items to sell');
drawFooter();
?>
