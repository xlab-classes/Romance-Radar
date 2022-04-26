<?php

require "./profile_page.php";

// TODO: Clear inputs after using??
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user ID and make sure user is logged in
    session_start();

    $user_id = (int) $_SESSION["user"]["id"];
    if (!$user_id) {
        print("NO USER WITH THIS ID\n");
        exit();
    }     // User not logged in


    /**** PERSONAL INFORMATION ****/
    
    // update profile picture
    
    if(!empty($_FILES['profile_picture']['name'])) {
        $extension_array = array('image/jpeg'=>'.jpg', 'image/png'=>'.png','image/gif' => '.gif');
        $new_profile_picture = $_FILES['profile_picture'];
        $content = file_get_contents($new_profile_picture['tmp_name']);
        update_profile_picture($user_id, $_SESSION["user"]["user_picture"], $content, $extension_array[$new_profile_picture['type']]);
    } 

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
    $new_pass_v = $_POST['RenPwd'] ;    // verification
    if ($new_pass == $new_pass_v) {
        update_password($user_id, $old_pass, $new_pass);
    }
    
    // Update email
    $email = $_POST['CngEmail'];
    if (!empty($email)) {
        update_email($user_id, $email);
    }


    /**** PREFERENCES ****/
    $prefs = array();

    # Only update preferences if they are not empty
    

    // Preferences for cost,distance,and time
    if (!empty($_POST["MaxCost"])) {
        $prefs['Date_preferences']['cost'] = (int)$_POST['MaxCost'];
    }
    if (!empty($_POST["MaxDist"])) {
        $prefs['Date_preferences']['distance'] = (int)$_POST['MaxDist'];
    }
    if (!empty($_POST["PreDateLen"])) {
        $prefs['Date_preferences']['length'] = (int)$_POST['PreDateLen'];
    }
    print_r($_POST);
    // Entertainment preferences
    if (!empty($_POST["Entertainment"])) {  // If true, allow all entertainment types
        $prefs['Entertainment']['concerts'] = 1;
        $prefs['Entertainment']['hiking'] = 1;
        $prefs['Entertainment']['bar'] = 1;
    }
    else {
        if (!empty($_POST["Concerts"])) {
            $prefs['Entertainment']['concerts'] = 1;
        }else{
            $prefs['Entertainment']['concerts'] = 0;
        }
        if (!empty($_POST["Hiking"])) {
            $prefs['Entertainment']['hiking'] = 1;
        }else{
            $prefs['Entertainment']['hiking'] = 0;
        }
        if (!empty($_POST["Bars"])) {
            $prefs['Entertainment']['bar'] = 1;
        }else{
            $prefs['Entertainment']['bar'] = 0;
        }
    }

    // Food preferences
    if (!empty($_POST["Food"])) {  // If true, allow all food types
        $prefs["Food"]['restaurant'] = 1;
        $prefs["Food"]['cafe'] = 1;
        $prefs["Food"]['fast_food'] = 1;
        $prefs["Food"]['alcohol'] = 1;
    }
    else {
        if (!empty($_POST["Restaurant"])) {
            $prefs["Food"]['restaurant'] = 1;
        }else{
            $prefs["Food"]['restaurant'] = 0;
        }
        if (!empty($_POST["Cafe"])) {
            $prefs["Food"]['cafe'] = 1;
        }else{
            $prefs["Food"]['cafe'] = 0;
        }
        if (!empty($_POST["FastFood"])) {
            $prefs["Food"]['fast_food'] = 1;
        }else{
            $prefs["Food"]['fast_food'] = 0;
        }
        if (!empty($_POST["Alcohol"])) {
            $prefs["Food"]['alcohol'] = 1;
        }else{
            $prefs["Food"]['alcohol'] = 0;
        }
    }
    
    // Venue preferences
    if (!empty($_POST["Venue"])) {  // If true, allow all venues
        $prefs['Venue']['indoors'] = 1;
        $prefs['Venue']['outdoors'] = 1;
        $prefs['Venue']['social_events'] = 1;
    }
    else {
        if (!empty($_POST["Indoors"])) {
            $prefs['Venue']['indoors'] = 1;
        }else{
            $prefs['Venue']['indoors'] = 0;
        }
        if (!empty($_POST["Outdoors"])) {
            $prefs['Venue']['outdoors'] = 1;
        }else{
            $prefs['Venue']['outdoors'] = 0;
        }
        if (!empty($_POST["SocialEvents"])) {
            $prefs['Venue']['social_events'] = 1;
        }else{
            $prefs['Venue']['social_events'] = 0;
        }
    }
    
    // Time preferences
    if (!empty($_POST["Anytime"])) {  // If true, allow all times
        $prefs['Date_time']['morning'] = 1;
        $prefs['Date_time']['afternoon'] = 1;
        $prefs['Date_time']['evening'] = 1;
    }
    else {
        if (!empty($_POST["Morning"])) {
            $prefs['Date_time']['morning'] = 1;
        }else{
            $prefs['Date_time']['morning'] = 0;
        }
        if (!empty($_POST["Afternoon"])) {
            $prefs['Date_time']['afternoon'] = 1;
        }else{
            $prefs['Date_time']['afternoon'] = 0;
        }
        if (!empty($_POST["Evening"])) {
            $prefs['Date_time']['evening'] = 1;
        }else{
            $prefs['Date_time']['evening'] = 0;
        }
    }

    update_preferences($user_id, $prefs);
}

header("Location: ../HTML/profile_page.php");
