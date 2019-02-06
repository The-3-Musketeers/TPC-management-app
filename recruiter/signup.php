<?php
  // Database connection variables
  require_once('../connectVars.php');

  // Insert the page header and navbar
  $page_title = 'Recruiter Signup';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  //Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if(isset($_POST['submit'])){
      // Grab the sign in data from the post
      $company_id = mysqli_real_escape_string($dbc, trim($_POST['company-id']));
      $company_name = mysqli_real_escape_string($dbc, trim($_POST['company-name']));
      $company_category = mysqli_real_escape_string($dbc, trim($_POST['company-category']));
      $hr_name = mysqli_real_escape_string($dbc, trim($_POST['hr-name']));
      $hr_email = mysqli_real_escape_string($dbc, trim($_POST['hr-email']));
      $password = mysqli_real_escape_string($dbc, trim($_POST['pwd']));
      $verify_password = mysqli_real_escape_string($dbc, trim($_POST['confirm-pwd']));
      if(!empty($company_id) && !empty($company_name) && $company_category!="0" &&
        !empty($hr_name) && !empty($hr_email) &&
        !empty($password) && !empty($verify_password) &&
        ($verify_password == $password)){
          // Check if company_id is available
          $query = "SELECT * FROM recruiters WHERE company_id = '$company_id'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) == 0){
            // company_id is available
            if($company_category == "1"){
              $company_category = "A1";
            }elseif($company_category == "2"){
              $company_category = "B1";
            }elseif($company_category == "3"){
              $company_category = "B2";
            }
            $query = "INSERT INTO recruiters (company_id, company_name, company_category, hr_name, hr_email, password, join_date) VALUES ".
              "('$company_id', '$company_name', '$company_category', '$hr_name', '$hr_email', SHA('$password'), NOW())";
            mysqli_query($dbc, $query);

            //Confirm success with the recruiter
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'You have been registered successfully. You can now log in <a href="./login.php">here</a>.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
            mysqli_close($dbc);
            exit();
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
        <select class="custom-select" id="company-category" name="company-category">
          <option value="0" selected>Select Category</option>
          <option value="1">A1</option>
          <option value="2">B1</option>
          <option value="3">B2</option>
        </select>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">Name of the HR:</span>
        </div>
        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="hr-name" name="hr-name" value="<?php if(!empty($hr_name)) echo $hr_name; ?>" placeholder="Enter name">
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">HR Email:</span>
        </div>
        <input type="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="hr-email" name="hr-email" value="<?php if(!empty($hr_email)) echo $hr_email; ?>" placeholder="Enter email id">
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
        <input type="password" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="pwd" name="confirm-pwd" placeholder="Re-Enter password">
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
