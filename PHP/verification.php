<?php

// File that contains account verification logic via a captcha

/* This function generates a random string of a random length */
function generate_captcha(){
    $length = rand(5, 10);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/* Comapres user input to the genrated capcha */
function compare_captcha($captcha, $user_input){
    if($captcha == $user_input){
        return true;
    }
    return false;
}

/* Verifies that the user is a valid user via a captcha */
function verify_user($id, $captcha){

    // Get user-inputted captcha from the front-end testbox

    if(empty($id) || empty($captcha)){
        return false;
    }
    
    //TODO(Jordan): Get user-inputted captcha from the front-end textbox
    $user_input = $_POST['captcha'];

    $user_status = user_exists($id);
    if($user_status == false){
        return false;
    }
    if(compare_captcha($captcha, $user_input)){
        return true;
    }
    return false;
}