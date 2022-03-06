<!--
Author: Alex Eastman
Created: 03/06/2022
Modified: 03/06/2022

Database api
-->
<?php

$host = 'localhost';
$user = 'root';
$password = 'diuFTC7#';
$db = 'rrdb';

$connection = mysqli_connect($host, $user, $password, $db);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
echo "Connected successfully";

/* TODO: Add to API docs */
# Create database if it doesn't exist
function create_db() {

}

/* TODO: Add to API docs */
# Create a table called 'users' in database if it doesn't exist
function create_table() {

}

/* TODO: Add to API docs */
# Destroy database
function destroy_db() {

}

/* TODO: Add to API docs */
# Destroy table 'users' in database if it exists
function destroy_table() {

}

# Check if there is an existing account with this email
function user_exists($user_id) {

}

# Get this user's ID by their email
function get_user_id($email) {

}

# Attempt to sign in the user whose email is `email` and whose password is
# `password`
function sign_in($email, $password) {

}

# Attempt to sign out the user with ID `user_id`
function sign_out($user_id) {

}

# Check that the password matches the password stored for the user with ID
# `user_id`
function check_password($user_id, $password) {

}

# Attempt to change the password of the user with ID `user_id`
function update_password($user_id, $old_pwd, $new_pwd) {

}

# Creates a new user and stores their data in the database. This function will
# create a unique user ID for the new user
function create_user($name, $email, $pwd, $phone) {

}

# Removes all of a user's data from the database
function delete_user($user_id) {

}

# Attempt to connect the users with IDs `user_id_a` and `user_id_b`. This
# requires that one of the users has sent a connection request and the other
# one has a pending request from the sender
function add_connection($user_id_a, $user_id_b) {

}

# Add a request to connect to the user with ID `user_id_rx`. Add the pending
# connection to the user with ID `user_id_tx`
function add_connection_request($user_id_tx, $user_id_rx) {

}

# Attempt to disconnect the users with IDs `user_id_a` and `user_id_b`. This
# requires that a connection exists between these users
function remove_connection($user_id_a, $user_id_b) {

}

# Get a JSON-formatted string of connections for the user with ID `user_id`
function get_connections($user_id) {

}

# Get the preferences of the user with ID `user_id`
function get_preferences($user_id) {

}

function update_preferences($user_id, $preferences) {

}

/* TODO: Add to API docs */
# Get the connection requests that this user needs to respond to
function get_requests($user_id) {

}

/* TODO: Add to API docs */
# Get the connection requests that this user has sent
function get_pending($user_id) {

}

?>