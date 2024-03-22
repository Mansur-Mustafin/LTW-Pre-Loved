<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

// require_once(__DIR__ . '/../database/connection.db.php');
// require_once(__DIR__ . '/../database/artist.class.php');

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');

$session->setId(123);

drawHeader($session);
drawFilter();
drawItems([], $session);
drawFooter();
?>