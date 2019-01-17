<?php
    $auth_error='';
    if (!isset($_SESSION['access_token']) || !isset($_SESSION['roll_number']) || !isset($_SESSION['username'])) {
      $auth_error = 'User is not Logged In';
    } else if(!isset($_SESSION['auth_verified']) || $_SESSION['auth_verified']!='true'){
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $query = "SELECT access_token FROM students WHERE roll_number='" . $_SESSION['roll_number'] ."' AND username='" . $_SESSION['username'] ."'";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) == 1){
        $row = mysqli_fetch_array($data);
        if($_SESSION['access_token'] == $row['access_token']){
          $_SESSION['auth_verified'] = 'true';
        } else{
          mysqli_close($dbc);
          $auth_error = 'Invalid token';
        }
      }
      mysqli_close($dbc);
    }
    if($auth_error!=''){
      $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/studentLogin.php';
      header('Location: ' . $home_url);
    }
 ?>
