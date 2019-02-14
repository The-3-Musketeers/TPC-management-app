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

    // Connect to Database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>
<div class="container">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="current_pos" data-toggle="tab" href="#current_pos1" role="tab" aria-controls="current_pos1" aria-selected="true">Current Positions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="expired_pos" data-toggle="tab" href="#expired_pos1" role="tab" aria-controls="profile1" aria-selected="false">Expired Positions</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane active" id="current_pos1" role="tabpanel" aria-labelledby="current_pos"><?php include "viewJobs.php" ?></div>
        <div class="tab-pane" id="expired_pos1" role="tabpanel" aria-labelledby="expired_pos">NO DATA</div>
    </div>
</div>
<?php 
// Insert footer
require_once('../templates/footer.php');
?>
