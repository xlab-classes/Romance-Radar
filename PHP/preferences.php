<?php

require "db_api.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $prefs = array();
    
    # Preferences for cost,distance,and time
    $prefs['Date_preferences']['cost'] = $_POST['MaxCost'];
    $prefs['Date_preferences']['distance'] = $_POST['MaxDist'];
    $prefs['Date_preferences']['length'] = $_POST['PreDateLen'];
    

    // Change personal information
    $first_name = $_POST['CngFN'] ;
    $last_name =  $_POST['CngLN'];
    $zip_code =  (int)$_POST['CngZip']; 
    $dob = $_POST['CngDob'];   // date of birth

    // Change password
    $old_pass = $_POST['OldPwd'] ;
    $new_pass = $_POST['NewPwd'] ; 
    $new_pass_v = $_POST['RenPwd'] ;     // verification
    
    // Change email
    $email = $email = $_POST['Email']; ;

    // Entertainment preferences
    if ($_POST["Entertainment"]) {  // If true, allow all entertainment types
        // TODO
    }
    $prefs['Entertainment']['concerts'] = $_POST["Concerts"]; 
    $prefs['Entertainment']['hiking'] = $_POST["Hiking"]; 
    $prefs['Entertainment']['bars'] = $_POST["Bars"]; 

    // Food preferences
    if ($_POST["Food"]) {  // If true, allow all food types
        // TODO
        
    }
    $prefs["Food"]['resturants'] = $_POST["Restaurant"];
    $prefs["Food"]['cafes'] = $_POST["Cafe"];
    $prefs["Food"]['fast_food'] = $_POST["FastFood"];
    $prefs["food"]['alcohol'] = $_POST["Alcohol"];
    
    // Venue preferences
    if ($venue = $_POST["Venue"]) {  // If true, allow all venues
        // TODO
    }
    $prefs['Venue']['indoors'] = $_POST["Indoors"];
    $prefs['Venue']['outdoors'] = $_POST["Outdoors"]; 
    $prefs['Venue']['social_events'] = $_POST["SocialEvents"]; 
    
    // Time preferences
    if ($_POST["Anytime"]) {  // If true, allow all times
        // TODO
    }
    $prefs['Date_time']['morning'] = $_POST["Morning"]; 
    $prefs['Date_time']['afternoon'] = $_POST["Afternoon"]; 
    $prefs['Date_time']['evening'] = $_POST["Evening"]; 

}

?>