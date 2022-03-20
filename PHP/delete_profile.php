<?php

require_once "./db_api.php"

// TODO: Clear inputs after using??
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user ID and make sure user is logged in
    session_start();
    $user_id = (int) $_SESSION["user"]["id"];
    if (!$user_id) {
        print("NO USER WITH THIS ID\n");
        header("Location: ../HTML/profile_page.html")
        exit();
    }     // User not logged in

    if (delete_user($user_id)) {
        print("Successfully deleted user\n");
    }
    else {
        print("Couldn't delete user\n");
    }
}

header("Location: ../HTML/landing.html");
