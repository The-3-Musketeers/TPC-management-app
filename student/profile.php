<?php
    // Start the session
    require_once('../templates/startSession.php');
    // Database connection variables
    require_once('../connectVars.php');

    // Authenticate user
    require_once('../templates/auth.php');
    checkUserRole('student', $auth_error);

    //Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $roll_number=$_SESSION['roll_number'];
    $username=$_SESSION['username'];

    $page_title = 'Dashboard';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');

    //Fetching info from student_data table
    $data_id;$cpi;$department;$course;$resume_url;$profile_pic_url;$resume_file;$mobile_number;$skype;$gmail;$emergency_number;
    function display(){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        global $roll_number;
        $query="SELECT * FROM students_data WHERE roll_number='{$roll_number}'";
        $select_from_student_data_query=mysqli_query($dbc,$query);
        if(!$select_from_student_data_query){
            die("QUERY FAILED ".mysqli_error($dbc));
        }

        global $data_id,$cpi,$skype,$gmail,$emergency_number,$department,$course,$resume_url,$profile_pic_url;
        $row=mysqli_fetch_assoc($select_from_student_data_query);
        $data_id=$row['data_id'];
        $cpi=$row['current_cpi'];
        $department=$row['department'];
        $skype=$row['skype_Id'];
        $gmail=$row['gmail_Id'];
        $emergency_number=$row['emergency_number'];
        $course=$row['course'];
        $resume_url=$row['resume_url'];
        $resume_file=$row['resume_file'];
        $mobile_number=$row['mobile_number'];
        if($course==null){
            $course="Select your course";
        }
        if($department==null){
            $department="Select your department";
        }
        if($row['profile_pic']!==null && $row['profile_pic']!==''){
            $profile_pic_url='../images/students/'.$row['profile_pic'];
        }
        else{
            $profile_pic_url='../pictures/user_icon.png';
        }
    }
    display();
    ?>

