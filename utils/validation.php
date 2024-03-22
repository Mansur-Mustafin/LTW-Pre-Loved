<?php

/*
This file contain all validation  functions.
*/

declare(strict_types=1);

function is_valid_email(?String $email) : bool{
    return isset($email) && !empty($email);
}

?>