<?php

/* This file contains the main api regarding the privacy settings functionality in our front-end */
require_once 'db_api.php';

session_start();

/* This function will take a user id and update its privacy settings to either all true or all false */
function update_privacy($id, $privacy_setting_choice) {
    if (empty($id) || empty($privacy_setting_choice)) {
        return 0;       // Can't have empty inputs
    }

    $user_exists = user_exists($id);
    if ($user_exists == false) {return 0;}            // User must exist
    else {
        // We are doing an either or on the privacy settings, so you either can see all or you can't
        $query = "UPDATE Privacy_settings SET max_cost=? max_distance=? date_len=? date_of_birth=? time_pref=? food_pref=? ent_pref=?, venue_pref=?, WHERE id=?";
        $data = [
            $privacy_setting_choice,
            $privacy_setting_choice,
            $privacy_setting_choice,
            $privacy_setting_choice,
            $privacy_setting_choice,
            $privacy_setting_choice, 
            $privacy_setting_choice, 
            $privacy_setting_choice, 
            $id];
        $result = exec_query($query, $data);
        if ($result == NULL) {
            return 0;
         } // Failed to execute query
         $_SESSION['user']['privacy_settings'] = $privacy_setting_choice;
         return 1;
    }
}

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
        ?>
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
        <?
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
        ?>
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
        <?
        return update_privacy($id, $privacy_setting);
        }
    else{
        return hide_all_privacy_settings($id);
    }
    }


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    print_r($_POST);
}