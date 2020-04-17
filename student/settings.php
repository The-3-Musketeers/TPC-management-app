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

    $page_title = 'Settings';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');
    
    if(isset($_POST['change_password'])){
        $current_password = mysqli_real_escape_string($dbc,trim($_POST['current_password']));
        $new_password = mysqli_real_escape_string($dbc,trim($_POST['new_password']));
        $verify_password = mysqli_real_escape_string($dbc,trim($_POST['verify_password']));
    
        $query = "SELECT username FROM students WHERE roll_number='$roll_number' && password=SHA('$current_password')";
        $confirm_admin_query = mysqli_query($dbc, $query);
        if(mysqli_num_rows($confirm_admin_query) == "1"){
          if($new_password != $verify_password){
            // Alert Warning : Password does not match
            echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
                  'Password does not match' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
          }else{
          $query = "UPDATE students SET password=SHA('$new_password') WHERE roll_number='$roll_number'";
          $update_query = mysqli_query($dbc, $query);
          if(!$update_query){
            die("QUERY FAILED ".mysqli_error($dbc));
          }
          // Alert Success : Password updated
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'Password updated' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
          }
        } else {
          // Alert Warning : Incorrect current password
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'Incorrect Current passsword' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }
    }

?>

<div class="container">
    <div class="card">
        <div class="card-header">
        <strong>Security</strong>
        </div>
        <div class="card-body">
        <div style="display:flex;">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom:0">
                <label for="password" style="margin-bottom:0"><h5>Change Password</h5></label>
                <input type="password" class="form-control" id="" name="current_password" placeholder="Current Password" style="margin-bottom:10px" required>
                <input type="password" class="form-control" id="" name="new_password" placeholder="New Password" style="margin-bottom:10px" required>
                <input type="password" class="form-control" id="" name="verify_password" placeholder="Verify Password" style="margin-bottom:10px" required>
                <button type="submit" name="change_password" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>

<?php
// Insert the footer
require_once('../templates/footer.php');
?>
