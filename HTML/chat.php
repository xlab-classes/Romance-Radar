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
    if($msg['sent_from'] == (int)$_SESSION['user']['id']){
        $display_messages.=sprintf('%s: ', $_SESSION['user']['name']);
    }else{
        $display_messages.=sprintf('%s: ', $_SESSION['partner']['name']);
    }
    $display_messages.=sprintf('%s<br>', $msg['message']);
}
?>

<!doctype html>
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    .iframe{
        height: 50vh;
        width: 100%;
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
  <body>

  <section class="chat">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-7 col-xl-7">
        <ul class="list-unstyled text-white">
            <li class="d-flex justify-content-between mb-4">
            <iframe src="./messages.php" frameborder="0" class="iframe"></iframe>
            </li>

          <li class="mb-3">
          <form action="./chat.php" method="post" enctype="multipart/form-data">
                <div class="form-outline form-white">
                <textarea class="form-control" id="textAreaExample" rows="4" name="message" required></textarea>
                </div>
            </li>
            <button type="Submit" class="btn btn-light btn-lg btn-rounded float-end">
                Send
            </button>
           </form>
        </ul>
      </div>
    </div>
  </div>
</section>
  


</body>
</html>
