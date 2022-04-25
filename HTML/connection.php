<?php
include './navigation.php'
?>
<?php
require_once '../PHP/db_api.php';
require_once '../PHP/privacy_settings.php';
require_once '../PHP/helper.php';

session_start();

if(!isset($_SESSION['user'])){
    echo 'Please Login!!';
    header('./login.html'); 
    exit();
}

$user = $_SESSION['user'];
$connection_requests = get_requests($user['id']);

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

if($user['partner'] == $user['id']){
    $res = "";
    foreach($connection_requests as $id){
            $request_user = getUser($id,NULL)->fetch_assoc();
            $res = $res . "<div class=\"col-3\">
            <div class=\"card card-block card-body\">
            <img class=\"card-img-top img-fluid rounded-circle\" style=\"height:60%\" src=\"{$request_user['user_picture']}\" size= alt=\"User image\">
            <h5 class=\"card-title\">{$request_user['name']}</h5>
            <div class=\"row\">
            <img src=\"\assets\icons\accept.png\" onclick='location.href=\"/PHP/modify_connections.php?type=1&from_id=$id&to_id={$user['id']}\"' class=\"col-sm-3 offset-sm-3\"></img>
            <img src=\"\assets\icons\\reject.png\" onclick='location.href=\"/PHP/modify_connections.php?type=0&from_id=$id\"' class=\"col-sm-3\"></img></div>
            </div>
        </div>";
        }
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
                        <form action="../PHP/modify_connections.php" method="post" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <input name="connection_request" type="text" class="form-control rounded-input opacity-75" required>
                                <input type="submit" class="input-group-text rounded-submit"></input>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row"><div class="col"><img src="%s" class="img-fluid rounded-circle"></div></div>
                <div class="row"><div class="col text-center">%s</div></div>
            </div>
            
        </div>
        <h3 style="margin-left:100px;margin-top:50px">Pending Requests</h3>
        <div class="scrolling-wrapper row flex-row flex-nowrap mt-4 pb-4 pt-2" style="margin-left: 100px;">
        %s
        </div>
    ', $user['user_picture'], $user['name'], $res);
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

    // Display the partner's privacy settings
    $partner_privacy = get_privacy_settings($user['id']);

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

    // if privacy settings result is 1
    if($partner_privacy == 1){
        // preferences_html will be empty
        $preferences_html = ''; 
    }
            $display = sprintf('
    <div class="row pt-5 gx-5 gy-5">
            %s
            <div class="col">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <img src="%s" class="img-fluid rounded-circle">  
                    </div>
                </div>
                <div class="row">
                    <div class="col pt-3 text-center">
                        <input type="button" value="Chat Now!" onclick="document.location.href=\'./chat.php\'" class="btn btn-custom fs-5">
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="row"><div class="col"><img src="%s" class="img-fluid rounded-circle"></div></div>
                <div class="row"><div class="col text-center">%s</div></div>
                    <div class="row">
                        <div class="col text-center">
                        <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#moveOnModal">
                            Moving On?
                        </button>
                    </div>
                <!-- Button trigger modal -->
                
                <!-- Modal -->
                <div class="modal fade" id="moveOnModal" tabindex="-1" aria-labelledby="moveOnModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-custom">
                        <div class="modal-content p-2 modal-custom-body">
                            <div class="modal-body">
                                <div class="row"><h5>Are you sure you want to move on?</h5></div>
                                <div class="row">
                                    <p class="text-start">
                                        Executing this action will discard all current and previous data from your account and this action is irreversible.
                                    </p>   
                                </div>
                                <di class="row">
                                    <div class="col text-center">
                                        <form action="../PHP/modify_connections.php" method="post" enctype="multipart/form-data">
                                            <button type="submit" name="remove_connection" class="btn btn-custom">Move on</button>
                                        </form>
                                    </div>
                                    <div class="col text-center">
                                        <button class="btn btn-custom" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </di>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal end -->
                </div>
            </div>
        </div>
    ', $preferences_html, $_SESSION['partner']['user_picture'], $user['user_picture'], $user['name']);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
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
            border-radius: 25px;
        }

        .modal-custom{
            opacity: 0.9;
            width: 400px;
        }
        .modal-custom-body{
            border-radius: 40px;
        }
        .scrolling-wrapper{
	overflow-x: auto;
}
.card-block{
	height: 500px;
	background-color: #fff;
	border: none;
	background-position: center;
	background-size: cover;
	transition: all 0.2s ease-in-out !important;
	border-radius: 24px;

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