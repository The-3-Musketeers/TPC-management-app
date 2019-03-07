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

  if(!isset($_GET['search']) && !isset($_GET['id']))
  {
    $_SESSION["keyword"]=null;
  }

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
  if(!isset($_POST['search'])){
    $keyword='';
  }else {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $_SESSION["keyword"]=mysqli_real_escape_string($dbc, trim($_POST['keyword']));
  }
?>

<div class="container">
  <form action="<?php echo $_SERVER['PHP_SELF'] . '?search=""';?>" method="post">
    <div class="input-group mb-3">
      <input type="text" class="form-control" name="keyword" placeholder="Type Keyword" value="<?php if(isset($_SESSION["keyword"])) echo $_SESSION["keyword"] ?>">
      <div class="input-group-append">
        <button name="search" class="btn btn-primary" type="submit">Search</button>
      </div>
    </div>
  </form>
  <?php 
    // Search Bar 
    if(isset($_POST['search']) || isset($_SESSION['keyword'])){ 
      ?>
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
      <tbody>
      <?php
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $keyword=$_SESSION['keyword'];
        $keywords=explode(" ",$keyword);
        $categories=["A1", "B1", "B2"];
        $category=[];// storing the category in the keyword
        $rem_words="";
        foreach($keywords as $key){
          if(in_array(strtoupper($key),$categories)){
            array_push($category,strtoupper($key));
          }
          else {
            $rem_words.=$key." ";
          }
        }
        if($rem_words!=""){
          $query="SELECT * FROM recruiters WHERE MATCH (company_name) AGAINST ('$rem_words')";
          $search_query=mysqli_query($dbc,$query);
          if(!$search_query){
            die("error ".mysqli_error($dbc));
          }
          $num=mysqli_num_rows($search_query);
          if($num!=0){
            add_row($search_query,$category);
          }
          else{
            echo "No result Found";
          }
        }
        else{
          $query="SELECT * FROM recruiters";
          $search_query=mysqli_query($dbc,$query);
          add_row($search_query,$category);
        }
      ?>
     </tbody>
    </table>
  <?php }?>
  <?php  
    function add_row($search_query,$category){
      $i=1;
      while($row=mysqli_fetch_assoc($search_query)){
        $id=$row['company_id'];
        $company_name=$row['company_name'];
        $company_category=$row['company_category'];
        $hr_name=$row['hr_name'];
        $hr_email=$row['hr_email'];
        $status=$row['company_status'];
        if(!empty($category)){
          if(in_array($company_category,$category)){
            ?>
          <tr>
            <th scope="row"><?php echo $i;?></th>
            <td><a href="<?php echo './company.php?id=' . $id;?>" target="_blank"><?php echo $company_name; ?></a></td>
            <td><?php echo $company_category;?></td>
            <td><?php echo $hr_name;?></td>
            <td> <?php echo $hr_email; ?> </td>
            <?php 
            if($status=='accepted')
              echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=1 method="post">'.
              '<button type="reject" class="btn btn-danger" name="reject">Reject</button></form></td>';
            else if($status=='pending')
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=2 method="post">'.
            '<button type="approve" class="btn btn-success" name="approve">Approve</button>'.
            '<button type="reject" class="btn btn-danger" name="reject">Reject</button></form></td>';
            else 
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=3 method="post">'.
            '<button type="approve" class="btn btn-success" name="approve">Approve</button></form></td>';
            ?>
          </tr>
          <?php
          $i++;
        }
      }else { ?>
      <tr>
            <th scope="row"><?php echo $i;?></th>
            <td><a href="<?php echo './company.php?id=' . $id;?>" target="_blank"><?php echo $company_name; ?></a></td>
            <td><?php echo $company_category;?></td>
            <td><?php echo $hr_name;?></td>
            <td> <?php echo $hr_email; ?> </td>
            <?php 
            if($status=='accepted')
              echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=1 method="post">'.
              '<button type="reject" class="btn btn-danger" name="reject">Reject</button></form></td>';
            else if($status=='pending')
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=2 method="post">'.
            '<button type="approve" class="btn btn-success" name="approve">Approve</button>'.
            '<button type="reject" class="btn btn-danger" name="reject">Reject</button></form></td>';
            else 
            echo '<td><form action=' . $_SERVER['PHP_SELF'] . '?id=' . $row["company_id"] . '&tab=3 method="post">'.
            '<button type="approve" class="btn btn-success" name="approve">Approve</button></form></td>';
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
  <?php } ?>
</div>

<?php require_once('../templates/footer.php');?>
