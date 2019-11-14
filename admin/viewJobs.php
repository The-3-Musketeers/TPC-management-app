<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  if(!isset($_GET['search']) && !isset($_GET['id']))
  {
    $_SESSION["keyword"]=null;
  }

  $page_title = 'Jobs';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $no_shown; $no_pending; $no_hidden;
  function countEntries(){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query_shown = "SELECT * FROM jobs INNER JOIN recruiters_data ON jobs.company_id=recruiters_data.company_id WHERE jobs.job_status='shown' AND recruiters_data.company_status='accepted'";
    $data_shown = mysqli_query($dbc, $query_shown);
    $query_pending = "SELECT * FROM jobs INNER JOIN recruiters_data ON jobs.company_id=recruiters_data.company_id WHERE jobs.job_status='pending' AND recruiters_data.company_status='accepted'";
    $data_pending = mysqli_query($dbc, $query_pending);
    $query_hidden = "SELECT * FROM jobs INNER JOIN recruiters_data ON jobs.company_id=recruiters_data.company_id WHERE jobs.job_status='hidden' AND recruiters_data.company_status='accepted'";
    $data_hidden = mysqli_query($dbc, $query_hidden);

    $GLOBALS['no_shown'] = mysqli_num_rows($data_shown);
    $GLOBALS['no_pending'] = mysqli_num_rows($data_pending);
    $GLOBALS['no_hidden'] = mysqli_num_rows($data_hidden);
  }
  countEntries();
  $activeTab = "1";

  if(isset($_POST['show'])){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }
    $job_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
    $update_status_query = "UPDATE jobs SET job_status='shown' WHERE job_id='$job_id'";
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
    $update_status_query = "UPDATE jobs SET job_status='hidden' WHERE job_id='$job_id'";
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
  if(!isset($_POST['search'])){
    $keyword='';
  }else {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $_SESSION["keyword"]=mysqli_real_escape_string($dbc, trim($_POST['keyword']));
  }
