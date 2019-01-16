<?php
    // Start the session
    require_once('templates/startSession.php');
    // Database connection variables
    require_once('connectVars.php');

    // Insert the page header and navbar
    $page_title = 'Profile';
    require_once('templates/header.php');
    require_once('templates/navbar.php');

    //Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<div class="container">
<div class="col-sm-3">
    <img src="./images/batman.jpg" width="250px" height="250px" alt="...">
</div>
<div class="col-sm-9">
<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="roll-number">Roll No.</label>
    <input type="text" class="form-control" id="" name="roll-number" value="">
  </div>
  <div class="form-group">
    <label for="username">Full Name</label>
    <input type="text" class="form-control" id="" name="username" value="">
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
    <input type="text" class="form-control" id="" name="cpi" value="">
  </div>
  <div class="form-group">
    <label for="resume">Resume</label>
    <input type="file" name="resume" value="">
  </div>
  <button type="submit" name="update" class="btn btn-primary">Update</button>
</form>
</div>
</div>