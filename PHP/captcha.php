<?php
require_once 'db_api.php';

session_start();

if(!isset($_SESSION['user'])){
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {   
    if($_SESSION['captcha']['code'] == $_POST['captcha'] && verify_user($_SESSION['user']['id'])){
        $_SESSION['user']['verified'] = 1;
        header('Location: ../HTML/privacy_settings.php');
        exit();
    }
    echo 'Could not verify user: '.strval($_SESSION['user']['id']);
}
?>