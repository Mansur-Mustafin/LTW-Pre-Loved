<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
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
if(!$user->admin_flag) die(header('Location: /'));

$allUsers = getAllUsers($db);
$tags = getEntitiesFromType("Tags");
$categories = getEntitiesFromType("Categories");
$brands = getEntitiesFromType("Brands");
$conditions = getEntitiesFromType("Condition");
$sizes = getEntitiesFromType("Size");
$items = getAllItems(100, 0, null);

drawHeader($session, 'Admin Page');
drawSideBar();

switch ($_GET['value']) {
    case 'statistics':
        drawStatisticsAdmin();
        break;
    case 'tags':
        drawEntitiesAdmin($tags,"Tags");
        break;
    case 'categories':
        drawEntitiesAdmin($categories,"Categories");
        break;
    case 'brands':
        drawEntitiesAdmin($brands,"Brands");
        break;
    case 'condition':
        drawEntitiesAdmin($conditions,"Condition");
        break;
    case 'size':
        drawEntitiesAdmin($sizes,'Size');
        break;
    case 'items':
        drawItemsAdmin($items);
        break;
    default:
        drawUsersAdmin($allUsers,$session);
        break;
}

drawFooter();