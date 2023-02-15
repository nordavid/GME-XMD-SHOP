<?php
function isValidEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

function isValidUsername($username, $maxLength = 20)
{
    if (empty($username) || strlen($username) > $maxLength) {
        return false;
    }
    return true;
}
