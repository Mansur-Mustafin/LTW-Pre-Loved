<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) exit(header('Location: /'));

$session->logout();

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
