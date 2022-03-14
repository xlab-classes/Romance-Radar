<?php

require './db_api.php';

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}

function validate_pwd($password){
    return preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $string_type = "string";
    $int_type = "integer";
    
    $email = $_POST['Email'];
    $password = $_POST['Password'];


    echo validate($email, $string_type);
    echo validate($password, $string_type);
    echo validate_pwd($password);

    if (validate($email, $string_type) &&
        validate($password, $string_type) &&
        validate_pwd($password)){
            sign_in($email, password_hash($password, PASSWORD_DEFAULT));
        }else{
            echo "Failed to login";
        }
}
header("Location: ../HTML/login.html");