<?php
//Updating Profile
if(isset($_POST['update'])){

$username=mysqli_real_escape_string($dbc,trim($_POST['username']));
$course=mysqli_real_escape_string($dbc,trim($_POST['course']));
$department=mysqli_real_escape_string($dbc,trim($_POST['department']));
$cpi=mysqli_real_escape_string($dbc,trim($_POST['cpi']));
$resume_url=mysqli_real_escape_string($dbc,trim($_POST['resume']));
$mobile_number=mysqli_real_escape_string($dbc,trim($_POST['mobile_number']));
$skype=mysqli_real_escape_string($dbc,trim($_POST['skype']));
$gmail=mysqli_real_escape_string($dbc,trim($_POST['gmail']));
$emergency_number=mysqli_real_escape_string($dbc,trim($_POST['emergency_number']));

$profile_img_name=$_FILES['profile_img']['name'];
$profile_img_tmp_name=$_FILES['profile_img']['tmp_name'];

$resume_name=$_FILES['resume_file']['name'];
$resume_tmp_name=$_FILES['resume_file']['tmp_name'];

if($profile_img_name !=='' && $profile_img_name !==null){
$profile_img_name=time()."_".$profile_img_name;
move_uploaded_file($profile_img_tmp_name,"../images/students/$profile_img_name");
}

if($resume_name !=='' && $resume_tmp_name !==null){
$resume_name=$_SESSION['roll_number'].".pdf";
move_uploaded_file($resume_tmp_name,"../resume/$resume_name");
}

$query="UPDATE students_data SET course='$course', department='$department', current_cpi=$cpi, skype_Id='$skype', gmail_Id='$gmail', emergency_number=$emergency_number ";
if($profile_img_name !== null && $profile_img_name !== ''){
$query=$query.",profile_pic='$profile_img_name' ";
}
if($resume_name !== null && $resume_name !== ''){
$query=$query.",resume_file='$resume_name' ";
}
if($resume_url!='' && $resume_url!=null){
$query=$query.",resume_url='$resume_url' ";
}
if($mobile_number!='' && $mobile_number!=null){
$query=$query.",mobile_number=$mobile_number ";
}

$query=$query." WHERE data_id=$data_id";
if($department!='null' && $department!=null && $department!='Select your department'){
    $update_query=mysqli_query($dbc,$query);
    if(!$update_query){
    die("QUERY FAILED db".mysqli_error($dbc));
    }
    // Alert Success : Profile Updated
    echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                    'Profile Updated' .
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                    '<span aria-hidden="true">&times;</span></button></div></div>';
    display();
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

<div class="container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<div class="row">
 <div class="col-sm-3">
    <img src="<?php echo $profile_pic_url; ?>" width="250px" height="250px" alt="...">
    <input type="file" name="profile_img" value="">
</div>
 <div class="col-sm-9">
  <div class="form-group">
    <label for="roll-number">Roll No.<span class="red">*</span></label>
    <input type="text" class="form-control" id="" name="roll_number" value="<?php echo $roll_number; ?>" readonly>
  </div>
  <div class="form-group">
    <label for="username">Full Name<span class="red">*</span></label>
    <input type="text" class="form-control" id="" name="username" value="<?php echo $username; ?>" required>
  </div>
  <div class="form-group">
    <label for="course">Course<span class="red">*</span></label>
    <select name="course" id="course">
        <?php
        echo "<option value='{$course}'>{$course}</option>";
        if($course=='Btech'){
        	echo '<option value="Mtech">Mtech</option>';
        	echo '<option value="Msc">Msc</option>';
         	echo '<option value="PHD">PHD</option>';
        }
        else if($course=='Mtech'){
         	echo '<option value="Btech">Btech</option>';
         	echo '<option value="Msc">Msc</option>';
         	echo '<option value="PHD">PHD</option>';
        }
        else if($course=='Msc'){
            echo '<option value="Btech">Btech</option>';
            echo '<option value="Mtech">Mtech</option>';
            echo '<option value="PHD">PHD</option>';
        }
        else if($course=='PHD'){
            echo '<option value="Btech">Btech</option>';
            echo '<option value="Mtech">Mtech</option>';
            echo '<option value="Msc">Msc</option>';
        }
        else{
            echo '<option value="Btech">Btech</option>';
            echo '<option value="Mtech">Mtech</option>';
            echo '<option value="Msc">Msc</option>';
            echo '<option value="PHD">PHD</option>';
        }
        ?>
    </select>
  </div>
  <div class="form-group">
    <label for="department">Department<span class="red">*</span></label>
    <select name="department" id="department">
        <option value="<?php echo $department?>"><?php echo $department;?></option>
    </select>
  </div>

  <div class="form-group">
    <label for="cpi">CPI<span class="red">*</span></label>
    <input type="text" class="form-control" id="" name="cpi" value="<?php echo $cpi; ?>" required>
  </div>
  <div class="form-group">
    <label for="skype">Skype Id<span class="red">*</span></label>
    <input type="email" class="form-control" id="" name="skype" value="<?php echo $skype; ?>" required>
  </div>
  <div class="form-group">
    <label for="gmail">Gmail Id<span class="red">*</span></label>
    <input type="email" class="form-control" id="" name="gmail" value="<?php echo $gmail; ?>" required>
  </div>
  <div class="form-group">
    <label for="emergency_number">Emergency Contact No.<span class="red">*</span></label>
    <input type="number" class="form-control" id="" name="emergency_number" value="<?php echo $emergency_number; ?>" required>
  </div>
  <div class="form-group">
    <label for="mobile_number">Mobile No.</label>
    <input type="number" class="form-control" id="" name="mobile_number" value="<?php echo $mobile_number; ?>">
  </div>
  <div class="form-group">
    <label for="resume">Resume</label>
    <input type="file" class="form-control-file" name="resume_file">
    <br>
    <input type="text" class="form-control" name="resume" value="<?php echo $resume_url; ?>" placeholder="Enter your Google Drive link">
  </div>
  <button type="submit" name="update" class="btn btn-primary">Update</button>
    </div>
    </div>
</form>
</div>

<?php
// Insert the footer
require_once('../templates/footer.php');
?>
