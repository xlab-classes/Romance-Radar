<?php

require '../PHP/db_api.php';
require '../PHP/helper.php';

$security_questions = '
    <div class="row justify-content-center m-4">
        <div class="col-6">
            <label for="Email" hidden>Email</label>
            <input name="Email" class="form-control text-center" id="Email" type="text" placeholder="Email" required/>
        </div>
    </div>
';
$password_changed = FALSE;
function get_question($question_id){
    $query = 'SELECT question FROM Security_questions WHERE id=?';
    $result = exec_query($query, [(int)$question_id]);

    if(!$result || !$result->num_rows){
        exit('No question Found');
    }

    return $result->fetch_assoc()['question'];

}

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Email'])) {

    $string_type = "string";
    $email = $_POST['Email'];
    if (validate($email, $string_type) && $user_id = get_user_id($email)){    
    
        $questions_query = "SELECT * FROM User_security_questions WHERE user_id=?";
        
        $result = exec_query($questions_query, [$user_id]);
        
        $row = $result->fetch_assoc();

        if($row){
            
            $_SESSION['security'] = $row;
            $_SESSION['security']['Email'] = $email;
            $security_questions = '';
            $input_name = 'question';
            $answer = 'answer_';
            $id = '_id_';
            
            for($i = 1; $i <= 3; $i+=1){
                $a = $answer.strval($i);
                $q = get_question($row[$input_name.$id.strval($i)]);
                $security_questions .= 
                sprintf('
                <div class="row justify-content-center m-4">
                    <div class="col-6">
                        <label for="%s">%s</label>
                        <input name="%s" class="form-control text-center" id="%s" type="text" placeholder="Answer" required/>
                    </div>
                </div>
                ', $input_name.strval($i), $q, $input_name.'_'.strval($i), $a);
            }
        }else{
            echo "Security Questions Not found";    
        }        
    }else{
        echo "User does not exist";
    }
}else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_1'])){
    $string_type = "string";
    $answer_1 = $_POST['question_1'];
    $answer_2 = $_POST['question_2'];
    $answer_3 = $_POST['question_3'];

    if(validate($answer_1, $string_type) && validate($answer_2, $string_type) && validate($answer_3, $string_type) && isset($_SESSION['security'])){
        $q_row = $_SESSION['security'];
        if($answer_1 == $q_row['answer_1'] && $answer_2 == $q_row['answer_2'] && $answer_3 == $q_row['answer_3']){
            $security_questions=sprintf('
                <div class="row justify-content-center m-4">
                    <div class="col-6">
                        <div class="row pb-2 justify-content-center"></<label for="Password">%s</label></div>
                        <div class="row"><input name="Password" class="form-control text-center" id="Password" type="text" placeholder="New Password" required/></div>
                    </div>
                </div>', $q_row['Email']);
        }else{
            $security_questions=sprintf('
            <div class="col-6">
                <div class="row pb-2 justify-content-center"></<label for="Error">%s</label></div>
            </div>
            ', 'Wrong Answer');
        }
    }else{
        echo 'Validation failed';
    }

}else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Password'])){
    $string_type = "string";
    $password_new = $_POST['Password'];

    if(validate($password_new, $string_type) && validate_pwd($password_new)){
        $query = 'UPDATE Users SET password=? WHERE id=?';
        $result = exec_query($query, [password_hash($password_new, PASSWORD_DEFAULT), (int)$_SESSION['security']['user_id']]);
        if(!$result){
            echo 'Failed to change password';
        }else{
            $password_changed = TRUE;
            $security_questions = '';
        }
    }else {
        echo 'Failed to change password';
    }

}
?>
<div class="row"></div>
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
                    <?php
                        echo $security_questions;
                    ?>
                    <?php
                    if(!$password_changed){
                    echo '
                    <div class="row justify-content-center m-3">
                        <div class="col-3">
                            <label for="Submit" hidden>Submit</label>
                            <input class="btn-primary form-control text-center" id="Submit" type="submit"/>
                        </div>
                    </div>';
                    }else{
                        echo 'Password Changed!';
                    }
                    ?>
                </form>
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