<?php
    // Start the session
    require_once('../templates/startSession.php');
    require_once('../connectVars.php');

    $error_msg = "";

    if(!isset($_SESSION['access_token'])){
      if(isset($_POST['submit'])  && $_POST['login-type'] == '0'){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $user_roll_number = mysqli_real_escape_string($dbc, trim($_POST['roll-number']));
        $user_password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));

        if(!empty($user_roll_number) && !empty($user_password)){
          $query = "SELECT username FROM students WHERE roll_number='$user_roll_number' AND password=SHA('$user_password')";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 1){
            $row = mysqli_fetch_array($data);
            $token = bin2hex(random_bytes(32));
            $_SESSION['access_token'] = $token;
            $_SESSION['username'] = $row['username'];
            $_SESSION['roll_number'] = $user_roll_number;
            $update_token_query="UPDATE students SET access_token='$token' WHERE roll_number='$user_roll_number'";
            $update_token=mysqli_query($dbc, $update_token_query);
            if(!$update_token){
                die("QUERY FAILED ".mysqli_error($dbc));
            }
            if(!empty($_POST['remember']) && $_POST['remember']=='on'){
              setcookie('access_token', $token, time() + (60*60*24*30));
              setcookie('username', $row['username'], time() + (60*60*24*30));
              setcookie('roll_number', $user_roll_number, time() + (60*60*24*30));
            }
          }
          else{
            $error_msg = "Roll number or password is incorrect!";
          }
        }
        else{
          $error_msg = "Enter both roll number and password!";
        }
        mysqli_close($dbc);
      }
      if(isset($_POST['submit'])  && $_POST['login-type'] == '1'){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $user_password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));
        if(!empty($user_password)){
          $query = "SELECT username FROM students WHERE user_role='admin' AND password='$user_password'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 1){
            $row = mysqli_fetch_array($data);
            $token = bin2hex(random_bytes(32));
            $_SESSION['access_token'] = $token;
            $_SESSION['username'] = $row['username'];
            $update_token_query="UPDATE students SET access_token='$token' WHERE user_role='admin' AND password='$user_password'";
            $update_token=mysqli_query($dbc, $update_token_query);
            if(!$update_token){
                die("QUERY FAILED ".mysqli_error($dbc));
            }
          }
          else{
            $error_msg = "Password is incorrect!";
          }
        } else{
          $error_msg = "Enter the password!";
        }
        mysqli_close($dbc);
      }
    }

    // Insert the page header and navbar
    $page_title = 'Student Login';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');
    if($error_msg != ""){
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
      $error_msg . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
      '<span aria-hidden="true">&times;</span></button></div></div>';
    }
    if(empty($_SESSION['access_token'])){
    ?>

    <div class="container" style="max-width: 60%; padding: 20px;">
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="company-category">Login Type</label>
        </div>
        <select class="custom-select" id="login-type" name="login-type">
          <option value="0" selected>Student</option>
          <option value="1">Admin</option>
        </select>
      </div>
      <div class="input-group mb-3" id="roll-number-div">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Roll Number:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="roll-number" name="roll-number" placeholder="Enter roll number">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Password:</span>
        </div>
        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="pwd" name="pwd" placeholder="Enter password">
      </div>
        <div class="form-group row" id="remember-div">
          <div class="col-sm-10">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="gridCheck1">
              <label class="form-check-label" for="gridCheck1" name="remember">
                Remember me
              </label>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-primary" name="submit">Log in</button>
          </div>
        </div>
        <div class="form-group row" id="signup-div">
            <div class="col-sm-10">
            <div>
                <label>New user? Signup <a href="./signup.php">here</a></label>
            </div>
            </div>
        </div>
    </form>
    </div>
    <?php
      }else{
        echo('<p class="login">You are logged in as '. $_SESSION['username'] .'.</p>');
        if($_POST['login-type'] == '0'){
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/dashboard.php';
        }else{
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../admin/dashboard.php';
        }
        header('Location: ' . $home_url);
      }
    ?>

<?php require_once('../templates/footer.php');?>
