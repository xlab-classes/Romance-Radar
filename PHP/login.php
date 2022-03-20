<?php

require './db_api.php';
require './helper.php';
#Checking request method
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $string_type = "string";
    $int_type = "integer";

    $email = $_POST['Email'];
    $password = $_POST['Password'];

    #Validating email and password
    if (validate($email, $string_type) &&
        validate($password, $string_type)){
            if(sign_in($email, $password) && $user = getUser(NULL, $email)->fetch_assoc()){
                session_start();
                $_SESSION['user'] = $user;
                header('Location: ../HTML/profile_page.html');
                exit();
            }else{
                exit('error occured');
            }
        }else{
            exit("Failed to login");
        }
}
header("Location: ../HTML/login.html");
