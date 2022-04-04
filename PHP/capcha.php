<?php

require_once '../PHP/db_api.php';

// Start the session
session_start();

// Get all the random capcha from the database
$capchas = exec_query("SELECT * FROM capcha ORDER BY RAND() LIMIT 1", []);

// Select a random capcha
$capcha = $capchas[rand(0, count($capchas) - 1)];

// Select the code for the capcha that was selected
$code = $capcha['code'];

?>