<?php
  //start session
  require_once('../templates/startSession.php');

  // Database connection variables
  require_once('../connectVars.php');

  // TEMPORARY CHANGE TO ADD RECRUITER FROM ADMIN PANEL
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  // Insert the page header and navbar
  $page_title = 'Add Company';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  //Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if(isset($_POST['submit'])){
      // Grab the sign in data from the post
      $company_id = mysqli_real_escape_string($dbc, trim($_POST['company-id']));
      $company_name = mysqli_real_escape_string($dbc, trim($_POST['company-name']));
      $hr_name_1 = mysqli_real_escape_string($dbc, trim($_POST['hr-name-1']));
      $hr_designation_1 = mysqli_real_escape_string($dbc, trim($_POST['hr-designation-1']));
      $hr_email_1 = mysqli_real_escape_string($dbc, trim($_POST['hr-email-1']));
      $password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));
      $verify_password = mysqli_real_escape_string($dbc, trim($_POST['confirm-pwd']));
      $captcha = mysqli_real_escape_string($dbc, trim($_POST['captcha']));
      // verify Captcha
      if(SHA1($captcha) == $_SESSION['passphrase']){
        if(!empty($company_id) && !empty($company_name) &&
          !empty($hr_name_1) && !empty($hr_designation_1) && !empty($hr_email_1) &&
          !empty($password) && !empty($verify_password) &&
          ($verify_password == $password)){
          // Check if company_id is available
          $query = "SELECT * FROM recruiters WHERE company_id = '$company_id'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 0){
            // company_id is available
            // inserting to recruiters table
            $query = "INSERT INTO recruiters (company_id, join_date, password) VALUES ".
              "('$company_id', NOW(), SHA('$password'))";
            mysqli_query($dbc, $query);
            // inserting to recruiters_data table
            $query = "INSERT INTO recruiters_data (company_id, company_name, company_status, hr_name_1, hr_designation_1, hr_email_1) VALUES ".
              "('$company_id', '$company_name', 'pending', '$hr_name_1', '$hr_designation_1', '$hr_email_1')";
            mysqli_query($dbc, $query);

            //Confirm success with the recruiter
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Registered successfully.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
            $company_id = "";
            $company_name = "";
            $hr_name_1 = "";
            $hr_designation_1 = "";
            $hr_email_1 = "";
            /*echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'You have been registered successfully. You can now log in <a href="./login.php">here</a>.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
            mysqli_close($dbc);*/
            // exit();
          }else{
            // company_id already exists
            if(mysqli_num_rows($data) != 0){
              echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This webmail ID is taken. If you are already registered you can <a href="login.php">Login here</a>' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
              $company_id = "";
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
          <span class="input-group-text" id="inputGroup-sizing-default">Company ID:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="company-id" name="company-id" placeholder="Enter Company ID" value="<?php if(!empty($company_id)) echo $company_id; ?>">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Name of the company:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="company-name" name="company-name" value="<?php if(!empty($company_name)) echo $company_name; ?>" placeholder="Enter name of the company">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Name of HR:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="hr-name-1" name="hr-name-1" value="<?php if(!empty($hr_name_1)) echo $hr_name_1; ?>" placeholder="Enter name">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Designation:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="hr-designation-1" name="hr-designation-1" value="<?php if(!empty($hr_designation_1)) echo $hr_designation_1; ?>" placeholder="Enter designation">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">HR Email:</span>
        </div>
        <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="hr-email-1" name="hr-email-1" value="<?php if(!empty($hr_email_1)) echo $hr_email_1; ?>" placeholder="Enter email id">
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
          <button type="submit" class="btn btn-primary" name="submit">Add</button>
        </div>
      </div>
    </form>
    </div>
<?php
  // Insert the footer
  require_once('../templates/footer.php');
?>
