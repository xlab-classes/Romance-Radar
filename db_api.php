<!--
Authors: Alex Eastman, Jordan Grant
Created: 03/06/2022
Modified: 03/07/2022

Database api
-->

<?php

$host = 'localhost';
$user = 'root';
$password = 'diuFTC7#';
$db = 'rrdb';

# Helper function. Not part of the API
# Takes in a SQL statement to execute.
# Returns:
# * The mysqli_result object of the SQL statement if executed successfully
# * NULL if there was a problem executing the SQL statement
function exec_query($query) {
    $connection = new mysqli($host, $user, $password);

    # Error connecting, return NULL
    if ($connection->connect_error) {
        echo "Connection failed: (" . $connection->errno . ") ." $connection->error;
        return NULL;
    }

    # Error executing, return NULL
    if (!$result = $connection->query($query)) {
        echo "Failed to execute SQL statement: " . $query;
        $connection->close();
        return NULL;

    # Executed successfully, return mysqli_result object
    }
    else {
        $connection->close();
        return $result;
    }

}


# Create a database to be used
function create_db() {
    # Using global variables
    global $host, $user, $password, $db;

    $result = exec_query("CREATE DATABASE IF NOT EXISTS $db");
    
    if ($result == NULL) {
        echo "Couldn't create database";
        return 1;
    }

    return 0;
}


# Create a table called 'users' in database if it doesn't exist
function create_table() {
    # Using global variables
    global $host, $user, $password, $db;

    # Create the table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        phone_number varchar(255) NOT NULL,
        online_status BOOL DEFAULT FALSE,
        preferences JSON NOT NULL DEFAULT ('{}'),
        connections JSON NOT NULL DEFAULT ('{}'),
        pending_connections JSON NOT NULL DEFAULT ('{}'),
        connection_requests JSON NOT NULL DEFAULT ('{}')
    )";

    $result = exec_query($sql);

    if ($result == NULL) {
        echo "Failed to create table";
        return 1;
    }

    return 0;
}


# Destroy database
function destroy_db() {
    global $db;
    $result = exec_query("DROP DATABASE IF EXISTS " . $db);

    if ($result == NULL) {
        echo "Failed to destroy database";
        return 1;
    }

    return 0;
}


# Destroy table 'users' in database if it exists
function destroy_table() {
    $result = exec_query("DROP TABLE IF EXISTS users");
    
    if ($result == NULL) {
        echo "Failed to destroy table";
        return 1;
    }

    return 0;
}


# Check if there is an existing account with this user_id
function user_exists($user_id) {
    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = exec_query($query);

    if ($result == NULL) {
        echo "Failed to query for user";
        return false;
    } 
    else return $result->num_rows > 0;
}


# Get this user's ID by their email
function get_user_id($email) {
    $query = "SELECT user_id FROM users WHERE email = $email";
    $result = exec_query($query);
    
    if ($result == NULL) {
        echo "Failed to query for user ID";
        return -1;
    }

    # $answer can be false or null, which will trigger the else
    if ($answer = $result->fetch_assoc()) {
        return (int) $answer["user_id"];
    }
    else {
        echo "Failed to get results of user ID query";
        return -1;
    }
}


# Attempt to sign in the user whose email is `email` and whose password is
# `password`
function sign_in($email, $password) {
  # Get the current online status of the select user with the given email
    $query = "SELECT online FROM users WHERE email = $email AND password = $password";
    $result = exec_query($query);

    if (!user_exists($email)) {
        echo "Couldn't find user with email $email";
    }
    # Query failed
    else if ($result == NULL) {
        echo "Couldn't query online status of user";
        return 1;
    }
    # User not found
    else if ($result->num_rows == 0) {
        echo "No results for sign_in . User does exist";
        return 1;
    }
    # See if user is online, set to online if not
    else if ($answer = $result->fetch_assoc()) {
        $online = $answer["online_status"] == "1";
        if (!$online) {
            $update = exec_query("UPDATE users SET online_status = TRUE WHERE email = $email AND password = $password");
            if ($update == NULL) {
                echo "Couldn't sign in user";
                return 1;
            } else {
                return 0;
            }
        }
        else {
            echo "User already online";
            return -1;
        }
    }
    # Couldn't get results from query
    else {
        echo "Failed to get results of sign-in query";
        return 1;
    }
}


