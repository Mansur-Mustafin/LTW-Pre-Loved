<?php
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  die(header('Location: /'));
}

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/connection.db.php');

$db = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['item-id'], $_POST['user-id'])) {
  var_dump($_POST['action']);

  $userId = intval($_POST['user-id']);
  $itemId = intval($_POST['item-id']);

  switch ($_POST['action']) {
    case 'cart-add':
      addToCart($db, $userId, $itemId);
      break;
    case 'cart-delete':
      removeFromCart($db, $userId, $itemId);
      break;
    case 'wishlist-add':
      addToWishlist($db, $userId, $itemId);
      break;
    case 'wishlist-delete':
      removeFromWishlist($db, $userId, $itemId);
      break;
    case 'delete':
      deleteItem($db, $userId, $itemId);
      break;
    case 'delete-main':
      deleteItem($db, $userId, $itemId);
      header('Location: /pages/profile.php');
      exit;
      break;
    default:
      die(header('Location: /'));
      break;
  }

}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
