<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) {
    echo json_encode(['error'=> 'Permission denied']);
    die();
}

require_once(__DIR__.'/../core/item.class.php');
require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/filter.db.php');
require_once(__DIR__.'/../database/user.db.php');


$db = getDatabaseConnection();

$user = getUser($db,$session->getName());
if(!$user->admin_flag) {
    echo json_encode(['error'=> 'Permission denied']); 
    die();
}

const possibleEntitiesTypes = ["Categories", "Size", "Condition","Tags", "Brands", "Models"]; 

if(in_array($_GET['search'],possibleEntitiesTypes)) {
    $entities = getEntitiesFromType($db,$_GET['search']);
    echo json_encode($entities);
} else {
    echo json_encode(['error'=> 'Invalid Entity']);
}

