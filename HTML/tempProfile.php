<?php

session_start();

if(isset($_SESSION['user'])){
    echo sprintf('<h1> Hello! %s</h1>', $_SESSION['user']['name']);
}else{
    header('Location: ./login.html');
}