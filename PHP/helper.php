<?php

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}
function validate_pwd($password){
    return TRUE;
}

function update_session_variables(){

    if(isset($_SESSION['user'])){
        $_SESSION['user'] = getUser($_SESSION['user']['id'], NULL)->fetch_assoc();

        $_SESSION['user']['privacy_settings'] = get_privacy_settings($_SESSION['user']['id']);
    }
    if($_SESSION['user']['id'] != $_SESSION['user']['partner']){
        $_SESSION['partner'] = getUser($_SESSION['user']['partner'], NULL)->fetch_assoc();
        $_SESSION['partner']['privacy_settings'] = get_privacy_settings($_SESSION['user']['partner']);
    }else{
        unset($_SESSION['partner']);
    }

}