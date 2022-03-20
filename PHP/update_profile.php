<?php

require "db_api.php";
require "profile_page.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user ID and make sure user is logged in
    start_session();
    $user_id = (int) $_SESSION["user"]["id"];
    if (!$user_id) return;      // User not logged in


    /**** PERSONAL INFORMATION ****/
    
    // Update name
    $first_name = $_POST['CngFN'] ;
    $last_name =  $_POST['CngLN'];
    $name = $first_name . " " . $last_name;
    if (!empty($first_name) && !empty($last_name)) {
        update_name($user_id, $name);
    }

    // Update zip code
    $zip_code =  (int)$_POST['CngZip'];
    if (!empty($zip_code)) {
        update_address($user_id, $zip_code);
    }

    // Update date of birth
    $dob = $_POST['CngDob'];
    if (!empty($dob)) {
        update_dob($user_id, $dob);
    }

    // Change password
    $old_pass = $_POST['OldPwd'] ;
    $new_pass = $_POST['NewPwd'] ; 
    $new_pass_v = $_POST['RenPwd'] ;     // verification
    if ($new_pass == $new_pass_v) {
        update_password($user_id, $old_pass, $new_pass);
    }
    
    // Update email
    $email = $email = $_POST['Email']; ;
    if (!empty($email)) {
        update_email($user_id, $email);
    }


    /**** PREFERENCES ****/
    $prefs = array();

    // Preferences for cost,distance,and time
    $prefs['Date_preferences']['cost'] = $_POST['MaxCost'];
    $prefs['Date_preferences']['distance'] = $_POST['MaxDist'];
    $prefs['Date_preferences']['length'] = $_POST['PreDateLen'];

    // Entertainment preferences
    if ($_POST["Entertainment"]) {  // If true, allow all entertainment types
        $prefs['Entertainment']['concerts'] = "1";
        $prefs['Entertainment']['hiking'] = "1";
        $prefs['Entertainment']['bars'] = "1";
    }
    else {
        $prefs['Entertainment']['concerts'] = $_POST["Concerts"];
        $prefs['Entertainment']['hiking'] = $_POST["Hiking"];
        $prefs['Entertainment']['bars'] = $_POST["Bars"];
    }

    // Food preferences
    if ($_POST["Food"]) {  // If true, allow all food types
        $prefs["Food"]['resturants'] = "1";
        $prefs["Food"]['cafes'] = "1";
        $prefs["Food"]['fast_food'] = "1";
        $prefs["food"]['alcohol'] = "1";
    }
    else {
        $prefs["Food"]['resturants'] = $_POST["Restaurant"];
        $prefs["Food"]['cafes'] = $_POST["Cafe"];
        $prefs["Food"]['fast_food'] = $_POST["FastFood"];
        $prefs["food"]['alcohol'] = $_POST["Alcohol"];
    }
    
    // Venue preferences
    if ($venue = $_POST["Venue"]) {  // If true, allow all venues
        $prefs['Venue']['indoors'] = "1";
        $prefs['Venue']['outdoors'] = "1";
        $prefs['Venue']['social_events'] = "1";
    }
    else {
        $prefs['Venue']['indoors'] = $_POST["Indoors"];
        $prefs['Venue']['outdoors'] = $_POST["Outdoors"];
        $prefs['Venue']['social_events'] = $_POST["SocialEvents"];
    }
    
    // Time preferences
    if ($_POST["Anytime"]) {  // If true, allow all times
        $prefs['Date_time']['morning'] = "1";
        $prefs['Date_time']['afternoon'] = "1";
        $prefs['Date_time']['evening'] = "1";
    }
    else {
        $prefs['Date_time']['morning'] = $_POST["Morning"];
        $prefs['Date_time']['afternoon'] = $_POST["Afternoon"];
        $prefs['Date_time']['evening'] = $_POST["Evening"];
    }

    update_preferences($user_id, $prefs);
}

?>