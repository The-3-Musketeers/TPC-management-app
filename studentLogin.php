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

    if(empty($_SESSION['user_id'])){
      echo '<p class="error">' . $error_msg . '</p>';
    ?>
    <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label class="control-label col-sm-2" for="roll-number">Roll Number:</label>
            <div class="col-sm-6">
            <input type="text" class="form-control" id="roll-number" name="roll-number" placeholder="Enter roll number">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Password:</label>
            <div class="col-sm-6">
            <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
            </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default" name="submit">Log In</button>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <div>
                <label>New user? Signup <a href="./studentSignup.php">here</a></label>
            </div>
            </div>
        </div>
    </form>
    <?php
      }
      else{
        echo('<p class="login">You are logged in as '. $_SESSION['username'] .'.</p>');
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/studentDashboard.php';
        header('Location: ' . $home_url);
      }
    ?>

<?php require_once('templates/footer.php');?>
