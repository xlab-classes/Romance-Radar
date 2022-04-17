<?php

require_once('../db_api.php');

// This file contains common functions used during testing

// Instantly connect these two users, avoiding constraints of add_connection
// function
//
// parameter: user_a    [int]
//      The ID of one of the users to connect
//
// parameter: user_b    [int]
//      The ID of the other user to connect
//
// returns:
//      1 on success
//      0 on failure
//
// constraints:
//      These users must exist
function connect_users($user_a, $user_b) {
    if (!user_exists($user_a) || !user_exists($user_b)) {
        print("One user doesn't exist in connect_users\n");
        return 0;
    }

    // Complete the connection
    $query = "UPDATE Users SET partner=? WHERE id=?";

    // Add sent_from as a partner to sent_to
    $result = exec_query($query, [$user_a, $user_b]);
    if ($result == NULL) {
        print("Failed to exec_query in connect_users (testing function)\n");
        return 0;
    }

    // Add sent_to as a partner to sent_from
    $result = exec_query($query, [$user_b, $user_a]);
    if ($result == NULL) {
        print("Failed to exec_query in connect_users (testing function)\n");
        return 0;
    }

    return 1;
}


// Instantly remove the connection between these two users, avoiding
// constraints of add_connection function
//
// parameter: user_a    [int]
//      The ID of one of the users to connect
//
// parameter: user_b    [int]
//      The ID of the other user to connect
//
// returns:
//      1 on success
//      0 on failure
//
// constraints:
//      These users must exist
function connect_users($user_a, $user_b) {
    if (!user_exists($user_a) || !user_exists($user_b)) {
        print("One user doesn't exist in connect_users\n");
        return 0;
    }

    // Complete the connection
    $query = "UPDATE Users SET partner=? WHERE id=?";

    // Add sent_from as a partner to sent_to
    $result = exec_query($query, [$user_a, $user_b]);
    if ($result == NULL) {
        print("Failed to exec_query in connect_users (testing function)\n");
        return 0;
    }

    // Add sent_to as a partner to sent_from
    $result = exec_query($query, [$user_b, $user_a]);
    if ($result == NULL) {
        print("Failed to exec_query in connect_users (testing function)\n");
        return 0;
    }

    return 1;
}