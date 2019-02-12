<?php
    // Start the session
    require_once('../templates/startSession.php');
    require_once('../connectVars.php');

    // Authenticate user
    require_once('../templates/auth.php');
    checkUserRole('student', $auth_error);

    $page_title = 'Jobs';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    ?>
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="new_openings" data-toggle="tab" href="#new_openings1" role="tab" aria-controls="new_openings1" aria-selected="true">New Openings</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="my_applications" data-toggle="tab" href="#my_applications1" role="tab" aria-controls="profile1" aria-selected="false">My Applications</a>
        </li>
        </ul>
        <div class="tab-content" id="myTabContent">
        <div class="tab-pane active" id="new_openings1" role="tabpanel" aria-labelledby="new_openings"><?php include "newOpenings.php" ?></div>
        <div class="tab-pane" id="my_applications1" role="tabpanel" aria-labelledby="my_applications"><?php include "myApplications.php" ?></div>
        </div>
    </div>

<?php require_once('../templates/footer.php');?>
