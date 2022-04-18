<?php

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}
function validate_pwd($password){
    return TRUE;
}
