<?php include './navigation.php';?>
<?php
require_once '../PHP/db_api.php';
session_start();
if(!isset($_SESSION['user'])){
    echo 'Not logged in';
    header('Location: ./login.html');
    exit();
}

$_SESSION['captcha'] = get_captcha(rand(1,3));

function chk($cat){
  return $_SESSION['user']['privacy_settings'][$cat]==1?'checked':'';
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

    </style>

  </head>
  <body>
    <section id="privacy&settings">
<div class="container px-4">
 <form action="../PHP/privacy_settings.php" method="post" enctype="multipart/form-data">
  <div class="row gx-5">
    <div class="row m-4">
        <div class="col">
            <h2 class="text-center">
                Settings and Privacy
            </h2>
        </div>
    </div>
      <!-- Privacy settings -->
    <div class="col">
     <div class="p-3 bg">
        <h3 class="text-center">Privacy Settings</h3>
        <h5 class="text-center">Select what you would like to hide from your connection</h5>
    <div class="container px-4">
          <div class="row gx-5">
            <div class="col">
             <div class="p-3 bg">
                 <div class="form-check">
                  <input class="form-check-input m-2" type="checkbox" value="true" name="MaxCost" id="MaxCost" <?php echo chk('max_cost')?>>
                  <label class="form-check-label text-black h6 m-2" for="MaxCost">
                  Max Cost
                  </label>
                  </div>
                  <div class="form-check">
                  <input class="form-check-input m-2" type="checkbox" value="true" name="MaxDistance" id="MaxDistance" <?php echo chk('max_distance')?>>
                  <label class="form-check-label text-black h6 m-2" for="MaxDistance">
                  Max Distance
                  </label>
                  </div>
                  <div class="form-check">
                  <input class="form-check-input m-2" type="checkbox" value="true" name="DateLen" id="DateLen" <?php echo chk('date_len')?>>
                  <label class="form-check-label text-black h6 m-2" for="DateLen">
                  Date Length
                  </label>
                  </div>
                  <div class="form-check">
                  <input class="form-check-input m-2" type="checkbox" value="true" name="DOB" id="DOB" <?php echo chk('date_of_birth')?>>
                  <label class="form-check-label text-black h6 m-2" for="DOB">
                  Date of Birth
                  </label>
                  </div>
             </div>
            </div>
            <div class="col">
             <div class="p-3 bg">
                 <div class="form-check">
                        <input class="form-check-input m-2" type="checkbox" value="true" name="TimePref" id="TimePref" <?php echo chk('time_pref')?>>
                        <label class="form-check-label text-black h6 m-2" for="TimePref">
                        Time Preferences
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input m-2" type="checkbox" value="true" name="EntPref" id="EntPref" <?php echo chk('ent_pref')?>>
                        <label class="form-check-label text-black h6 m-2" for="EntPref">
                        Entertain. Pref.
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input m-2" type="checkbox" value="true" name="VenuePref" id="VenuePref" <?php echo chk('venue_pref')?>>
                        <label class="form-check-label text-black h6 m-2" for="VenuePref">
                        Venue Preferences
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input m-2" type="checkbox" value="true" name="FoodPref" id="FoodPref" <?php echo chk('food_pref')?>>
                        <label class="form-check-label text-black h6 m-2" for="FoodPref">
                        Food Preferences
                        </label>
                        </div>
             </div>
            </div>
          </div>
    </div>
     </div>
    </div>
  
    <!-- User verification -->
    <!-- This section should only be visible to the user if they are not verified -->
  </div>
  <div class="d-grid gap-2 col-2 mx-auto">
    <button class="btn btn-success" type="submit">Save Changes</button>
  </div>
 </form>
</div>
<div class="p-3 bg">
        <h3 class="text-center">Additional Settings</h3>
        <h5 class="text-center">User verification</h5>
        <div class="text-center">Current Status: <?php echo $_SESSION['user']['verified']?'Verified':'Not Verified'; ?></div>
        <!-- Generate a random capcha image from the database--> 
        <img src="<?php echo $_SESSION['captcha']['image'];?>" alt="captcha" class="mx-auto d-block">
        <div class="d-grid gap-2 col-4 mx-auto">
          <!-- There should be an input form the enter the capcha-->
          <div class="p-3 bg">
            <h5 class="text-center">Enter the captcha</h5>
            <form  action="../PHP/captcha.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Enter the captcha">
              </div>
              <button class="btn btn-primary" type="submit" name="VerifyBtn">Verify User</button>
            </form>
          </div>
     </div>
    </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
