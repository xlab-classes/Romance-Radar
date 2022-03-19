<?php

require './db_api.php';
require './helper.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $string_type = "string";
    $int_type = "integer";
    
    $name = $_POST['Name'];
    $address = $_POST['Address'];
    $zip = (int)$_POST['Zip'];
    $city = $_POST['City'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $bday = $_POST['Date'];


    echo validate($name, $string_type);
    echo validate($address, $string_type);
    echo validate($zip, $int_type);
    echo validate($city, $string_type);
    echo validate($email, $string_type);
    echo validate($password, $string_type);
    echo validate($bday, $string_type);
    echo validate_pwd($password);

    if (validate($name, $string_type) && 
        validate($address, $string_type) && 
        validate($zip, $int_type) &&
        validate($city, $string_type) &&
        validate($email, $string_type) &&
        validate($password, $string_type) &&
        validate($bday, $string_type) &&
        validate_pwd($password)){
            create_user($name, $email, password_hash($password, PASSWORD_DEFAULT), $address, $zip, $bday);
        }else{
            echo "Failed to register";
        }
}
header("Location: ../HTML/registration.html");