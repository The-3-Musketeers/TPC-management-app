<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  $page_title = 'Create Job';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  // Authenticate user
  require_once('../templates/auth.php');

  //Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  if(isset($_POST['submit'])){
    $job_position=mysqli_real_escape_string($dbc,trim($_POST['job_position']));
    $multiple_course = implode(",",$_POST["course"]);
    $course=mysqli_real_escape_string($dbc,trim($multiple_course));
    $multiple_branch = implode(",",$_POST["branch"]);
    $branch=mysqli_real_escape_string($dbc,trim($multiple_branch));
    $min_cpi=mysqli_real_escape_string($dbc,trim($_POST['min_cpi']));
    $stipend=mysqli_real_escape_string($dbc,trim($_POST['stipend']));
    $ctc=mysqli_real_escape_string($dbc,trim($_POST['ctc']));
    $test_date=mysqli_real_escape_string($dbc,trim($_POST['test_date']));
    $job_desc=mysqli_real_escape_string($dbc,trim($_POST['job_desc']));
    $company_id=$_SESSION['company_id'];
    if($course!='' && $branch!=''){
      $query1 = "INSERT INTO positions (job_position, course, branch, min_cpi, job_desc, company_id ";
      $query2 = "('$job_position', '$course', '$branch', $min_cpi, '$job_desc', $company_id ";
      if(!empty($test_date)){
        $query1 = $query1.", test_date";
        $query2 = $query2.", $test_date";
      }
      if(!empty($stipend)){
        $query1 = $query1.", stipend";
        $query2 = $query2.", $stipend";
      }
      if(!empty($ctc)){
        $query1 = $query1.", ctc";
        $query2 = $query2.", $ctc";
      }
      $query=$query1.") VALUES ".$query2.")";
      $create_job_query=mysqli_query($dbc,$query);
      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Job Position Created' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
    }
    else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                'Please Select Course and Branch' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

?>

<div class="container" style="max-width: 60%; padding: 20px;">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div class="form-group row">
    <label for="job_position" class="col-sm-2 col-form-label">Job Position<span style="color:red;">*</span></label>
    <input type="text" class="col-sm-10 form-control" id="job_position" name="job_position" value="" required>
  </div>
  <div class="form-group row">
  <label class="col-sm-2 col-form-label">Course<span style="color:red;">*</span></label>
  <div class="col-sm-10">
    <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="btech" value="btech" name="course[]" checked>
    <label class="form-check-label" for="btech">BTech</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="mtech" name="course[]" value="mtech">
      <label class="form-check-label" for="mtech">MTech</label>
    </div>
  </div>  
  </div>
  <div class="form-group row">
  <label class="col-sm-2 col-form-label">Branch<span style="color:red;">*</span></label>
  <div class="col-sm-10">
    <div id="btech_branch"></div>
    <div id="mtech_branch"></div>
  </div>  
  </div>
  <div class="form-group row">
    <label for="min_cpi" class="col-sm-2 col-form-label">Minimum CPI<span style="color:red;">*</span></label>
    <input type="text" class="col-sm-10 form-control" name="min_cpi" value="" required>
  </div>
  <div class="form-group row">
    <label for="stipend" class="col-sm-2 col-form-label">Stipend</label>
    <input type="text" class="col-sm-10 form-control" id="stipend" name="stipend" value="">
  </div>
  <div class="form-group row">
    <label for="ctc" class="col-sm-2 col-form-label">CTC</label>
    <input type="text" class="col-sm-10 form-control" id="ctc" name="ctc" value="">
  </div>
  <div class="form-group row">
    <label for="test_date" class="col-sm-2 col-form-label">Test Date</label>
    <input type="date" class="col-sm-10 form-control" name="test_date" value="" id="test_date">
  </div>
  <div class="form-group row">
    <label for="job_desc" class="col-sm-2 col-form-label">Job Description<span style="color:red;">*</span></label>
    <textarea class="col-sm-10 form-control" id="job_desc" rows="3" name="job_desc" value="" required></textarea>
  </div>
  <div class="form-group row">
    <div class="col-sm-2">
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
</div>

<?php
  // Insert the footer
  require_once('../templates/footer.php');
?>

