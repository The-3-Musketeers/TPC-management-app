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
    // of length 8
    return substr(str_shuffle($str_result), 0, 8); 
  } 

  if(isset($_POST['addNewCategory'])){
    $company_category = strtoupper(mysqli_real_escape_string($dbc, trim($_POST['company-category'])));
    // TODO: check if company_cat is not empty
    if(!empty($company_category)){
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
        mysqli_query($dbc, $query);
        echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Category added successfully.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
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
    // TODO: check if company_cat is duplicate
    
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
        // TODO: check if no company exists of this category
        $query = "SELECT * FROM recruiters_data WHERE company_category";
        $data = mysqli_query($dbc, $query);
        if(mysqli_num_rows($data) == 0){
          $query = "DELETE FROM company_category WHERE name='$company_category'";
          mysqli_query($dbc, $query);
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Category deleted successfully.' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }else{
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Before deleting this category, please delete all companies of this category or change their category.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter a valid company category.' .
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
?>

<div class="container">
  <div class="card">
    <div class="card-header">
      <strong>Company Categories</strong>
    </div>
    <div class="card-body">
      <div class="card" style="width: 18rem;">
        <div class="card-header">
          Category
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
        <small>Before deleting a category, please delete all the companies of this category or change their categories.</small>
      </form>
    </div>
  </div>
</div>

<?php require_once('../templates/footer.php');?>