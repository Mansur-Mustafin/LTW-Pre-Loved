<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__.'/../utils/Session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
  die(header('Location: /'));
}

require_once(__DIR__ . '/../core/Item.php');
require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/user.db.php');

$db = getDatabaseConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $oldItem = $_GET['item_id'] ?? null;

  if ($oldItem === null) {
    $session->addMessage('error', 'No item ID provided.');
    header('Location: ../pages/profile.php');
      exit;
  }
  $itemTitle = $_POST['item-title'];
  $itemDescription = $_POST['item-description'] ?? '';
  $itemPathArray = [""];
  if (!empty($_FILES["item-images"]["name"])) {
    if (count($_FILES["item-images"]["name"]) > 3) {
      $session->addMessage("error", "Choose only 3 photos!");
      header('Location: ../pages/edit_item.php');
      exit;
    }
    $uploadDirectory = "../data/item_img/";
    if (!is_dir($uploadDirectory)) {
      mkdir($uploadDirectory, 0755, true);
    }
    foreach ($_FILES["item-images"]["name"] as $key => $filename) {
      $fileTmpName = $_FILES["item-images"]["tmp_name"][$key];
      $fileType = $_FILES["item-images"]["type"][$key];
      $fileSize = $_FILES["item-images"]["size"][$key];
      $fileError = $_FILES["item-images"]["error"][$key];
      if ($fileError === 0) {
        $targetFilePath = $uploadDirectory . basename($filename);
        move_uploaded_file($fileTmpName, $targetFilePath);
        $itemPathArray[$key] = $targetFilePath;
      } 
    }
  } else {
      $itemPathArray[0] = "../assets/img/default_item.svg";
  }
  $itemImages = json_encode($itemPathArray);
  $itemPrice = (int)$_POST['item-price'];
  if($itemPrice < 0){
    $session->addMessage("error", "Invalid price!");
    header('Location: ../pages/add_item.php');
    exit;
  }
  $itemTradable = isset($_POST['tradable-item']) ? 1 : 0;
  $itemUser_id = getUser($db, $session->getName())->id;
  $itemCategory = $_POST['item-category'];
  $itemSize = $_POST['item-size'];
  $itemCondition = $_POST['item-condition'];
  $itemModel = $_POST['item-model'];
  $itemPriority = 1;

  $item = new Item(
    price: $itemPrice,
      user_id: $itemUser_id,
      tradable: $itemTradable,
      priority: $itemPriority,
      model: $itemModel,
      description: $itemDescription,
      title: $itemTitle,
      images: $itemImages,
      condition: $itemCondition,
      category: $itemCategory,
      size: $itemSize,
  );

  updateItem($session,$db,$item,intval($oldItem));
}
header('Location: ../pages/profile.php');
exit;