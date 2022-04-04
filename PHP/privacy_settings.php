<?php
require_once '../PHP/db_api.php';
session_start();
if(!isset($_SESSION['user'])){
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $max_distance = (int)$_POST['MaxDistance'];
    $max_cost = (int)$_POST['MaxCost'];
    $date_length = (int)$_POST['DateLen'];
    $date_of_birth = $_POST['DOB'];
    $time_preference = $_POST['TimePref'];
    $entertainment_preference = $_POST['EntPref'];
    $venue_preference = $_POST['VenuePref'];
    $food_preference = $_POST['FoodPref'];

    if(!validate($max_distance, 'integer') || !validate($max_cost, 'integer') 
    || !validate($date_length, 'integer') || !validate($date_of_birth, 'string') 
    || !validate($time_preference, 'string') || !validate($entertainment_preference, 'string') 
    || !validate($venue_preference, 'string') || !validate($food_preference, 'string')){
        echo 'Invalid input';
        header('Location: ./profile_page.html');
        exit();
    }
    // If the verify user button is pressed
    if (isset($_POST['VerifyBtn'])) {
        
    }


$veriifed_status = get_verified_status($_SESSION['user']['id']);


