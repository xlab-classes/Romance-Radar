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
      .img {
        width:100%;
        height: 32px;
        margin-top:2px
      }

    #profile_picture{
            height: 200px;
            width: 200px;
        }

    </style>

  </head>
  <body>
    <div class="container px-4">
    <form action="../PHP/connection_without_match.php" method="post" enctype="multipart/form-data">
  <div class="row gx-5">
    <div class="row m-4">
        <div class="col">
            <h2 class="text-center text-dark">
                What are you waiting for?
            </h2>
        </div>
    </div>
    <div class="col">
     <div class="p-3 bg">
         <img src="./assets/cupid.png" class="img-fluid" alt="...">
     </div>
    </div>
    <div class="col">
      <div class="p-3 bg">
          <p class="text-dark fst-italic fs-6">
            Let's face it, coming up with a date idea that's as fun and unique as your relationship can be just as hard as finding someone to date in the first place. Whether you're commuting to work or traveling 20 steps from your bed to your desk and back again, most of us just don't have a lot of creative juices left over when we're done for the day.
          </p>

          <p class="text-dark fst-italic fs-6">Leave the creativity to us!</p>

    <p class="text-dark fst-italic fs-6 mb-2">Match with your soulmate right now!</p>

    <form class="form-inline">
    <div class="row">
    <div class="form-group mx-sm-2 mb-2">
    <div class="input-group">
    <label for="sendRequest" class="sr-only"></label>
      <input type="sendRequest" class="form-control" id="sendRequest" placeholder="alison#4512">
      <span class="input-group-btn">
        <button class="btn btn-danger" type="button">
            <img src="./assets/love.png" class="img-fluid img" alt="Responsive image">
        </button>
      </span>
    </div>
  </div>
  </div>
  </form>
  </div>
  </div>
    <div class="col">
     <div class="p-3 bg">
         <div class="col-md-6 col-lg-3">
                    <div class="p-4 bg">
                    <div class="text-center">
                        <?php
                            echo '<img id="profile_picture" src="'.$_SESSION['user']['user_picture'].'" class="border-0 img-thumbnail rounded-circle">'
                        ?>
                        <input class="form-control form-control-sm m-2" type="file" name="profile_picture"/>
                        <p class="lead text-center text-black"><h1><?php echo $_SESSION['user']['name']; ?></h1></p>
                    </div>
                    </div>
                </div>
     </div>
    </div>
  </div>
</form>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
