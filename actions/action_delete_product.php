<?php 
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__.'/../database/item.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../core/Item.php');

$db = getDatabaseConnection();

if($request->isPost()){
    $product_id = $request->post('product_id');
    deleteItemById($db, intval($product_id));
}

header('Location: ../pages/admin.php?value=items');
exit;