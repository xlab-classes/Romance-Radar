<?php

require_once 'db_api.php';

// Update a user's first and last name
// Returns
//      0 on failure
//      1 on successs
function update_name($id, $name) {

    # Check if user exists
    $user = user_exists($id);
    if ($user == false) {
        echo "User does not exist. Cannot update name.";
        return 0;
    }

    # Check if either first or last name is null
    if (empty($name)) {
        echo "Name cannot be empty.";
        return 0;
    }

    # Craft the query
    $query = "UPDATE Users SET name=? WHERE id =?";

    # Execute the query
    $result = exec_query($query, [$name, $id]);

    # Check if query was successful 
    if (!$result) {
        echo "Failed to update name. The query was not successful.";
        return 0;
    }
    # Return true if successful
    return 1;
}

// Update a user's address
// Returns
//      0 on failure
//      1 on success
// TODO: Update other aspects of the address 
function update_address($id, $zip) {

    if (empty($id) || empty($address) || empty($zip) || empty($city)) {
        return 0;       // Can't have empty inputs
    }

    $user_exists = user_exists($id);
    if ($user_exists == false) return 1;            // User must exist
    else {
        $result = exec_query("UPDATE Users SET zipcode=? WHERE id=?", [$zip, $id]);
        if ($result == NULL) return 0;  // Failed to execute query
        else return 1;
    }
}

// Update the users email.
// Returns
//      1 if the user email was updated
//      0 if not
function update_email($id, $email) {

    # Checking the validtiy of the inputs
    if(empty($id) || empty($email)) {
        echo "User ID or email cannot be empty.";
        return 0;
    }
    
    # Check if the user with $id exists
    $user = user_exists($id);
    if ($user == false) {
        echo "User does not exist. Cannot update email.";
        return 0;
    }

    // Check if the email is valid
    // Docs for filter_var: http://php.net/manual/en/function.filter-var.php
    // Docs for FILTER_VALIDATE_EMAIL: http://php.net/manual/en/filter.filters.validate.php
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email is not valid.";
        return 0;
    }

    # Craft the query
    $query = "UPDATE Users SET email=? WHERE id=?";

    # Check if the query was successful
    $result = exec_query($query, [$email, $id]);
    if (!$result) {
        echo "Failed to update email. The query was not successful.";
        return 0;
    }
    # Return 1 if successful
    return 1;
}

// Updates the users date of birth
// Returns
    //   1 if the user's date of birth was updated
    //   0 if not
function update_dob($id, $dob) {

    // Checking the validtiy of the inputs
    if(empty($id) || empty($dob)) {
        echo "User ID or date of birth cannot be empty.";
        return 0;
    }
    
    // Check if the user with $user_id exists
    $user = user_exists($id);
    if ($user == false) {
        echo "User does not exist. Cannot update email.";
        return 0;
    }

    // Check if the date of birth is valid
    // Docs for preg_match: http://php.net/manual/en/function.preg-match.php
    // Stack overflow ref: https://stackoverflow.com/questions/13194322/php-regex-to-check-date-is-in-yyyy-mm-dd-format
    // TODO: Check if the date of birth is feasible (i.e. not in the future or before 1900)
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $dob)) {
        echo "Date of birth is not valid.";
        echo "The date of birth must be in the format YYYY-MM-DD.";
        return 0;
    }

    // Craft the query
    $query = "UPDATE Users SET dob=? WHERE id=?";
    
    // Check if the query was successful
    $result = exec_query($query, [$dob, $id]);

    if (!$result) {
        echo "Failed to update date of birth. The query was not successful.";
        return 0;
    }

    // Return 1 if successful
    return 1;
}
