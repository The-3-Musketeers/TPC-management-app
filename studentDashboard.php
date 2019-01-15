<?php
    // Start the session
    require_once('templates/startSession.php');

    if (!isset($_SESSION['user_id'])) {
      echo '<p class="login">Please <a href="studentLogin.php">log in</a> to access this page.</p>';
      exit();
    }

    $page_title = 'Dashboard';
    require_once('templates/header.php');
    require_once('templates/navbar.php');
    echo '<p>You are logged in as ' . $_SESSION['username'] . '</p>';
    ?>



<?php require_once('templates/footer.php');?>
