<?php
require_once 'db_api.php';

session_start();

if(!isset($_SESSION['user'])){
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}

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
