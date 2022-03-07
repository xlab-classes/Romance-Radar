<!--
Authors: Alex Eastman, Jordan Grant
Created: 03/06/2022
Modified: 03/06/2022

Database api
-->
<?php

# Create a databaase to be used
function create_db() {
    # setting global variables
    global $host, $user, $password, $db;

    # Create a connection to the database
    $conn = new mysqli($host, $user, $password);

    # Check for errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    # Create the database if it doesn't exist
    $sql = "SHOW DATABASES LIKE '$db'";
    $result = $conn->query($sql);
    if ($conn->query("CREATE DATABASE IF NOT EXISTS $db") === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }
    $conn->close();
}
?>

<?php
/* TODO: Add to API docs */
# Create a table called 'users' in database if it doesn't exist
function create_table() {
    # setting global variables
    global $host, $user, $password, $db;

    # Create a connection to the database
    $conn = new mysqli($host, $user, $password, $db);

    # Check for errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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

    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
        }
    $conn->close();
    }
?>

<?php

/* TODO: Add to API docs */
# Destroy database
function destroy_db() {
    $connection = new mysqli($host, $user, $password, $db);
    if ($connection->connect_errno) {
        echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
        exit();
    }
    $connection->query("DROP DATABASE IF EXISTS " . DB_NAME);
    $connection->close();
}
?>

<?php
/* TODO: Add to API docs */
# Destroy table 'users' in database if it exists
function destroy_table() {
    $connection = new mysqli($host, $user, $password, $db);
    if ($connection->connect_errno) {
        echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
        exit();
    }
    $connection->query("DROP TABLE IF EXISTS users");
    $connection->close();

}
?>
<?php
# Check if there is an existing account with this user_id
function user_exists($user_id) {
    # Connect to database
    $connection = new mysqli($host, $user, $password, $db);
    
    # Error check connection
    if ($connection->connect_errno) {
        echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
        exit();
    }

    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = $connection->query($query);
    $connection->close();
    return $result->num_rows > 0;
}
?>

<?php
# Get this user's ID by their email
function get_user_id($email) {
    # Connect to database
    $connection = new mysqli($host, $user, $password, $db);
    
    # Error check connection
    if ($connection->connect_errno) {
        echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
        exit();
    }

    $query = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $connection->query($query);
    $connection->close();
    return $result->fetch_assoc()['user_id'];
}
?>

<?php
# Attempt to sign in the user whose email is `email` and whose password is
# `password`
function sign_in($email, $password) {
    # Connect to database
    $connection = new mysqli($host, $user, $password, $db);
    
    # Error check connection
    if ($connection->connect_errno) {
        echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
        exit();
    }

  # Get the current online status of the select user with the given email
    $query = "SELECT online FROM users WHERE email = '$email'";
    $result = $db->query($query);

  # If no such user exists, return -1
    if (!$result = $connection->query($query)) {
        $connection->close();
        return -1;
    }

  # If the user is already online return -1
    if ($result->fetch_assoc()['online'] == TRUE) {
        $connection->close();
        return -1;
    }

    # Else update the user with the given email to be online
    $query = "UPDATE users SET online = TRUE WHERE email = '$email'";
    $connection->query($query);
    $connection->close();
}
?>

<?php
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
?>

<?php
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
    }   
?>

<?php
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
?>

<?php
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
?>

<?php
# Removes all of a user's data from the database
function delete_user($user_id) {
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
    if ($result->num_rows == 0) {
        $db->close();
        print("User does not exist");
        return -1;
    }

    # Delete the user from the database
    $query = "DELETE FROM users WHERE user_id = '$user_id'";
    $db->query($query);
    $db->close();
}
?>

