<?php
    // Start the session
    require_once('../templates/startSession.php');
    require_once('../connectVars.php');

    // Authenticate user
    require_once('../templates/auth.php');
    checkUserRole('admin', $auth_error);

    $page_title = 'Dashboard';
    require_once('../templates/header.php');
    require_once('../templates/navbar.php');
    ?>
    <div class="container" style="max-width: 80%; padding: 20px;">
      <div id="stats" class="card">
        <div class="card-header">
          <div style="display:inline-block; margin-bottom: -10px;">
            <h5 class="card-title">Student Statistics</h5>
          </div>
        </div>
        <div class="card-body table-responsive">
          <div id="bar-chart-container">
            <canvas id="students"></canvas>
          </div>
        </div>
      </div>
      <div id="stats" class="card">
        <div class="card-header">
          <div style="display:inline-block; margin-bottom: -10px;">
            <h5 class="card-title">Company Statistics</h5>
          </div>
        </div>
        <div class="card-body table-responsive">
          <div class="pie-chart-container">
            <canvas id="company-category"></canvas>
          </div>
        </div>
      </div>
    </div>
<?php require_once('../templates/footer.php');?>
