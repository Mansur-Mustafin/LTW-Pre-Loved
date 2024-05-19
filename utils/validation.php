<?php

/*
This file contain all validation  functions.
*/

declare(strict_types=1);

const possibleEntitiesTypes = ["Categories", "Size", "Condition","Tags", "Brands", "Models"]; 

function is_valid_email(?String $email) : bool{
    return !empty($email);
}

function is_valid_entity(string $value) : bool {
    return in_array($value, possibleEntitiesTypes);
}
