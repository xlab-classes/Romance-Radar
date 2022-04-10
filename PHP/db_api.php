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

// Check if there is a date in Date_ideas with this ID
//
// parameter: date_id   [int]
//      The ID of the date to check for existence
//
// returns:
//      true if a date with this ID exists
//      false otherwise
function date_exists($date_id) {
    $query = "SELECT * FROM Date_ideas WHERE id=?";
    $data = [$date_id];
    $result = exec_query($query, $data);
    return ($result != NULL && $result->num_rows > 0);
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
    $data = [$name, $email, $pwd, '../assets/generic_profile_picture.jpg', $addr, $zipcode, $bday, $city];
    $result = exec_query($query, $data);
    if (!$result) {
        echo "Couldn't insert user into database\n";
        return 0;
    }

    // Add an entry for this user to the Connection_requests table
    $id = get_user_id($email);
    $query = "INSERT INTO Connection_requests (sent_from, sent_to) VALUES (?,?)";
    $result = exec_query($query, [$id, $id]);

    // Set new user's partner to themselves to indicate that they don't have a
    // partner
    $query = "UPDATE Users SET partner=? WHERE id=?";
    $result = exec_query($query, [$id, $id]);

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

// Add a connection between these users
//
// parameter: sent_from     [int]
//      The user ID of the user that sent the connection request
//
// parameter: sent_to       [int]
//      The user ID of the user accepting the connection request
//
// returns:
//      0 on failure
//      1 on success
//
// constraints:
//      sent_from and sent_to MUST exist
//      sent_from MUST have sent a request to sent_to
//      sent_from MUST NOT already have a partner
//      sent_to MUST NOT already have a partner
//      sent_to MUST have received a request from sent_from
function add_connection($sent_from, $sent_to) {
    // Make sure both users exist
    if (!user_exists($sent_from) || !user_exists($sent_to)) {
        echo "No user with this ID in delete_user\n";
        return 0;
    }

    // Make sure that sent_from has sent a connection request to sent_to
    $outgoing_request_query =
        "SELECT * FROM Connection_requests WHERE sent_from=?";
    $result = exec_query($outgoing_request_query, [$sent_from]);
    if ($result == NULL || $result->num_rows <= 0) {
        print("There is no entry in Connection_requests with user ID sent_from");
        return 0;
    }
    if ($result->fetch_assoc()["sent_to"] != $sent_to) {
        print("There is no connection request from user sent_from to user sent_to\n");
        return 0;
    }

    // Make sure that sent_to has a connection request from sent_from
    $incoming_requests = get_requests($sent_to);
    if (!in_array($sent_from, $incoming_requests)) {
        print("User sent_to has not received a request from user sent_from\n");
        return 0;
    }

    // Make sure that sent_from doesn't have a partner
    if (get_partner($sent_from) != $sent_from) {
        print("User sent_from already has a partner!\n");
        return 0;
    }

    // Make sure that sent_to doesn't have a partner
    if (get_partner($sent_to) != $sent_to) {
        print("User sent_to already has a partner!\n");
        return 0;
    }

    // Complete the connection
    $query = "UPDATE Users SET partner=? WHERE id=?";

    // Add sent_from as a partner to sent_to
    $result = exec_query($query, [$sent_from, $sent_to]);
    if ($result == NULL) {
        print("Failed to exec_query in add_connection\n");
        return 0;
    }

    // Add sent_to as a partner to sent_from
    $result = exec_query($query, [$sent_to, $sent_from]);
    if ($result == NULL) {
        print("Failed to exec_query in add_connection\n");
        return 0;
    }

    return 1;
}

// Remove an existing connection between these users
//
// parameter: user_id_a     [int]
//      The user ID of one of the users in the connection to be removed
//
// parameter: user_id_b     [int]
//      The user ID of the other user in the connection to be removed
//
// returns:
//      0 on failure
//      1 on success
//
// constraints:
//      user_id_a and user_id_b MUST exist
//      There MUST be a connection between these users
//      user_id_a and user_id_b MUST NOT be the same user
function remove_connection($user_id_a, $user_id_b) {
    // Make sure the users exist
    if (!user_exists($user_id_a) || !user_exists($user_id_b)) {
        print("One of these users does not exist in remove connection!\n");
        return 0;
    }

    // Make sure that user_id_a and user_id_b are different users
    if ($user_id_a == $user_id_b) {
        print("Cannot remove a connection between the same user!\n");
        return 0;
    }

    // Make sure that user A's partner is user B
    if (get_partner($user_id_a) != $user_id_b) {
        print("These users don't have a connection to remove\n");
        return 0;
    }

    // Make sure that user B's partner is user A
    if (get_partner($user_id_b) != $user_id_a) {
        print("These users have a lopsided connection!\n");
        return 0;
    }

    $update_query = "UPDATE Users SET partner=? WHERE id=?";
    $result = exec_query($update_query, [$user_id_a, $user_id_a]);
    if ($result == NULL) {
        print("Couldn't exec_query in remove_connection\n");
        return 0;
    }
    
    $result = exec_query($update_query, [$user_id_b, $user_id_b]);
    if ($result == NULL) {
        print("Coudln't exec_query on user B in remove_connection\n");
        return 0;
    }

    return 1;
}

// Send a request from one user to another
//
// parameter: sent_from     [int]
//      The user ID of the user sending the request
//
// parameter: sent_to       [int]
//      The user ID of the user receiving the request
//
// returns:
//      0 on failure
//      1 on success
//
// constraints:
//      sent_from and sent_to MUST exist
//      sent_from and sent_to MUST NOT be the same user
//      sent_from MUST NOT have an outgoing connection request already
//      sent_from MUST NOT have a partner already
//      sent_from MUST NOT have a request from sent_to
function add_connection_request($sent_from, $sent_to) {
    // Make sure both users exist
    if (!user_exists($sent_from) || !user_exists($sent_to)) {
        echo "No user with this ID\n";
        return 0;
    }

    // Make sure a user isn't sending a request to themselves
    if ($sent_from == $sent_to) {
        print("Can't add a connection request between the same user!\n");
        return 0;   
    }

    // Each user can only send one connection request at a time. Make sure
    // sent_from doesn't have an existing outgoing request
    $query = "SELECT * FROM Connection_requests WHERE sent_from=?";
    $result = exec_query($query, [$sent_from]);
    if ($result == NULL) {
        print("Couldn't exec_query in add_connection_request\n");
        return 0;
    }
    if ($result->fetch_assoc()["sent_to"] != $sent_from) {
        print("sent_from already has an outgoing request!\n");
        return 0;
    }

    // Make sure that sent_from doesn't already have a request from sent_to
    $sent_from_requesting = get_requests($sent_from);
    if (in_array($sent_to, $sent_from_requesting)) {
        print("Can't send a request to someone that's already requesting you!\n");
        return 0;
    }

    // Make sure that sent_from doesn't have a connection already. Their partner
    // will be themselves if they don't already have one
    $sent_from_partner_id = get_partner($sent_from);
    if ($sent_from_partner_id != $sent_from) {
        echo "One of these connections has a partner! Cannot create new connection\n";
        return 0;
    }

    // Insert this connection into the Connection_requests table
    $insert_query = "UPDATE Connection_requests SET sent_to=? WHERE sent_from=?";
    if (exec_query($insert_query, [$sent_to, $sent_from])){
        return 1;
    }

    return 0;
}

// Remove the connection request sent by this user
//
// parameter: sent_from     [int]
//      The user ID of a user that has sent a connection request
//
// returns:
//      0 on failure
//      1 on success
//
// constraints:
//      sent_from MUST exist
//      sent_from MUST have sent a connection request to someone
function remove_connection_request($sent_from) {
    // Make sure user exists
    if (!user_exists($sent_from)) {
        echo "No user with this ID\n";
        return 0;
    }

    // Make sure user has an outgoing connection request
    $query = "SELECT * FROM Connection_requests WHERE sent_from=?";
    $result = exec_query($query, [$sent_from]);
    if ($result == NULL) {
        print("Couldn't SELECT from Connection_requests in remove_connection_request\n");
        return 0;
    }
    if ($result->fetch_assoc()["sent_to"] == $sent_from) {
        print("There is no outgoing request to remove\n");
        return 0;
    }

    // Reset sent_to value == sent_from to indicate there is no outgoing request
    $update_query = "UPDATE Connection_requests SET sent_to=? WHERE sent_from=?";
    $result = exec_query($update_query, [$sent_from, $sent_from]);
    if ($result == NULL) {
        print("Couldn't UPDATE Connection_requests in remove_connection_request\n");
        return 0;
    }

    return 1;
}

// Get the incoming connection requests sent to this user
//
// parameter: user_id       [int]
//      The user ID whose incoming connection requests we're retrieving
//
// returns:
//      0 on failure
//      An array of user ID's requesting connections on success
//
// contraints:
//      user_id MUST exist
function get_requests($user_id) {
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    $query = "SELECT * FROM Connection_requests WHERE sent_to=?";
    $result = exec_query($query, [$user_id]);
    if ($result == NULL) {
        print("Couldn't SELECT from Connection_requests in get_requests\n");
        return 0;
    }
    $return = array();
    while($row = $result->fetch_assoc()){
        array_push($return, $row['sent_from']);
    }
    return $return;
}

// Get the partner (current connection) of this user
//
// parameter: user_id       [int]
//      The user ID whose partner we want to get
//
// returns:
//      0 on failure
//      The user ID of user_id's partner on success
//      user_id if this user does not have a partner
//
// constraints:
//      user_id MUST exist
function get_partner($user_id){
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }
    $user = getUser($user_id, NULL);
    return $user->fetch_assoc()['partner'];
}

