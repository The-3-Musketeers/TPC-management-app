<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- If IE use the latest rendering engine -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Set the page to the width of the device and set the zoon level -->
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/TPC-management-app/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <?php if(isset($_SESSION['username']) && $_SESSION['user_role']=='admin'){ ?>
      <link rel="stylesheet" href="/TPC-management-app/css/viewJobs.css">
    <?php } ?>
    
    <?php
      echo '<title>' . $page_title . ' - T&amp;P IIT Patna</title>';
    ?>
</head>
<body>
