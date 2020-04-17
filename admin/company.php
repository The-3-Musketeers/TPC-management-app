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

  // Fetch id
  $company_id = $_GET['id'];

  $page_title = 'Company Details';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $query = "SELECT * FROM recruiters_data WHERE company_id='". $company_id ."'";
  $data = mysqli_query($dbc, $query);

  if(!$data){
    die("QUERY FAILED ".mysqli_error($dbc));
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
    $query = "UPDATE recruiters SET password=SHA('$new_password') WHERE company_id='". $company_id ."'";
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
  
  if(mysqli_num_rows($data) == "1"){
    $row = mysqli_fetch_assoc($data);
    $company_name = $row["company_name"];
    $company_category_id = $row["company_category_id"];
    // fetch company category name from id
    $query_cat = "SELECT name FROM company_category WHERE id='$company_category_id'";
    $data_cat = mysqli_query($dbc, $query_cat);
    $row_cat = mysqli_fetch_assoc($data_cat);
    $company_category = $row_cat['name'];
    $hr_name_1 = $row["hr_name_1"];
    $hr_designation_1 = $row["hr_designation_1"];
    $hr_email_1 = $row["hr_email_1"];
    $hr_name_2 = $row["hr_name_2"];
    $hr_designation_2 = $row["hr_designation_2"];
    $hr_email_2 = $row["hr_email_2"];
    $hr_name_3 = $row["hr_name_3"];
    $hr_designation_3 = $row["hr_designation_3"];
    $hr_email_3 = $row["hr_email_3"];
    $company_desc = $row["company_desc"];
    $company_status = $row["company_status"];
    $company_url = $row["company_url"];
    ?>
    <div class="container" style="max-width: 80%; padding: 20px;">
      <div class="card">
        <div class="card-header">
          <div style="display:inline-block">
            <h4 class="card-title" ><?php echo $company_name; ?></h4>
            <h6 class="card-subtitle mb-2 text-muted">
              <?php  if($company_url) echo "(<a href='$company_url'>$company_url</a>)";?>
            </h6>
          </div>
        </div>
        <div class="card-body">
          <div style="display:flex;"><h5>ID:</h5> <div style="padding-left: 5px;"><?php echo $company_id; ?></div></div>
          <div style="display:flex;"><h5>Category:</h5> <div style="padding-left: 5px;"><?php echo $company_category; ?></div></div><br>
          <h5>1st HR Details:</h5>
          <div style="display:flex;"><b>Name:</b> <div style="padding-left: 5px;"><?php echo $hr_name_1; ?></div></div>
          <div style="display:flex;"><b>Designation:</b> <div style="padding-left: 5px;"><?php echo $hr_designation_1; ?></div></div>
          <div style="display:flex;"><b>Email:</b> <div style="padding-left: 5px;"><?php echo $hr_email_1; ?></div></div><br>
          <h5>2nd HR Details:</h5>
          <div style="display:flex;"><b>Name:</b> <div style="padding-left: 5px;"><?php echo $hr_name_2; ?></div></div>
          <div style="display:flex;"><b>Designation:</b> <div style="padding-left: 5px;"><?php echo $hr_designation_2; ?></div></div>
          <div style="display:flex;"><b>Email:</b> <div style="padding-left: 5px;"><?php echo $hr_email_2; ?></div></div><br>
          <?php if($hr_name_3 != "" && $hr_designation_3 != "" && $hr_email_3 != ""){ ?>
            <h5>3rd HR Details:</h5>
          <div style="display:flex;"><b>Name:</b> <div style="padding-left: 5px;"><?php echo $hr_name_3; ?></div></div>
          <div style="display:flex;"><b>Designation:</b> <div style="padding-left: 5px;"><?php echo $hr_designation_3; ?></div></div>
          <div style="display:flex;"><b>Email:</b> <div style="padding-left: 5px;"><?php echo $hr_email_3; ?></div></div><br>
          <?php } ?>
          <h5>About:</h5>
          <p><?php echo $company_desc;?></p>
          <br/>
          <div style="display:flex;">
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $company_id; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group" style="margin-bottom:0">
                <label for="password" style="margin-bottom:0"><h5>Reset Password</h5></label>
                <input type="password" class="form-control" id="" name="new_password" placeholder="New Password" style="margin-bottom:10px" required>
                <input type="password" class="form-control" id="" name="verify_password" placeholder="Verify Password" style="margin-bottom:10px" required>
                <button type="submit" name="reset" class="btn btn-primary">Reset</button>
              </div>
            </form>
          </div>
        </div>
        <div class="card-footer text-muted">
          <div style="float:left;margin-bottom:4px;">
            <?php if ($company_status == "accepted"){ ?>
            <span class="badge badge-success">Accepted</span>
            <?php } elseif ($company_status == "pending") { ?>
            <span class="badge badge-warning">Pending</span>
            <?php } elseif ($company_status == "rejected") { ?>
            <span class="badge badge-danger">Rejected</span>
            <?php } ?>
          </div>
        </div>
      </div>
      <br/>
      <?php
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT job_id, job_position, job_status, min_cpi  FROM jobs WHERE company_id=$company_id";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) != 0){
      ?>
      <div class="card">
        <div class="card-header">
          <div style="display:inline-block">
            <h4 class="card-title" >Jobs Posted</h4>
          </div>
        </div>
        <div class="card-body table-responsive">
        <table class="table">
          <thead class="thead-light">
            <tr>
              <th scope="col">S.No.</th>
              <th scope="col">Job Name</th>
              <th scope="col">Degree</th>
              <th scope="col">Branch</th>
              <th scope="col">Min. CPI</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['job_id'];
              $job_name = $row['job_position'];
              $job_status = $row['job_status'];

              // Get all eligible degrees
              $degree_query = "SELECT DISTINCT degree.degree_name AS degree_name FROM degree, degree_branch, jobs_db "
                              ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                              ."AND degree_branch.degree_id = degree.degree_id";
              $get_all_degree=mysqli_query($dbc,$degree_query);
              $degree_row=mysqli_fetch_assoc($get_all_degree);
              $degree = $degree_row['degree_name'];
              while($degree_row=mysqli_fetch_assoc($get_all_degree)){
                $degree = $degree . ', ' . $degree_row['degree_name'];
              }

              // Get all eligible branches
              $branch_query = "SELECT DISTINCT branch.branch_name AS branch_name FROM branch, degree_branch, jobs_db "
                              ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                              ."AND branch.branch_id = degree_branch.branch_id";
              $get_all_branch=mysqli_query($dbc,$branch_query);
              $branch_row=mysqli_fetch_assoc($get_all_branch);
              $branch = $branch_row['branch_name'];
              while($branch_row=mysqli_fetch_assoc($get_all_branch)){
                $branch = $branch . ', ' . $branch_row['branch_name'];
              }

              $min_cpi = $row['min_cpi'];
              $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';
            ?>
                <tr>
                  <th scope="row"><?php  echo $curr; ?></th>
                  <td><a href="<?php echo $job_url . '?id=' . $row["job_id"];?>"><?php echo $row["job_position"];?></a></td>
                  <td><?php echo $degree;?></td>
                  <td><?php echo $branch;?></td>
                  <td><?php echo $row["min_cpi"];?></td>
                  <td>
                    <?php if ($job_status == "shown"){ ?>
                    <span class="badge badge-success">Shown</span>
                    <?php } elseif ($job_status == "pending") { ?>
                    <span class="badge badge-warning">Pending</span>
                    <?php } elseif ($job_status == "hidden") { ?>
                    <span class="badge badge-danger">Hidden</span>
                    <?php } ?>
                  </td>
                </tr>
                <?php $curr = $curr + 1;
            }
          ?>
          </tbody>
        </table>
      </div>
      <?php } else { ?>
        <br/>
        <h5>This company has not posted any jobs yet!</h5>
      <?php } ?>
    </div>

<?php  } ?>

<?php require_once('../templates/footer.php');?>
