<?php

/* This file contains the main api regarding the privacy settings functionality in our front-end */
require_once 'db_api.php';

session_start();

function hide_all_privacy_settings($id) {
    $user_exists = user_exists($id);
    if ($user_exists == false) {
        return 0;
    }
}

function show_all_privacy_settings($id) {
    $user_exists = user_exists($id);
    if ($user_exists == false) {
        return 0;
    }
}