<?php
// Authenticate user
require_once('../templates/auth.php');
if($_SESSION['user_role'] != "admin"){
    checkUserRole('recruiter', $auth_error);
}
$company_id = $_SESSION['company_id'];
// Fetch data from jobs table
$get_all_jobs_query="SELECT * FROM jobs WHERE company_id='$company_id'";
$get_all_jobs=mysqli_query($dbc,$get_all_jobs_query);
$num=mysqli_num_rows($get_all_jobs);
if($num!=0){
  while($jobs_row=mysqli_fetch_assoc($get_all_jobs)){
    $job_id=$jobs_row['job_id'];
    $job_position=$jobs_row['job_position'];

    // Get all eligible degree and branch pairs
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

    $min_cpi=$jobs_row['min_cpi'];
    $no_of_opening=$jobs_row['no_of_opening'];
    if($no_of_opening==null){
        $no_of_opening='N.A.';
    }
    $apply_by=$jobs_row['apply_by'];
    if($apply_by==null){
        $apply_by='N.A.';
    } else {
        $apply_by=date('d-m-y',strtotime($apply_by));
    }
    $test_date=$jobs_row['test_date'];
    if($test_date==null){
        $test_date='N.A.';
    } else {
        $test_date=date('d-m-y',strtotime($test_date));
    }
    $created_on=$jobs_row['created_on'];
    $created_on=date('d-m-y',strtotime($created_on));
?>
    <br>
    <div class="card">
      <div class="card-header">
        <div style="display:inline-block">
          <h5 class="card-title" ><?php echo $job_position; ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">Created on <?php echo $created_on;?></h6>
        </div>
        <a href="/TPC-management-app/recruiter/editJob.php?job_id=<?php echo $job_id?>">
        <button class="btn btn-primary" style="float:right;margin-top:10px;" <?php if($_SESSION['user_role'] == "admin"){ echo "disabled"; }?> >View Details
        </button></a>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-borderless">
        <thead>
            <tr>
            <th scope="col" class="text-muted">Test Date</th>
            <th scope="col" class="text-muted" style="width:35%;">Degree-Branch</th>
            <th scope="col" class="text-muted">Minimum CPI</th>
            <th scope="col" class="text-muted">Openings</th>
            <th scope="col" class="text-muted">Apply By</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><?php echo $test_date; ?></td>
            <td><?php echo nl2br($degreeBranch); ?></td>
            <td><?php echo $min_cpi; ?></td>
            <td><?php echo $no_of_opening; ?></td>
            <td><?php echo $apply_by; ?></td>
            </tr>
        </tbody>
        </table>
      </div>
    </div>

<?php
 }
} else {
?>
  <div> NO DATA </div>
<?php } ?>
