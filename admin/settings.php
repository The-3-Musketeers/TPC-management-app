<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');
  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  $page_title = 'Settings';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  //Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  function generateID() { 
    // String of all alphanumeric characters
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
    // Shufle the $str_result and returns substring
    // of length 6
    return substr(str_shuffle($str_result), 0, 6); 
  } 

  if(isset($_POST['addNewCategory'])){
    $company_category = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['company-category'])));
    if(!empty($company_category)){
      // Check if company_category is duplicate
      $query = "SELECT * FROM company_category WHERE name='$company_category'";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) == 0){
        $id = generateID();
        $query_collision = "SELECT * FROM company_category WHERE id='$id'";
        $data_collision = mysqli_query($dbc, $query_collision);
        while(mysqli_num_rows($data) > 0){
          $id = generateID();
          $query_collision = "SELECT * FROM company_category WHERE id='$id'";
          $data_collision = mysqli_query($dbc, $query_collision);
        }
        $query = "INSERT INTO company_category VALUES ('$id', '$company_category') ";
        $data = mysqli_query($dbc, $query);
        if(!$data){
          die("QUERY FAILED ".mysqli_error($dbc));
        }else{
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Category added successfully.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This category already exists.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter a company category.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['deleteCategory'])){
    $company_category = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['company-category'])));
    if(!empty($company_category)){
      $query = "SELECT * FROM company_category WHERE name='$company_category'";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) > 0){
        $query = "SELECT id FROM company_category WHERE name='$company_category'";
        $data = mysqli_query($dbc, $query);
        $row = mysqli_fetch_assoc($data);
        $id = $row['id'];
        // check if no company exists of this category
        $query = "SELECT * FROM recruiters_data WHERE company_category_id='$id'";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) == 0){
          $query = "DELETE FROM company_category WHERE name='$company_category'";
          $data = mysqli_query($dbc, $query);
          if(!$data){
            die("QUERY FAILED ".mysqli_error($dbc));
          }else{
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'Category deleted successfully.' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                  '<span aria-hidden="true">&times;</span></button></div></div>';
          }
        }else{
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Before deleting this category, please delete all companies of this category or change their category.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Category does not exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter a company category.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['changeCategory'])){
    $company_category_old = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['company-category-old'])));
    $company_category_new = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['company-category-new'])));
    if(!empty($company_category_old) && !empty($company_category_new)){
      $query = "SELECT * FROM company_category WHERE name='$company_category_old'";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) > 0){
        $query = "UPDATE company_category SET name='$company_category_new' WHERE name='$company_category_old'";
        $data = mysqli_query($dbc, $query);
        if(!$data){
          die("QUERY FAILED ".mysqli_error($dbc));
        }else{
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Category name changed successfully.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Category does not exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter both old company category and new company category to rename.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['addNewConstraint'])){
    $curr_name = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['selected-in-category'])));
    $can_to_name = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['can-apply-to-category'])));
    $num_times = mysqli_real_escape_string($dbc, trim($_POST['num-times']));
    if(!empty($curr_name) && !empty($can_to_name) && !empty($num_times)){
      $num_times = (int)$num_times;
      $query = "SELECT cat1.id as id1, cat2.id as id2 FROM company_category cat1, company_category cat2 WHERE cat1.name='$curr_name' AND cat2.name='$can_to_name';";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) > 0){
        $row = mysqli_fetch_assoc($data);
        $curr_id = $row['id1'];
        $can_to_id = $row['id2'];
        $query = "INSERT INTO company_constraints VALUES ('$curr_id', '$can_to_id', $num_times)";
        $data = mysqli_query($dbc, $query);
        if(!$data){
          die("QUERY FAILED ".mysqli_error($dbc));
        }else{
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Company constraint added successfully.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Company category does not exist. Please add the category before adding constraint on it.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter all three fields.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['deleteConstraint'])){
    $curr_name = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['selected-in-category'])));
    $can_to_name = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['can-apply-to-category'])));
    if(!empty($curr_name) && !empty($can_to_name)){
      $query = "SELECT cat1.id as id1, cat2.id as id2 FROM company_category cat1, company_category cat2 WHERE cat1.name='$curr_name' AND cat2.name='$can_to_name';";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) > 0){
        $row = mysqli_fetch_assoc($data);
        $curr_id = $row['id1'];
        $can_to_id = $row['id2'];
        $query = "SELECT * FROM company_constraints WHERE current_id='$curr_id' AND can_apply_id='$can_to_id'";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) > 0){
          $query = "DELETE FROM company_constraints WHERE current_id='$curr_id' AND can_apply_id='$can_to_id'";
          $data = mysqli_query($dbc, $query);
          if(!$data){
            die("QUERY FAILED ".mysqli_error($dbc));
          }else{
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'Company constraint deleted successfully.' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                  '<span aria-hidden="true">&times;</span></button></div></div>';
          }
        }else{
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Entered constraint does not exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Entered category does not exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter both fields.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['changeConstraint'])){
    $curr_name = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['selected-in-category'])));
    $can_to_name = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['can-apply-to-category'])));
    $new_num_times = mysqli_real_escape_string($dbc, trim($_POST['num-times']));
    if(!empty($curr_name) && !empty($can_to_name)){
      $query = "SELECT cat1.id as id1, cat2.id as id2 FROM company_category cat1, company_category cat2 WHERE cat1.name='$curr_name' AND cat2.name='$can_to_name';";
      $data = mysqli_query($dbc, $query);
      if(mysqli_num_rows($data) > 0){
        $row = mysqli_fetch_assoc($data);
        $curr_id = $row['id1'];
        $can_to_id = $row['id2'];
        $query = "SELECT * FROM company_constraints WHERE current_id='$curr_id' AND can_apply_id='$can_to_id'";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) > 0){
          $query = "UPDATE company_constraints SET num=$new_num_times WHERE current_id='$curr_id' AND can_apply_id='$can_to_id'";
          $data = mysqli_query($dbc, $query);
          if(!$data){
            die("QUERY FAILED ".mysqli_error($dbc));
          }else{
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'Company constraint changed successfully.' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                  '<span aria-hidden="true">&times;</span></button></div></div>';
          }
        }else{
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Entered constraint does not exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Entered category does not exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter all three fields.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }
?>

