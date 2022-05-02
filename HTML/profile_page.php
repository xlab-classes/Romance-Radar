<?php
include './navigation.php'
?>
<?php
require_once '../PHP/db_api.php';
session_start();
if (!isset($_SESSION['user'])) {
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}
$p = get_preferences($_SESSION['user']['id']);

function selectedCategory($cat)
{
    global $p;
    foreach ($p[$cat] as $c => $sc) {
        if (!$sc) {
            return "";
        }
    }
    return "checked";
}

function selectedSubCategory($cat, $sub)
{
    global $p;
    return $p[$cat][$sub] ? "checked" : "";
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body {
            background-color: #FFC0CB;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: #9F1111;
        }

        #profile_picture {
            height: 200px;
            width: 200px;
            <?php if($_SESSION['user']['verified']){ ?>
                box-shadow: 0 0 10px #0000FF;
            <?php } ?>
        }

        
        .dark-mode {
            background-color: palevioletred;
        }


        button:hover {
            background-color: #e76c6c;
            border-color: #e76c6c;
            transition: 0.3s;
        }



    </style>

</head>

<body>
    <button onclick="myFunction()">Toggle dark mode</button>

    <script>
        function myFunction() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>
    <section id="propage">
        <div class="container">
            <form action="../PHP/update_profile.php" method="post" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5>Date Preference</h5>
                            <label for="MaxCost">Maximum Cost</label>
                            <?php
                            echo '<input name="MaxCost" value=' . strval($p['Date_preferences']['cost']) . ' class="form-control left-align my-2 p-2" id="MaxCost" type="number" placeholder="Maximum Cost"/>';
                            ?>
                            <label for="MaxDist">Maximum Distance</label>
                            <?php
                            echo sprintf('<input name="MaxDist" value=%d class="form-control left-align my-2 p-2" id="MaxDist" type="text" placeholder="Maximum Distance"/>', $p['Date_preferences']['distance']);
                            ?>
                            <label for="PreDateLen">Preferred date length (hours)</label>
                            <?php
                            echo sprintf('<input name="PreDateLen" value=%d class="form-control left-align my-2 p-2" id="PreDateLen" type="text" placeholder="Preferred Date Length (hrs)"/>', $p['Date_preferences']['length']);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5>Personal Details</h5>
                            <label for="CngFN">Change your first name</label>
                            <?php
                            $name_split = explode(" ", $_SESSION['user']['name']);
                            echo sprintf('<input value="%s" name="CngFN" class="form-control left-align my-2 p-2" id="CngFN" type="text" placeholder="Change your first name"/>', $name_split[0]);
                            ?>

                            <label for="CngLN">Change your last name</label>
                            <?php
                            $last_name = isset($name_split[1]) ? $name_split[1] : "";
                            echo sprintf('<input value="%s" name="CngLN" class="form-control left-align my-2 p-2" id="CngLN" type="text"/>', $last_name);
                            ?>
                            <label for="CngZip">Change your zip code</label>
                            <?php
                            echo sprintf('<input value=%d name="CngZip" class="form-control left-align my-2 p-2" id="CngZip" type="number" placeholder="Change your zip code"/>', $_SESSION['user']['zipcode']);
                            ?>

                            <label for="CngDob">Change your date of birth</label>
                            <?php
                            echo sprintf('<input name="CngDob" value="%s" class="form-control left-align my-2 p-2" id="CngDob" type="date" placeholder="Change your date of birth"/>', $_SESSION['user']['birthday']);
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5>Security</h5>
                            <label for="OldPwd" hidden>Old password</label>
                            <input name="OldPwd" class="form-control left-align my-2 p-2" id="OldPwd" type="text" placeholder="Old password" />

                            <label for="NewPwd" hidden>New password</label>
                            <input name="NewPwd" class="form-control left-align my-2 p-2" id="NewPwd" type="text" placeholder="New password" />

                            <label for="RenPwd" hidden>Re-enter password</label>
                            <input name="RenPwd" class="form-control left-align my-2 p-2" id="RenPwd" type="text" placeholder="Re-enter password" />

                            <label for="CngEmail" hidden>Change your email</label>
                            <input name="CngEmail" class="form-control left-align my-2 p-2" id="CngEmail" type="text" placeholder="Change your email" />
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 bg">
                            <div class="text-center">
                                <?php
                                echo '<img id="profile_picture" src="' . $_SESSION['user']['user_picture'] . '" class="border-0 img-thumbnail rounded-circle">'
                                ?>
                                <input class="form-control form-control-sm m-2" type="file" name="profile_picture" />
                                <p class="lead text-center text-black">
                                <h1><?php echo $_SESSION['user']['name']; ?></h1>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5 class="font-weight-bold text-black">Preferred Catagories</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" name="Entertainment" id="Entertainment">
                                <label class="form-check-label text-black h6" for="Entertainment">
                                    Entertainment
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Concerts" id="Concerts" <?php echo selectedSubCategory('Entertainment', 'concerts'); ?>>
                                    <label class="form-check-label text-black h6" for="Concerts">
                                        Concerts
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Hiking" id="Hiking" <?php echo selectedSubCategory('Entertainment', 'hiking'); ?>>
                                    <label class="form-check-label text-black h6" for="Hiking">
                                        Hiking
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Bars" id="Bars" <?php echo selectedSubCategory('Entertainment', 'bar'); ?>>
                                    <label class="form-check-label text-black h6" for="Bars">
                                        Bars
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5 class="font-weight-bold text-black mt-4"> </h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" name="Food" id="Food">
                                <label class="form-check-label text-black h6" for="Food">
                                    Food
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Restaurant" id="Restaurant" <?php echo selectedSubCategory('Food', 'restaurant'); ?>>
                                    <label class="form-check-label text-black h6" for="Restaurant">
                                        Restaurant
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Cafe" id="Cafe" <?php echo selectedSubCategory('Food', 'cafe'); ?>>
                                    <label class="form-check-label text-black h6" for="Cafe">
                                        Cafe
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="FastFood" id="FastFood" <?php echo selectedSubCategory('Food', 'fast_food'); ?>>
                                    <label class="form-check-label text-black h6" for="FastFood">
                                        Fast Food
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Alcohol" id="Alcohol" <?php echo selectedSubCategory('Food', 'alcohol'); ?>>
                                    <label class="form-check-label text-black h6" for="Alcohol">
                                        Alcohol
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5 class="font-weight-bold text-black mt-4"></h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" name="Venue" id="Venue">
                                <label class="form-check-label text-black h6" for="Venue">
                                    Venue
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Indoors" id="Indoors" <?php echo selectedSubCategory('Venue', 'indoors'); ?>>
                                    <label class="form-check-label text-black h6" for="Indoors">
                                        Indoors
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Outdoors" id="Outdoors" <?php echo selectedSubCategory('Venue', 'outdoors'); ?>>
                                    <label class="form-check-label text-black h6" for="Outdoors">
                                        Outdoors
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="SocialEvents" id="SocialEvents" <?php echo selectedSubCategory('Venue', 'social_events'); ?>>
                                    <label class="form-check-label text-black h6" for="SocialEvents">
                                        Social Events
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-5 bg">
                            <h5 class="font-weight-bold text-black mt-4"></h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" name="Anytime" id="Anytime">
                                <label class="form-check-label text-black h6" for="Anytime">
                                    Anytime
                                </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Morning" id="Morning" <?php echo selectedSubCategory('Date_time', 'morning'); ?>>
                                    <label class="form-check-label text-black h6" for="Morning">
                                        Morning
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Afternoon" id="Afternoon" <?php echo selectedSubCategory('Date_time', 'afternoon'); ?>>
                                    <label class="form-check-label text-black h6" for="Afternoon">
                                        Afternoon
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="Evening" id="Evening" <?php echo selectedSubCategory('Date_time', 'evening'); ?>>
                                    <label class="form-check-label text-black h6" for="Evening">
                                        Evening
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-4 mt-4 pe-4">
                        <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
            <form method="post" action="../PHP/delete_profile.php">
                <button type="submit" class="btn btn-danger">Delete Account</button>
        </div>
        </div>
        </form>
        </div>
    </section>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>
