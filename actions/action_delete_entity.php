<?php 
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__.'/../database/filter.db.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../core/item.class.php');

$db = getDatabaseConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $type_value = explode("/", $_POST["typeValue"]);
    $type = $type_value[0];
    $value = $type_value[1];

    removeEntity($db,$value,$type);
}

header("Location: ../pages/admin.php?value=". strtolower($type) ."");
exit;