<?php
require_once 'db_api.php';

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
}


$veriifed_status = get_verified_status($_SESSION['user']['id']);



if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categories = array(
    'MaxCost'=>'max_cost',
    'MaxDistance'=>'max_distance',
    'DateLen'=>'date_len',
    'DOB'=>'date_of_birth',
    'TimePref'=>'time_pref',
    'FoodPref'=>'food_pref',
    'EntPref'=>'ent_pref',
    'VenuePref'=>'venue_pref');
    $selected_settings = array();
    foreach($categories as $cat=>$alias){
        $selected_settings[$alias] = isset($_POST[$cat])?1:0;
    }

    if(!update_privacy((int)$_SESSION['user']['id'], $selected_settings)){
        echo 'Settings not updated';
        exit();
    }
    header('Location: ../HTML/privacy_settings.php');
}
