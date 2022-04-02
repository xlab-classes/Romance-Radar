<?php

// File that contains account verification logic via a captcha

$files = glob('assets/Captchas/*.png');


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
    return $file;
}

/* Function that will take in user input and compare it to the correct answer */
function verify_captcha($input) {
    global $captcha_map;
    $captcha = get_captcha();
    $answer = $captcha_map[$captcha];
    return $input == $answer;
}




