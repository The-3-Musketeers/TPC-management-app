<?php
// Authenticate user
require_once('../templates/auth.php');
checkUserRole('student', $auth_error);
$query="SELECT * FROM jobs WHERE job_status='shown'";
$get_all_jobs_query=mysqli_query($dbc,$query);
$num=mysqli_num_rows($get_all_jobs_query);
if($num!=0){
  while($row=mysqli_fetch_assoc($get_all_jobs_query)){
    $job_id=$row['job_id'];
    $application_query="SELECT * FROM applications WHERE job_id='". $job_id ."' AND student_roll_number='". $_SESSION['roll_number'] ."'";
    $application_data=mysqli_query($dbc, $application_query);
    $app_num=mysqli_num_rows($application_data);
    if($app_num==0){
      $company_id=$row['company_id'];
      $job_position=$row['job_position'];
      
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

      $no_of_opening=$row['no_of_opening'];
      $stipend=$row['stipend'];

      $company_cat_query = "SELECT company_category_id FROM recruiters_data WHERE company_id='" . $company_id . "'";
      $company_cat_data = mysqli_query($dbc, $company_cat_query);
      $company_cat_row = mysqli_fetch_assoc($company_cat_data);
      $company_category_id = $company_cat_row['company_category_id'];
      // fetch company category name from id
      $query_cat = "SELECT name FROM company_category WHERE id='$company_category_id'";
      $data_cat = mysqli_query($dbc, $query_cat);
      $row_cat = mysqli_fetch_assoc($data_cat);
      $company_category = $row_cat['name'];

      if($stipend==null){
          $stipend='N.A.';
      }
      if($no_of_opening==null){
          $no_of_opening='N.A.';
      }
      $apply_by=$row['apply_by'];
      if($apply_by==null){
          $apply_by='N.A.';
      } else {
          $apply_by=date('d-m-y',strtotime($apply_by));
      }
      $test_date=$row['test_date'];
      if($test_date==null){
          $test_date='N.A.';
      } else {
          $test_date=date('d-m-y',strtotime($test_date));
      }
      $created_on=$row['created_on'];
      $created_on=date('d-m-y',strtotime($created_on));
      $company_query="SELECT company_name FROM recruiters_data WHERE company_id='" . $company_id . "'";
      $get_company_name_query=mysqli_query($dbc,$company_query);
      $num1=mysqli_num_rows($get_company_name_query);
      if($num1==1){
        $row1=mysqli_fetch_assoc($get_company_name_query);
        $company_name=$row1['company_name'];
        $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';
  ?>
        <br>
        <div class="card">
          <div class="card-header">
            <div style="display:inline-block">
              <h5 class="card-title" ><?php echo $job_position; ?></h5>
              <h6 class="card-subtitle mb-2 text-muted"><?php echo $company_name;?></h6>
            </div>
            <a href="<?php echo $job_url . '?id=' . $job_id; ?>">
              <button class="btn btn-primary" style="float:right;margin-top:10px;">
                View Details
              </button>
            </a>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-borderless">
            <thead>
                <tr>
                <th scope="col" class="text-muted">Test Date</th>
                <th scope="col" class="text-muted">Degree</th>
                <th scope="col" class="text-muted">Branch</th>
                <th scope="col" class="text-muted">Stipend</th>
                <th scope="col" class="text-muted">Openings</th>
                <th scope="col" class="text-muted">Category</th>
                <th scope="col" class="text-muted">Apply By</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td><?php echo $test_date; ?></td>
                <td><?php echo $degree; ?></td>
                <td><?php echo $branch; ?></td>
                <td><?php echo $stipend; ?></td>
                <td><?php echo $no_of_opening; ?></td>
                <td><?php echo $company_category; ?></td>
                <td><?php echo $apply_by; ?></td>
                </tr>
            </tbody>
            </table>
          </div>
          <div class="card-footer text-muted">
            Posted on <?php echo $created_on;?>
          </div>
        </div>

<?php
      }
    }
  }
} else {
?>
<div> NO DATA </div>
<?php } ?>
