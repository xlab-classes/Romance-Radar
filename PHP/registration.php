<?php

$host = 'oceanus.cse.buffalo.edu';
$user = 'swastikn';
$db = 'cse442_2022_spring_team_j_db';
$password = 50307246;

$connection = new mysqli($host, $user, $password, $db);

function create_user($name, $email, $pwd, $addr, $zipcode, $bday) {
    global $connection;
    // Make sure none of the required fields are empty
    if (empty($name) || empty($email) || empty($pwd) || empty($addr) || empty($zipcode) || empty($bday)) {
        echo "Missing required fields in create_user";
        return 0;
    }

    // data validate missing will be added once dev_api is complet
    $user_picture = 'Testing';
    $stmt = $connection->prepare("INSERT INTO Users (name, email, password, user_picture, street_address, zipcode, birthday) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssis', $name, $email, $pwd, $user_picture, $addr, $zipcode, $bday);
    $result = $stmt->execute();
    if (!$result) {
        echo "Couldn't insert user into database";
    }
}


if ($connection->connect_error) {
    echo "Connection failed: (" . $connection->errno . ") ." . $connection->error;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['Name'];
    $address = $_POST['Address'];
    $zip = $_POST['Zip'];
    $city = $_POST['City'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $month = $_POST['Month'];
    $day = $_POST['Day'];
    $year = $_POST['Year'];

    $date = mktime(0, 0, 0, $day, $month, $year); 
    $bday = date('Y-m-d', $date);

    create_user($name, $email, $password, $address, $zip, $bday);
}

header("Location: ../HTML/registration.html");