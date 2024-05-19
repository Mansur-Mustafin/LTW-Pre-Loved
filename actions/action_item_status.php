<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/connection.db.php');

$db = getDatabaseConnection();



if ($request->isGet() && $request->get('action') !== null && $request->get('item-id') !== null) {

  $response = [
    'success' => true,
  ];

  $userId = $session->getId();
  $itemId = intval($request->get('item-id'));

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
      $response['redirect'] = $request->get('action') === 'delete-main' ? '/pages/profile.php' : null;
      break;
    case 'edit-main':
      $response['success'] = true;
      $response['redirect'] = '../pages/edit_item.php?item_id=' . $itemId;
      header('Content-Type: application/json');
      echo json_encode($response);
      exit;
    default:
      $response['success'] = false;
  }

  echo json_encode($response);
  die();
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
