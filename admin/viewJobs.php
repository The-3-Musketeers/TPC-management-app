<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  $page_title = 'Jobs';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $activeTab = "1";

  if(isset($_POST['show'])){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $job_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
    $update_status_query = "UPDATE positions SET job_status='shown' WHERE job_id='$job_id'";
    $update_status = mysqli_query($dbc, $update_status_query);
    if(!$update_status){
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
        'Failed to update. Please try again.' . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
        '<span aria-hidden="true">&times;</span></button></div></div>';
      die("QUERY FAILED ".mysqli_error($dbc));
    } else {
      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
          'Successfully Updated.<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
    }
    $activeTab = $_GET['tab'];
  }

  if(isset($_POST['hide'])){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $job_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
    $update_status_query = "UPDATE positions SET job_status='hidden' WHERE job_id='$job_id'";
    $update_status = mysqli_query($dbc, $update_status_query);
    if(!$update_status){
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
        'Failed to update. Please try again.' . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
        '<span aria-hidden="true">&times;</span></button></div></div>';
      die("QUERY FAILED ".mysqli_error($dbc));
    } else {
      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
          'Successfully Updated.<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
    }
    $activeTab = $_GET['tab'];
  }
?>

<div class="container">
  <ul class="nav nav-tabs" id="companiesTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==1){echo 'active';} ?>" id="home-tab" data-toggle="tab" href="#shown" role="tab" aria-selected="true">Shown</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==2){echo 'active';} ?>" id="profile-tab" data-toggle="tab" href="#pending" role="tab" aria-selected="false">Pending</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==3){echo 'active';} ?>" id="contact-tab" data-toggle="tab" href="#hidden" role="tab" aria-selected="false">Hidden</a>
    </li>
  </ul>
  <div class="tab-content" id="companiesTabContent">
    <div class="tab-pane fade <?php if($activeTab==1){echo 'show active';} ?>" id="shown">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Job Name</th>
            <th scope="col">Course</th>
            <th scope="col">Branch</th>
            <th scope="col">Min. CPI</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT job_id, job_position, course, branch, min_cpi, company_id FROM positions WHERE job_status='shown'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['company_id'];
              $query_recruiters = "SELECT company_name, company_status FROM recruiters WHERE company_id='$id'";
              $data_recruiters = mysqli_query($dbc, $query_recruiters);
              $row_recruiters = mysqli_fetch_array($data_recruiters);
              if($row_recruiters["company_status"] == "accepted"){
                echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td>' . $row_recruiters["company_name"] . '</td>' .
                        '<td>' . $row["job_position"] . '</td>' .
                        '<td>' . $row["course"] . '</td>' .
                        '<td>' . $row["branch"] . '</td>' .
                        '<td>' . $row["min_cpi"] . '</td>' .
                        '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=1" method="post">' .
                        '<button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>' .
                    '</tr>';
                $curr = $curr + 1;
              }
            }
          ?>
        </tbody>
        <?php } else { ?>
          <tr>
            <td>No data</td>
          </tr>
        <?php } ?>
      </table>
    </div>
    <div class="tab-pane fade <?php if($activeTab==2){echo 'show active';} ?>" id="pending" role="tabpanel">
    <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Job Name</th>
            <th scope="col">Course</th>
            <th scope="col">Branch</th>
            <th scope="col">Min. CPI</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT job_id, job_position, course, branch, min_cpi, company_id FROM positions WHERE job_status='pending'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['company_id'];
              $query_recruiters = "SELECT company_name, company_status FROM recruiters WHERE company_id='$id'";
              $data_recruiters = mysqli_query($dbc, $query_recruiters);
              if(!$data_recruiters){
                die("QUERY FAILED ".mysqli_error($dbc));
              }
              $row_recruiters = mysqli_fetch_array($data_recruiters);
              if($row_recruiters["company_status"] == "accepted"){
                echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td>' . $row_recruiters["company_name"] . '</td>' .
                        '<td>' . $row["job_position"] . '</td>' .
                        '<td>' . $row["course"] . '</td>' .
                        '<td>' . $row["branch"] . '</td>' .
                        '<td>' . $row["min_cpi"] . '</td>' .
                        '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=2" method="post">' .
                        '<button type="show" class="btn btn-success" name="show">Show</button> ' .
                        '<button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>' .
                    '</tr>';
                $curr = $curr + 1;
              }
            }
          ?>
        </tbody>
          <?php
              if($curr == 1){
                echo "<tr><td>No data</td></tr>";
              }
            } else { ?>
          <tr>
            <td>No data</td>
          </tr>
        <?php } ?>
      </table>
    </div>
    <div class="tab-pane fade <?php if($activeTab==3){echo 'show active';} ?>" id="hidden" role="tabpanel">
    <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Job Name</th>
            <th scope="col">Course</th>
            <th scope="col">Branch</th>
            <th scope="col">Min. CPI</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT job_id, job_position, course, branch, min_cpi, company_id FROM positions WHERE job_status='hidden'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['company_id'];
              $query_recruiters = "SELECT company_name, company_status FROM recruiters WHERE company_id='$id'";
              $data_recruiters = mysqli_query($dbc, $query_recruiters);
              $row_recruiters = mysqli_fetch_array($data_recruiters);
              if($row_recruiters["company_status"] == "accepted"){
                echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td>' . $row_recruiters["company_name"] . '</td>' .
                        '<td>' . $row["job_position"] . '</td>' .
                        '<td>' . $row["course"] . '</td>' .
                        '<td>' . $row["branch"] . '</td>' .
                        '<td>' . $row["min_cpi"] . '</td>' .
                        '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=3" method="post">' .
                        '<button type="show" class="btn btn-success" name="show">Show</button></form></td>' .
                      '</tr>';
                $curr = $curr + 1;
              }
            }
          ?>
        </tbody>
        <?php } else { ?>
          <tr>
            <td>No data</td>
          </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>

<?php require_once('../templates/footer.php');?>
