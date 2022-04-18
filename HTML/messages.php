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
    $message_template = '
    <li class="d-flex justify-content-between mb-4">
        <img id="profile_picture" src="%s"
            alt="avatar"
            class="rounded-circle d-flex align-self-start me-3 shadow-1-strong"
            width="60" >
        <div class="card mask-custom w-100">
            <div
                class="card-header d-flex justify-content-between p-3"
                style="border-bottom: 1px solid rgba(255, 255, 255, 0.3);">
                <p class="fw-bold mb-0">%s</p>
                <p class="text-light small mb-0">
            </div>
            <div class="card-body">
            <p class="mb-0">
                %s
            </p>
            </div>
        </div>

    </li>
    ';
    if($msg['sent_from'] == $_SESSION['user']['id']){
        $display_messages.=sprintf($message_template, $_SESSION['user']['user_picture'], $_SESSION['user']['name'], $msg['message']);
    }else{
        $display_messages.=sprintf($message_template, $_SESSION['partner']['user_picture'], $_SESSION['partner']['name'], $msg['message']);
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        body{
                    background-color: #FFC0CB;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                    color: #9F1111;
                }

        .text-left {
        text-align: left;
        }

        .text-right {
        text-align: right;
        }

        .text-center {
        text-align: center;
        }



        .mask-custom {
        background: rgba(24, 24, 16, .2);
        border-radius: 2em;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.05);
        background-clip: padding-box;
        box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
        }

    </style>
</head>
<body onload='setTimeout(()=>{location.reload()}, 7000)'>
    <?php echo $display_messages;?>
</body>
</html>