# Attempt to sign out the user with ID `user_id`
function sign_out($user_id) {
    $result = exec_query("SELECT online FROM users WHERE user_id = $user_id")

    # Check if user exists by seeing if user_id is in the database
    if(!user_exists($user_id)) {
        echo "Couldn't find user with ID $user_id";
        return 1;
    }
    else if ($result == NULL) {
        echo "Couldn't execute query for sign-out";
        return 1;
    }
    else if ($result->num_rows == 0) {
        echo "No results for sign_out. User does exist";
        return 1;
    }
    else if ($answer = $result->fetch_assoc()) {
        $online = $answer["online_status"] == "1";
        if ($online) {
            $update = exec_query("UPDATE users SET online_status = TRUE WHERE user_id = $user_id");
            if ($update == NULL) {
                echo "Couldn't sign user out";
                return 1;
            }
            else {
                return 0;
            }
        }
        else {
            echo "User already offline";
            return -1;
        }
    }
    else {
        echo "Couldn't fetch results for sign_out";
        return 1;
    }
}


# Check that the password matches the password stored for the user with ID
# `user_id`
function check_password($user_id, $password) {
    $query = "SELECT * FROM users WHERE user_id = $user_id AND password = $password";
    $result = exec_query($query);

    # Check that user exists
    if(!user_exists($user_id)) {
        return -1;
    }
    # Check that query executed successfully
    else if ($result == NULL) {
        echo "Failed to execute query to check password";
        return 1;
    }
    # No results, password and user_id don't match an entry
    else if ($result->num_rows == 0) {
        return -1;
    }
    # Passwords and user_id match an entry
    else {
        return 1;
    }
}  


# Attempt to change the password of the user with ID `user_id`
function update_password($user_id, $old_pwd, $new_pwd) {
    $query = "SELECT * FROM users WHERE user_id = $user_id AND password = $old_pwd";
    $result;

    #If eitheir old or new password is empty, return -1
    if(empty($old_pwd) || empty($new_pwd)) {
        echo "Passwords cannot be empty"
        return 1;
    }
    else if (!user_exists($user_id)) {
        echo "User doesn't exist in update_password";
        return 1;
    }
    else if ($result = exec_query($query) == NULL) {
        echo "Couldn't execute query in update_password";
        return 1;
    }
    else if ($result->num_rows == 0) {
        echo "No matching entry with this user ID and password";
        return 1;
    }
    else {
        $update = exec_query("UPDATE users SET password = $new_pwd WHERE user_id = $user_id AND password = $old_pwd");
        if ($update == NULL) {
            echo "Couldn't execute query to update password";
            return 1;
        }
        else {
            return 0;
        }
    }
}


# Creates a new user and stores their data in the database. This function will
# create a unique user ID for the new user
function create_user($name, $email, $pwd, $phone) {
    
    # Make sure none of the required fields are empty
    if (empty($name) || empty($email) || empty($pwd) || empty($phone)) {
        echo "Missing required fields in create_user";
        return 1;
    }

    # Make sure this email isn't being used
    $email_used = exec_query("SELECT * from users WHERE email=$email");
    if ($email_used == NULL) {
        echo "Couldn't execute query to find email";
        return 1;
    }
    else if ($email_used->num_rows > 0) {
        echo "An account with this email already exists";
        return 1;
    }

    # Attempt to create this user
    $query = "INSERT INTO users (name, email, password, phone) VALUES ($name, $email, $pwd, $phone)";
    $result = exec_query($query);
    if (result == NULL) {
        echo "Couldn't insert user into database";
        return 1;
    }
    else {
        return 0;
    }
}


# Removes all of a user's data from the database
function delete_user($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID in delete_user";
        return 1;
    }

    $result = exec_query("DELETE FROM users WHERE user_id = $user_id");
    if ($result == NULL) {
        echo "Failed to execute statement to remove user";
        return 1;
    }
    else {
        return 0;
    }
}


# Attempt to connect the users with IDs `user_id_a` and `user_id_b`. This
# requires that one of the users has sent a connection request and the other
# one has a pending request from the sender
# TODO(Jordan): This function is not yet implemented
function add_connection($user_id_a, $user_id_b) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        return -1;
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id_a . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        print("User does not exist");
        return -1;
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not exist");
        return -1;
    }

    # Check if the users are already connected. If they are, return -1
    # Users have to both be connected to each other for it to be considered a connection. 
    # This is a symmetric relation i.e A->B and B->A must be true.
    # Note to Alex: I was thinking about splitting the logic to see if two users are connected to each other to a helper function ?
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        echo ("Users are already connected");
        return -1;
    }

    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        echo ("Users are already connected");
        return -1;
    }

    # Check if the user a has a pending connection request to user b. If they don't, return -1

    $query = "SELECT * FROM pending_connections WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not have a pending connection");
        return -1;
    }
    # Ensure that user_id b is in user_id_a's connection_requests (reverse of above)
    $query = "SELECT * FROM connection_requests WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not have a connection request");
        return -1;
    }

    # Remove the pending_connection and connection_requests from the appropriate users
    $query = "DELETE FROM pending_connections WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $db->query($query);
    $query = "DELETE FROM connection_requests WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $db->query($query);

    # Add each user to the others' connections list
    $query = "INSERT INTO connections (user_id_a, user_id_b) VALUES ('$user_id_a', '$user_id_b')";
    $db->query($query);
    $query = "INSERT INTO connections (user_id_a, user_id_b) VALUES ('$user_id_b', '$user_id_a')";
    $db->query($query);
    db.close();
    return 1;
}


