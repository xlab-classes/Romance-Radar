<?php

require_once '../PHP/db_api.php';
require_once '../PHP/helper.php';

session_start();

if(!isset($_SESSION['user'])){
    echo 'Please Login!';
    header('./login.html');
    exit();
}

if(!$_SESSION['user']['partner']){
    echo "You don't have a partner to chat with!";
    header('./profile_page.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    addChatMessages($_SESSION['user']['id'], $_SESSION['user']['partner'], $_POST['message']);
}

$messages = getChatMessages($_SESSION['user']['id'], $_SESSION['user']['partner']);

$display_messages = '';

foreach($messages as $msg){
    if($msg['sent_from'] == $_SESSION['user']['id']){
        $display_messages.=sprintf('%s: ', $_SESSION['user']['name']);
    }else{
        $display_messages.=sprintf('%s: ', $_SESSION['partner']['name']);
    }
    $display_messages.=sprintf('%s<br>', $msg['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./chat.php" method="post" enctype="multipart/form-data">
        <lable for="message">Send a message</label>
        <textarea id="story" name="message" rows="5" cols="33" required></textarea>
        <input type='submit'/>
    </form>
<hr/>
<iframe src='./messages.php'>
</body>
</html>