<?php

declare(strict_types=1);

function getEntitiesFromType(PDO $db, $type) {
    $stmt = $db->prepare("SELECT * FROM $type");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function removeEntity(PDO $db,$entity,$type) {
    $stmt = $db->prepare("DELETE FROM :type_value WHERE id=?");
    $stmt->bindParam(':type_value',$type->value,PDO::PARAM_STR);
    $stmt->execute([$entity]);
}

function addEntity(PDO $db, $entity,$type) {
    $stmt = $db->prepare("INSERT INTO :type_value (name) SELECT (?) WHERE NOT EXISTS (SELECT name FROM :type_value WHERE name = ?)");
    $stmt->bindParam(':type_value',$type,PDO::PARAM_STR);
    $stmt->execute([$entity,$entity]);
}