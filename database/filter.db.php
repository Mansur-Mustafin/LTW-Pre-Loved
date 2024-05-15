<?php

declare(strict_types=1);
require_once(__DIR__ . '/../utils/validation.php');


function getEntitiesFromType(PDO $db, string $type) 
{
    $stmt = $db->prepare("SELECT * FROM $type");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function removeEntity(PDO $db, string $entity, string $type) 
{
    if(!is_valid_entity($type)) {
        exit();
    }
    $stmt = $db->prepare("DELETE FROM $type WHERE id=?");
    $stmt->execute([$entity]);
}

function addEntity(PDO $db, string $entity, string $type) 
{
    if(!is_valid_entity($type)) {
        exit();
    }
    $stmt = $db->prepare("INSERT INTO $type (name) SELECT (?) WHERE NOT EXISTS (SELECT name FROM $type WHERE name = ?)");
    $stmt->execute([$entity,$entity]);
}