# Add a request to connect to the user with ID `user_id_rx`. Add the pending
# connection to the user with ID `user_id_tx`
# TODO(Jordan): This function is not yet implemented
function add_connection_request($user_id_tx, $user_id_rx) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        return -1;
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id_tx . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not exist");
        return -1;
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id_rx . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not exist");
        return -1;
    }

    # Check if the users are already connected
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_tx . "' AND user_id_b = '" . $user_id_rx . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        echo ("Users are already connected");
        return -1;
    }

    # Check if the users are already connected
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_rx . "' AND user_id_b = '" . $user_id_tx . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        echo ("Users are already connected");
        return -1;
    }

    # Check if the users have a pending

}


# Attempt to disconnect the users with IDs `user_id_a` and `user_id_b`. This
# requires that a connection exists between these users
# TODO(Jordan): This function is not yet implemented
function remove_connection($user_id_a, $user_id_b) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        return -1;
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id_a . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not exist");
        return -1;
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("User does not exist");
        return -1;
    }

    # Check if the users are already connected
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("Users are not connected");
        return -1;
    }

    # Check if the users are already connected
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        echo ("Users are not connected");
        return -1;
    }

    # Remove the connections from the appropriate users
    $query = "DELETE FROM connections WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $db->query($query);
    $query = "DELETE FROM connections WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $db->query($query);
    db.close();
    return 1;
}


# Get a JSON-formatted string of connections for the user with ID `user_id`
# Returns an empty JSON dictionary if the user has no connections
# TODO(Jordan): Take a second look at this function
function get_connections($user_id) {
    if (!user_exists($user_id)) {
        echo "User does not exist in get_connections";
        return "";
    }

    $result = exec_query("SELECT connections FROM users WHERE user_id = $user_id");
    
    # Failed to execute query
    if ($result == NULL) {
        echo "Couldn't retrieve connections in get_connections";
        return "";
    }
    # No results returned
    else if ($result->num_rows == 0) {
        echo "No results in get_connections, although user exists";
        return "";
    }
    # Attempt to get first row of results
    else if ($answer = $result->fetch_assoc()) {
        return $answer["connections"];
    }
    # Couldn't get first row of results
    else {
        echo "Failed to retrieve results in get_connections.";
        return "";
    }
}


# Get the preferences of the user with ID `user_id` returns a JSON-formatted string
function get_preferences($user_id) {
    if (!user_exists($user_id)) {
        echo "User does not exist in get_preferences";
        return "";
    }

    $result = exec_query("SELECT preferences FROM users WHERE user_id = $user_id");
    
    # Failed to execute query
    if ($result == NULL) {
        echo "Couldn't retrieve preferences in get_preferences";
        return "";
    }
    # No results returned
    else if ($result->num_rows == 0) {
        echo "No results in get_preferences, although user exists";
        return "";
    }
    # Attempt to get first row of results
    else if ($answer = $result->fetch_assoc()) {
        return $answer["preferences"];
    }
    # Couldn't get first row of results
    else {
        echo "Failed to retrieve results in get_preferences.";
        return "";
    }
}

# Set the preferences of the user with ID `user_id` to `preferences`

function update_preferences($user_id, $preferences) {

    # if preferences is not a valid JSON string, return -1
    if (json_decode($preferences) == NULL) {
        echo "Invalid JSON data in update_preferences";
        return 1;
    } else if (!user_exists($user_id)) {
        echo "User doesn't exist in update_preferences";
        return 1;
    }

    $query = "UPDATE users SET preferences = $preferences WHERE user_id = $user_id";
    $result = exec_query($query);

    # Couldn't execute query
    if ($result == NULL) {
        echo "Failed to execute query in update_preferences";
        return 1;
    }
    # Successfully updated preferences
    else {
        return 0;
    }

}


# Get the connection requests that this user needs to respond to
# Returns a JSON-formatted string of connection requests
function get_requests($user_id) {
    if (!user_exists($user_id)) {
        echo "User does not exist in get_requests";
        return "";
    }

    $result = exec_query("SELECT connection_requests FROM users WHERE user_id = $user_id");
    
    # Failed to execute query
    if ($result == NULL) {
        echo "Couldn't retrieve requets in get_requests";
        return "";
    }
    # No results returned
    else if ($result->num_rows == 0) {
        echo "No results in get_requests, although user exists";
        return "";
    }
    # Attempt to get first row of results
    else if ($answer = $result->fetch_assoc()) {
        return $answer["connection_requests"];
    }
    # Couldn't get first row of results
    else {
        echo "Failed to retrieve results in get_requests.";
        return "";
    }
}
?>