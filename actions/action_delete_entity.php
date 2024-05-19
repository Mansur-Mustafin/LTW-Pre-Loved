<?php 
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__.'/../database/filter.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../core/Item.php');

$db = getDatabaseConnection();

if($request->isPost()){
    $type_value = explode("/", $request->post("typeValue"));
    $type = $type_value[0];
    $value = $type_value[1];

    removeEntity($db,$value,$type);
}

header("Location: ../pages/admin.php?value=". strtolower($type));   // TODO .""?
exit;