<?php


require_once './db_api.php';

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
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if($_GET['type']){
        $to_id=$_GET['to_id'];
        $from_id=$_GET['from_id'];
        add_connection($from_id,$to_id);
    }else{
        $from_id=$_GET['from_id'];
        remove_connection_request($from_id);
    }
    $current_user = getUser($to_id,NULL)->fetch_assoc();
    $_SESSION['user'] = $current_user;
    header('Location: ../HTML/connection.php');
}
?>