// Check whether this user has incoming connection requests
//
// parameter: user_id       [int]
//      The user ID to check for incoming connection requests
//
// returns:
//      false if the user has no incoming connection requests
//      -1 on failure
//      true if the user has incoming connection requests
//
// constraints:
//      user_id MUST exist
function has_requests($user_id) {
    if (!user_exists($user_id)) {
        print("No user with this ID in has_requests\n");
        return -1;
    }
    $result = exec_query("SELECT * FROM Connection_requests WHERE sent_to=?", [$user_id]);
    if ($result != NULL) {
        return ($result->num_rows > 0);
    }
    else return -1;
}

// Check whether this user has a partner
//
// parameter: user_id       [int]
//      The user ID to check for a partner
//
// returns:
//      false if the user doesn't have a partner
//      -1 on failure
//      true if the user does have a partner
//
// constraints:
//      user_id MUST exist
function has_partner($user_id) {
    if (!user_exists($user_id)) {
        print("No user with this ID in has_partner\n");
        return -1;
    }
    $result = exec_query("SELECT partner FROM Users WHERE id=?", [$user_id]);
    if ($result != NULL) {
        return $result->fetch_assoc()["partner"] != $user_id;
    }
    else return -1;
}

// Check whether this user has sent a connection request
//
// parameter: user_id       [int]
//      The user ID to check for a sent request
//
// returns:
//      false if the user has not sent a request
//      -1 on failure
//      true if the user has sent a request
//
// constraints;
//      user_id MUST exist
function sent_request($user_id) {
    if (!user_exists($user_id)) {
        print("User with this ID couldn't be found in sent_request\n");
        return -1;
    }
    $result = exec_query("SELECT * FROM Connection_requests WHERE sent_from=? and sent_to!=?", [$user_id, $user_id]);
    if ($result != NULL) {
        return ($result->num_rows > 0);
    }
    else return -1;
}

