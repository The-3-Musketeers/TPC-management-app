<?php
    // Start the session
    require_once('../templates/startSession.php');
    // Database connection variables
    require_once('../connectVars.php');

    // Authenticate user
    require_once('../templates/auth.php');
    checkUserRole('recruiter', $auth_error);

    //Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $company_id=$_SESSION['company_id'];

    $page_title = 'Profile';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php'); 

    //Fetching info from student_data table
    $company_name;$company_desc;$company_category;$company_url;$hr_name_1;$hr_email_1;$company_img;
    function display(){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        global $company_id;
        $query="SELECT * FROM recruiters WHERE company_id='{$company_id}'";
        $select_from_recruiters_query=mysqli_query($dbc,$query);
        if(!$select_from_recruiters_query){
            die("QUERY FAILED ".mysqli_error($dbc));
        }

        global $company_name,$company_desc,$company_category,$company_url,$hr_name_1,$hr_designation_1,$hr_email_1,$hr_name_2,$hr_designation_2,$hr_email_2,$hr_name_3,$hr_designation_3,$hr_email_3,$company_img;
        $row=mysqli_fetch_assoc($select_from_recruiters_query);
        $company_name=$row['company_name'];
        $company_desc=$row['company_desc'];
        $company_category=$row['company_category'];
        $company_url=$row['company_url'];
        $hr_name_1=$row['hr_name_1'];
        $hr_designation_1=$row['hr_designation_1'];
        $hr_email_1=$row['hr_email_1'];
        $hr_name_2=$row['hr_name_2'];
        $hr_designation_2=$row['hr_designation_2'];
        $hr_email_2=$row['hr_email_2'];
        $hr_name_3=$row['hr_name_3'];
        $hr_designation_3=$row['hr_designation_3'];
        $hr_email_3=$row['hr_email_3'];
        $company_img=$row['company_img'];
        if($company_img!==null && $company_img!==''){
            $company_img='/TPC-management-app/images/recruiters/'.$row['company_img'];
        }
        else{
            $company_img='/TPC-management-app/pictures/company_icon.png';
        }
    }
    display();
    ?>

