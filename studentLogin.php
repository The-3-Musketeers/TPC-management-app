<?php
    // Start the session
    require_once('templates/startSession.php');
    require_once('connectVars.php');

    $error_msg = "";

    if(!isset($_SESSION['user_id'])){
      if(isset($_POST['submit'])){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $user_roll_number = mysqli_real_escape_string($dbc, trim($_POST['roll-number']));
        $user_password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));

        if(!empty($user_roll_number) && !empty($user_password)){
          $query = "SELECT user_id, username FROM students WHERE roll_number='$user_roll_number' AND password=SHA('$user_password')";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 1){
            $row = mysqli_fetch_array($data);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            setcookie('user_id', $row['user_id'], time() + (60*60*24*30));
            setcookie('username', $row['username'], time() + (60*60*24*30));
            $home_url = 'http://'. $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']).'/index.php';
            header('Location: ', $home_url);
          }
          else{
            $error_msg = "Roll number or password is incorrect!";
          }
        }
        else{
          $error_msg = "Enter both roll number and password!";
        }
      }
    }

    // Insert the page header and navbar
    $page_title = 'Login';
    require_once('templates/header.php');
    require_once('templates/navbar.php');
    if($error_msg != ""){
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
      $error_msg . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
      '<span aria-hidden="true">&times;</span></button></div></div>';
    }
    if(empty($_SESSION['user_id'])){
    ?>

    <div class="container" style="max-width: 60%; padding: 20px;">
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div class="input-group mb-3">
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
        <div class="form-group row">
          <div class="col-sm-10">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="gridCheck1">
              <label class="form-check-label" for="gridCheck1">
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
        <div class="form-group row">
            <div class="col-sm-10">
            <div>
                <label>New user? Signup <a href="./studentSignup.php">here</a></label>
            </div>
            </div>
        </div>
    </form>
    </div>
    <?php
      }
      else{
        echo('<p class="login">You are logged in as '. $_SESSION['username'] .'.</p>');
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/studentDashboard.php';
        header('Location: ' . $home_url);
      }
    ?>

<?php require_once('templates/footer.php');?>
