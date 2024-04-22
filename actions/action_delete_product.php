<?php 
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../core/item.class.php');

$db = getDatabaseConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $product_id = $_POST['product_id'];
    deleteItembyId($db, intval($product_id));
}

header('Location: ../pages/admin.php?value=items');
exit;