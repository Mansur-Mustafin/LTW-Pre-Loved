<?php

declare(strict_types=1);
require_once(__DIR__ . '/../utils/validation.php');


function getEntitiesFromType(string $type): array
{
    $qb = new QueryBuilder();
    return $qb->select()->from($type)->all();
}

function removeEntity(PDO $db, string $entity, string $type): void
{
    if(!is_valid_entity($type)) {
        exit();
    }
    $stmt = $db->prepare("DELETE FROM $type WHERE id=?");
    $stmt->execute([$entity]);
}

function addEntity(PDO $db, string $entity, string $type): void
{
    if(!is_valid_entity($type)) {
        exit();
    }
    $stmt = $db->prepare("INSERT INTO $type (name) SELECT (?) WHERE NOT EXISTS (SELECT name FROM $type WHERE name = ?)");
    $stmt->execute([$entity,$entity]);
}
