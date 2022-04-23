<?php

require_once './db_api.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {

    // Get user ID and make sure user is logged in
    session_start();

    $user_id = (int) $_SESSION["user"]["id"];
    if (!$user_id) {
        print("NO USER WITH THIS ID\n");
        exit();
    }     // User not logged in

    if (isset($_GET['status'])) {
        set_status($user_id, $_GET['status']);
    }

}

header("Location: ../HTML/profile_page.php");
