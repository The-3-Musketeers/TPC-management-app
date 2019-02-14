<?php
// Authenticate user
require_once('../templates/auth.php');
checkUserRole('recruiter', $auth_error);
$company_id=$_SESSION['company_id'];
// Fetch data from positions table
$query="SELECT * FROM positions WHERE company_id='$company_id'";
$get_all_positions_query=mysqli_query($dbc,$query);
$num=mysqli_num_rows($get_all_positions_query);
if($num!=0){
  while($row=mysqli_fetch_assoc($get_all_positions_query)){
    $job_id=$row['job_id'];
    $job_position=$row['job_position'];
    $course=$row['course'];
    $branch=$row['branch'];
    $min_cpi=$row['min_cpi'];
    $no_of_opening=$row['no_of_opening'];
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
?>
    <br>
    <div class="card">
      <div class="card-header">
        <div style="display:inline-block">
          <h5 class="card-title" ><?php echo $job_position; ?></h5>
          <h6 class="card-subtitle mb-2 text-muted">Created on <?php echo $created_on;?></h6>
        </div>
        <a class="btn btn-primary" style="float:right;margin-top:10px;" href="/TPC-management-app/recruiter/editJob.php?job_id=<?php echo $job_id?>">View Details</a>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-borderless">
        <thead>
            <tr>
            <th scope="col" class="text-muted">Test Date</th>
            <th scope="col" class="text-muted">Course</th>
            <th scope="col" class="text-muted">Branch</th>
            <th scope="col" class="text-muted">Minimum CPI</th>
            <th scope="col" class="text-muted">Openings</th>
            <th scope="col" class="text-muted">Apply By</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><?php echo $test_date; ?></td>
            <td><?php echo $course; ?></td>
            <td><?php echo $branch; ?></td>
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
