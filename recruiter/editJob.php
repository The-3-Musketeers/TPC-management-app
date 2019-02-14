<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  $page_title = 'View Position';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('recruiter', $auth_error);

  //Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  //Select Job Info from  Position Table 
  $job_id=$_GET['job_id'];
  $query="SELECT * FROM positions WHERE job_id=$job_id";
  $select_job_query=mysqli_query($dbc,$query);
  $row=mysqli_fetch_assoc($select_job_query);
  $job_position=$row['job_position'];
  $course=$row['course'];
  $branch=$row['branch'];
  $min_cpi=$row['min_cpi'];
  $no_of_opening=$row['no_of_opening'];
  $apply_by=$row['apply_by'];
  $stipend=$row['stipend'];
  $ctc=$row['ctc'];
  $test_date=$row['test_date'];
  $job_desc=$row['job_desc'];

  // Update Position
  if(isset($_POST['update'])){
    $job_id=$_GET['job_id'];
    $job_position=mysqli_real_escape_string($dbc,trim($_POST['job_position']));
    $multiple_course = implode(",",$_POST["course"]);
    $course=mysqli_real_escape_string($dbc,trim($multiple_course));
    $multiple_branch = implode(",",$_POST["branch"]);
    $branch=mysqli_real_escape_string($dbc,trim($multiple_branch));
    $min_cpi=mysqli_real_escape_string($dbc,trim($_POST['min_cpi']));
    $no_of_opening=mysqli_real_escape_string($dbc,trim($_POST['no_of_opening']));
    $apply_by=mysqli_real_escape_string($dbc,trim($_POST['apply_by']));
    $stipend=mysqli_real_escape_string($dbc,trim($_POST['stipend']));
    $ctc=mysqli_real_escape_string($dbc,trim($_POST['ctc']));
    $test_date=mysqli_real_escape_string($dbc,trim($_POST['test_date']));
    $job_desc=mysqli_real_escape_string($dbc,trim($_POST['job_desc']));
    $company_id=$_SESSION['company_id'];

    if($course!='' && $branch!=''){
      $query1 = "UPDATE positions SET job_position='$job_position',course='$course',branch='$branch',min_cpi=$min_cpi,job_desc='$job_desc'";
      // Append to insert query is the fields are not empty
      if($test_date!=null){
        $query1 = $query1.",test_date='$test_date'";
      }else{
        $query1 = $query1.",test_date=NULL";
      }
      if($apply_by!=null){
        $query1 = $query1.",apply_by='$apply_by'";
      }else{
        $query1 = $query1.",apply_by=NULL";
      }
      if($no_of_opening!=null){
        $query1 = $query1.",no_of_opening=$no_of_opening";
      }else{
        $query1 = $query1.",no_of_opening=NULL";
      }
      if($stipend!=null){
        $query1 = $query1.",stipend=$stipend";
      }else{
        $query1 = $query1.",stipend=NULL";
      }
      if($ctc!=null){
        $query1 = $query1.",ctc=$ctc";
      }else{
        $query1 = $query1.",ctc=NULL";
      }
      $query=$query1." WHERE job_id={$job_id}";
      $today=date('Y-m-d');
      if(!empty($apply_by) && $today > $apply_by){
        // Alert Error if apply by is less than today
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Incorrect Apply Date' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      } else if(!empty($test_date) && $today > $test_date){
        // Alert Error if test date is less than today
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                'Incorrect Test Date' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
      } else {
      $create_job_query=mysqli_query($dbc,$query);
      if(!$create_job_query){
        die("QUERY FAILED ".mysqli_error($dbc));
      }
      // Alert Success : Profile Updated
      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Job Position Updated' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
      }

    }
    else{
      // Alert error : when branch or course not selected
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                'Please Select Course and Branch' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

?>

<div class="container" style="max-width: 60%; padding: 20px;">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?job_id=' . $job_id ?>">
  <div class="form-group row">
    <label for="job_position" class="col-sm-2 col-form-label">Job Position<span class="red">*</span></label>
    <input type="text" class="col-sm-10 form-control" id="job_position" name="job_position" value="<?php echo $job_position; ?>" required>
  </div>
  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Course<span class="red">*</span></label>
    <div class="col-sm-10">
      <div class="form-check form-check-inline">
        <input type="hidden" id="course" value="<?php echo $course;?>">
        <input class="form-check-input" type="checkbox" id="update_btech" name="course[]" value="btech">
        <label class="form-check-label" for="btech">BTech</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="update_mtech" name="course[]" value="mtech">
        <label class="form-check-label" for="mtech">MTech</label>
      </div>
    </div>  
  </div>
  <div class="form-group row">
    <label class="col-sm-2 col-form-label">Branch<span class="red">*</span></label>
    <div class="col-sm-10">
      <input type="hidden" id="branch" value="<?php echo $branch;?>">
      <div id="update_btech_branch"></div>
      <div id="update_mtech_branch"></div>
    </div>  
  </div>
  <div class="form-group row">
    <label for="min_cpi" class="col-sm-2 col-form-label">Minimum CPI<span class="red">*</span></label>
    <input type="text" class="col-sm-10 form-control" name="min_cpi" value="<?php echo $min_cpi; ?>" required>
  </div>
  <div class="form-group row">
    <label for="no_of_opening" class="col-sm-2 col-form-label">No. of Openings</label>
    <input type="text" class="col-sm-10 form-control" id="no_of_opening" name="no_of_opening" value="<?php echo $no_of_opening; ?>">
  </div>
  <div class="form-group row">
    <label for="apply_by" class="col-sm-2 col-form-label">Apply By</label>
    <input type="date" class="col-sm-10 form-control" id="apply_by" name="apply_by" value="<?php echo $apply_by;?>">
  </div>
  <div class="form-group row">
    <label for="stipend" class="col-sm-2 col-form-label">Stipend</label>
    <input type="text" class="col-sm-10 form-control" id="stipend" name="stipend" value="<?php echo $stipend; ?>">
  </div>
  <div class="form-group row">
    <label for="ctc" class="col-sm-2 col-form-label">CTC</label>
    <input type="text" class="col-sm-10 form-control" id="ctc" name="ctc" value="<?php echo $ctc; ?>">
  </div>
  <div class="form-group row">
    <label for="test_date" class="col-sm-2 col-form-label">Test Date</label>
    <input type="date" class="col-sm-10 form-control" name="test_date" value="<?php echo $test_date;?>" id="test_date">
  </div>
  <div class="form-group row">
    <label for="job_desc" class="col-sm-2 col-form-label">Job Description<span class="red">*</span></label>
    <textarea class="col-sm-10 form-control" id="job_desc" rows="3" name="job_desc" required><?php echo $job_desc; ?></textarea>
  </div>
  <div class="form-group row">
    <div class="col-sm-2">
      <button type="submit" name="update" class="btn btn-primary">Update</button>
    </div>
  </div>
</form>
</div>

<?php
  // Insert the footer
  require_once('../templates/footer.php');
?>