<?php
  session_start();
  if(!isset($_SESSION['access_token'])) {
    if(isset($_COOKIE['access_token'])) $_SESSION['access_token'] = $_COOKIE['access_token'];
    if(isset($_COOKIE['username'])) $_SESSION['username'] = $_COOKIE['username'];
    if(isset($_COOKIE['roll_number'])) $_SESSION['roll_number'] = $_COOKIE['roll_number'];
    if(isset($_COOKIE['company_name'])) $_SESSION['company_name'] = $_COOKIE['company_name'];
    if(isset($_COOKIE['company_id'])) $_SESSION['company_id'] = $_COOKIE['company_id'];
  }
 ?>