<?php
# Attempt to connect the users with IDs `user_id_a` and `user_id_b`. This
# requires that one of the users has sent a connection request and the other
# one has a pending request from the sender
function add_connection($user_id_a, $user_id_b) {
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);
    
    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
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
        print("User does not exist");
        return -1;
    }

    # Check if the users are already connected
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $db->close();
        print("Users are already connected");
        return -1;
    }

    # 3. Ensure that user_id_a is in user_id_b's pending_connections (maybe reverse)
    $query = "SELECT * FROM pending_connections WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        print("User does not have a pending connection");
        return -1;
    }
    # Ensure that user_id b is in user_id_a's connection_requests (reverse of above)
    $query = "SELECT * FROM connection_requests WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $result = $db->query($query);
    if ($result->num_rows == 0) {
        $db->close();
        print("User does not have a pending connection");
        return -1;
    }

    # Remove the pending_connection and connection_requests from the appropriate users
    $query = "DELETE FROM pending_connections WHERE user_id_a = '" . $user_id_b . "' AND user_id_b = '" . $user_id_a . "'";
    $db->query($query);
    $query = "DELETE FROM connection_requests WHERE user_id_a = '" . $user_id_a . "' AND user_id_b = '" . $user_id_b . "'";
    $db->query($query);

    # Add each user to the others' connections list
    $query = "INSERT INTO connections (user_id_a, user_id_b) VALUES ('$user_id_a', '$user_id_b')";
    $db->query($query);
    $query = "INSERT INTO connections (user_id_a, user_id_b) VALUES ('$user_id_b', '$user_id_a')";
    $db->query($query);
    db.close();
    }
?>

<?php
# Add a request to connect to the user with ID `user_id_rx`. Add the pending
# connection to the user with ID `user_id_tx`
function add_connection_request($user_id_tx, $user_id_rx) {

}
?>

<?php
# Attempt to disconnect the users with IDs `user_id_a` and `user_id_b`. This
# requires that a connection exists between these users
function remove_connection($user_id_a, $user_id_b) {

}
?>

<?php
# Get a JSON-formatted string of connections for the user with ID `user_id`
function get_connections($user_id) {
    # Check if user exists
    if (user_exists($user_id) == -1) {
        print("User does not exist");
        return -1;
    }
    # get connections
    $db = new mysqli($host, $user, $password, $name);
    $query = "SELECT * FROM connections WHERE user_id_a = '" . $user_id . "' OR user_id_b = '" . $user_id . "'";
    $result = $db->query($query);
    $connections = array();
    while ($row = $result->fetch_assoc()) {
        if ($row['user_id_a'] == $user_id) {
            $connections[] = $row['user_id_b'];
        } else {
            $connections[] = $row['user_id_a'];
        }
    }
    $db->close();
}
?>

<?php
# Get the preferences of the user with ID `user_id` returns a JSON-formatted string
function get_preferences($user_id) {
    # Check if user exists
    if (!user_exists($user_id)) {
        print("User does not exist");
        # Return empty String version of JSON  
        return "{}";
    }
    # Connect to database
    $db = new mysqli($host, $user, $password, $name);

    # Error check connection
    if ($db->connect_errno) {
        echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
        exit();
    }

    # Get the preferences of the user
    $query = "SELECT * FROM preferences WHERE user_id = '" . $user_id . "'";
    $result = $db->query($query);
    $preferences = $result->fetch_assoc();
    $db->close();
    return json_encode($preferences);
}
?>

<?php
function update_preferences($user_id, $preferences) {

    # if preferences is not a valid JSON string, return -1
    if (json_decode($preferences) == NULL) {
        return -1;
    }

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
    if ($result->num_rows == 0) {
        $db->close();
        print("User does not exist");
        return -1;
    }
    # Try / Catch to update the preferences
    try {
        $query = "UPDATE users SET preferences = '" . $preferences . "' WHERE user_id = '" . $user_id . "'";
        $db->query($query);
    } catch (Exception $e) {
        $db->close();
        print("Failed to update preferences");
        return -1;
    }
    $db->close();
    return 0;
}
?>

<?php
/* TODO: Add to API docs */
# Get the connection requests that this user needs to respond to
function get_requests($user_id) {
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
    if ($result->num_rows == 0) {
        $db->close();
        print("User does not exist");
        exit();
    }
    # Get the connection requests for the user with ID user_id and return them as a JSON string
    $query = "SELECT * FROM connection_requests WHERE user_id_b = '" . $user_id . "'";
    $result = $db->query($query);
    $db->close();
    return json_encode($result);

}
?>