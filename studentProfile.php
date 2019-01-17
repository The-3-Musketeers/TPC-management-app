<?php
    // Start the session
    require_once('templates/startSession.php');
    // Database connection variables
    require_once('connectVars.php');

    if (!isset($_SESSION['user_id'])) {
      echo '<p class="login">Please <a href="studentLogin.php">log in</a> to access this page.</p>';
      exit();
    }
    
    //Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $user_id=$_SESSION['user_id'];
    $roll_number=$_SESSION['roll_number'];
    $username=$_SESSION['username'];

    //Fetching info from student_data table
    $query="SELECT * FROM students_data WHERE roll_number='{$roll_number}'";
    $select_from_student_data_query=mysqli_query($dbc,$query);
    if(!$select_from_student_data_query)
    {
        die("QUERY FAILED ".mysqli_error($dbc));
    }

    $row=mysqli_fetch_assoc($select_from_student_data_query);
    $data_id=$row['data_id'];
    $current_cpi=$row['current_cpi'];
    $department=$row['department'];
    $course=$row['course'];
    $profie_pic=$row['profile_pic'];
    $resume_url=$row['resume_url'];

    ?>

<?php
//Updating Profile
if(isset($_POST['update']))
{
$username=mysqli_real_escape_string($dbc,trim($_POST['username']));
$course=mysqli_real_escape_string($dbc,trim($_POST['course']));
$department=mysqli_real_escape_string($dbc,trim($_POST['department']));
$cpi=mysqli_real_escape_string($dbc,trim($_POST['cpi']));
$resume_url=mysqli_real_escape_string($dbc,trim($_POST['resume']));

$profile_img_name=$_FILES['profile_img']['name'];
$profile_img_tmp_name=$_FILES['profile_img']['tmp_name'];
if($profile_img_name !=='')
{
$profile_img_name=time()."_".$profile_img_name;
move_uploaded_file($profile_img_tmp_name,"./images/$profile_img_name");
}

//$resume_link_name=$_FILES['resume']['name'];
//$resume_link_tmp_name=$_FILES['resume']['tmp_name'];
//$resume_link_name=$resume_link_name."_".time();
//move_uploaded_file($resume_link_tmp_name,"./resume/$resume_link_name");
if($profile_img_name !=='')
{
$query="UPDATE students_data SET course='$course', department='$department', current_cpi=$cpi,profile_pic='$profile_img_name',resume_url='$resume_url' WHERE data_id=$data_id";
}
else
{
$query="UPDATE students_data SET course='$course', department='$department', current_cpi=$cpi,resume_url='$resume_url' WHERE data_id=$data_id";
}

$update_query=mysqli_query($dbc,$query);
if(!$update_query)
{
die("QUERY FAILED db".mysqli_error($dbc));
}
$profile_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/studentProfile.php';
header('Location: ' . $profile_url);
}
?>
<?php
$page_title = 'Dashboard';
    require_once('templates/header.php');
    require_once('templates/navbar.php'); ?>
<div class="container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div class="row">
 <div class="col-sm-3">
    <img src="./images/<?php echo $profie_pic; ?>" width="250px" height="250px" alt="...">
    <input type="file" name="profile_img" value="">
</div>
 <div class="col-sm-9">
  <div class="form-group">
    <label for="roll-number">Roll No.</label>
    <input type="text" class="form-control" id="" name="roll_number" value="<?php echo $roll_number; ?>" readonly>
  </div>
  <div class="form-group">
    <label for="username">Full Name</label>
    <input type="text" class="form-control" id="" name="username" value="<?php echo $username; ?>">
  </div>
  <div class="form-group">
    <label for="course">Course</label>
    <select name="course" id="">
        <option value="Btech">Btech</option>
        <option value="Mtech">Mtech</option>
        <option value="PHD">PHD</option>
    </select>
  </div>
  <div class="form-group">
    <label for="department">Department</label>
    <select name="department" id="">
        <option value="CS">CS</option>
        <option value="EE">EE</option>
        <option value="ME">ME</option>
        <option value="CE">CE</option>
        <option value="CB">CB</option>
    </select>
  </div>
  <div class="form-group">
    <label for="cpi">CPI</label>
    <input type="text" class="form-control" id="" name="cpi" value="<?php echo $current_cpi; ?>">
  </div>
  <div class="form-group">
    <label for="resume">Resume</label>
    <input type="text" class="form-control" name="resume" value="<?php echo $resume_url; ?>">
  </div>
  <button type="submit" name="update" class="btn btn-primary">Update</button>
    </div>
    </div>
</form>
</div>
<?php require_once('templates/footer.php');?>
