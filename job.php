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
    $button_message = "";
    $is_stud_eligible = TRUE;
    if($_SESSION['user_role'] == 'student'){
      // Fetch general details
      $student_query = "SELECT current_cpi, db_id, job_offers FROM students_data WHERE roll_number='". $_SESSION['roll_number'] ."'";
      $data = mysqli_query($dbc, $student_query);
      if(mysqli_num_rows($data) == 1){
        $row = mysqli_fetch_array($data);
        $student_cpi = $row['current_cpi'];

        $db_id = $row['db_id'];
        if($db_id != null){
          $fetch_degree_branch = "SELECT D.degree_name AS d_name, B.branch_name AS b_name FROM degree_branch AS DB,"
                                ." degree AS D, branch AS B WHERE D.degree_id = DB.degree_id AND B.branch_id = DB.branch_id"
                                ." AND db_id='{$db_id}'";

          $fetch_degree_branch_query = mysqli_query($dbc,$fetch_degree_branch);
          $degree_branch = mysqli_fetch_assoc($fetch_degree_branch_query);
          $student_department = $degree_branch['b_name'];
          $student_course = $degree_branch['d_name'];
        }
      }
    }

    // Fetch job Details
    $query="SELECT * FROM jobs WHERE job_id='". $job_id ."'";
    $get_all_jobs_query=mysqli_query($dbc,$query);
    $num=mysqli_num_rows($get_all_jobs_query);
    if($num==1){
      $row=mysqli_fetch_assoc($get_all_jobs_query);
      $company_id=$row['company_id'];
      $job_position=$row['job_position'];
      
      // Get all db_id
      $db_id_query = "SELECT db_id FROM jobs_db WHERE job_id = '$job_id'";
      $db_id_data = mysqli_query($dbc,$db_id_query);
      $db_id_row=mysqli_fetch_assoc($db_id_data);
      $db_id_array = $db_id_row['db_id'];
      while($db_id_row=mysqli_fetch_assoc($db_id_data)){
        $db_id_array = $db_id_array . ', ' . $db_id_row['db_id'];
      }
      // Get all eligible degree and branche pairs
      $degree_query = "SELECT DISTINCT degree.degree_name AS degree_name, degree_branch.degree_id AS degree_id FROM degree, degree_branch, jobs_db "
                      ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                      ."AND degree_branch.degree_id = degree.degree_id";
      $get_all_degree=mysqli_query($dbc,$degree_query);
      $degreeBranch="";
      while($degree_row=mysqli_fetch_assoc($get_all_degree)){
        $d_id = $degree_row['degree_id'];
        $degreeBranch .= '<b>' . $degree_row['degree_name']. '</b>' . "\n";
        $branch_query = "SELECT branch.branch_name AS branch_name FROM branch, degree_branch "
                      ."WHERE branch.branch_id = degree_branch.branch_id AND degree_branch.degree_id='$d_id'";
        $get_all_branch=mysqli_query($dbc,$branch_query);
        $cnt = 0;
        while($branch_row=mysqli_fetch_assoc($get_all_branch)){
          if($cnt == 0)
            $degreeBranch .= $branch_row['branch_name'];
          else
            $degreeBranch .= ", " . $branch_row['branch_name'];
          $cnt++;
        }
        $degreeBranch .= "\n";
      }

      $min_cpi=$row['min_cpi'];
      $no_of_opening=$row['no_of_opening'];
      $apply_by=$row['apply_by'];

      // Category of job
      $company_query = "SELECT company_category_id FROM recruiters_data WHERE company_id='" . $company_id . "'";
      $company_data = mysqli_query($dbc, $company_query);
      $company_row = mysqli_fetch_assoc($company_data);
      $company_cat_id = $company_row['company_category_id'];

      // ************ ELIGIBITLY check starts here ************

      if($_SESSION['user_role'] == 'student' && $is_stud_eligible){
        // Check if student is blocked by admin
        $stud_blocked_query = "SELECT blocked FROM students WHERE roll_number='" . $_SESSION['roll_number'] . "'";
        $data = mysqli_query($dbc, $stud_blocked_query);
        $row = mysqli_fetch_assoc($data);
        if($row["blocked"] == 1){
          $is_stud_eligible = FALSE;
          $button_message = "You have been blocked by TPC. Please contact TPC if you think this is an error.";
        }

        // Check if student has already applied
        $stud_applications_query = "SELECT * FROM applications WHERE student_roll_number='" . $_SESSION['roll_number'] . "' AND job_id='" . $job_id . "'";
        $data = mysqli_query($dbc, $stud_applications_query);
        if(mysqli_num_rows($data) != 0){
          $is_stud_eligible = FALSE;
          $button_message = "You have already applied for this job.";
        }
        if($is_stud_eligible){
          // Fetch student's current job offers' details
          $student_query = "SELECT job_offers FROM students_data WHERE roll_number='". $_SESSION['roll_number'] ."'";
          $data = mysqli_query($dbc, $student_query);
          $row1 = mysqli_fetch_array($data);
          $stud_job_offers = $row1['job_offers'];
          $stud_job_offers_arr = explode(",", $stud_job_offers);
          if(sizeof($stud_job_offers_arr) > 0 && strstr($stud_job_offers, ',') != ""){
            // check of which company category offer is and in how many more companies can student apply
            $num_times_can_apply = -1; // to the company category of job opened
            $num_times_already_applied = 0; // to the company category of job opened
            
            $query_count_app = "SELECT recruiters_data.company_category_id FROM applications, jobs, recruiters_data WHERE applications.student_roll_number='" . $_SESSION['roll_number'] . "' AND applications.job_id=jobs.job_id AND jobs.company_id=recruiters_data.company_id";
            $data_count_app = mysqli_query($dbc, $query_count_app);
            while($row_count_app = mysqli_fetch_assoc($data_count_app)){
              if($row_count_app['company_category_id'] == $company_cat_id){
                $num_times_already_applied += 1;
              }
            }
            foreach($stud_job_offers_arr as $cat_id){
              $query_num = "SELECT num FROM company_constraints WHERE current_id='$cat_id' AND can_apply_id='$company_cat_id'";
              $data_num = mysqli_query($dbc, $query_num);
              $row_num = mysqli_fetch_array($data_num);
              if(mysqli_num_rows($data_num) > 0){
                if($num_times_can_apply == -1){
                  $num_times_can_apply = $row_num['num'];
                }else{
                  $num_times_can_apply = min($num_times_can_apply, $row_num['num']);
                }
              }
            }
            // if no constraint or already applied as many times as specified in constraints
            if($num_times_can_apply == -1 || $num_times_already_applied >= $num_times_can_apply){
              $is_stud_eligible = FALSE;
              $button_message = "You are ineligible for further participation in campus placements.";
            }
          }

          // Check general eligibility (Branch, Degree and CPI based)
          if($is_stud_eligible){
            $degree_arr = explode(", ", $degree);
            $branch_arr = explode(", ", $branch);
            $db_id_arr = explode(",",$db_id_array);
            $db_id_match = FALSE;
            foreach ($db_id_arr as $indiv_db_id) {
              if(strval(trim($indiv_db_id)) == strval(trim($db_id))){
                $db_id_match = TRUE;
                break;
              }
            }
            if($db_id_match){
              $cpi_above = FALSE;
              if(floatval($student_cpi) >= floatval($min_cpi)){
                $cpi_above = TRUE;
              }
              if(!$cpi_above){
                $is_stud_eligible = FALSE;
                $button_message = "Your CPI doesn't meet eligibility criteria. Please contact TPC if you think this is an error.";
              }
            } else {
              $is_stud_eligible = FALSE;
              $button_message = "This job is not available for $student_course and $student_department. Please contact TPC if you think this is an error.";
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
      $company_query="SELECT company_name, company_url, company_desc FROM recruiters_data WHERE company_id='" . $company_id . "'";
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
        if(isset($_GET['apply']) && $_GET['apply']=='true' && $_SESSION['user_role']=='student' && $is_stud_eligible){
          $apply_error='';
          $apply_query="SELECT application_id FROM applications WHERE job_id='" . $job_id . "' AND student_roll_number='". $_SESSION['roll_number'] ."'";
          $query3=mysqli_query($dbc,$apply_query);
          $num3=mysqli_num_rows($query3);
          if($num3==0){
            $apply_query = "INSERT INTO applications (job_id, student_roll_number, applied_on) VALUES ".
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
                  <th scope="col" class="text-muted" style="width:35%;">Degree-Branch</th>
                  <th scope="col" class="text-muted">Min CPI</th>
                  <th scope="col" class="text-muted">Stipend</th>
                  <th scope="col" class="text-muted">CTC</th>
                  <th scope="col" class="text-muted">Test Date</th>
                  <th scope="col" class="text-muted">Apply By</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                  <td><?php echo nl2br($degreeBranch); ?></td>
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
                      if(!$is_stud_eligible)
                        echo "disabled";
                    ?>
                  >
                    Apply
                  </button>
                </a>
                <div style="float:right;margin-top:5px;margin-right:15px;color:red;">
                  <?php
                    if(!$is_stud_eligible)
                      echo $button_message;
                  ?>
                </div>
              <?php } ?>
            </div>
          </div>

<?php
        if($_SESSION['user_role']=='admin'){

          if(isset($_GET['remove'])){
            $id=$_GET['remove'];
            $query="DELETE FROM applications WHERE job_id=$job_id AND student_roll_number='".$id."'";
            $remove_applicant_query=mysqli_query($dbc,$query);
            if(!$remove_applicant_query)
            {
              die("QUERY FAILED ". mysqli_error($dbc));
            }
          }

          if(isset($_POST['add_applicant'])){
            $roll_number=$_POST['roll_number'];
            $email=$_POST['email'];
            $query="INSERT INTO applications (job_id,student_roll_number,application_status,applied_on) VALUES".
                    "($job_id,'$roll_number','pending',NOW())";
            $insert_applicant_query=mysqli_query($dbc,$query);
            if(!$insert_applicant_query){
              die("QUERY FAILED ".mysqli_error($dbc));
            }
          }

          $applicant_query="SELECT * FROM applications WHERE job_id='" . $job_id ."' ORDER BY student_roll_number ASC";
          $applicant_data=mysqli_query($dbc,$applicant_query);
          $applicant_num=mysqli_num_rows($applicant_data);
          $sno=1;
          $resume_download_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/util/resumes.php?job_id=' . $job_id;
          $generate_pdf_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/util/pdf.php?job_id=' . $job_id;
          $generate_csv_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/util/csv.php?job_id=' . $job_id;
          $table_content='';
          if($applicant_num!=0){
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
                  <h5 class="card-title" >List of Applicants</h5>
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addApplicantModal">
                    Add Applicant
                  </button>
                  <!-- void span for to prevent default closing of modal in js -->
                  <!-- Modal -->
                  <div class="modal fade" id="addApplicantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Add Applicant</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF']."?id=".$job_id; ?>" method="post">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="roll-number">Roll No.<span class="red">*</span></label>
                              <input type="text" class="form-control" id="" name="roll_number" required>
                            </div>
                            <div class="form-group">
                              <label for="username">Email<span class="red">*</span></label>
                              <input type="email" class="form-control" name="email" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" name="add_applicant">Add</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!-- Modal ends here -->
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
                      <th scope="col">Actions</th>
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
                $table_content .= '
                    <tr>
                      <td>'. $sno .'</td>
                      <td>'. $app_roll_no .'</td>
                      <td><a href="./admin/student.php?roll='. $app_roll_no .'">'. $student_name .'</a></td>
                      <td>'. $student_email .'</td>
                      <td>'. $applied_on .'</td>
                      <td>';
                if($app_status == "accepted"){
                  $table_content .= '<span class="badge badge-success">Accepted</span>';
                }elseif($app_status == "pending") {
                  $table_content .= '<span class="badge badge-warning">Pending</span>';
                }elseif($app_status == "rejected") {
                  $table_content .= '<span class="badge badge-danger">Rejected</span>';
                }
                $table_content .= '</td>';
                $table_content .= '
                      <td>
                        <form action="'.$_SERVER['PHP_SELF']."?id=".$job_id."&remove=".$app_roll_no.'" method="post">
                          <button type="submit" name="remove" class="btn btn-danger">Remove</button>
                        </form>
                      </td>
                    </tr>';
                $sno+=1;
              }
            }
              echo $table_content;
?>
                  </tbody>
                </table>
                  <a href="<?php echo $generate_pdf_url; ?>" target="_blank">
                    <button name="create_applicant_pdf" class="btn btn-danger">Create PDF</button>
                  </a>
                  <a href="<?php echo $generate_csv_url; ?>" target="_blank">
                    <button name="create_applicant_csv" class="btn btn-danger" style="margin-left:10px;">Create CSV</button>
                  </a>
                </div>
              </div>
            </div>
<?php
            }else{
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
