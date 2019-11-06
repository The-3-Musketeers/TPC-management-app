<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');
  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);
  // Connect to Database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
  }
  // Fetch Student Details
  $roll_number = $_GET['roll'];
  $page_title = 'Student Details';

  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $query1 = "SELECT * FROM students WHERE roll_number='". $roll_number ."'";
  $data1 = mysqli_query($dbc, $query1);
  $query2 = "SELECT * FROM students_data WHERE roll_number='". $roll_number ."'";
  $data2 = mysqli_query($dbc, $query2);
  $job_offers = "";

  if(!$data1 || !$data2){
    die("QUERY FAILED ".mysqli_error($dbc));
  }

  if(isset($_POST['submit'])){
    $job_offers = mysqli_real_escape_string($dbc,trim($_POST['job_offers']));
    $query = "UPDATE students_data SET job_offers='$job_offers' WHERE roll_number='$roll_number'";
    $update_query = mysqli_query($dbc, $query);
    // Query db again for receiving job offer
    $data2 = mysqli_query($dbc, $query2);
    if(!$update_query){
      die("QUERY FAILED ".mysqli_error($dbc));
    }
    // Alert Success : Job offers updated
    echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
            'Job offer(s) updated' .
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
  }

  if(isset($_POST['reset'])){
    $new_password = mysqli_real_escape_string($dbc,trim($_POST['new_password']));
    $verify_password = mysqli_real_escape_string($dbc,trim($_POST['verify_password']));
    if($new_password != $verify_password){
      // Alert Warning : Password does not match
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
            'Password does not match' .
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
    }else{
    $query = "UPDATE students SET password=SHA('$new_password') WHERE roll_number='$roll_number'";
    $update_query = mysqli_query($dbc, $query);
    if(!$update_query){
      die("QUERY FAILED ".mysqli_error($dbc));
    }
    // Alert Success : Password updated
    echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
            'Password updated' .
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(mysqli_num_rows($data1) == "1" && mysqli_num_rows($data2) == "1"){
    $row1 = mysqli_fetch_assoc($data1);
    $row2 = mysqli_fetch_assoc($data2);

    $student_name = $row1["username"];
    $webmail_id = $row1["webmail_id"];
    $current_cpi = $row2["current_cpi"];
    $department = $row2["department"];
    $course = $row2["course"];
    $resume_url = $row2["resume_url"];
    $mobile_number = $row2["mobile_number"];
    $job_offers = $row2["job_offers"];
    ?>
    <div class="container" style="max-width: 80%; padding: 20px;">
      <div class="card">
        <div class="card-header">
          <div style="display:inline-block">
            <h4 class="card-title" >Student's Details</h4>
          </div>
        </div>
        <div class="card-body">
          <div style="display:flex;"><h5>Name:</h5> <div style="padding-left: 5px;"><?php echo $student_name; ?></div></div>
          <div style="display:flex;"><h5>Roll Number:</h5> <div style="padding-left: 5px;"><?php echo $roll_number; ?></div></div>
          <div style="display:flex;"><h5>Webmail ID:</h5> <div style="padding-left: 5px;"><?php echo $webmail_id; ?></div></div>
          <div style="display:flex;"><h5>CPI:</h5> <div style="padding-left: 5px;"><?php echo $current_cpi; ?></div></div>
          <div style="display:flex;"><h5>Course:</h5> <div style="padding-left: 5px;"><?php echo $course; ?></div></div>
          <div style="display:flex;"><h5>Department:</h5> <div style="padding-left: 5px;"><?php echo $department; ?></div></div>
          <div style="display:flex;">
            <h5>Resume:</h5> <a href="<?php echo $resume_url;?>" target="_blank">
            <div style="padding-left: 5px;"><?php echo $resume_url; ?></a></div>
          </div>
          <div style="display:flex;"><h5>Mobile Number:</h5> <div style="padding-left: 5px;"><?php echo $mobile_number; ?></div></div>
          <div style="display:flex;">
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?roll=" . $roll_number; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group" style="margin-bottom:0">
                <label for="roll-number" style="margin-bottom:0"><h5>Job Offer(s):</h5></label>
                <div style="display:flex">
                <input type="text" class="form-control" id="" name="job_offers" value="<?php echo $job_offers; ?>" required>
                <button type="submit" name="submit" class="btn btn-primary" style="margin-left:10px">Submit</button>
              </div>
              </div>
              <small>Please enter company category of the job offer received by student. If multiple offers are received, write the categories separated by comma. Ex: <code>A1,B2</code> or <code>B2</code> or <code>B1,B2</code> etc.</small>
            </form>
          </div>
          <div style="display:flex;">
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?roll=" . $roll_number; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group" style="margin-bottom:0">
                <label for="password" style="margin-bottom:0"><h5>Reset Password</h5></label>
                <input type="password" class="form-control" id="" name="new_password" placeholder="New Password" style="margin-bottom:10px" required>
                <input type="password" class="form-control" id="" name="verify_password" placeholder="Verify Password" style="margin-bottom:10px" required>
                <button type="submit" name="reset" class="btn btn-primary">Reset</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  <?php  }

  $query3 = "SELECT * FROM applications WHERE student_roll_number='". $roll_number ."'";
  $data3 = mysqli_query($dbc, $query3);

  if(!$data3){
    die("QUERY FAILED ".mysqli_error($dbc));
  }

  if(mysqli_num_rows($data3) != 0){
  ?>

    <div class="container" style="max-width: 80%; padding: 20px;">
      <div class="card">
        <div class="card-header">
          <div style="display:inline-block">
            <h4 class="card-title" >Student's Applications</h4>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th scope="col">S.No.</th>
                <th scope="col">Job Name</th>
                <th scope="col">Company</th>
                <th scope="col">Applied on</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $curr = 1;
              while($row_app = mysqli_fetch_array($data3)){
                $query_position = "SELECT * FROM positions WHERE job_id='". $row_app["job_id"] ."'";
                $data_position = mysqli_query($dbc, $query_position);
                $row_position = mysqli_fetch_assoc($data_position);
                $query_recruiter = "SELECT * FROM recruiters_data WHERE company_id='". $row_position["company_id"] ."'";
                $data_recruiter = mysqli_query($dbc, $query_recruiter);
                $row_recruiter = mysqli_fetch_assoc($data_recruiter);
              ?>
              <tr>
                <td><?php echo $curr; ?></td>
                <td><a href="../job.php?id=<?php echo $row_app["job_id"]; ?>"><?php echo $row_position["job_position"]; ?></a></td>
                <td><a href="./company.php?id=<?php echo $row_position["company_id"] ?>"><?php echo $row_recruiter["company_name"]; ?></a></td>
                <td><?php echo $row_app["applied_on"]; ?></td>
                <td><?php
                  if($row_app["application_status"] == "accepted"){
                    echo '<span class="badge badge-success">Accepted</span>';
                  }elseif($row_app["application_status"] == "pending") {
                    echo '<span class="badge badge-warning">Pending</span>';
                  }elseif($row_app["application_status"] == "rejected") {
                    echo '<span class="badge badge-danger">Rejected</span>';
                  }
                ?></td>
              </tr>
              <?php
                $curr = $curr + 1;
              } ?>
            </tbody>
        </div>
      </div>
    </div>

  <?php } else { ?>

  <?php } ?>

<?php require_once('../templates/footer.php');?>
