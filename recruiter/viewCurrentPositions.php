<?php 
// Authenticate user
require_once('../templates/auth.php');
checkUserRole('recruiter', $auth_error);
$company_id=$_SESSION['company_id'];
$query="SELECT * FROM positions WHERE company_id=$company_id";
$get_all_positions_query=mysqli_query($dbc,$query);
while($row=mysqli_fetch_assoc($get_all_positions_query)){
    $job_id=$row['job_id'];
    $job_position=$row['job_position'];
    $course=$row['course'];
    $branch=$row['branch'];
    $min_cpi=$row['min_cpi'];
    $stipend=$row['stipend'];
    if($stipend==null){
        $stipend='N.A.';
    }
    $ctc=$row['ctc'];
    if($ctc==null){
        $ctc='N.A.';
    }
    $test_date=$row['test_date'];
    if($test_date==null){
        $test_date='N.A.';
    }else {
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
<button class="btn btn-primary" style="float:right;margin-top:10px;">View Details</button>
</div>
  <div class="card-body table-responsive">
    <table class="table table-borderless">
    <thead>
        <tr>
        <th scope="col" class="text-muted">Test Date</th>
        <th scope="col" class="text-muted">Course</th>
        <th scope="col" class="text-muted">Branch</th>
        <th scope="col" class="text-muted">Minimum CPI</th>
        <th scope="col" class="text-muted">Stipend</th>
        <th scope="col" class="text-muted">CTC</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><?php echo $test_date; ?></td>
        <td><?php echo $course; ?></td>
        <td><?php echo $branch; ?></td>
        <td><?php echo $min_cpi; ?></td>
        <td><?php echo $stipend; ?></td>
        <td><?php echo $ctc; ?></td>
        </tr>
    </tbody>
    </table>
    </div>
</div>

<?php } ?>
