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
    $button_disable_error = "";
    if($_SESSION['user_role'] == 'student'){
      $student_query = "SELECT current_cpi, department, course FROM students_data WHERE roll_number='". $_SESSION['roll_number'] ."'";
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

      // Check eligibility
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
            $button_disable_error = "Your CPI doesn't meet eligibility criteria. Please contact TPC if you think this is an error.";
          }
        } else {
          $button_disable_error = "This job is not available for $student_department. Please contact TPC if you think this is an error.";
        }
      } else {
        $button_disable_error = "This job is not available for $student_course. Please contact TPC if you think this is an error.";
      }

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
        if(!isset($_POST['create_applicant_list'])){
          require_once('templates/header.php');
          require_once('templates/navbar.php');
        }
        if(isset($_GET['apply']) && $_GET['apply']=='true' && $_SESSION['user_role']=='student'){
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
        if(!isset($_POST['create_applicant_list'])){
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
                      if(!$cpi_above || !$dept_match || !$course_match)
                        echo "disabled";
                    ?>
                  >
                    Apply
                  </button>
                </a>
                <div style="float:right;margin-top:5px;margin-right:15px;color:red;">
                  <?php
                    if(!$cpi_above || !$dept_match || !$course_match)
                      echo $button_disable_error;
                  ?>
                </div>
              <?php } ?>
            </div>
          </div>

<?php
        }
        if($_SESSION['user_role']=='admin'){
          $applicant_query="SELECT * FROM applications WHERE job_id='" . $job_id ."'";
          $applicant_data=mysqli_query($dbc,$applicant_query);
          $applicant_num=mysqli_num_rows($applicant_data);
          $sno=1;
          $resume_download_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/resumes.php';
          $table_content='';
          if($applicant_num!=0){
            if(!isset($_POST['create_applicant_list'])){
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
            }
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
                      <td><a href="./admin/student.php?roll='. $app_roll_no .'" target="_blank">'. $student_name .'</a></td>
                      <td>'. $student_email .'</td>
                      <td>';
                if(!isset($_POST['create_applicant_list'])){
                  $table_content .= $applied_on .'</td><td>';}
                  if($app_status == "accepted"){
                    $table_content .= '<span class="badge badge-success">Accepted</span>';
                  }elseif($app_status == "pending") {
                    $table_content .= '<span class="badge badge-warning">Pending</span>';
                  }elseif($app_status == "rejected") {
                    $table_content .= '<span class="badge badge-danger">Rejected</span>';
                  }
                $table_content .= '
                      </td>
                    </tr>';
                $sno+=1;
              }
            }
            if(!isset($_POST['create_applicant_list'])){
              echo $table_content;
?>
                  </tbody>
                </table>
                <br />
                <form target="_blank" method="post">
                  <input type="submit" name="create_applicant_list" class="btn btn-danger" value="Create PDF" />
                </form>
                </div>
              </div>
            </div>
<?php
            } else{
              require_once('lib/tcpdf/tcpdf.php');
              $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
              $obj_pdf->SetCreator(PDF_CREATOR);
              $obj_pdf->SetTitle("Applicant list");
              $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
              $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
              $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
              $obj_pdf->SetDefaultMonospacedFont('helvetica');
              $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
              $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
              $obj_pdf->setPrintHeader(false);
              $obj_pdf->setPrintFooter(false);
              $obj_pdf->SetAutoPageBreak(TRUE, 10);
              $obj_pdf->SetFont('helvetica', '', 12);
              $obj_pdf->AddPage();
              $content = '
              <h3 align="center">List of applicants</h3><br /><br />
              <table border="1" cellspacing="0" cellpadding="5">
                <tr>
                  <th width="5%">S.No</th>
                  <th width="20%">Roll Number</th>
                  <th width="30%">Name</th>
                  <th width="30%">Email</th>
                  <th width="15%">Status</th>
                </tr>
              ';
              $content .= $table_content;
              $content .= '</table>';
              $obj_pdf->writeHTML($content);
              $obj_pdf->Output('applicants.pdf', 'I');
              }
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
<?php if(!isset($_POST['create_applicant_list'])) require_once('templates/footer.php');?>
