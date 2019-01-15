<?php
    // Database connection variables
    require_once('connectVars.php');

    // Insert the page header and navbar
    $page_title = 'Signup';
    require_once('templates/header.php');
    require_once('templates/navbar.php');

    //Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if(isset($_POST['submit'])){
      // Grab the sign in data from the post
      $roll_number = mysqli_real_escape_string($dbc, trim($_POST['roll-number']));
      $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $webmail_ID = mysqli_real_escape_string($dbc, trim($_POST['email']));
      $password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));
      $verify_password = mysqli_real_escape_string($dbc, trim($_POST['confirm-pwd']));
      if(!empty($roll_number) && !empty($webmail_ID) && !empty($username) &&
        !empty($password) && !empty($verify_password) &&
        ($verify_password == $password)){
          // Check if webmail_ID is available
          $query = "SELECT * FROM students WHERE webmail_ID = '$webmail_ID'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 0){
            // webmail_ID is available
            $query = "INSERT INTO students (roll_number, username, webmail_id, password, join_date) VALUES ".
              "('$roll_number', '$username', '$webmail_ID', SHA('$password'), NOW())";
            mysqli_query($dbc, $query);

            //Confirm success with the user
            echo '<p>You have been registered successfully. You can now log in '.
              '<a href="studentLogin.php">here</a>.</p>';
            mysqli_close($dbc);
            exit();
          }
          else{
            // Webmail ID already exists
            echo '<p class="error">This webmail ID is taken. If you are '.
            'already registered you can <a href="studentLogin.php">Login here</a></p>';
            $webmail_ID = "";
          }
        }
        else{
          echo '<p class="error">Please enter all fields and make sure to enter same password twice</p>';
        }
    }

    mysqli_close($dbc);
    ?>

    <br>

    <script>

    </script>

    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label class="control-label col-sm-2" for="roll-number">Roll Number:</label>
            <div class="col-sm-6">
            <input type="text" class="form-control" id="roll-number" name="roll-number"
              value="<?php if(!empty($roll_number)) echo $roll_number; ?>" placeholder="Enter roll number">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="username">Full Name:</label>
            <div class="col-sm-6">
            <input type="username" class="form-control" id="username" name="username"
              value="<?php if(!empty($username)) echo $username; ?>" placeholder="Enter full name">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Webmail ID:</label>
            <div class="col-sm-6">
            <input type="email" class="form-control" id="email" name="email"
              value="<?php if(!empty($webmail_ID)) echo $webmail_ID; ?>" placeholder="Enter webmail id">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Password:</label>
            <div class="col-sm-6">
            <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="confirm-pwd">Comfirm Password:</label>
            <div class="col-sm-6">
            <input type="password" class="form-control" id="pwd" name="confirm-pwd" placeholder="Re-Enter password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default" name="submit">Sign Up</button>
            </div>
        </div>
    </form>
<?php
  // Insert the footer
  require_once('templates/footer.php');
  ?>
