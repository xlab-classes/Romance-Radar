<?php

require "./privacy_settings.php";

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user ID and make sure user is logged in
    session_start();

    $user_id = (int) $_SESSION["user"]["id"];
    if (!$user_id) {
        print("NO USER WITH THIS ID\n");
        exit();
    }     // User not logged in

    // Get the user id and see whther or not the privacy settings are set
    $privacy_settings = all_privacy_settings_set($user_id);

    // Update the privacy settings
    update_privacy_settings($user_id, $privacy_settings);

    // Check if the privacy settings are set in the session
    if (!isset($_SESSION['user']["privacy_settings"])) {
        $_SESSION['user']["privacy_settings"] = $privacy_settings;
    }

    // Based on the current privacy setting of the user, call the appropriate function

    if($privacy_settings == 0){
        hide_all_privacy_settings($user_id);
    }
    else{
        show_all_privacy_settings($user_id);
    }
}

header("Location: ../HTML/privacy_settings.php");
