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

?>

<div class="container">
  <ul class="nav nav-tabs" id="companiesTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#accepted" role="tab" aria-controls="home" aria-selected="true">Accepted</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="profile" aria-selected="false">Pending</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="contact" aria-selected="false">Rejected</a>
    </li>
  </ul>
  <div class="tab-content" id="companiesTabContent">
    <div class="tab-pane fade show active" id="accepted">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Category</th>
            <th scope="col">HR Name</th>
            <th scope="col">Email</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT company_name, company_category, hr_name, hr_email FROM recruiters WHERE company_status='accepted'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td>' . $row["company_name"] . '</td>' .
                        '<td>' . $row["company_category"] . '</td>' .
                        '<td>' . $row["hr_name"] . '</td>' .
                        '<td>' . $row["hr_email"] . '</td>' .
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
    <div class="tab-pane fade" id="pending" role="tabpanel">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Category</th>
            <th scope="col">HR Name</th>
            <th scope="col">Email</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT company_name, company_category, hr_name, hr_email FROM recruiters WHERE company_status='pending'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td>' . $row["company_name"] . '</td>' .
                        '<td>' . $row["company_category"] . '</td>' .
                        '<td>' . $row["hr_name"] . '</td>' .
                        '<td>' . $row["hr_email"] . '</td>' .
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
    <div class="tab-pane fade" id="rejected" role="tabpanel">
      <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Category</th>
            <th scope="col">HR Name</th>
            <th scope="col">Email</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT company_name, company_category, hr_name, hr_email FROM recruiters WHERE company_status='rejected'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              echo '<tr><th scope="row">' . $curr . '</th>' .
                        '<td>' . $row["company_name"] . '</td>' .
                        '<td>' . $row["company_category"] . '</td>' .
                        '<td>' . $row["hr_name"] . '</td>' .
                        '<td>' . $row["hr_email"] . '</td>' .
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
