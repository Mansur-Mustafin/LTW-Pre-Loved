<?php

declare(strict_types = 1);

function getDatabaseConnection() : PDO 
{
  try{
    $db = new PDO('sqlite:' . __DIR__ . '/../data/database.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return($db);
  }
  catch(PDOException $e) {
    die($e->getMessage());
  } 
}
