<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');
require_once(__DIR__ . '/../database/filter.db.php');
require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');
require_once(__DIR__ . '/../templates/admin.tpl.php');

$db = getDatabaseConnection();
$user = getUser($db,$session->getName());
$allUsers = getAllUsers($db);
$tags = getTags($db);
$categories = getCategories($db);
$brands = getBrands($db);
$conditions = getConditions($db);
$items = getAllItems($db,100,0,null);

// if(!user->isAdmin()) die(header('Location: /'));

drawHeader($session, 'Admin Page');
drawSideBar();
switch ($_GET['value']) {
    case 'users':
        drawUsersAdmin($allUsers);
        break;
    /* TODO
    case 'statistics':
        drawStatisticsAdmin();
        break;
    */
    case 'tags':
        drawEntitiesAdmin($tags);
        break;
    case 'categories':
        drawEntitiesAdmin($categories);
        break;
    case 'brands':
        drawEntitiesAdmin($brands);
        break;
    case 'conditions':
        drawEntitiesAdmin($conditions);
        break;
    case 'items':
        drawItemsAdmin($items);
        break;
    default:
        break;
}
drawFooter();