<?php
//Updating Profile
if(isset($_POST['update'])){

    $company_name=mysqli_real_escape_string($dbc,trim($_POST['company_name']));
    $company_category=mysqli_real_escape_string($dbc,trim($_POST['company_category']));
    $company_url=mysqli_real_escape_string($dbc,trim($_POST['company_url']));
    $hr_name_1=mysqli_real_escape_string($dbc,trim($_POST['hr_name_1']));
    $hr_designation_1=mysqli_real_escape_string($dbc,trim($_POST['hr_designation_1']));
    $hr_email_1=mysqli_real_escape_string($dbc,trim($_POST['hr_email_1']));
    $hr_name_2=mysqli_real_escape_string($dbc,trim($_POST['hr_name_2']));
    $hr_designation_2=mysqli_real_escape_string($dbc,trim($_POST['hr_designation_2']));
    $hr_email_2=mysqli_real_escape_string($dbc,trim($_POST['hr_email_2']));
    $hr_name_3=mysqli_real_escape_string($dbc,trim($_POST['hr_name_3']));
    $hr_designation_3=mysqli_real_escape_string($dbc,trim($_POST['hr_designation_3']));
    $hr_email_3=mysqli_real_escape_string($dbc,trim($_POST['hr_email_3']));
    $company_desc=mysqli_real_escape_string($dbc,trim($_POST['company_desc']));
    
    $is_correct = TRUE;

    if($hr_name_3 != "" || $hr_designation_3 != "" || $hr_email_3 != ""){
        $is_correct = FALSE;
        if($hr_name_3 == "" || $hr_designation_3 == "" || $hr_email_3 == ""){
            // Alert Warning : Write all fields for third HR
            echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
            'Please enter all fields for the 3rd HR.' .
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
            '<span aria-hidden="true">&times;</span></button></div></div>';
        } else {
            $is_correct = TRUE;
        }
    }
    if($is_correct){
        $company_img_name=$_FILES['company_img']['name'];
        $company_img_tmp_name=$_FILES['company_img']['tmp_name'];
        $query="UPDATE recruiters SET company_name='$company_name', company_category='$company_category',company_url='$company_url',
            hr_name_1='$hr_name_1',hr_designation_1='$hr_designation_1',hr_email_1='$hr_email_1',
            hr_name_2='$hr_name_2',hr_designation_2='$hr_designation_2',hr_email_2='$hr_email_2',
            hr_name_3='$hr_name_3',hr_designation_3='$hr_designation_3',hr_email_3='$hr_email_3',
            company_desc='$company_desc' ";
        if($company_img_name !=='' && $company_img_name !==null){
            $company_img_name=time()."_".$company_img_name;
            move_uploaded_file($company_img_tmp_name,"../images/recruiters/$company_img_name");
            $query=$query.",company_img='$company_img_name'";
        }
        $query=$query." WHERE company_id='$company_id'";
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
}
?>

<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-3">
                <img src="<?php echo $company_img; ?>" width="250px" height="250px" alt="...">
                <input type="file" name="company_img" value="">
            </div>
            <div class="col-sm-9">
                <div class="form-group">
                    <label for="company_id">Company Id<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="company_id" value="<?php echo $company_id; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="company_name">Company Name<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="company_name" value="<?php echo $company_name; ?>" required>
                </div>
                <div class="form-group">
                    <label for="company_category">Company Category<span class="red">*</span></label>
                    <select name="company_category" id="course">
                    <?php
                        echo "<option value='{$company_category}'>{$company_category}</option>";
                        if($company_category=='A1'){
                            echo '<option value="B1">B1</option>';
                            echo '<option value="B2">B2</option>';
                        }
                        else if($company_category=='B1'){
                            echo '<option value="A1">A1</option>';
                            echo '<option value="B2">B2</option>';
                        }
                        else{
                            echo '<option value="A1">A1</option>';
                            echo '<option value="B2">B2</option>';
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comapny_url">Company Website Link<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="company_url" value="<?php echo $company_url; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_name_1">1st HR Name<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="hr_name_1" value="<?php echo $hr_name_1; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_designation_1">1st HR Designation<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="hr_designation_1" value="<?php echo $hr_designation_1; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_email_1">1st HR Email<span class="red">*</span></label>
                    <input type="email" class="form-control" name="hr_email_1" value="<?php echo $hr_email_1; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_name_2">2nd HR Name<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="hr_name_2" value="<?php echo $hr_name_2; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_designation_2">2nd HR Designation<span class="red">*</span></label>
                    <input type="text" class="form-control" id="" name="hr_designation_2" value="<?php echo $hr_designation_2; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_email_2">2nd HR Email<span class="red">*</span></label>
                    <input type="email" class="form-control" name="hr_email_2" value="<?php echo $hr_email_2; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hr_name_3">3rd HR Name</label>
                    <input type="text" class="form-control" id="" name="hr_name_3" value="<?php echo $hr_name_3; ?>">
                </div>
                <div class="form-group">
                    <label for="hr_designation_3">3rd HR Designation</label>
                    <input type="text" class="form-control" id="" name="hr_designation_3" value="<?php echo $hr_designation_3; ?>">
                </div>
                <div class="form-group">
                    <label for="hr_email_3">3rd HR Email</label>
                    <input type="email" class="form-control" name="hr_email_3" value="<?php echo $hr_email_3; ?>">
                </div>
                <div class="form-group">
                    <label for="company_desc">Company Description<span class="red">*</span></label>
                    <textarea class="form-control" id="company_desc" rows="3" name="company_desc" value="" required><?php echo $company_desc; ?></textarea>
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
