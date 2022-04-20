<?php

/* This file contains the main api regarding the privacy settings functionality in our front-end */
require_once 'db_api.php';

session_start();

/* Function that chjecks if privacy settings have been set */
function all_privacy_settings_set($id) {
    // An array of the privacy settings
    $privacy_settings = array('MaxCost','MaxDistance','DateLen','DOB','TimePref','FoodPref','EntPref','VenuePref');
    // Check if at least one privacy setting has been set for the user if not return false
    foreach ($privacy_settings as $setting) {
        if(!isset($_POST[$setting])) {
            return 0; /* If at least one privacy setting has not been set, return 0 */
        }
    }
    return 1; /* If all privacy settings have been set, return 1 */
}

/* Function that hides all the privacy settings for a user. */
/* Will return 0 if the user doesn not exist else hides all the privacy settings for the user*/
function hide_all_privacy_settings($id) {
    // Check if user exists
    $user_exists = user_exists($id);
    if ($user_exists == false) {
        return 0;
    }

    // Check if user has any privacy settings ticked
    
    //TODO: Change it so that it checks if the select all privacy settings is ticked
    
    $privacy_setting = all_privacy_settings_set($id);
    
    if($privacy_setting == 1) {
        // Create a javascript script to hide all elemnts with the the resepctive id
        echo '
        <script language="javascript">
            document.getElementById("MaxCost").style.display = "none";
            document.getElementById("MaxDistance").style.display = "none";
            document.getElementById("DateLen").style.display = "none";
            document.getElementById("DOB").style.display = "none";
            document.getElementById("TimePref").style.display = "none";
            document.getElementById("FoodPref").style.display = "none";
            document.getElementById("EntPref").style.display = "none";
            document.getElementById("VenuePref").style.display = "none";
        </script>
        ';
        return update_privacy($id, $privacy_setting);
        }
    else{
    return  show_all_privacy_settings($id);
}
}

/* Function that will show all the privacy settings for a user */
/* Function retunrs 0 in the case that the user doesn't exist or the user has no privacy settings selected */
function show_all_privacy_settings($id) {
    
    // Check if user exists
    $user_exists = user_exists($id);
    if ($user_exists == false) {
        return 0;
    }

    // Check if user has any privacy settings ticked
    
    //TODO: Change it so that it checks if the select all privacy settings is ticked
    
    $privacy_setting = all_privacy_settings_set($id);

    
    if($privacy_setting == 0){
        // Create a javascript script to show all elemnts with the the resepctive id
        echo '
        <script language="javascript">
            document.getElementById("MaxCost").style.display = "block";
            document.getElementById("MaxDistance").style.display = "block";
            document.getElementById("DateLen").style.display = "block";
            document.getElementById("DOB").style.display = "block";
            document.getElementById("TimePref").style.display = "block";
            document.getElementById("FoodPref").style.display = "block";
            document.getElementById("EntPref").style.display = "block";
            document.getElementById("VenuePref").style.display = "block";
        </script>
        ';
        return update_privacy($id, $privacy_setting);
        }
    else{
        return hide_all_privacy_settings($id);
    }
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