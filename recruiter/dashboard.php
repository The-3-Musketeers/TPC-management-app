<?php
    // Start the session
    require_once('../templates/startSession.php');
    require_once('../connectVars.php');

    // Authenticate user
    require_once('../templates/auth.php');
    checkUserRole('recruiter', $auth_error);

    $page_title = 'Recruiter Dashboard';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');
    echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
            'You are logged in as ' . $_SESSION['company_name'] . '.' .
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
            '<span aria-hidden="true">&times;</span></button></div></div>';
    ?>

<?php require_once('../templates/footer.php');?>
