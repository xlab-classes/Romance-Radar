<?php

function getTypes($data){
    $retString = '';

    foreach ($data as &$value) {
        $retString .= gettype($value)[0];
    }
    unset($value);
    return $retString;
}


# Helper function. Not part of the API
# Takes in a SQL statement to execute.
# Returns:
# * The mysqli_result object of the SQL statement if executed successfully
# * NULL if there was a problem executing the SQL statement
function exec_query($query, $data) {

    $host = 'oceanus.cse.buffalo.edu';
    $user = 'alexeast';
    $db = 'cse442_2022_spring_team_j_db';
    $password = 50252636;
    
    $connection = new mysqli($host, $user, $password, $db);
    $result;
    
    # Error connecting, return NULL
    if ($connection->connect_error) {
        echo "Connection failed: (" . $connection->errno . ") ." . $connection->error . "\n";
        return NULL;
    }

    # If there is data to be concatenated into the query, do it here
    if($data){
        
        # Returns false on error
        $stmt = $connection->prepare($query);
        if (!$stmt) {   # prepare() call failed
            echo "Couldn't prepare SQL statement\n";
            $connection->close();
            return NULL;
        }

        # Returns false on failure
        $stmt->bind_param(getTypes($data), ...$data);
        if (!$stmt) {    # bind_param() call failed
            echo "Couldn't bind parameters to prepared SQL statement\n";
            $connection->close();
            return NULL;
        }

        # Returns false on failure
        $result_execute = $stmt->execute(); 
        if ($result_execute) {  # True if successful
            
            if($query[0] != 'S'){
                return $result_execute;
            }

            $result = $stmt->get_result();

            if (!$result) {  # Failed
                echo "Couldn't get result from statement execution\n";
                $connection->close();
                return NULL;
            }
            else{
                $connection->close();
                return $result;
            }
        }

        # execute() call failed
        else {
            echo "Couldn't execute prepared statement\n";
            $connection->close();
            return NULL;
        }

    # Otherwise, just execute the query
    } else {
        $result = $connection->query($query);
        if (!$result) {  # False if failed
            echo "Couldn't execute non-prepared query\n";
            $connection->close();
            return NULL;
        }
    }

    $connection->close();
    return $result; // returns Object, True, False
}

# Check if there is an existing account with this user_id
function user_exists($user_id) {
    $query = "SELECT * FROM Users WHERE id =?";
    $result = exec_query($query, [$user_id]);
    return $result->num_rows > 0;
}


# Get this user's ID by their email
function get_user_id($email) {
    $query = "SELECT id FROM Users WHERE email =?";
    $result = exec_query($query, [$email]);
    
    if (!$result->num_rows) {
        return 0;
    }

    # $answer can be false or null, which will trigger the else
    if ($answer = $result->fetch_assoc()) {
        return $answer["id"];
    }
    else {
        echo "Failed to get results of user ID query\n";
        return 0;
    }
}


# Attempt to sign in the user whose email is `email` and whose password is
# `password`
function sign_in($email, $password) {
    # Get the current online status of the select user with the given email
    $query = "SELECT * FROM Users WHERE email=?";
    $result = exec_query($query, [$email, $password]);
    $row = $result->fetch_assoc();
    if (!row) {
        echo "Couldn't find user with email $email\n";
    }
    else if (!$result->num_rows) {
        echo "No results for sign_in. User does exist\n";
        return 0;
    }
    # See if user is online, set to online if not
    else if ($row['password'] == $password) {
        return 1;
    }
    # Couldn't get results from query
    else {
        echo "Failed to get results of sign-in query\n";
        return 0;
    }
}


# Attempt to change the password of the user with ID `user_id`
function update_password($user_id, $old_pwd, $new_pwd) {
    
    #If eitheir old or new password is empty, return -1
    if(empty($old_pwd) || empty($new_pwd)) {
        echo "Passwords cannot be empty\n";
        return 0;
    }else if ($old_pwd == $new_pwd){
        echo "Passwords are the same\n";
        return 0;
    }
    else if (!user_exists($user_id)) {
        echo "User doesn't exist in update_password\n";
        return 0;
    }
    else {
        $query = "UPDATE Users SET password =?  WHERE user_id =? AND password =?";
        $data = [$new_pwd, $user_id, $old_pwd];
        $update = exec_query($query, $data);
        if (!$update) {
            echo "Couldn't execute query to update password\n";
            return 0;
        }
        return 1;
    }
}


