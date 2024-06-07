<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

// require_once(__DIR__ . '/../database/connection.db.php');

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../templates/forms.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.db.php');

$db = getDatabaseConnection();
$allCountries = getCountries($db);

drawHeader($session, 'Enter in account');
drawLoginForm($session, $allCountries);
drawFooter();
