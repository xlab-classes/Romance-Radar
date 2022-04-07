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
<body onload='setTimeout(()=>{location.reload()}, 5000)'>
    <?php echo $display_messages;?>
</body>
</html>