# Creates a new user and stores their data in the database. This function will
# create a unique user ID for the new user
function create_user($name, $email, $pwd, $addr, $zipcode, $bday) {
    # Make sure none of the required fields are empty
    if (empty($name) || empty($email) || empty($pwd) || empty($addr) || empty($zipcode) || empty($bday)) {
        echo "Missing required fields in create_user\n";
        return 0;
    }

    # Make sure this email isn't being used
    $email_used = get_user_id($email);
    
    if ($email_used) {
        echo "Couldn't execute query to find email";
        return 0;
    }

    # Attempt to create this user
    $query = "INSERT INTO Users (name, email, password, user_picture, street_address, zipcode, birthday) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $data = [$name, $email, $pwd, 'Testing', $addr, $zipcode, $bday];
    $result = exec_query($query, $data);
    if (!$result) {
        echo "Couldn't insert user into database\n";
        return 0;
    }
    return 1;
}


# Removes all of a user's data from the database
function delete_user($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID in delete_user\n";
        return 0;
    }

    $result = exec_query("DELETE FROM Users WHERE id=?", [$user_id]);
    if (!$result) {
        echo "Failed to execute statement to remove user\n";
        return 0;
    }
    return 1;
}


# Attempt to connect the users with IDs `user_id_a` and `user_id_b`. This
# requires that one of the users has sent a connection request and the other
# one has a pending request from the sender
# TODO(Jordan): This function is not yet implemented
function add_connection($user_id_a, $user_id_b) {
    if (!user_exists($user_id_a) || !user_exists($user_id_b)) {
        echo "No user with this ID in delete_user\n";
        return 0;
    }
    remove_connection($user_id_a);
    remove_connection($user_id_b);
    $update_query = "UPDATE Users SET partner=? WHERE id=?";
    if (exec_query($update_query, [$user_id_a, $user_id_b]) &&
        exec_query($update_query, [$user_id_b, $user_id_a])){
            remove_connection_request($user_id_a);
            remove_connection_request($user_id_b);
            return 1;
     }else{
         echo "Failed to update partners\n";
         return 0;
     }
}

# Attempt to disconnect the users with IDs `user_id_a` and `user_id_b`. This
# requires that a connection exists between these users
# TODO(Jordan): This function is not yet implemented
function remove_connection($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID in delete_user\n";
        return 0;
    }

    $user = "SELECT * FROM Users WHERE id=?";
    $result = exec_query($user, [$user_id])->fetch_assoc();

    if($result && $partner = $user["partner"]){
        $update_query = "UPDATE Users SET partner = NULL WHERE id=? OR id=?";
        if(exec_query($update_query, [$user_id, $partner])){
            return 1;
        }
    }

    return 0;
}

# Add a request to connect to the user with ID `user_id_rx`. Add the pending
# connection to the user with ID `user_id_tx`
# TODO(Jordan): This function is not yet implemented
function add_connection_request($sent_from, $sent_to) {
    if (!user_exists($sent_from) || !user_exists($sent_to)) {
        echo "No user with this ID\n";
        return 0;
    }
    
    $user = "SELECT * FROM Users WHERE id=?";
    $sent_from = exec_query($user, [$sent_from])->fetch_assoc();
    $sent_to = exec_query($user, [$sent_to])->fetch_assoc();

    remove_connection_request($sent_from);
    remove_connection_request($sent_to);
    if ($sent_from && $sent_to && !$sent_from['partner'] && !$sent_to['partner']){
        $insert_query = "INSERT INTO Connection_requests (sent_from, sent_to) VALUE (?,?)";
        if (exec_query($insert_query, [$sent_from, $sent_to])){
            return 1;
        }
    }
    return 0;

}

function remove_connection_request($sent_from) {
    if (!user_exists($user_id_a) || !user_exists($user_id_b)) {
        echo "No user with this ID\n";
        return 0;
    }
    
    $delete_query = "DELETE FROM Connection_requests WHERE sent_from=?";
    if (exec_query($delete_query, [$sent_from])){
        return 1;
    }
    return 0;
}


# Get the connection requests that this user needs to respond to
# Returns a JSON-formatted string of connection requests
function get_requests($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    $query = "SELECT * FROM Connection_requests WHERE sent_to=?";
    return exec_query($query, [$user_id])->fetch_all();
}

function get_partner($user_id){
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    $query = "SELECT * FROM Users WHERE id=?";
    return exec_query($query, [$user_id])->fetch_assoc()['partner'];
}

# Get the preferences of the user with ID `user_id` returns a JSON-formatted string
function get_preferences($user_id) {
    
}

# Set the preferences of the user with ID `user_id` to `preferences`

function update_preferences($user_id, $preferences) {

}
