<?php
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  header('Location: /');
  die();
}

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/connection.db.php');

$db = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'], $_GET['item-id'])) {

  $response = [
    'success' => true,
  ];

  $userId = $session->getId();
  $itemId = intval($_GET['item-id']);

  switch ($_GET['action']) {
    case 'cart-toggle':
      toggleCartItem($db, $userId, $itemId);
      break;
    case 'wishlist-toggle':
      toggleWishlistItem($db, $userId, $itemId);
      break;
    case 'delete':
    case 'delete-main':
      deleteItemById($db, $itemId);
      $response['success'] = true;
      $response['itemId'] = $itemId;
      $response['redirect'] = $_GET['action'] === 'delete-main' ? '/pages/profile.php' : null;
      break;
    default:
      $response['success'] = false;
  }

  echo json_encode($response);
  exit();
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
