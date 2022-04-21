<?php
require_once 'db_api.php';

session_start();

if(!isset($_SESSION['user'])){
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {   
    print_r($_SESSION);
}
?>