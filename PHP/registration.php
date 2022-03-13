<?php

require './db_api.php';

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}

function validate_pwd($password){
    $exp = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$";
    return preg_match($exp, $password);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $string_type = "string";
    $int_type = "integer";
    
    $name = $_POST['Name'];
    $address = $_POST['Address'];
    $zip = $_POST['Zip'];
    $city = $_POST['City'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $bday = $_POST['Date'];

    if (validate($name, $string_type) && 
        validate($address, $string_type) && 
        validate($zip, $int_type) &&
        validate($city, $string_type) &&
        validate($email, $string_type) &&
        validate($password, $string_type) &&
        validate($bday, $string_type) &&
        validate_pwd($password)){
            create_user($name, $email, password_hash($password), $address, $zip, $bday);
        }
}

header("Location: ../HTML/registration.html");