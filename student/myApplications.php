<?php
// Authenticate user
require_once('../templates/auth.php');
checkUserRole('student', $auth_error);
$get_all_applications_query = "SELECT * FROM applications WHERE student_roll_number='" . $_SESSION['roll_number'] . "'";
$application_data = mysqli_query($dbc,$get_all_applications_query);
$num=mysqli_num_rows($application_data);
if($num!=0){
  ?>
  <table class="table">
    <thead class="thead-light">
      <tr>
        <th scope="col">S.No.</th>
        <th scope="col">Job Name</th>
        <th scope="col">Company Name</th>
        <th scope="col">Category</th>
        <th scope="col">Test Date</th>
        <th scope="col">Applied on</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
  <?php
  $sno=1;
  while($row=mysqli_fetch_assoc($application_data)){
    $job_id=$row['job_id'];
    $application_status=$row['application_status'];
    $applied_on=$row['applied_on'];
    $applied_on=date('d-m-y',strtotime($applied_on));
    $job_query="SELECT * FROM positions WHERE job_id='" . $job_id . "'";
    $job_data=mysqli_query($dbc,$job_query);
    $num1=mysqli_num_rows($job_data);
    if($num1==1){
      $row1=mysqli_fetch_assoc($job_data);
      $company_id=$row1['company_id'];
      $company_query="SELECT * FROM recruiters_data WHERE company_id='" . $company_id . "'";
      $company_data=mysqli_query($dbc,$company_query);
      $com_row=mysqli_fetch_assoc($company_data);
      $company_name=$com_row['company_name'];
      $company_cat = $com_row['company_category'];

      $job_position=$row1['job_position'];
      $test_date=$row1['test_date'];
      if($test_date==''){
        $test_date='N.A.';
      }
      $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';
?>
            <tr>
            <td><?php echo $sno; ?></td>
            <td><a href="<?php echo $job_url.'?id='.$job_id; ?>"><?php echo $job_position; ?></a></td>
            <td><?php echo $company_name; ?></td>
            <td><?php echo $company_cat; ?></td>
            <td><?php echo $test_date; ?></td>
            <td><?php echo $applied_on; ?></td>
            <td><?php
              if($application_status == "accepted"){
                echo '<span class="badge badge-success">Accepted</span>';
              }elseif($application_status == "pending") {
                echo '<span class="badge badge-warning">Pending</span>';
              }elseif($application_status == "rejected") {
                echo '<span class="badge badge-danger">Rejected</span>';
              }
            ?></td>
            </tr>
            <?php $sno+=1; ?>

<?php
}}?>
</tbody>
</table>
  <?php
} else {
?>
<div> You have not applied for a job yet! </div>
<?php } ?>
