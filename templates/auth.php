<?php
    $auth_error='';
    if (!isset($_SESSION['access_token'])) {
      $auth_error = 'User is not Logged In';
    } else if(!isset($_SESSION['auth_verified']) || $_SESSION['auth_verified']!='true'){
      if(isset($_SESSION['username'])){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT access_token, user_role FROM students WHERE username='" . $_SESSION['username'] ."'";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) == 1){
          $row = mysqli_fetch_array($data);
          if($_SESSION['access_token'] == $row['access_token']){
            $_SESSION['auth_verified'] = 'true';
            $_SESSION['user_role'] = $row['user_role'];
          } else{
            mysqli_close($dbc);
            $auth_error = 'Invalid token';
          }
        }
        mysqli_close($dbc);
      } else if(isset($_SESSION['company_name'])){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT access_token FROM recruiters WHERE company_name='" . $_SESSION['company_name'] ."'";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) == 1){
          $row = mysqli_fetch_array($data);
          if($_SESSION['access_token'] == $row['access_token']){
            $_SESSION['auth_verified'] = 'true';
            $_SESSION['user_role'] = 'recruiter';
          } else{
            mysqli_close($dbc);
            $auth_error = 'Invalid token';
          }
        }
        mysqli_close($dbc);
      } else{
        $auth_error = 'Invalid user';
      }

    }
    function checkUserRole($userRole, $auth_error){
      if($auth_error!='' || $_SESSION['user_role']!=$userRole){
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] .'/TPC-management-app/logout.php';
        header('Location: ' . $home_url);
      }
    }
 ?>
