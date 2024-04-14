<?php

declare(strict_types=1);

function getEntitiesFromType(PDO $db, $type) {
    $stmt = $db->prepare("SELECT * FROM $type");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function removeEntity(PDO $db,$entity,$type) {
    $stmt = $db->prepare("DELETE FROM $type WHERE id=?");
    $stmt->execute([$entity]);
}

function addEntity(PDO $db, $entity,$type) {
    $stmt = $db->prepare("INSERT INTO $type (name) SELECT (?) WHERE NOT EXISTS (SELECT name FROM $type WHERE name = ?)");
    $stmt->execute([$entity,$entity]);
}