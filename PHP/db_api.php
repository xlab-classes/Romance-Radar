<?php

function getTypes($data){
    $retString = '';

    foreach ($data as &$value) {
        $retString .= gettype($value)[0];
    }
    unset($value);
    return $retString;
}

function getUser($user_id, $email){
    $query = "";
    $data = [];
    if($user_id){
        $query = "SELECT * FROM Users WHERE id=?";
        $data = [$user_id];
    }else if($email){
        $query = "SELECT * FROM Users WHERE email=?";
        $data = [$email];
    }else{
        return NULL;
    }

    $result = exec_query($query, $data);
    return $result;
}

# Helper function. Not part of the API
# Takes in a SQL statement to execute.
# Returns:
# * The mysqli_result object of the SQL statement if executed successfully
# * NULL if there was a problem executing the SQL statement
function exec_query($query, $data) {

    
    $host = 'oceanus.cse.buffalo.edu';
    $user = 'jjgrant';
    $db = 'cse442_2022_spring_team_j_db';
    $password = 50276673;
    
    $connection = new mysqli($host, $user, $password, $db);
    
    # Error connecting, return NULL
    if ($connection->connect_error) {
        echo "Connection failed: (" . $connection->errno . ") ." . $connection->error . "\n";
        return NULL;
    }

    # If there is data to be concatenated into the query, do it here
    if($data) {

        if (gettype($data) != "array") {
            echo "Mismatched data given to exec_query function\n";
            return NULL;
        }
        
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
    $result = getUser($user_id, NULL);
    if ($result == NULL) return false;
    else return $result->num_rows > 0;
}

# Check if a $user_id's password matches $password
function check_password($user_id, $password) {
    if (!user_exists($user_id)) return 1;           // User doesn't exist
    
    $result = exec_query("SELECT * FROM Users WHERE $user_id=? AND $password=?",
        [$user_id, password_hash($password)]);

    if ($result == NULL) return 1;                  // Err executing sql
    else if ($result->num_rows == 0) return -1;     // No matching user id and password
    else return 0;                                  // Password matches
}

# Get this user's ID by their email
function get_user_id($email) {
    
    $result = getUser(NULL, $email);
    
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
    $result = getUser(NULL, $email);
    $row = $result->fetch_assoc();
    if (!$row) {
        echo "Couldn't find user with email $email\n";
    }
    else if (!$result->num_rows) {
        echo "No results for sign_in. User does exist\n";
        return 0;
    }
    else if (password_verify($password, $row['password'])) {
        // NEED TO SET TO ONLINE. Jesus swastik.
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
    $user = getUser($user_id, NULL);
    $user = $user->fetch_assoc();
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
    }else if ($user['password'] != $old_pwd){
        echo "Password is wrong\n";
        return 0;
    }else {
        $query = "UPDATE Users SET password =?  WHERE id =? AND password =?";
        $data = [$new_pwd, $user_id, $old_pwd];
        $update = exec_query($query, $data);
        echo $update;
        if (!$update) {
            echo "Couldn't execute query to update password\n";
            return 0;
        }
        return 1;
    }
}


# Creates a new user and stores their data in the database. This function will
# create a unique user ID for the new user
function create_user($name, $email, $pwd, $addr, $city, $zipcode, $bday) {
    # Make sure none of the required fields are empty
    if (empty($name) || empty($email) || empty($pwd) || empty($addr) || empty($city) || empty($zipcode) || empty($bday)) {
        echo "Missing required fields in create_user\n";
        return 0;
    }

    # Make sure this email isn't being used
    $email_used = get_user_id($email);
    
    if ($email_used != 0) {
        echo "User already exists\n";
        return 0;
    }

    # Attempt to create this user
    $query = "INSERT INTO Users (name, email, password, user_picture, street_address, zipcode, birthday, city) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $data = [$name, $email, $pwd, 'Testing', $addr, $zipcode, $bday, $city];
    $result = exec_query($query, $data);
    if (!$result) {
        echo "Couldn't insert user into database\n";
        return 0;
    }

    // Add an entry for this user to the Connection_requests table
    $id = get_user_id($email);
    $query = "INSERT INTO Connection_requests (sent_from, sent_to) VALUES (?,?)";
    $result = exec_query($query, [$id, 0]);

    if (!initialize_preferences($id)) {
        echo "Couldn't initialize preferences for new user!\n";
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

function remove_connection($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID in delete_user\n";
        return 0;
    }

    $user = getUser($user_id, NULL)->fetch_assoc();
    if($partner = $user["partner"]){
        $update_query = "UPDATE Users SET partner = NULL WHERE id=? OR id=?";
        if(exec_query($update_query, [$user_id, $partner])){
            return 1;
        }
    }

    return 0;
}

function add_connection_request($sent_from, $sent_to) {

    // Regardless of it $sent_to has a connection, allow the connection request
    // If $sent_from has a connection, do not allow a connection request
    // If $sent_from has an existing connection request to another user, delete
    // that before sending the new one

    /*
        TODO: Prompt user that sending a new connection request will delete their
        current conection request
    */

    // Make sure both users exist
    if (!user_exists($sent_from) || !user_exists($sent_to)) {
        echo "No user with this ID\n";
        return 0;
    }

    // Each user can only send one connection request at a time, so if $sent_from
    // has an existing request, remove it
    remove_connection_request($sent_from);

    // Make sure that sent_from doesn't have a connection already
    $sent_from_partner = get_partner($sent_from);
    if ($sent_from_partner != NULL && $sent_from_partner > 0) {
        echo "One of these connections has a partner! Cannot create new connection\n";
        return 0;
    }

    // Insert this connection into the Connection_requests table
    $insert_query = "UPDATE Connection_requests SET sent_from=?, sent_to=? WHERE sent_from=?";
    if (exec_query($insert_query, [$sent_from, $sent_to, $sent_from])){
        return 1;
    }

    return 0;
}

function remove_connection_request($sent_from) {
    if (!user_exists($sent_from)) {
        echo "No user with this ID\n";
        return 0;
    }
    
    $delete_query = "DELETE FROM Connection_requests WHERE sent_from=?";
    if (exec_query($delete_query, [$sent_from])){
        return 1;
    }
    return 0;
}

function get_requests($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    $query = "SELECT * FROM Connection_requests WHERE sent_to=?";
    $result = exec_query($query, [$user_id]);
    $return = array();
    while($row = $result->fetch_assoc()){
        array_push($return, $row['sent_from']);
    }
    return $return;
}

function get_partner($user_id){
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    $user = getUser($user_id, NULL);
    return $user->fetch_assoc()['partner'];
}

function get_preferences($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }

    $preferences = [];
    $preferences_categories = array('Food', 'Entertainment', 'Venue', 'Date_time', 'Date_preferences');
    
    foreach($preferences_categories as $cat){
        $query = sprintf("SELECT * FROM %s WHERE user_id=?", $cat);
        $result = exec_query($query, [$user_id]);
        if(!$result || $result->num_rows == 0){
            echo "Records don't exist\n";
            return [];
        }
        $preferences[$cat] = $result->fetch_assoc();
    }

    return $preferences;

}

# Set the preferences of the user with ID `user_id` to `preferences`
function update_preferences($user_id, $preferences) {
    
    $preferences_categories = array(
                     'Food' => array('restaraunt' => 1, 'cafe' =>1, 'fast_food'=>1, 'alcohol'=>1),
                     'Entertainment' => array('concerts' => 1, 'hiking'=>1, 'bars'=>1),
                     'Venue' => array('indoors'=>1, 'outdoors'=>1, 'social_events'=>1),
                     'Date_time' => array('morning'=>1, 'afternoon'=>1, 'evening'=>1),
                     'Date_preferences'=>array('cost'=>1, 'distance'=>1, 'length'=>1));

    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    foreach($preferences as $cat => $changes){
        if(!isset($preferences_categories[$cat])){
            echo 'Category does not exist\n';
            return 0;
        }

        foreach($changes as $sub_cat => $value){
            if(!isset($preferences_categories[$cat][$sub_cat])){
                echo 'Sub-Category does not exist\n';
                return 0;
            }
            $query = sprintf("UPDATE %s SET %s=? WHERE user_id=?", $cat, $sub_cat);
            $result = exec_query($query, [$value, $user_id]);
            if (!$result){
                echo 'No executed\n';
            }
        }
    }
    return 1;

}

function initialize_preferences($user_id){
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }

    $preferences_categories = array(
        'Food' => ['(restaraunt, cafe, fast_food, alcohol, user_id)', '(?,?,?,?,?)', [0,0,0,0]], 
        'Entertainment' => ['(concerts, hiking, user_id)', '(?,?,?)', [0,0]],
        'Venue' => ['(indoors, outdoors, social_events, user_id)', '(?,?,?,?)', [0,0,0]],
        'Date_time' => ['(morning, afternoon, evening, user_id)', '(?,?,?,?)', [0,0,0]],
        'Date_preferences' => ['(cost, distance, length, user_id)', '(?,?,?,?)', [0,0,0]]
    );

    foreach($preferences_categories as $cat => $placeholders){
        $query = sprintf("INSERT INTO %s %s VALUES %s", $cat, $placeholders[0], $placeholders[1]);
        $result = exec_query($query, array_merge($placeholders[2], [$user_id]));
        if(!$result){
            echo "Error in execution\n";
            return 0;
        }
    }
    return 1;
}

function get_question($question_id){
    $query = 'SELECT question FROM Security_questions WHERE id=?';
    $result = exec_query($query, [(int)$question_id]);

    if(!$result || !$result->num_rows){
        exit('No question Found');
    }

    return $result->fetch_assoc()['question'];
}

function addSecurityQuestions($user_id, $data){
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    
    $query = "INSERT INTO User_security_questions(user_id, question_id_1, question_id_2, question_id_3, answer_1, answer_2, answer_3) VALUES (?,?,?,?,?,?,?)";

    if(!exec_query($query, $data)){
        echo 'Could not insert security questions';
        return 0;
    }

    return 1;
}