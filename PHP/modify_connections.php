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

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like'])) {
    $date_id = (int)$_POST['date_id'];
    $user_opinion = (int)$_POST['opinion'];
    if($user_opinion == 0 && like_date($_SESSION['user']['id'], $date_id)){
        header('Location: ../HTML/connection.php');
    }else if($user_opinion == 1 && unlike_date($_SESSION['user']['id'], $date_id)){
        header('Location: ../HTML/connection.php');
    }
    echo 'Liking Failed';
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dislike'])) {
    $date_id = (int)$_POST['date_id'];
    $user_opinion = (int)$_POST['opinion'];
    if($user_opinion == 0 && dislike_date($_SESSION['user']['id'], $date_id)){
        header('Location: ../HTML/connection.php');
    }else if($user_opinion == -1 && unlike_date($_SESSION['user']['id'], $date_id)){
        header('Location: ../HTML/connection.php');
    }
    echo 'DisLiking Failed';
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if($_GET['type']){
        $to_id=$_GET['to_id'];
        $from_id=$_GET['from_id'];
        if(add_connection($from_id,$to_id)){
            $_SESSION['user']['partner'] = $from_id; 
            $_SESSION['partner'] = getUser($from_id, NULL)->fetch_assoc();
            $_SESSION['partner']['privacy_settings'] = get_privacy_settings($from_id);
        }
    }else{
        $from_id=$_GET['from_id'];
        remove_connection_request($from_id);
    }
    header('Location: ../HTML/connection.php');
}
?>