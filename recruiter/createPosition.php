<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  $page_title = 'Create Job';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  // Authenticate user
  require_once('../templates/auth.php');
  if($_SESSION['user_role'] != "admin"){ // if not admin
    checkUserRole('recruiter', $auth_error);
  }

  //Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  // Random job_id generator
  function generate_job_id() 
  { 
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
    // Shufle the $str_result and returns substring 
    // of length 6 
    return substr(str_shuffle($str_result), 0, 6); 
  } 
  
  // Insert New Job
  if(isset($_POST['submit'])){
    $job_position=mysqli_real_escape_string($dbc,trim($_POST['job_position']));
    $multiple_degree = implode(",",$_POST["course"]);
    $degree=mysqli_real_escape_string($dbc,trim($multiple_degree));
    $multiple_branch = implode(",",$_POST["branch"]);
    $branch=mysqli_real_escape_string($dbc,trim($multiple_branch));
    $min_cpi=mysqli_real_escape_string($dbc,trim($_POST['min_cpi']));
    $no_of_opening=mysqli_real_escape_string($dbc,trim($_POST['no_of_opening']));
    $apply_by=mysqli_real_escape_string($dbc,trim($_POST['apply_by']));
    $stipend=mysqli_real_escape_string($dbc,trim($_POST['stipend']));
    $ctc=mysqli_real_escape_string($dbc,trim($_POST['ctc']));
    $test_date=mysqli_real_escape_string($dbc,trim($_POST['test_date']));
    $job_desc=mysqli_real_escape_string($dbc,trim($_POST['job_desc']));
    $company_id = "";
    if($_SESSION['company_id']){
      $company_id = $_SESSION['company_id'];
    }else{
      $company_id = $_POST['post_id'];
    }
    $company_name = "";
    if($_SESSION['company_name']){
      $company_name = $_SESSION['company_name'];
    }else{
      $company_name = $_POST['post_name'];
    }
    $job_id=generate_job_id();

    if($degree!='' && $branch!=''){
      $query1 = "INSERT INTO jobs (job_id, job_position, min_cpi, job_desc, company_id,created_on,company_name ";
      $query2 = "('$job_id', '$job_position', $min_cpi, '$job_desc', '$company_id', NOW(),'$company_name' ";
      // Append to insert query is the fields are not empty
      if(!empty($test_date)){
        $query1 = $query1.", test_date";
        $query2 = $query2.", '$test_date'";
      }
      if(!empty($apply_by)){
        $query1 = $query1.", apply_by";
        $query2 = $query2.", '$apply_by'";
      }
      if(!empty($no_of_opening)){
        $query1 = $query1.", no_of_opening";
        $query2 = $query2.", $no_of_opening";
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
      
      // Checking whether the random generated job_id is already present or not
      $job_id_query="SELECT * FROM jobs WHERE job_id='$job_id'";
      $select_query=mysqli_query($dbc,$job_id_query);
      $select_query_row_count=mysqli_num_rows($select_query);
      while($select_query_row_count>0){
        $job_id=generate_job_id();
        $select_query=mysqli_query($dbc,$job_id_query);
        $select_query_row_count=mysqli_num_rows($select_query);
      }

      $create_job_query=mysqli_query($dbc,$query);
      if(!$create_job_query){
        die("QUERY FAILED ".mysqli_error($dbc));
      }
      // fetch all db_id
      $query3 = "SELECT DB.db_id AS db_id FROM degree AS D, branch AS B, degree_branch  AS DB WHERE D.degree_id = DB.degree_id "
                . "AND B.branch_id = DB.branch_id AND LOCATE(D.degree_name, '$degree') > 0 AND LOCATE(B.branch_name, '$branch') > 0";
      $select_DB_query=mysqli_query($dbc,$query3);
      if(!$select_DB_query){
        die("QUERY FAILED ".mysqli_error($dbc));
      }

      // insert them in jobs_db
      while($DB_row=mysqli_fetch_assoc($select_DB_query)){
        $db_id = $DB_row['db_id'];
        $query4 = "INSERT INTO jobs_db VALUES('$job_id', '$db_id')";
        $insert_DB_query=mysqli_query($dbc,$query4);
        if(!$insert_DB_query){
          die("QUERY FAILED ".mysqli_error($dbc));
        }
      }

      if(!$select_DB_query){
        die("QUERY FAILED ".mysqli_error($dbc));
      }
      // Alert Success : Profile Updated
      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Job Position Created' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
      }

    }
    else{
      // Alert error : when branch or degree not selected
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                'Please Select Degree and Branch' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

?>

<div class="container" style="max-width: 60%; padding: 20px;">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div class="form-group row">
    <label for="job_position" class="col-sm-2 col-form-label">Job Position<span class="red">*</span></label>
    <input type="text" class="col-sm-10 form-control" id="job_position" name="job_position" value="" required>
  </div>
  <div class="form-group row">
  <label class="col-sm-2 col-form-label">Degree<span class="red">*</span></label>
  <div class="col-sm-10">
    <?php 
      $query = "SELECT degree_name FROM degree";
      $data = mysqli_query($dbc,$query);
      while($row = mysqli_fetch_assoc($data)){
        $degree_name = $row['degree_name'];
        echo "<div class='form-check form-check-inline course'>
                <input class='form-check-input' type='checkbox' id='$degree_name' value='$degree_name' name='course[]'>
                <label class='form-check-label' for='$degree_name'>$degree_name</label>
              </div>";
      }
    ?>
  </div>  
  </div>
  <div class="form-group row">
  <label class="col-sm-2 col-form-label">Branch<span class="red">*</span></label>
  <div class="col-sm-10">
    <?php 
      $query = "SELECT degree_name FROM degree";
      $data = mysqli_query($dbc,$query);
      while($row = mysqli_fetch_assoc($data)){
        $degree_name = $row['degree_name'];
        $degree_name .= "_branch";
        echo "<div id='$degree_name'></div>";
      }
    ?>
  </div>  
  </div>
  <div class="form-group row">
    <label for="min_cpi" class="col-sm-2 col-form-label">Minimum CPI<span class="red">*</span></label>
    <input type="text" class="col-sm-10 form-control" name="min_cpi" value="" required>
  </div>
  <div class="form-group row">
    <label for="no_of_opening" class="col-sm-2 col-form-label">No. of Openings</label>
    <input type="text" class="col-sm-10 form-control" id="no_of_opening" name="no_of_opening" value="">
  </div>
  <div class="form-group row">
    <label for="apply_by" class="col-sm-2 col-form-label">Apply By</label>
    <input type="date" class="col-sm-10 form-control" id="apply_by" name="apply_by" value="">
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
    <label for="job_desc" class="col-sm-2 col-form-label">Job Description<span class="red">*</span></label>
    <textarea class="col-sm-10 form-control" id="job_desc" rows="3" name="job_desc" value="" required></textarea>
  </div>
  <!-- FOR ADMIN PANEL (2 hidden input fields)-->
  <input type="text" class="form-control" name="post_id" value="<?php echo $_POST['post_id']; ?>" style="display: none">
  <input type="text" class="form-control" name="post_name" value="<?php echo $_POST['post_name']; ?>" style="display: none">
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


