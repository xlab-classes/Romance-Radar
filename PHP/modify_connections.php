<?php

require './db_api.php';
require './helper.php';
#Checking request method
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_connection'])) {
    if(!isset($_SESSION['user'])){
        echo 'Please Login!';
        exit();
    }

    if($_SESSION['user']['id'] == $_SESSION['user']['partner']){
        echo 'You don\'t have connection';
        exit();
    }

    if(!remove_connection((int)$_SESSION['user']['id'], (int)$_SESSION['user']['partner'])){
        echo 'Connection could not be removed';
        exit();
    }
    $_SESSION['user']['id'] = $_SESSION['user']['partner'];
    unset($_SESSION['partner']);
    header('Location: ../HTML/connection.php');
}
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['connection_request'])) {
    $email = $_POST['connection_request'];
    $result = getUser(NULL, $email);
    if(!$result){
        echo 'Email malformed';
        exit();
    }
    if(!$other_user = $result->fetch_assoc()){
        echo 'User not found';
        exit();
    }
    print_r($other_user);
    if(!add_connection_request((int)$_SESSION['user']['id'], (int)$other_user['id'])){
        echo 'Adding connection request failed';
    }
    header('Location: ../HTML/connection.php');
}
?>