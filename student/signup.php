<?php
    //start session
    require_once('../templates/startSession.php');

    // Database connection variables
    require_once('../connectVars.php');

    // Insert the page header and navbar
    $page_title = 'Student Signup';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');

    //Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if(isset($_POST['submit'])){
      // Grab the sign in data from the post
      $roll_number = mysqli_real_escape_string($dbc, trim($_POST['roll-number']));
      $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
      $webmail_ID = mysqli_real_escape_string($dbc, trim($_POST['email']));
      $password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));
      $verify_password = mysqli_real_escape_string($dbc, trim($_POST['confirm-pwd']));
      $year_of_roll = mysqli_real_escape_string($dbc, trim($_POST['year-of-roll']));
      $captcha = mysqli_real_escape_string($dbc, trim($_POST['captcha']));
      // verify Captcha
      if(SHA1($captcha) == $_SESSION['passphrase']){
        if(!empty($roll_number) && !empty($webmail_ID) && !empty($username) &&
          !empty($password) && !empty($verify_password) &&
          ($verify_password == $password)){
          // Check if webmail_ID is available
          $query = "SELECT * FROM students WHERE webmail_ID = '$webmail_ID'";
          $data = mysqli_query($dbc, $query);
          // Check if roll_number is available
          $query = "SELECT * FROM students WHERE roll_number = '$roll_number'";
          $dataR = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 0 && mysqli_num_rows($dataR) == 0){
            // Check if roll_number matches pattern ####XX##
            if(strlen($roll_number) == 8 &&
              is_numeric(substr($roll_number, 0, 4)) &&
              !is_numeric(substr($roll_number, 4, 2)) && 
              is_numeric(substr($roll_number, 6, 2))){

              // webmail_ID and roll_number is available
              $query = "INSERT INTO students (roll_number, username, user_role, webmail_id, password, join_date) VALUES ".
                "('$roll_number', '$username', 'student', '$webmail_ID', SHA('$password'), NOW())";
              mysqli_query($dbc, $query);

              // Finding enrollment year
              if(date('m') > 5){
                $year_of_roll = $year_of_roll-1;
              }

                //Insert into students_data
                $query = "INSERT INTO students_data (roll_number,year_of_enroll) VALUES ".
                "('$roll_number',YEAR(DATE_SUB(CURDATE(),INTERVAL $year_of_roll YEAR)))";
              $insert_in_student_data=mysqli_query($dbc, $query);
              if(!$insert_in_student_data){
                die("QUERY FAILED ".mysqli_error($dbc));
              }
              //Confirm success with the user
              echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'You have been registered successfully. You can now log in <a href="login.php">here</a>.' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                  '<span aria-hidden="true">&times;</span></button></div></div>';
              mysqli_close($dbc);
              exit();
            }else{
              echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Roll number format invalid. Please try again.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
            }
          }else{
            // Webmail ID or Roll number already exists
            if(mysqli_num_rows($data) != 0){
              echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This webmail ID is taken. If you are already registered you can <a href="login.php">Login here</a>' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
              $webmail_ID = "";
            }elseif(mysqli_num_rows($dataR) != 0){
              echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This roll number is taken. If you are already registered you can <a href="login.php">Login here</a>' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
              $roll_number = "";
            }
          }
        }else{
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
          'Please enter all fields and make sure to enter same password twice&#33;<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      } else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
        'Incorrect Captcha&#33; Please try again.<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
        '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }

    mysqli_close($dbc);
    ?>

    <div class="container" style="max-width: 60%; padding: 20px;">
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Roll Number:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="roll-number" name="roll-number" placeholder="Enter roll number" value="<?php if(!empty($roll_number)) echo $roll_number; ?>">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Full Name:</span>
        </div>
        <input type="username" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="username" name="username" value="<?php if(!empty($username)) echo $username; ?>" placeholder="Enter full name">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Webmail ID:</span>
        </div>
        <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="email" name="email" value="<?php if(!empty($webmail_ID)) echo $webmail_ID; ?>" placeholder="Enter webmail id">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Password:</span>
        </div>
        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="pwd" name="pwd" placeholder="Enter password">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Confirm Password:</span>
        </div>
        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="confirm-pwd" name="confirm-pwd" placeholder="Re-Enter password">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend" style="margin-right:25px">
          <span class="input-group-text" id="inputGroup-sizing-default">Current Year:</span>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="year-of-roll" value="3">
          <label class="form-check-label">3rd Year</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="year-of-roll" value="4">
          <label class="form-check-label">4th Year</label>
        </div>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Verify Captcha:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="captcha" name="captcha" placeholder="Enter captcha">
      </div>
      <div class="captcha-div">
        <img id="captcha-image" src="../util/captcha.php" alt="captcha verification">
        <label class="reload">&#x21BB;</label>
      </div>
      <div class="form-group row">
        <div class="col-sm-10">
          <button type="submit" class="btn btn-primary" name="submit">Sign Up</button>
        </div>
      </div>
    </form>
    </div>
<?php
  // Insert the footer
  require_once('../templates/footer.php');
  ?>