?>
<div class="container">
  <!-- <form action="<?php //echo $_SERVER['PHP_SELF'] . '?search=""';?>" method="post">
    <div class="input-group mb-3">
      <input type="text" class="form-control" name="keyword" placeholder="Type Keyword" value="<?php //if(isset($_SESSION["keyword"])) echo $_SESSION["keyword"] ?>">
      <div class="input-group-append">
        <button name="search" class="btn btn-primary" type="submit">Search</button>
      </div>
    </div>
  </form> -->
    <?php
    // Search Bar
    if(isset($_POST['search']) || isset($_SESSION['keyword'])){
      ?>
      <table class="table">
      <thead class="thead-light">
        <tr>
          <th scope="col">S.No.</th>
          <th scope="col">Company Name</th>
          <th scope="col">Job Name</th>
          <th scope="col">Degree</th>
          <th scope="col">Branch</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $keyword=$_SESSION['keyword'];
      $keywords=explode(" ",$keyword);
      $branches=["cs", "ee", "me", "ce", "cb"];
      $branch=[];// storing the branches in the keyword
      $rem_words="";
      foreach($keywords as $key){
        if(in_array(strtolower($key),$branches)){
          array_push($branch,strtoupper($key));
        }
        else {
          $rem_words.=$key." ";
        }
      }
      if($rem_words!=""){
        // Finding intersection of remaining words
        $query="SELECT * FROM jobs WHERE MATCH (company_name) AGAINST ('$rem_words') AND MATCH (job_position) AGAINST ('$rem_words')";
        $search_query=mysqli_query($dbc,$query);
        if(!$search_query){
          die("error ".mysqli_error($dbc));
        }
        $num=mysqli_num_rows($search_query);
        if($num!=0){
          add_row($search_query,$branch);
        }
        else{
          // Finding union of remaining words
          $query="SELECT * FROM jobs WHERE MATCH (company_name) AGAINST ('$rem_words') OR MATCH (job_position) AGAINST ('$rem_words')";
          $search_query=mysqli_query($dbc,$query);
          if(!$search_query){
            die("error ".mysqli_error($dbc));
          }
          $num=mysqli_num_rows($search_query);
          if($num!=0){
            add_row($search_query,$branch);
          }
          else{
            echo "No result Found";
          }
        }
      }
      else{
        // Only branch is present in keyword
        $query="SELECT * FROM jobs";
        $search_query=mysqli_query($dbc,$query);
        add_row($search_query,$branch);
      }
    ?>
     </tbody>
    </table>
  <?php }?>
  <?php
  function add_row($search_query,$B){
    $i=1;
    while($row=mysqli_fetch_assoc($search_query)){
      $id=$row['company_id'];
      $company_name=$row['company_name'];
      $job_position=$row['job_position'];

      $degree_query="SELECT degree.degree_name AS degree_name FROM degree, degree_branch WHERE degree_branch.job_id='$job_id' AND degree.degree_id=degree_branch.degree_id";
      $get_all_degree=mysqli_query($dbc,$degree_query);
      $degree_row=mysqli_fetch_assoc($get_all_degree);
      $degree = $degree_row['degree_name'];
      while($degree_row=mysqli_fetch_assoc($get_all_degree)){
        $degree = $degree . ', ' . $degree_row['degree_name'];
      }

      $branch=$row['branch'];
      $status=$row['job_status'];
      $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';
      if(!empty($B)){
        $db_branch=explode(",",$row['branch']);
        if(!empty(array_intersect($db_branch,$B))){
          ?>
          <tr>
            <th scope="row"><?php echo $i;?></th>
            <td><a href="<?php echo './company.php?id=' . $id;?>"><?php echo $company_name; ?></a></td>
            <td><a href="<?php echo $job_url.'?id='.$row["job_id"]; ?>"><?php echo $row["job_position"]; ?></a></td>
            <td><?php echo $degree;?></td>
            <td> <?php echo $branch; ?> </td>
            <?php
            if($status=='shown')
              echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=1 method="post">'.
              '<button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>';
            else if($status=='pending')
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=2 method="post">'.
            '<button type="show" class="btn btn-success" name="show">Show</button>'.
            '<button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>';
            else
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=3 method="post">'.
            '<button type="show" class="btn btn-success" name="show">Show</button></form></td>';
            ?>
          </tr>
        <?php
          $i++;
        }
      }else { ?>
        <tr>
          <th scope="row"><?php echo $i;?></th>
          <td><a href="<?php echo './company.php?id=' . $id;?>"><?php echo $company_name; ?></a></td>
          <td><a href="<?php echo $job_url.'?id='.$row["job_id"]; ?>"><?php echo $row["job_position"]; ?></a></td>
          <td><?php echo $degree;?></td>
          <td> <?php echo $branch; ?> </td>
          <?php
          if($status=='shown')
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=1 method="post">'.
            '<button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>';
          else if($status=='pending')
           echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=2 method="post">'.
           '<button type="show" class="btn btn-success" name="show">Show</button>'.
           '<button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>';
          else
           echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=3 method="post">'.
           '<button type="show" class="btn btn-success" name="show">Show</button></form></td>';
          ?>
        </tr>
      <?php
        $i++;
      }
    }
  }
  ?>
  <?php if(!isset($_POST['search']) && !isset($_SESSION['keyword'])) { ?>
  <ul class="nav nav-tabs" id="companiesTab" role="tablist">
  <li class="nav-item">
      <a class="nav-link <?php if($activeTab==1){echo 'active';} ?>" id="shown-tab" data-toggle="tab" href="#shown" role="tab" aria-selected="true">
      Shown <span class="badge badge-secondary">
        <?php if(isset($_POST['show']) || isset($_POST['hide'])) { countEntries(); }
        echo $no_shown;
        ?></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==2){echo 'active';} ?>" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-selected="false">
      Pending <span class="badge badge-secondary">
        <?php if(isset($_POST['show']) || isset($_POST['hide'])) { countEntries(); }
        echo $no_pending;
        ?></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php if($activeTab==3){echo 'active';} ?>" id="hidden-tab" data-toggle="tab" href="#hidden" role="tab" aria-selected="false">
      Hidden <span class="badge badge-secondary">
        <?php if(isset($_POST['show']) || isset($_POST['hide'])) { countEntries(); }
        echo $no_hidden;
        ?></span>
      </a>
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
            <th scope="col">Degree</th>
            <th scope="col">Branch</th>
            <th scope="col">Min. CPI</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT job_id, job_position, min_cpi, company_id FROM jobs WHERE job_status='shown'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['company_id'];
              $query_recruiters = "SELECT company_name, company_status FROM recruiters_data WHERE company_id='$id'";
              $data_recruiters = mysqli_query($dbc, $query_recruiters);
              $row_recruiters = mysqli_fetch_array($data_recruiters);
              if($row_recruiters["company_status"] == "accepted"){
                $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';
                
                // Get all eligible degrees
                $degree_query = "SELECT DISTINCT degree.degree_name AS degree_name FROM degree, degree_branch, jobs_db "
                                ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                                ."AND degree_branch.degree_id = degree.degree_id";
                $get_all_degree=mysqli_query($dbc,$degree_query);
                $degree_row=mysqli_fetch_assoc($get_all_degree);
                $degree = $degree_row['degree_name'];
                while($degree_row=mysqli_fetch_assoc($get_all_degree)){
                $degree = $degree . ', ' . $degree_row['degree_name'];
                }

                // Get all eligible branches
                $branch_query = "SELECT DISTINCT branch.branch_name AS branch_name FROM branch, degree_branch, jobs_db "
                                ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                                ."AND branch.branch_id = degree_branch.branch_id";
                $get_all_branch=mysqli_query($dbc,$branch_query);
                $branch_row=mysqli_fetch_assoc($get_all_branch);
                $branch = $branch_row['branch_name'];
                while($branch_row=mysqli_fetch_assoc($get_all_branch)){
                $branch = $branch . ', ' . $branch_row['branch_name'];
                }

            ?>
                <tr>
                  <th scope="row"><?php echo $curr;?></th>
                  <td><a href="<?php echo './company.php?id=' . $id;?>"><?php echo $row_recruiters["company_name"]; ?></a></td>
                  <td><a href="<?php echo $job_url.'?id='.$row["job_id"]; ?>"><?php echo $row["job_position"]; ?></a></td>
                  <td><?php echo $degree;?></td>
                  <td> <?php echo $branch; ?> </td>
                  <td><?php echo $row["min_cpi"]; ?>  </td>
                  <td><form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row['job_id'] . '&tab=1';?>" method="post">
                  <button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>
                </tr>
                <?php $curr = $curr + 1;
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
    <div class="tab-pane fade <?php if($activeTab==2){echo 'show active';} ?>" id="pending" role="tabpanel">
    <table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Company Name</th>
            <th scope="col">Job Name</th>
            <th scope="col">Degree</th>
            <th scope="col">Branch</th>
            <th scope="col">Min. CPI</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT job_id, job_position, min_cpi, company_id FROM jobs WHERE job_status='pending'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['company_id'];
              $query_recruiters = "SELECT company_name, company_status FROM recruiters_data WHERE company_id='$id'";
              $data_recruiters = mysqli_query($dbc, $query_recruiters);
              if(!$data_recruiters){
                die("QUERY FAILED ".mysqli_error($dbc));
              }
              $row_recruiters = mysqli_fetch_array($data_recruiters);
              if($row_recruiters["company_status"] == "accepted"){
                $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';

                // Get all eligible degrees
                $degree_query = "SELECT DISTINCT degree.degree_name AS degree_name FROM degree, degree_branch, jobs_db "
                                ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                                ."AND degree_branch.degree_id = degree.degree_id";
                $get_all_degree=mysqli_query($dbc,$degree_query);
                $degree_row=mysqli_fetch_assoc($get_all_degree);
                $degree = $degree_row['degree_name'];
                while($degree_row=mysqli_fetch_assoc($get_all_degree)){
                  $degree = $degree . ', ' . $degree_row['degree_name'];
                }

                // Get all eligible branches
                $branch_query = "SELECT DISTINCT branch.branch_name AS branch_name FROM branch, degree_branch, jobs_db "
                                ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                                ."AND branch.branch_id = degree_branch.branch_id";
                $get_all_branch=mysqli_query($dbc,$branch_query);
                $branch_row=mysqli_fetch_assoc($get_all_branch);
                $branch = $branch_row['branch_name'];
                while($branch_row=mysqli_fetch_assoc($get_all_branch)){
                  $branch = $branch . ', ' . $branch_row['branch_name'];
                }
            ?>
                <tr>
                  <th scope="row"><?php  echo $curr; ?></th>
                  <td><a href="<?php echo './company.php?id=' . $id;?>"><?php echo $row_recruiters["company_name"];?></a></td>
                  <td><a href="<?php echo $job_url . '?id=' . $row["job_id"];?>"><?php echo $row["job_position"];?></a></td>
                  <td><?php echo $degree;?></td>
                  <td><?php echo $branch;?></td>
                  <td><?php echo $row["min_cpi"];?></td>
                  <td><form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=2';?>" method="post">
                  <button type="show" class="btn btn-success" name="show">Show</button>
                  <button type="hide" class="btn btn-danger" name="hide">Hide</button></form></td>
                </tr>
                <?php $curr = $curr + 1;
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
            <th scope="col">Degree</th>
            <th scope="col">Branch</th>
            <th scope="col">Min. CPI</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <?php
          $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          $query = "SELECT job_id, job_position, min_cpi, company_id FROM jobs WHERE job_status='hidden'";
          $data = mysqli_query($dbc, $query);
          if(mysqli_num_rows($data) != 0){
        ?>
        <tbody>
          <?php
            $curr = 1;
            while($row = mysqli_fetch_array($data)){
              $id = $row['company_id'];
              $query_recruiters = "SELECT company_name, company_status FROM recruiters_data WHERE company_id='$id'";
              $data_recruiters = mysqli_query($dbc, $query_recruiters);
              $row_recruiters = mysqli_fetch_array($data_recruiters);
              if($row_recruiters["company_status"] == "accepted"){
                $job_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../job.php';

                // Get all eligible degrees
                $degree_query = "SELECT DISTINCT degree.degree_name AS degree_name FROM degree, degree_branch, jobs_db "
                                ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                                ."AND degree_branch.degree_id = degree.degree_id";
                $get_all_degree=mysqli_query($dbc,$degree_query);
                $degree_row=mysqli_fetch_assoc($get_all_degree);
                $degree = $degree_row['degree_name'];
                while($degree_row=mysqli_fetch_assoc($get_all_degree)){
                $degree = $degree . ', ' . $degree_row['degree_name'];
                }

                // Get all eligible branches
                $branch_query = "SELECT DISTINCT branch.branch_name AS branch_name FROM branch, degree_branch, jobs_db "
                                ."WHERE jobs_db.job_id = '$job_id' AND jobs_db.db_id = degree_branch.db_id "
                                ."AND branch.branch_id = degree_branch.branch_id";
                $get_all_branch=mysqli_query($dbc,$branch_query);
                $branch_row=mysqli_fetch_assoc($get_all_branch);
                $branch = $branch_row['branch_name'];
                while($branch_row=mysqli_fetch_assoc($get_all_branch)){
                $branch = $branch . ', ' . $branch_row['branch_name'];
                }
            ?>
                <tr>
                  <th scope="row"><?php echo $curr;?></th>
                  <td><a href="<?php echo './company.php?id=' . $id;?>"><?php echo $row_recruiters["company_name"]; ?></a></td>
                  <td><a href="<?php echo $job_url . '?id=' . $row["job_id"];?>"><?php echo $row["job_position"];?></a></td>
                  <td><?php echo $degree;?></td>
                  <td><?php echo $branch;?></td>
                  <td><?php echo $row["min_cpi"];?></td>
                  <td><form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row["job_id"] . '&tab=3';?>" method="post">
                  <button type="show" class="btn btn-success" name="show">Show</button></form></td>
                </tr>
            <?php $curr = $curr + 1;
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
  </div>
        <?php } ?>
</div>

<?php require_once('../templates/footer.php');?>
