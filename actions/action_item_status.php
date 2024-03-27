<?php
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  die(header('Location: /'));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['item-id'], $_POST['user-id'])) {
    $userId = $_POST['user-id'];
    $itemId = $_POST['item-id'];
    $action = $_POST['action'];

    if ($action == 'cart') {
        
    }else if ($action == 'wishlist'){

    }else if ($action == 'delete'){

    }

}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;