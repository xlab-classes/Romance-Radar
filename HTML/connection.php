<?php
require_once '../PHP/db_api.php';
require_once '../PHP/helper.php';

session_start();
if(!isset($_SESSION['user'])){
    echo 'Please Login!!';
    header('./login.html');
    exit();
}

$user = $_SESSION['user'];

if(is_null($user['partner'])){
    $display = sprintf('
    <div class="row pt-5">
            <div class="col text-center"><h3>What are you waiting for?</h3></div>
        </div>
        <div class="row pt-5 g-5">
            <div class="col-4">
                <img src="../assets/cupid.png" class="img-fluid">
            </div>

            <div class="col">
                <div class="row">
                    <p class="col fst-italic fw-light">
                        Let\'s face it, coming up with a date idea that\'s as fun and unique as your relationship can be just as hard as finding someone to date in the first place. Whether you\'re commuting to work or traveling 20 steps from your bed to your desk and back again, most of us just don\'t have a lot of creative juices left over when we\'re done for the day. 
                        <br/><br/>Leave the creativity to us!
                        <br/><br/><br/>Match with your soulmate right now!
                    </p>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control rounded-input opacity-75">
                            <input value="submit" type="button" class="input-group-text rounded-submit"></input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row"><div class="col"><img src="%s" class="img-fluid rounded-circle"></div></div>
                <div class="row"><div class="col text-center">%s</div></div>
            </div>
        </div>
    ', $user['user_picture'], $user['name']);
}else{
    $partner_preferences = get_preferences((int)$user['partner']);
    $preferences_categories = array(
        'Food' => array('restaurant' => 'Restaurant', 'cafe' =>'Cafe', 'fast_food'=>'Fast Food', 'alcohol'=>'Alcohol'),
        'Entertainment' => array('concerts' => 'Concerts', 'hiking'=>'Hiking', 'bar'=>'Bar'),
        'Venue' => array('indoors'=>'Indoors', 'outdoors'=>'Outdoors', 'social_events'=>'Social Events'),
        'Date_time' => array('morning'=>'Morning', 'afternoon'=>'Afternoon', 'evening'=>'Afternoon'));
    $selected_preferences = array();
    foreach($preferences_categories as $cat => $sub_cats){
        $selected_preferences[$cat] = '';
        foreach($sub_cats as $sub_cat => $value){
            if($partner_preferences[$cat][$sub_cat]){
                $selected_preferences[$cat] .= sprintf('%s, ', $value);
            }
        }
        $selected_preferences[$cat] = $selected_preferences[$cat]==''? 'None Selected' : rtrim($selected_preferences[$cat], ", ");
    }
    $preferences_html = sprintf('
    <div class="ps-5 col-3">
        <div class="row">
            <div class="col"><h3>%s</h3></div>
        </div>
        <div class="row">
            <div class="col">
                <p class="fst-italic fw-light font">“When I need a pick me up, I just think of your laugh and it makes me smile”</p>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-3">
                <img src="../assets/icons/entertainment.png" alt="" class="img-fluid">
            </div>
            <div class="col">%s</div>
        </div>
        <div class="row p-2">
            <div class="col-3">
                <img src="../assets/icons/restaurant.png" alt="" class="img-fluid">
            </div>
            <div class="col">%s</div>
        </div>
        <div class="row p-2">
            <div class="col-3">
                <img src="../assets/icons/map-locator.png" alt="" class="img-fluid">
            </div>
            <div class="col">%s</div>
        </div>
        <div class="row p-2">
            <div class="col-3">
                <img src="../assets/icons/clock.png" alt="" class="img-fluid">
            </div>
            <div class="col">%s</div>
        </div>
        <div class="row p-2">
            <div class="col-3">
                <img src="../assets/icons/distance.png" alt="" class="img-fluid">
            </div>
            <div class="col">%d</div>
            <div class="col-3">
                <img src="../assets/icons/dollar.png" alt="" class="img-fluid">
            </div>
            <div class="col">%d</div>
        </div>
    </div>',$_SESSION['partner']['name'], $selected_preferences['Entertainment'], $selected_preferences['Food'], $selected_preferences['Venue'], $selected_preferences['Date_time']
            , $_SESSION['partner']['zipcode'], $partner_preferences['Date_preferences']['cost']);
    $display = sprintf('
    <div class="row pt-5 gx-5 gy-5">
            %s
            <div class="col">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <img src="../assets/generic_profile_picture.jpg" class="img-fluid rounded-circle">  
                    </div>
                </div>
                <div class="row">
                    <div class="col pt-3 text-center">
                        <input type="button" value="Chat Now!" class="btn btn-custom fs-5">
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row"><div class="col"><img src="../assets/generic_profile_picture.jpg" class="img-fluid rounded-circle"></div></div>
                <div class="row"><div class="col text-center">Swastik</div></div>
                <div class="row">
                    <div class="col text-center pt-5">
                        <input type="button" value="Moving On?" class="btn btn-custom">
                    </div>
                </div>
            </div>
        </div>
    ', $preferences_html);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body{
            background-color: #FFC0CB;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            
        }
        .rounded-input{
            border-radius: 25px 0 0 25px;
        }
        .rounded-submit{
            border-radius: 0 25px 25px 0px;
            background-color: #FF4F4F;
        }
        .font{
            font-size: small;
        }
        .icon{
            height: 30px;
            width: 30px;
        }
        .btn-custom{
            color: white;
            background-color: #FF4F4F;
            border-radius: 25px 25px 25px 25px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <?php
        echo $display;
        ?>
    </div>
</body>
</html>