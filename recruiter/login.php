<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  $error_msg = "";

  if(!isset($_SESSION['access_token'])){
    if(isset($_POST['submit'])){
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $company_id = mysqli_real_escape_string($dbc, trim($_POST['company-id']));
      $company_password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));

      if(!empty($company_id) && !empty($company_password)){
        $query = "SELECT company_name FROM recruiters WHERE company_id='$company_id' AND password=SHA('$company_password')";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) == 1){
          $row = mysqli_fetch_array($data);
          $token = bin2hex(random_bytes(32));
          $_SESSION['access_token'] = $token;
          $_SESSION['user_role'] = 'recruiter';
          $_SESSION['company_name'] = $row['company_name'];
          $_SESSION['company_id'] = $company_id;
          $update_token_query="UPDATE recruiters SET access_token='$token' WHERE company_id='$company_id'";
          $update_token=mysqli_query($dbc, $update_token_query);
          if(!$update_token){
            die("QUERY FAILED ".mysqli_error($dbc));
          }
          if(!empty($_POST['remember']) && $_POST['remember']=='on'){
            setcookie('access_token', $token, time() + (60*60*24*30));
            setcookie('user_role', $_SESSION['user_role'], time() + (60*60*24*30));
            setcookie('company_name', $row['company_name'], time() + (60*60*24*30));
            setcookie('company_id', $company_id, time() + (60*60*24*30));
          }
        }else{
          $error_msg = "Company ID or password is incorrect!";
        }
      }else{
        $error_msg = "Enter both Company ID and Password!";
      }
    }
  }

  // Insert the page header and navbar
  $page_title = 'Recruiter Login';
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
      <span class="input-group-text" id="inputGroup-sizing-default">Company ID:</span>
    </div>
  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="company-id" name="company-id" placeholder="Enter Company ID">
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
        <div class="form-group row">
            <div class="col-sm-10">
            <div>
                <label>First time recruiting from IIT Patna? Signup <a href="./signup.php">here</a></label>
            </div>
            </div>
        </div>
    </form>
    </div>
    <?php
      } else {
        echo('<p class="login">You are logged in as '. $_SESSION['company_name'] .'.</p>');
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/dashboard.php';
        header('Location: ' . $home_url);
      }

  // Insert the footer
  require_once('../templates/footer.php');
?>
