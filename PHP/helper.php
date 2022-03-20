<?php

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}
function validate_pwd($password){
    return preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password);
}
