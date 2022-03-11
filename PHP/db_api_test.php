# This file will contain tests for the respective db_api.php file.

<?php

# Test for the get_all_users() function.
function test_get_all_users() {
    $users = get_all_users();
    if (count($users) == 0) {
        echo "No users found.\n";
    } else {
        echo "Users found:\n";
        foreach ($users as $user) {
            echo $user['username'] . "\n";
        }
    }
}

# Test for the get_user_by_id() function.
function test_get_user_by_id() {
    # Get all the user ids
    $user_ids = get_all_user_ids();

}
# Test for the get_user_by_username() function.
function test_get_user_by_username() {
    # Get all the usernames
    $usernames = get_all_usernames();
}
?>