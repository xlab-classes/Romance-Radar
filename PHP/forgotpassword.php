<?php

require './db_api.php';

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $string_type = "string";
    $email = $_POST['Email'];
    if (validate($email, $string_type) && $id = get_user_id($email)){    

    }else{
        echo "User does not exist";
    }
}
header("Location: ../HTML/forgetpassword.html");