// Get the preferences for the user with this ID
//
// parameter: user_id   [int]
//      The ID of the user whose preferences we want
//
// returns:
//      An associative array of associative arrays for this user's preferences,
//          if the user exists
//      0 if the user doesn't exist
//
// constraints:
//      A user with this ID MUST exist
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
                     'Food' => array('restaurant' => 1, 'cafe' =>1, 'fast_food'=>1, 'alcohol'=>1),
                     'Entertainment' => array('concerts' => 1, 'hiking'=>1, 'bar'=>1),
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

// Set initial preference values for a new user
//
// parameter: user_id   [int]
//      The user ID whose preferences are being initialized
//
// returns:
//      1 if the preferences were set successfully
//      0 on error
//
// constraints:
//      The user with this ID MUST exist
//
// Note:
//      All preferences are initialized to 0
function initialize_preferences($user_id){
    if (!user_exists($user_id)) {
        echo "No user with this ID\n";
        return 0;
    }

    $preferences_categories = array(
        'Food' => ['(restaurant, cafe, fast_food, alcohol, user_id)', '(?,?,?,?,?)', [0,0,0,0]], 
        'Entertainment' => ['(concerts, hiking, bar, user_id)', '(?,?,?,?)', [0,0,0]],
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

function getChatMessages($sent_from, $sent_to){
    if(!user_exists($sent_from) || !user_exists($sent_to)){
        echo 'user does not exist';
        return 0;
    }

    $query = 'SELECT message, sent_from, sent_to FROM Chat_Messages WHERE (sent_from=? AND sent_to=?) OR (sent_from=? AND sent_to=?) ORDER BY date';
    $result = exec_query($query, [$sent_from, $sent_to, $sent_to, $sent_from]);
    $return = array();
    while($message = $result->fetch_assoc()){
        $return[] = $message;
    }
    return $return;
}


function addChatMessages($sent_from, $sent_to, $message){
    if($message == ""){
        echo 'message is empty';
        return 0;
    }
    if(!user_exists($sent_from) || !user_exists($sent_to)){
        echo 'user does not exist';
        return 0;
    }

    $query = 'INSERT INTO Chat_Messages (sent_from, sent_to, message) VALUES (?,?,?)';
    $result = exec_query($query, [$sent_from, $sent_to, htmlspecialchars($message)]);
    if(!$result){
        echo 'Could not insert new message';
        return 0;
    }

    return 1;
}

// Get an array of date ID's that have tags corresponding to these preferences
//
// parameter: preferences
//      An associative array of the kind returned by get_preferences
//
// returns:
//      An array of date ID's with tags corresponding to these preferences
//      NULL on error
//
// contraints:
//      None
//
// Note:
//      This should not be called outside of generate_dates. It is a helper function
function get_date_ids($preferences) {
    $arr = array();

    // For every category
    foreach ($preferences as $category) {
        // For every tag in this category
        foreach ($category as $tag) {
            // Get all rows from the Date_tags table with this tag
            $query = "SELECT * FROM Date_tags WHERE tag=?";
            $data = [$tag];
            $result = exec_query($query, $data);

            // Make sure we retrieved at least 1 row
            if ($result == NULL) {
                print("Failed to exec_query in get_date_ids\n");
                return NULL;
            }
            else if ($result->num_rows <= 0) {
                print("Encountered unknown tag in get_date_ids\n");
                print("Tag: " . $tag);
                return NULL;
            }

            // For every row we found, add the date ID to the array of date IDs
            $row = $result->fetch_assoc();
            while ($row != NULL) {
                if (!in_array($row["date_id"], $arr)) {
                    array_push($arr, $row["date_id"]);
                }
            }
        }
    }
    return $arr;
}

// Generate an array of date ideas for two users
//
// parameter: user_a    [int]
//      The user ID of one of the users
//
// parameter: user_b    [int]
//      The user ID of the other user
//
// returns:
//      An array of date idea ID's that are compatible with both users' preferences
//      NULL if either user doesn't exist
//      NULL if any other error occured
//
// constraints:
//      Both users MUST exist
function generate_dates($user_a, $user_b) {
    if (!user_exists($user_a) || !user_exist($user_b)) {
        print("User doesn't exist in generate_dates\n");
        return NULL;
    }

    // Get the preferences for both users, as associative arrays
    $ua_prefs = get_preferences($user_a);
    $ub_prefs = get_preferences($user_b);
    if ($ua_prefs == 0 || $ub_prefs == 0) {
        print("Couldn't get preferences in generate_dates\n");
        return NULL;
    }

    // Get date ID's for each users' tags
    $ua_dids = get_date_ids($ua_prefs, $ua_dids);
    $ub_dids = get_date_ids($ub_prefs, $ub_dids);

    // Get date ideas with tags matching for the second user
    $compatible_dates = array();
    foreach ($ua_dids as $ua) {
        foreach ($ub_dids as $ub) {
            if ($ua == $ub && !in_array($ua, $compatible_dates)) {
                array_push($compatible_dates, $ua);
            }
        }
    }

    // Return overlapping date ideas
    return $compatible_dates;
}

// Get information about the date with this ID
//
// parameter: date_id   [int]
//      The ID of the date whose information we want
//
// returns:
//      An associative array of information for this date
//      NULL if no date with this ID exists
//
// constraints:
//      A date with this ID MUST exist
function get_date_information($date_id) {
    if (!date_exists($date_id)) {
        return NULL;
    }

    $query = "SELECT * FROM Date_ideas WHERE id=?";
    $data = [$date_id];
    $result = exec_query($query, $data);

    if ($result == NULL) {
        print("Couldn't exec_query in get_date_information\n");
        return NULL;
    }
    else if ($result->num_rows <= 0) {
        print("No date with this ID in get_date_information\n");
        return NULL;
    }

    return $result->fetch_assoc();
}

// Add an entry to the Date_tags table that tags this date with this tag
//
// parameter: date_id   [int]
//      The ID of the date that we're tagging
//
// parameter: tag       [string]
//      The tag to add
//
// returns:
//      1 on success
//      0 on failure
//
// constraints:
//      A date with this ID MUST exist
//      The tag should be a column name from one of the preferences tables
//          e.g. "restaurant", "concerts", "morning", etc.
function add_tag($date_id, $tag) {
    if (!date_exists($date_id)) {
        print("Date doesn't exist in add_tag\n");
        return 0;
    }

    $query = "INSERT INTO Date_tags (date_id, tag) VALUES (?, ?)";
    $date = [$date_id, $tag];
    $result = exec_query($query, $date);
    
    if ($result == NULL) {
        print("Couldn't exec_query in add_tag\n");
        return 0;
    }

    return 1;
}

// Calculate the distance between this date location and this user's location
//
// parameter: date_id   [int]
//      The ID of the date to check the distance against
//
// parameter: user_id   [int]
//      The ID of the user to check the distance against
//
// returns:
//      The distance between this user and the date location, on success
//      NULL on failure
//
// constraints:
//      A date with this ID MUST exist
//      A user with this ID MUST exist
//
// Note:
//      This is a hard problem. For now, if the date and user exist, this
//      function will always return 10
function calc_distance($date_id, $user_id) {
    if (!date_exists($date_id) || !user_exists($user_id)) {
        print("Date or user doesn't exist in calc_distancen\n");
        return NULL;
    }
    return 10;
}

// Get the ID of the date with this name
//
// parameter: name  [int]
//      The name of the date whose ID we are retrieving
//
// returns:
//      The ID of the date with this name, if it exists
//      NULL otherwise
//
// constraints:
//      There MUST be a date with this name in Date_ideas
function get_date_id($name) {
    $query = "SELECT * FROM Date_ideas WHERE name=?";
    $data = [$name];
    $result = exec_query($query, $data);
    if ($result == NULL) {
        print("Couldn't exec_query in get_date_id\n");
        return NULL;
    }
    else if ($result->num_rows <= 0) {
        print("No dates with this name in get_date_id\n");
        return NULL;
    }
    return $result->fetch_assoc()["id"];
}