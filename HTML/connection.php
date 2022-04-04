
<?php

require '../PHP/db_api.php';
require '../PHP/helper.php';

$user = getUser("2","test2")->fetch_assoc();
// echo $user->fetch_assoc()['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../CSS/connection.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>connection Page</title>
</head>
<body>
    <h1><?= $user['name']?></h1>
    <h2>“When I need a pick me up, I just think of your laugh and it makes me smile”</h2>
    <h3> &#128222;</h3>
    <h4>+17165555555</h4>
    <h5>&#128231;</h5>
    <h6><?= $user['email']?></h6>
    <p>&#127969; </p>
    <p class="address"><?= $user['street_address']?></p>
    <p class="distance1">
        &#128205;</p>
    <p class="distance2">
        10km</p>
    <p class="money1"> &#128176; </p>
    <p class="money2">$500</p>
    <p class="date">Date Ideas</p>
    <img src="<?= $user['user_picture']?>" alt="Couple image" allign="right">
</body>
</html>