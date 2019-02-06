<?php
    // Start the session
    require_once('../templates/startSession.php');
    require_once('../connectVars.php');

    // Authenticate user
    require_once('../templates/auth.php');
    checkUserRole('student', $auth_error);

    $page_title = 'Student Dashboard';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');
    echo '<p>You are logged in as ' . $_SESSION['username'] . '</p>';
    ?>



<?php require_once('../templates/footer.php');?>
