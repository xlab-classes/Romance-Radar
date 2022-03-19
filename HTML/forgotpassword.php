<?php

require './db_api.php';

function validate($value, $type){
    return !empty($value) && (gettype($value) == $type);
}

$security_questions = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $string_type = "string";
    $email = $_POST['Email'];
    if (validate($email, $string_type) && $user_id = get_user_id($email)){    
        $questions_query = "SELECT S.question FROM Users U, User_security_questions Us, Security_questions S WHERE
                            Us.user_id = U.id AND U.id=? AND (S.id = Us.question_id_1 OR S.id = Us.question_id_2 OR S.id = Us.question_id_3)";
        
        $result = exec_query($questions_query, [$user_id]);
        
        $security_questions .= '<form action="./forgotpassword.php" method="post" enctype="multipart/form-data">';
        
        while($row = $result->fetch_assoc()){
            $security_questions .= '<label>'.$row['question'].'</label>';
        }
        
        $security_questions .= '</form>';
    
    }else{
        echo "User does not exist";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Forgot Password</title>
    <style>
        body{
            background-color: #FFC0CB;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #9F1111;
        }
        #Submit{
            background-color: #C75656;
            border-color: #C75656;
        }
        #Submit:hover{
            background-color: #e76c6c;
            border-color: #e76c6c;
            transition: 0.3s;
        }
        #top{
            height: 110px;
            width: 110px;
        }
        #bottom{
            height: 200px;
            width: 200px;
        }
        .heading{
            padding-top: 30vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 align-self-end">
                <div class="row">
                    <div class="col">
                        <img id="bottom" src="../assets/hearts/hearts.png" alt="three hearts" class="img">
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="row m-4">
                    <div class="col">
                        <h3 class="text-center heading">
                            Forgot Password
                        </h3>
                    </div>
                </div>
                <form action="./test.php" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Email" hidden>Email</label>
                            <input name="Email" class="form-control text-center" id="Email" type="text" placeholder="Email"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-3">
                        <div class="col-3">
                            <label for="Submit" hidden>Submit</label>
                            <input class="btn-primary form-control text-center" id="Submit" type="submit"/>
                        </div>
                    </div>
                </form>
                <?php
                    echo $security_questions;
                ?>
            </div>
            <div class="col-2 align-self-end">
                <div class="row">
                    <div class="col">
                        <img id="bottom" src="../assets/hearts/hearts.png" alt="three hearts" class="img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>