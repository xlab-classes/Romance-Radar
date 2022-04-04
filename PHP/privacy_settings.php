<?php
require_once '../PHP/db_api.php';
session_start();
if(!isset($_SESSION['user'])){
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $max_distance = (int)$_POST['MaxDistance'];
    $max_cost = (int)$_POST['MaxCost'];
    $date_length = (int)$_POST['DateLen'];
    $date_of_birth = $_POST['DOB'];
    $time_preference = $_POST['TimePref'];
    $entertainment_preference = $_POST['EntPref'];
    $venue_preference = $_POST['VenuePref'];
    $food_preference = $_POST['FoodPref'];

    if(!validate($max_distance, 'integer') || !validate($max_cost, 'integer') 
    || !validate($date_length, 'integer') || !validate($date_of_birth, 'string') 
    || !validate($time_preference, 'string') || !validate($entertainment_preference, 'string') 
    || !validate($venue_preference, 'string') || !validate($food_preference, 'string')){
        echo 'Invalid input';
        header('Location: ./profile_page.html');
        exit();
    }
    // If the verify user button is pressed
    if (isset($_POST['VerifyBtn'])) {
        
    }


// File that contains account verification logic via a captcha

$files = glob('assets/Captchas/*.png');

$veriifed_status = get_verified_status($_SESSION['user']['id']);



/* Create a mapping of a capcha image to a string representing the answer */
$captcha_map = array(
    'assets/Captchas/2ceg.png' => '2ceg',
    'assets/Captchas/24f6w.png' => '24f6w',
    'assets/Captchas/226md.png' => '226md',
);


/* Function that will fetch a random capcha image from our assets folder */
function get_captcha() {
    global $files;
    $file = $files[rand(0, count($files) - 1)];
    imagejpeg($file);
}

/* Function that will take in user input and compare it to the correct answer */
function verify_captcha($input) {
    global $captcha_map;
    $captcha = get_captcha();
    $answer = $captcha_map[$captcha];
    return $input == $answer;
}
}



