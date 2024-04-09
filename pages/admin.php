<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');
require_once(__DIR__ . '/../templates/admin.tpl.php');

$db = getDatabaseConnection();
$user = getUser($db,$session->getName());
$allUsers = getAllUsers($db);

// if(!user->isAdmin()) die(header('Location: /'));


drawHeader($session, 'Admin Page');
drawSideBar();
switch ($_GET['value']) {
    case 'users':
        drawUsersAdmin($allUsers);
        break;
    case 'statistics':
        drawStatisticsAdmin();
        break;
    case 'tags':
        drawTagsAdmin();
        break;
    case 'items':
        drawItemsAdmin();
        break;
    default:
        break;
}
drawFooter();