<div class="container">
  <div class="card">
    <div class="card-header">
      <strong>Company Categories</strong>
    </div>
    <div class="card-body">
      <div class="card" style="width: 60%;">
        <div class="card-header">
          Current Categories
        </div>
        <ul class="list-group list-group-flush">
          <?php
            $query = "SELECT * FROM company_category ORDER BY name ASC";
            $data = mysqli_query($dbc, $query);
            while($row = mysqli_fetch_assoc($data)){
              $category = $row['name'];
              echo "<li class='list-group-item'>$category</li>";
            }
          ?>
        </ul>
      </div>
      <br>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Add new category: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control" name="company-category" placeholder="Enter category">
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="addNewCategory">Add</button>
      </form>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Delete category: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control" name="company-category" placeholder="Enter category">
        </div>
        <button type="submit" class="btn btn-danger mb-2" name="deleteCategory">Delete</button>
      </form>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Rename category: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control" name="company-category-old" placeholder="Old name"> 
          <input type="text" class="form-control" name="company-category-new" placeholder="New name">
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="changeCategory">Change</button>
      </form>
      <hr>
      <p>
      <small><strong>Instructions:</strong> <br>1. Before deleting a category, please delete all the companies of this category or change their categories.
                   <br>2. Maximum length of a company category can be 8 characters.
      </small>
      </p>
    </div>
  </div>
  <br>
  <div class="card">
    <div class="card-header">
      <strong>Company Constraints</strong>
    </div>
    <div class="card-body">
      <div class="card" style="width: 60%;">
        <div class="card-header">
          Current Constraints
        </div>
        <ul class="list-group list-group-flush">
          <?php
            $query = "SELECT * FROM company_constraints";
            $data = mysqli_query($dbc, $query);
            if(!$data){
              die("QUERY FAILED ".mysqli_error($dbc));
            }
            while($row = mysqli_fetch_assoc($data)){
              $curr_id = $row['current_id'];
              $can_to_id = $row['can_apply_id'];
              $num = $row['num'];
              $query1 = "SELECT cat1.name as name1, cat2.name as name2 FROM company_category cat1, company_category cat2 WHERE cat1.id='$curr_id' AND cat2.id='$can_to_id';";
              $data1 = mysqli_query($dbc, $query1);
              if(!$data1){
                die("QUERY FAILED ".mysqli_error($dbc));
              }
              $row1 = mysqli_fetch_assoc($data1);
              $curr_name = $row1['name1'];
              $can_to_name = $row1['name2'];
              echo "<li class='list-group-item'>Student selected in <b>$curr_name</b> can apply to <b><i>$num</i></b> <b>$can_to_name</b> companies.</li>";
            }
          ?>
        </ul>
      </div>
      <br>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Add new constraint: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <br>
          <input type="text" class="form-control" name="selected-in-category" placeholder="Selected in category">
          <input type="text" class="form-control" name="can-apply-to-category" placeholder="Can apply to category">
          <input type="number" class="form-control" name="num-times" placeholder="Number of times">
          <br>
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="addNewConstraint">Add</button>
      </form>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Delete constraint: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <br>
          <input type="text" class="form-control" name="selected-in-category" placeholder="Selected in category">
          <input type="text" class="form-control" name="can-apply-to-category" placeholder="Can apply to category">
          <br>
        </div>
        <button type="submit" class="btn btn-danger mb-2" name="deleteConstraint">Delete</button>
      </form>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Change constraint: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <br>
          <input type="text" class="form-control" name="selected-in-category" placeholder="Selected in category">
          <input type="text" class="form-control" name="can-apply-to-category" placeholder="Can apply to category">
          <input type="number" class="form-control" name="num-times" placeholder="New number of times">
          <br>
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="changeConstraint">Change</button>
      </form>
      <hr>
      <p><small><strong>Instructions:</strong><br>1. Format in table is: Student selected in <code>&lt;Selected in category&gt;</code> can apply to <code>&lt;Number of times&gt;</code> <code>&lt;Can apply to category&gt;</code> companies. 
          <br>2. If you want to delete a constraint that tells that if a student is selected in an A2 company then he/she can apply to atmost 2 more A1 companies, then write A2 in the "Selected in category" field, A2 in the "Can apply to category" and 2 in "Number of times" field.
          <br>3. For deleting, just specify the category "Selected in" and the category "can apply to".
          <br>4. For changing, specify the category "Selected in" and the category "can apply to" and the new number of times a student can apply.
      </small></p>
    </div>
  </div>
</div>

<?php require_once('../templates/footer.php');?>