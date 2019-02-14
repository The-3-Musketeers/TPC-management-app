<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');
  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  // Connect to Database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (!$dbc) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Fetch id
  $company_id = $_GET['id'];

  $page_title = 'Company Details';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $query = "SELECT * FROM recruiters WHERE company_id='". $company_id ."'";
  $data = mysqli_query($dbc, $query);

  if(!$data){
    die("QUERY FAILED ".mysqli_error($dbc));
  }

  if(mysqli_num_rows($data) == "1"){
    $row = mysqli_fetch_assoc($data);
    $company_name = $row["company_name"];
    $company_category = $row["company_category"];
    $hr_name = $row["hr_name"];
    $hr_email = $row["hr_email"];
    $company_desc = $row["company_desc"];
    $company_status = $row["company_status"];
    ?>
    <div class="container" style="max-width: 80%; padding: 20px;">
      <div class="card">
        <div class="card-header">
          <div style="display:inline-block">
            <h4 class="card-title" ><?php echo $company_name; ?></h4>
            <h6 class="card-subtitle mb-2 text-muted">
              <?php  if($company_url) echo "(<a href='$company_url' target='_blank'>$company_url</a>)";?>
            </h6>
          </div>
        </div>
        <div class="card-body">
          <div style="display:flex;"><h5>ID:</h5> <div style="padding-left: 5px;"><?php echo $company_id; ?></div></div>
          <div style="display:flex;"><h5>Category:</h5> <div style="padding-left: 5px;"><?php echo $company_category; ?></div></div>
          <div style="display:flex;"><h5>HR Name:</h5> <div style="padding-left: 5px;"><?php echo $hr_name; ?></div></div>
          <div style="display:flex;"><h5>HR Email:</h5> <div style="padding-left: 5px;"><?php echo $hr_email; ?></div></div>
          <h5>About:</h5>
          <p><?php echo $company_desc;?></p>
        </div>
        <div class="card-footer text-muted">
          <div style="float:left;margin-bottom:4px;">
            <?php if ($company_status == "accepted"){ ?>
            <span class="badge badge-success">Accepted</span>
            <?php } elseif ($company_status == "pending") { ?>
            <span class="badge badge-warning">Pending</span>
            <?php } elseif ($company_status == "rejected") { ?>
            <span class="badge badge-danger">Rejected</span>
            <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php  }

?>

<?php require_once('../templates/footer.php');?>
