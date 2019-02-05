<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  $error_msg = "";

  // Insert the page header and navbar
  $page_title = 'Recruiter Login';
  require_once('./templates/header.php');
  require_once('./templates/navbar.php');

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
                <label>New user? Signup <a href="./recruiterSignup.php">here</a></label>
            </div>
            </div>
        </div>
    </form>
    </div>
    <?php
      } else {
        echo('<p class="login">You are logged in as '. $_SESSION['username'] .'.</p>');
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/studentDashboard.php';
        header('Location: ' . $home_url);
      }

  // Insert the footer
  require_once('templates/footer.php');
?>
