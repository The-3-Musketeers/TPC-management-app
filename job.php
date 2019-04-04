<?php
    // Start the session
    require_once('templates/startSession.php');
    require_once('connectVars.php');

    // Authenticate user
    require_once('templates/auth.php');
    checkUserRole($_SESSION['user_role'], $auth_error);

    // Connect to Database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Fetch job ID
    $job_id = $_GET['id'];

    // Fetch student details to check eligibility
    $student_cpi = ""; $student_course = ""; $student_department = "";
    $cpi_above = FALSE; $course_match = FALSE; $dept_match = FALSE;
    $button_message = "";
    $is_stud_eligible = TRUE;
    if($_SESSION['user_role'] == 'student'){
      // Fetch general details
      $student_query = "SELECT current_cpi, department, course, job_offers FROM students_data WHERE roll_number='". $_SESSION['roll_number'] ."'";
      $data = mysqli_query($dbc, $student_query);
      if(mysqli_num_rows($data) == 1){
        $row = mysqli_fetch_array($data);
        $student_cpi = $row['current_cpi'];
        $student_course = $row['course'];
        $student_department = $row['department'];
      }
    }

    // Fetch job Details
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

      // ************ ELIGIBITLY check starts here ************

      // Check general eligibility (Branch, Course and CPI based)
      if($_SESSION['user_role'] == 'student' && $is_stud_eligible){
        $course_arr = explode(",", $course);
        $branch_arr = explode(",", $branch);
        $course_match = FALSE;
        foreach ($course_arr as $indiv_course) {
          if(strtolower($indiv_course) == strtolower($student_course)){
            $course_match = TRUE;
            break;
          }
        }
        if($course_match){
          $dept_match = FALSE;
          foreach ($branch_arr as $indiv_branch) {
            if(strtolower($indiv_branch) == strtolower($student_department)){
              $dept_match = TRUE;
              break;
            }
          }
          if($dept_match){
            $cpi_above = FALSE;
            if(floatval($student_cpi) >= floatval($min_cpi)){
              $cpi_above = TRUE;
            }
            if(!$cpi_above){
              $button_message = "Your CPI doesn't meet eligibility criteria. Please contact TPC if you think this is an error.";
            }
          } else {
            $button_message = "This job is not available for $student_department. Please contact TPC if you think this is an error.";
          }
        } else {
          $button_message = "This job is not available for $student_course. Please contact TPC if you think this is an error.";
        }
        
        // Fetch student's current job offers' details
        $student_query = "SELECT job_offers FROM students_data WHERE roll_number='". $_SESSION['roll_number'] ."'";
        $data = mysqli_query($dbc, $student_query);
        $row = mysqli_fetch_array($data);
        $stud_job_offers = $row['job_offers'];
        $stud_job_offers_arr = explode(",", $stud_job_offers);
        foreach($stud_job_offers_arr as $job_offer_id){
          if(!$is_stud_eligible){
            break;
          }
          // Fetch company id using job id
          $job_query = "SELECT company_id FROM positions WHERE job_id='" . $job_offer_id . "'";
          $data = mysqli_query($dbc, $job_query);
          $row = mysqli_fetch_array($data);
          $job_offer_company_id = $row['company_id'];
          // Fetch company category using company id
          $company_query = "SELECT company_category FROM recruiters WHERE company_id='" . $job_offer_company_id . "'";
          $data = mysqli_query($dbc, $company_query);
          $row = mysqli_fetch_array($data);
          $job_offer_company_cat = $row['company_category'];
          //echo "Already is " . $job_offer_company_cat . "<br/>";
          // Check if company is A1
          if(strtolower($job_offer_company_cat) == "a1"){
            // Ineligible for further placements.
            $is_stud_eligible = false;
            $button_message = "You are ineligible for further participation in campus placements.";
            break;
          }

          // Check else if company is B1
          elseif(strtolower($job_offer_company_cat) == "b1"){
            // Able to apply for only two A1
            // Check how many A1s has student already applied to
            $count_A1_query = "SELECT * FROM applications WHERE student_roll_number='" . $_SESSION['roll_number'] . "' AND company_category='A1'";
            $data = mysqli_query($dbc, $count_A1_query);
            if(mysqli_num_rows($data) < 2){
              // Student can apply for more A1s
              $company_query = "SELECT company_category FROM recruiters WHERE company_id='" . $company_id . "'";
              $data = mysqli_query($dbc, $company_query);
              $row = mysqli_fetch_assoc($data);
              //echo "Current company is " . $row['company_category'];
              // Check if current job is of A1 company.
              if(strtolower($row['company_category']) != 'a1'){
                // Can not apply to this job
                $is_stud_eligible = false;
                $button_message = "You are ineligible to apply for this job.";
                break;
              }
            } else {
              // Ineligible for further placements.
              $is_stud_eligible = false;
              $button_message = "You are ineligible for further participation in campus placements.";
              break;
            }
          }


        }
      }

      // ************ ELIGIBITLY check ends here ************

      // Adding job details
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
        if(isset($_GET['apply']) && $_GET['apply']=='true' && $_SESSION['user_role']=='student'){
          $apply_error='';
          $apply_query="SELECT application_id FROM applications WHERE job_id='" . $job_id . "' AND student_roll_number='". $_SESSION['roll_number'] ."'";
          $query3=mysqli_query($dbc,$apply_query);
          $num3=mysqli_num_rows($query3);
          if($num3==0){
            $comp_id_query="SELECT company_id FROM positions WHERE job_id='" . $job_id . "'";
            $query3=mysqli_query($dbc,$comp_id_query);
            $row3=mysqli_fetch_assoc($query3);
            $comp_cat_query="SELECT company_category FROM recruiters WHERE company_id='" . $row3['company_id'] . "'";
            $query3=mysqli_query($dbc,$comp_cat_query);
            $row3=mysqli_fetch_assoc($query3);
            $apply_query="INSERT INTO applications (job_id, student_roll_number, company_category, applied_on) VALUES ".
              "('" . $job_id . "', '" . $_SESSION['roll_number'] . "', '" . $row['company_category'] . "' , NOW())";
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

        <div class="container" style="max-width: 80%; padding: 20px;">
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
              <?php if($_SESSION['user_role']=='student'){ ?>
                <a href="<?php echo $job_url . '?id=' . $job_id . '&apply=true'; ?>">
                  <button class="btn btn-primary" style="float:right;"
                    <?php
                      if(!$cpi_above || !$dept_match || !$course_match || !$is_stud_eligible)
                        echo "disabled";
                    ?>
                  >
                    Apply
                  </button>
                </a>
                <div style="float:right;margin-top:5px;margin-right:15px;color:red;">
                  <?php
                    if(!$cpi_above || !$dept_match || !$course_match || !$is_stud_eligible)
                      echo $button_message;
                  ?>
                </div>
              <?php } ?>
            </div>
          </div>

<?php
        if($_SESSION['user_role']=='admin'){
          $applicant_query="SELECT * FROM applications WHERE job_id='" . $job_id ."'";
          $applicant_data=mysqli_query($dbc,$applicant_query);
          $applicant_num=mysqli_num_rows($applicant_data);
          $sno=1;
          if($applicant_num!=0){
          $resume_download_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/resumes.php';
  ?>
            <br/>
            <div class="card">
              <div class="card-header">
                <div style="display:inline-block">
                  <h5 class="card-title" >Application Distribution</h5>
                </div>
              </div>
              <div class="card-body table-responsive">
                <div class="chart-container" style="height: auto; margin: auto;">
                  <div class="pie-chart-container">
                    <canvas id="btech"></canvas>
                  </div>
                  <div class="pie-chart-container">
                    <canvas id="mtech"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <br />
            <div class="card">
              <div class="card-header">
                <div style="display:inline-block;float:left;">
                  <h5 class="card-title" >List of applicants</h5>
                </div>

                <a href="<?php echo $resume_download_url; ?>">
                  <button class="btn btn-primary" style="float:right;margin-top:10px;">
                    Download resumes
                  </button>
                </a>
              </div>
              <div class="card-body table-responsive">
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">S.No.</th>
                      <th scope="col">Roll Number</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Applied on</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>

<?php
            while($applicant_row=mysqli_fetch_assoc($applicant_data)){
              $app_roll_no=$applicant_row['student_roll_number'];
              $student_query="SELECT * FROM students WHERE roll_number='" . $app_roll_no ."'";
              $student_data=mysqli_query($dbc,$student_query);
              $student_num=mysqli_num_rows($student_data);
              if($student_num==1){
                $student_row=mysqli_fetch_assoc($student_data);
                $student_name=$student_row['username'];
                $student_email=$student_row['webmail_id'];

                $applied_on=$applicant_row['applied_on'];
                $app_status=$applicant_row['application_status'];

  ?>
                  <tr>
                  <td><?php echo $sno; ?></td>
                  <td><?php echo $app_roll_no; ?></td>
                  <td><a href="./admin/student.php?roll=<?php echo $app_roll_no; ?>" target="_blank"><?php echo $student_name; ?></a></td>
                  <td><?php echo $student_email; ?></td>
                  <td><?php echo $applied_on; ?></td>
                  <td>
                    <?php if ($app_status == "accepted"){ ?>
                    <span class="badge badge-success">Accepted</span>
                    <?php } elseif ($app_status == "pending") { ?>
                    <span class="badge badge-warning">Pending</span>
                    <?php } elseif ($app_status == "rejected") { ?>
                    <span class="badge badge-danger">Rejected</span>
                    <?php } ?>
                  </td>
                  </tr>
                <?php $sno+=1; ?>

<?php
              }
            }
?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
<?php
          } else{
  ?>
          <br/>
          <h5>No one has applied for this job yet!</h5>
<?php
          }
        }
      }
    } else {
?>
  <div> Job not found! </div>
<?php } ?>
<?php require_once('templates/footer.php');?>
