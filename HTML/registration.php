
<?php

require '../PHP/db_api.php';
require '../PHP/helper.php';

$question_id_1 = rand(1,20);
$question_id_2 = rand(1,20);
$question_id_3 = rand(1,20);


$question_1 = get_question($question_id_1);
$question_2 = get_question($question_id_2);
$question_3 = get_question($question_id_3);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Registration</title>
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
            width: 11   0px;
        }
        #bottom{
            height: 200px;
            width: 200px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <div class="row">
                    <div class="col">
                        <img id="top" src="../assets/hearts/hearts.png" alt="three hearts" class="img">
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="row m-4">
                    <div class="col">
                        <h3 class="text-center">
                            One step away from stepping up your dating game!
                        </h3>
                    </div>
                </div>
                <form action="../PHP/registration.php" method="post" enctype="multipart/form-data">
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Name" hidden>Full Name</label>
                            <input name="Name" class="form-control text-center" id="Name" type="text" placeholder="Full Name"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Address" hidden>Street Address</label>
                            <input name="Address" class="form-control text-center" id="Address" type="text" placeholder="Street Address"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-3">
                            <label for="Zip" hidden>Zip Code</label>
                            <input name="Zip" class="form-control text-center" id="Zip" type="number" placeholder="Zip Code"/>
                        </div>
                        <div class="col-3">
                            <label for="City" hidden>City</label>
                            <input name="City" class="form-control text-center" id="City" type="text" placeholder="City"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Email" hidden>Email</label>
                            <input name="Email" class="form-control text-center" id="Email" type="text" placeholder="Email"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Password" hidden>Password</label>
                            <input name="Password" class="form-control text-center" id="Password" type="password" placeholder="Password"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Date" hidden>Date</label>
                            <input name="Date" class="form-control text-center" type="date" name="date" id="date">
                        </div>
                    </div>
                    <?php
                    echo sprintf('
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Question_1">%s</label>
                            <input name="Question_id_1" value="%u" hidden>
                            <input name="Question_1" class="form-control text-center" id="Question_1" type="text" placeholder="Answer"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Question_2">%s</label>
                            <input name="Question_id_2" value="%u" hidden>
                            <input name="Question_2" class="form-control text-center" id="Question_2" type="text" placeholder="Answer"/>
                        </div>
                    </div>
                    <div class="row justify-content-center m-4">
                        <div class="col-6">
                            <label for="Question_3">%s</label>
                            <input name="Question_id_3" value="%u" hidden>
                            <input name="Question_3" class="form-control text-center" id="Question_3" type="text" placeholder="Answer"/>
                        </div>
                    </div>
                    ', $question_1, $question_id_1, $question_2, $question_id_2, $question_3, $question_id_3);
                    ?>
                    <div class="row justify-content-center m-3">
                        <div class="col-3">
                            <label for="Submit" hidden>Submit</label>
                            <input class="btn-primary form-control text-center" id="Submit" type="submit"/>
                        </div>
                    </div>
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