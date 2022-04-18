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
    
    $question_id_1 = (int)$_POST['Question_id_1'];
    $question_id_2 = (int)$_POST['Question_id_2'];
    $question_id_3 = (int)$_POST['Question_id_3'];


    $answer_1 = $_POST['Question_1'];
    $answer_2 = $_POST['Question_2'];
    $answer_3 = $_POST['Question_3'];


    if (validate($name, $string_type) && 
        validate($address, $string_type) && 
        validate($zip, $int_type) &&
        validate($city, $string_type) &&
        validate($email, $string_type) &&
        validate($password, $string_type) &&
        validate($bday, $string_type) &&
        validate_pwd($password) &&
        validate($question_id_1, $int_type) &&
        validate($question_id_2, $int_type) &&
        validate($question_id_3, $int_type) &&
        validate($answer_1, $string_type) &&
        validate($answer_2, $string_type) &&
        validate($answer_3, $string_type)
        ){
            if(!create_user($name, $email, password_hash($password, PASSWORD_DEFAULT), $address, $city, $zip, $bday)){
                header("Location: ../HTML/registration.php");
                exit('Failed to create a user');
            }
            $user_id = get_user_id($email);
            $data = array($user_id,
                $question_id_1, $question_id_2, $question_id_3, $answer_1, $answer_2, $answer_3
            );
            $sq = addSecurityQuestions($user_id, $data);
            if(!$sq){
                header("Location: ../HTML/registration.php");
                exit('Failed to insert security questions');
            }
            header("Location: ../HTML/login.html");
            exit();
        }else{
            echo "Failed to register";
            header("Location: ../HTML/registration.php");
            exit();
        }
}else{
    header("Location: ../HTML/registration.php");
    exit();
}