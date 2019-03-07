<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');
  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);
  $page_title = 'Company';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $no_accepted; $no_pending; $no_rejected;
  function countEntries(){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query_accepted = "SELECT * FROM recruiters WHERE company_status='accepted'";
    $data_accepted = mysqli_query($dbc, $query_accepted);
    $query_pending = "SELECT * FROM recruiters WHERE company_status='pending'";
    $data_pending = mysqli_query($dbc, $query_pending);
    $query_rejected = "SELECT * FROM recruiters WHERE company_status='rejected'";
    $data_rejected = mysqli_query($dbc, $query_rejected);
    $GLOBALS['no_accepted'] = mysqli_num_rows($data_accepted);
    $GLOBALS['no_pending'] = mysqli_num_rows($data_pending);
    $GLOBALS['no_rejected'] = mysqli_num_rows($data_rejected);
  }
  countEntries();
  $activeTab = "1";

  if(isset($_POST['approve'])){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $company_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
    $update_status_query = "UPDATE recruiters SET company_status='accepted' WHERE company_id='$company_id'";
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
  if(isset($_POST['reject'])){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $company_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
    $update_status_query = "UPDATE recruiters SET company_status='rejected' WHERE company_id='$company_id'";
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
      <a class="nav-link <?php if($activeTab==1){echo 'active';} ?>" id="accepted-tab" data-toggle="tab" href="#accepted" role="tab" aria-selected="true">
      Accepted <span class="badge badge-secondary">
        <?php if(isset($_POST['approve']) || isset($_POST['reject'])) { countEntries(); }
        echo $no_accepted;
        ?></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==2){echo 'active';} ?>" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-selected="false">
      Pending <span class="badge badge-secondary">
        <?php if(isset($_POST['approve']) || isset($_POST['reject'])) { countEntries(); }
        echo $no_pending;
        ?></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==3){echo 'active';} ?>" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-selected="false">
      Rejected <span class="badge badge-secondary">
        <?php if(isset($_POST['approve']) || isset($_POST['reject'])) { countEntries(); }
        echo $no_rejected;
        ?></span>
      </a>
    </li>
  </ul>
  <div class="tab-content" id="companiesTabContent">
    <div class="tab-pane fade <?php if($activeTab==1){echo 'show active';} ?>" id="accepted">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Category</th>
            <th scope="col">HR Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT company_id, company_name, company_category, hr_name, hr_email FROM recruiters WHERE company_status='accepted'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td><a href="./company.php?id=' . $row["company_id"] . '" target="_blank">' . $row["company_name"] . '</a></td>' .
                        '<td>' . $row["company_category"] . '</td>' .
                        '<td>' . $row["hr_name"] . '</td>' .
                        '<td>' . $row["hr_email"] . '</td>' .
                        '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=1" method="post">' .
                        '<button type="reject" class="btn btn-danger" name="reject">Reject</button></form></td>' .
                    '</tr>';
              $curr = $curr + 1;
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
            <th scope="col">Category</th>
            <th scope="col">HR Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT company_id, company_name, company_category, hr_name, hr_email FROM recruiters WHERE company_status='pending'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td><a href="./company.php?id=' . $row["company_id"] . '" target="_blank">' . $row["company_name"] . '</a></td>' .
                        '<td>' . $row["company_category"] . '</td>' .
                        '<td>' . $row["hr_name"] . '</td>' .
                        '<td>' . $row["hr_email"] . '</td>' .
                        '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=2" method="post">' . 
                        '<button type="approve" class="btn btn-success" name="approve">Approve</button> ' .
                        '<button type="reject" class="btn btn-danger" name="reject">Reject</button></form></td>' .
                    '</tr>';
              $curr = $curr + 1;
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
    <div class="tab-pane fade <?php if($activeTab==3){echo 'show active';} ?>" id="rejected" role="tabpanel">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Category</th>
            <th scope="col">HR Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT company_id, company_name, company_category, hr_name, hr_email FROM recruiters WHERE company_status='rejected'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td><a href="./company.php?id=' . $row["company_id"] . '" target="_blank">' . $row["company_name"] . '</a></td>' .
                        '<td>' . $row["company_category"] . '</td>' .
                        '<td>' . $row["hr_name"] . '</td>' .
                        '<td>' . $row["hr_email"] . '</td>' .
                        '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=3" method="post">' . 
                        '<button type="approve" class="btn btn-success" name="approve">Approve</button></form></td>' .
                    '</tr>';
              $curr = $curr + 1;
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
