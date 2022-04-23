<?php

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {

    // Get user ID and make sure user is logged in
    session_start();

    $user_id = (int) $_SESSION["user"]["id"];
    if (!$user_id) {
        print("NO USER WITH THIS ID\n");
        exit();
    }     // User not logged in

    echo "STATUS: $_GET['status']\n";

}

header("Location: ../HTML/profile_page.php");
