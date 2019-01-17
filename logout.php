<?php
  session_start();
  if(isset($_SESSION['access_token'])){
    $_SESSION = array();
    if(isset($_COOKIE[session_name()])){
      setcookie(session_name(),'', time() - 3600);
    }
    session_destroy();
  }

  setcookie('access_token', '', time() - 3600);
  setcookie('username', '', time() - 3600);
  setcookie('roll_number', '', time() - 3600);

  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
  header('Location: ' . $home_url);
?>
