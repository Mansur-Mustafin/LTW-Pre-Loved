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

function getTags(PDO $db) {
    return $db->query("SELECT * FROM Tags")->fetchAll(PDO::FETCH_ASSOC);
}

function removeTag(PDO $db, $tagId) {
    $stmt = $db->prepare("DELETE FROM Tags WHERE id=?");
    $stmt->execute([$tagId]);
}

function removeCategory(PDO $db, $category) {
    $stmt = $db->prepare("DELETE FROM Categories WHERE id=?");
    $stmt->execute([$category]);
}

function removeCondition(PDO $db, $condition) {
    $stmt = $db->prepare("DELETE FROM Condition WHERE id=?");
    $stmt->execute([$condition]);
}

function removeBrand(PDO $db, $brand) {
    $stmt = $db->prepare("DELETE FROM Brands WHERE id=?");
    $stmt->execute([$brand]);
}

function addTag(PDO $db, $tag) {
    $stmt = $db->prepare("INSERT INTO Tags (name) VALUES (?)");
    $stmt->execute([$tag]);
}

function addCategory(PDO $db, $category) {
    $stmt = $db->prepare("INSERT INTO Categories (name) VALUES (?)");
    $stmt->execute([$category]);
}

function addCondition(PDO $db, $condition) {
    $stmt = $db->prepare("INSERT INTO Conditions (name) VALUES (?)");
    $stmt->execute([$condition]);
}

function addBrand(PDO $db, $brand) {
    $stmt = $db->prepare("INSERT INTO Brands (name) VALUES (?)");
    $stmt->execute([$brand]);
}