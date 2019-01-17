<?php
  session_start();
  if (!isset($_SESSION['access_token'])) {
    if (isset($_COOKIE['access_token']) && isset($_COOKIE['username']) && isset($_COOKIE['roll_number'])) {
      $_SESSION['access_token'] = $_COOKIE['access_token'];
      $_SESSION['username'] = $_COOKIE['username'];
      $_SESSION['roll_number'] = $_COOKIE['roll_number'];
    }
  }
 ?>
