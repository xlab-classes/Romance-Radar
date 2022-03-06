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
$name = 'rrdb';

/* TODO: Add to API docs */
# Create database if it doesn't exist
function create_db() {
    $db = new mysqli($host, $user, $password, $name);
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }
    $db->query("CREATE DATABASE IF NOT EXISTS " . $name);
    $db->close();
}

/* TODO: Add to API docs */
# Create a table called 'users' in database if it doesn't exist
function create_table() {
    
}

/* TODO: Add to API docs */
# Destroy database
function destroy_db() {
    $db = new mysqli($host, $user, $password, $name);
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }
    $db->query("DROP DATABASE IF EXISTS " . $name);
    $db->close();
}

/* TODO: Add to API docs */
# Destroy table 'users' in database if it exists
function destroy_table() {
    $db = new mysqli($host, $user, $password, $name);
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }
    $db->query("DROP TABLE IF EXISTS users");
    $db->close();
}

# Check if there is an existing account with this user_id
function user_exists($user_id) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE user_id = '" . $user_id . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        return true;
    }

}

# Get this user's ID by their email
function get_user_id($email) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }

    $query = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $db->query($query);
    $db->close();
    return $result->fetch_assoc()['user_id'];

}

# Attempt to sign in the user whose email is `email` and whose password is
# `password`
function sign_in($email, $password) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }

  # Get the current online status of the select user with the given email
    $query = "SELECT online FROM users WHERE email = '$email'";
    $result = $db->query($query);

  # If no such user exists, return -1
    if ($result->num_rows == 0) {
        return -1;
    }

  # If the user is already online return -1
    if ($result->fetch_assoc()['online'] == TRUE) {
        return -1;
    }

    # Else update the user with the given email to be online
    $query = "UPDATE users SET online = TRUE WHERE email = '$email'";
    $db->query($query);
    $db->close();
}

# Attempt to sign out the user with ID `user_id`
function sign_out($user_id) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);

    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }
    # Check if user exists by seeing if user_id is in the database
    if(!user_exists($user_id)) {
        return -1;
    }

    # Else update the user with the given email to be offline
    $query = "UPDATE users SET online = FALSE WHERE user_id = '$user_id'";
    $db->query($query);
    $db->close();
}

# Check that the password matches the password stored for the user with ID
# `user_id`
function check_password($user_id, $password) {
    # if the user doesn't exists, return -1
    if(!user_exists($user_id)) {
        return -1;
    }

    # Connect to database
    $db = new mysqli($host, $user, $password, $name);

    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }
    # Check that the password matches the password stored for the user with ID
    # `user_id`
    $query = "SELECT password FROM users WHERE user_id = '$user_id'";
    $result = $db->query($query);
    $db->close();
    if ($result->fetch_assoc()['password'] == $password) {
        return 1;
    }
    return -1;

# Attempt to change the password of the user with ID `user_id`
function update_password($user_id, $old_pwd, $new_pwd) {
    # if eitheir old or new password is empty, return -1
    if(empty($old_pwd) || empty($new_pwd)) {
        return -1;
    }

    # Check that  the old password is correct for the user with ID
    # `user_id`
    if(check_password($user_id, $old_pwd) != 1) {
        return -1;
    }
    # update user with user_id password to new password
    $db = new mysqli($host, $user, $password, $name);
    $query = "UPDATE users SET password = '$new_pwd' WHERE user_id = '$user_id'";
    $db->query($query);
    $db->close();
}

# Get the user ID of the user whose email is `email`
function get_user_id($email) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }

    # Get the user ID of the user whose email is `email`
    $query = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $db->query($query);
    $db->close();
    return $result->fetch_assoc()['user_id'];

}

# Creates a new user and stores their data in the database. This function will
# create a unique user ID for the new user
function create_user($name, $email, $pwd, $phone) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }

    # Check if user exists by seeing if user_id is in the database
    $query = "SELECT * FROM users WHERE email = '" . $email . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        print("User already exists");
        return -1;
    }

    # Insert the new user into the database
    $query = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$pwd', '$phone')";
    $db->query($query);
    $db->close();
    return $user_id;

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
? >