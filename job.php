<?php
    // Start the session
    require_once('templates/startSession.php');
    require_once('connectVars.php');

    // Authenticate user
    require_once('templates/auth.php');
    checkUserRole($_SESSION['user_role'], $auth_error);

    // Connect to Database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Fetch job Details
    $job_id = $_GET['id'];

    $query="SELECT * FROM positions WHERE job_id='". $job_id ."'";
    $get_all_positions_query=mysqli_query($dbc,$query);
    $num=mysqli_num_rows($get_all_positions_query);
    if($num==1){
      $row=mysqli_fetch_assoc($get_all_positions_query);
      $company_id=$row['company_id'];
      $job_position=$row['job_position'];
      $course=$row['course'];
      $branch=$row['branch'];
      $min_cpi=$row['min_cpi'];
      $no_of_opening=$row['no_of_opening'];
      $apply_by=$row['apply_by'];
      if($apply_by==null){
          $apply_by='N.A.';
      } else {
          $apply_by=date('d-m-y',strtotime($apply_by));
      }
      $stipend=$row['stipend'];
      if($stipend==null){
          $stipend='N.A.';
      }
      $ctc=$row['ctc'];
      if($ctc==null){
          $ctc='N.A.';
      }
      if($no_of_opening==null){
          $no_of_opening='N.A.';
      }
      $test_date=$row['test_date'];
      if($test_date==null){
          $test_date='N.A.';
      } else {
          $test_date=date('d-m-y',strtotime($test_date));
      }
      $job_desc=$row['job_desc'];
      $created_on=$row['created_on'];
      $created_on=date('d-m-y',strtotime($created_on));
      $company_query="SELECT company_name, company_url, company_desc FROM recruiters WHERE company_id='" . $company_id . "'";
      $get_company_name_query=mysqli_query($dbc,$company_query);
      $num1=mysqli_num_rows($get_company_name_query);
      if($num1==1){
        $row1=mysqli_fetch_assoc($get_company_name_query);
        $company_name=$row1['company_name'];
        $company_url=$row1['company_url'];
        $company_desc=$row1['company_desc'];
        $page_title = $job_position;
        $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/job.php';
        require_once('templates/header.php');
        require_once('templates/navbar.php');
        if(isset($_GET['apply']) && $_GET['apply']=='true'){
          $apply_error='';
          $apply_query="SELECT application_id FROM applications WHERE job_id='" . $job_id . "' AND student_roll_number='". $_SESSION['roll_number'] ."'";
          $query3=mysqli_query($dbc,$apply_query);
          $num3=mysqli_num_rows($query3);
          if($num3==0){
            $apply_query="INSERT INTO applications (job_id, student_roll_number, applied_on) VALUES ".
              "('" . $job_id . "', '" . $_SESSION['roll_number'] . "', NOW())";
            $query3=mysqli_query($dbc,$apply_query);
            if(!$query3){
                die("QUERY FAILED ".mysqli_error($dbc));
                $apply_error='Unable to apply for this job. Try again!';
            }
            // Alert Success : Application successful
            echo '<div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">' .
                        'You have successfully applied for this job!' .
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                          '<span aria-hidden="true">&times;</span>' .
                        '</button>' .
                    '</div>
                  </div>';
          } else{
            $apply_error='You have already applied for this job!';
          }
          if($apply_error){
            // Alert error
            echo '<div class="container">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                      $apply_error .
                      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                        '<span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </div>';
          }
        }
    ?>

        <div class="container" style="max-width: 60%; padding: 20px;">
          <div class="card">
            <div class="card-header">
              <div style="display:inline-block">
                <h5 class="card-title" ><?php echo $job_position; ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo $company_name;?></h6>
              </div>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-borderless">
              <thead>
                  <tr>
                  <th scope="col" class="text-muted">Course</th>
                  <th scope="col" class="text-muted">Branch</th>
                  <th scope="col" class="text-muted">Min CPI</th>
                  <th scope="col" class="text-muted">Stipend</th>
                  <th scope="col" class="text-muted">CTC</th>
                  <th scope="col" class="text-muted">Test Date</th>
                  <th scope="col" class="text-muted">Apply By</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                  <td><?php echo $course; ?></td>
                  <td><?php echo $branch; ?></td>
                  <td><?php echo $min_cpi; ?></td>
                  <td><?php echo $stipend; ?></td>
                  <td><?php echo $ctc; ?></td>
                  <td><?php echo $test_date; ?></td>
                  <td><?php echo $apply_by; ?></td>
                  </tr>
              </tbody>
              </table>
              <div class="dropdown-divider"></div>
              <h5>
                About <?php echo $company_name;?> <?php  if($company_url) echo "(<a href='$company_url'>$company_url</a>)";?>
              </h5>
              <p><?php echo $company_desc;?></p>
              <br />
              <h5>Job Description</h5>
              <p><?php echo $job_desc;?></p>
              <br />
              <h5>Number of Openings: <?php echo $no_of_opening; ?></h5>
            </div>
            <div class="card-footer text-muted">
              <div style="float:left;margin-top:5px;">
                Posted on <?php echo $created_on;?>
              </div>
              <a href="<?php echo $job_url . '?id=' . $job_id . '&apply=true'; ?>">
                <button class="btn btn-primary" style="float:right;">
                  Apply
                </button>
              </a>
            </div>
          </div>
        </div>
<?php
      }
    } else {
?>
  <div> Job not found! </div>
<?php } ?>
<?php require_once('templates/footer.php');?>
