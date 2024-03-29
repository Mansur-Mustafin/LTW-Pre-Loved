<?php

declare(strict_types=1);

function getCategories(PDO $db) {
    return $db->query("SELECT * FROM Categories")->fetchAll(PDO::FETCH_ASSOC);
}

function getBrands(PDO $db) {
    return $db->query("SELECT * FROM Brands")->fetchAll(PDO::FETCH_ASSOC);
}

function getModels(PDO $db) {
    return $db->query("SELECT * FROM Models")->fetchAll(PDO::FETCH_ASSOC);
}

function getSizes(PDO $db) {
    return $db->query("SELECT * FROM Size")->fetchAll(PDO::FETCH_ASSOC);
}

function getConditions(PDO $db) {
    return $db->query("SELECT * FROM Condition")->fetchAll(PDO::FETCH_ASSOC);
}


