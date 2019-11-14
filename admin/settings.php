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
  function generateNonNumericID() { 
    // String of all alphanumeric characters
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
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
        while(mysqli_num_rows($data_collision) > 0){
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
          $query_constraint = "SELECT * FROM company_constraints WHERE current_id='$id' OR can_apply_id='$id'";
          $data_constraint = mysqli_query($dbc, $query_constraint);
          if(mysqli_num_rows($data_constraint) == 0){
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
                  'Please delete constaint of category ' . $company_category . ' before deleting it.' .
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
        $query_duplicate = "SELECT * FROM company_constraints WHERE current_id='$curr_id' AND can_apply_id='$can_to_id'";
        $data_duplicate = mysqli_query($dbc, $query_duplicate);
        if(mysqli_num_rows($data_duplicate) == 0){
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
              'Company constraint already exists. Please use change constraint option in order to change it.' .
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

  if(isset($_POST['archiveData'])){
    $admin_passw = mysqli_real_escape_string($dbc, trim($_POST['admin-passw']));
    $query = "SELECT * FROM students WHERE user_role='admin' AND password='$admin_passw'";
    $data = mysqli_query($dbc,$query);
    if(mysqli_num_rows($data) == 1){
      $current_year = date("Y");
      $year = $current_year - 4;
      $query = "SELECT students.roll_number,username,mobile_number,gmail_Id,year_of_enroll,db_id,final_accepted_offer FROM students_data INNER JOIN students ON students_data.roll_number=students.roll_number WHERE year_of_enroll=$year";
      $res = mysqli_query($dbc,$query);

      while ($row = mysqli_fetch_assoc($res)) {
        $roll_number = $row['roll_number'];
        $username = $row['username'];
        $mobile_number = $row['mobile_number'];
        $gmail_Id = $row['gmail_Id'];
        $year_of_enroll = $row['year_of_enroll'];
        $db_id = $row['db_id'];
        $company_id = $row['final_accepted_offer'];
        $query = "INSERT into archive_students_data VALUES ('$roll_number','$username','$db_id','$mobile_number','$gmail_Id',$year_of_enroll,'$company_id')";
        $res=mysqli_query($dbc,$query);
        if(!$res){
          die("QUERY FAILED ".mysqli_error($dbc));
        }
      }

      $query = "SELECT DISTINCT jobs.company_id,jobs.company_name,name FROM jobs INNER JOIN recruiters_data ON jobs.company_id=recruiters_data.company_id INNER JOIN company_category ON recruiters_data.company_category_id=company_category.id";
      $res = mysqli_query($dbc,$query);
      while ($row = mysqli_fetch_assoc($res)) {
        $company_id = $row['company_id'];
        $company_name = $row['company_name'];
        $company_category_name = $row['name'];
        $query = "INSERT INTO archive_company_visit_year VALUES ('$company_id',$current_year-1,'$company_category_name')";
        mysqli_query($dbc,$query);
      }

      $query = "DELETE students,students_data FROM students INNER JOIN students_data ON students.roll_number=students_data.roll_number WHERE year_of_enroll=$year";
      mysqli_query($dbc,$query);
      $query = "DELETE FROM jobs_db";
      mysqli_query($dbc,$query);
      $query = "DELETE FROM applications";
      mysqli_query($dbc,$query);
      $query = "DELETE FROM jobs";
      mysqli_query($dbc,$query);

      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
              'Archive Successfull' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';

    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Incorrect Admin Password' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }

  }

  if(isset($_POST['addDegree'])){
    $degree_name = mysqli_real_escape_string($dbc, trim($_POST['new-degree']));
    if(!empty($degree_name)){
      // Check if degree_name is duplicate
      $query = "SELECT degree_name FROM degree";
      $data = mysqli_query($dbc, $query);
      $isNotDuplicate = TRUE;
      while($row = mysqli_fetch_assoc($data)){
        $d_name = $row['degree_name'];
        if(strtolower($degree_name) == strtolower($d_name)){
          $isNotDuplicate = FALSE;
          break;
        }
      }
      if($isNotDuplicate){
        $id = generateNonNumericID();
        $query_collision = "SELECT * FROM degree WHERE degree_id='$id'";
        $data_collision = mysqli_query($dbc, $query_collision);
        while(mysqli_num_rows($data_collision) > 0){
          $id = generateNonNumericID();
          $query_collision = "SELECT * FROM degree WHERE degree_id='$id'";
          $data_collision = mysqli_query($dbc, $query_collision);
        }
        $query = "INSERT INTO degree VALUES ('$id', '$degree_name')";
        $data = mysqli_query($dbc, $query);
        if(!$data){
          die("QUERY FAILED ".mysqli_error($dbc));
        }else{
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Degree added successfully' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This degree already exists.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter a degree name.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['addBranch'])){
    $branch_name = mysqli_real_escape_string($dbc, trim($_POST['new-branch']));
    if(!empty($branch_name)){
      // Check if branch_name is duplicate
      $query = "SELECT branch_name FROM branch";
      $data = mysqli_query($dbc, $query);
      $isNotDuplicate = TRUE;
      while($row = mysqli_fetch_assoc($data)){
        $b_name = $row['branch_name'];
        if(strtolower($branch_name) == strtolower($b_name)){
          $isNotDuplicate = FALSE;
          break;
        }
      }
      if($isNotDuplicate){
        $id = generateNonNumericID();
        $query_collision = "SELECT * FROM branch WHERE branch_id='$id'";
        $data_collision = mysqli_query($dbc, $query_collision);
        while(mysqli_num_rows($data_collision) > 0){
          $id = generateNonNumericID();
          $query_collision = "SELECT * FROM branch WHERE branch_id='$id'";
          $data_collision = mysqli_query($dbc, $query_collision);
        }
        $query = "INSERT INTO branch VALUES ('$id', '$branch_name')";
        $data = mysqli_query($dbc, $query);
        if(!$data){
          die("QUERY FAILED ".mysqli_error($dbc));
        }else{
          echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                'Branch added successfully' .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This branch already exists.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter a branch name.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }

  if(isset($_POST['addDegreeBranchRelation'])){
    $degree_name = mysqli_real_escape_string($dbc, trim($_POST['degree-name']));
    $branch_name = mysqli_real_escape_string($dbc, trim($_POST['branch-name']));
    if(!empty($degree_name) && !empty($branch_name)){
      $query_d = "SELECT * FROM degree WHERE degree_name='$degree_name'";
      $data_d = mysqli_query($dbc, $query_d);
      $query_b = "SELECT * FROM branch WHERE branch_name='$branch_name'";
      $data_b = mysqli_query($dbc, $query_b);
      if(mysqli_num_rows($data_d) > 0 && mysqli_num_rows($data_b) > 0){
        $row_d = mysqli_fetch_assoc($data_d);
        $row_b = mysqli_fetch_assoc($data_b);
        $degree_id = $row_d['degree_id'];
        $branch_id = $row_b['branch_id'];
        $query_duplicate = "SELECT * FROM degree_branch WHERE degree_id='$degree_id' AND branch_id='$branch_id'";
        $data_duplicate = mysqli_query($dbc, $query_duplicate);
        if(mysqli_num_rows($data_duplicate) == 0){
          $id = generateNonNumericID();
          $query_collision = "SELECT * FROM degree_branch WHERE db_id='$id'";
          $data_collision = mysqli_query($dbc, $query_collision);
          while(mysqli_num_rows($data_collision) > 0){
            $id = generateNonNumericID();
            $query_collision = "SELECT * FROM degree_branch WHERE db_id='$id'";
            $data_collision = mysqli_query($dbc, $query_collision);
          }
          echo $query;
          $query = "INSERT INTO degree_branch VALUES ('$id', '$degree_id', '$branch_id')";
          $data = mysqli_query($dbc, $query);
          if(!$data){
            die("QUERY FAILED ".mysqli_error($dbc));
          }else{
            echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
                  'Relation added successfully.' .
                  '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
                  '<span aria-hidden="true">&times;</span></button></div></div>';
          }
        }else{
          echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'This relation already exists.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
        }
      }else{
        echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Degree or branch does not exist. Please make sure both degree and branch exist.' .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
              '<span aria-hidden="true">&times;</span></button></div></div>';
      }
    }else{
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
              'Please enter both degree and branch.' .
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
  <br/>
  <div class="card">
    <div class="card-header">
      <strong>Degrees and Branches</strong>
    </div>
    <div class="card-body">
      <div style="display: flex;">
        <div class="card" style="width: 60%;">
          <div class="card-header">
            Current Degrees
          </div>
          <ul class="list-group list-group-flush">
            <?php
              $query = "SELECT * FROM degree ORDER BY degree_name ASC";
              $data = mysqli_query($dbc, $query);
              while($row = mysqli_fetch_assoc($data)){
                $deg_name = $row['degree_name'];
                echo "<li class='list-group-item'>$deg_name</li>";
              }
            ?>
          </ul>
        </div>
        <div class="card" style="width: 60%;">
          <div class="card-header">
            Current Branches
          </div>
          <ul class="list-group list-group-flush">
            <?php
              $query = "SELECT * FROM branch ORDER BY branch_name ASC";
              $data = mysqli_query($dbc, $query);
              while($row = mysqli_fetch_assoc($data)){
                $branch_name = $row['branch_name'];
                echo "<li class='list-group-item'>$branch_name</li>";
              }
            ?>
          </ul>
        </div>
      </div>
      <br>
      <div class="card" style="width: 60%;">
        <div class="card-header">
          Degrees Branches Relations
        </div>
        <ul class="list-group list-group-flush">
          <?php
            $query = "SELECT degree_name, branch_name FROM degree_branch, degree, branch WHERE degree_branch.degree_id=degree.degree_id AND degree_branch.branch_id=branch.branch_id";
            $data = mysqli_query($dbc, $query);
            while($row = mysqli_fetch_assoc($data)){
              $branch_name = $row['branch_name'];
              $degree_name = $row['degree_name'];
              echo "<li class='list-group-item'>$degree_name - $branch_name</li>";
            }
          ?>
        </ul>
      </div>
      <br>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Add new degree: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control" name="new-degree" placeholder="New degree name">
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="addDegree">Add</button>
      </form>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Add new branch: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control" name="new-branch" placeholder="New branch name">
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="addBranch">Add</button>
      </form>
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2">
          <label>Add new degree branch relation: </label>
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" class="form-control" name="degree-name" placeholder="Enter degree name">
          <input type="text" class="form-control" name="branch-name" placeholder="Enter branch name">
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="addDegreeBranchRelation">Add</button>
      </form>
    </div>
  </div>
  <br>
  <div class="card">
    <div class="card-header">
      <strong>Archive</strong>
    </div>
    <div class="card-body">
      <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group mb-2" style="margin-right:10px;">
          <input type="password" class="form-control" name="admin-passw" placeholder="Admin Password">
        </div>
        <button type="submit" class="btn btn-danger mb-2" name="archiveData">Archive Data</button>
      </form>
      <hr>
      <p><small><strong>Note:</strong>
          <br/>To archive data, enter admin password and click on <b>Archive Data</b> button.
          <br>Clicking on <b>Archive Data</b> will archive students and company data.  Preferably archive shall be done once a year after the current placement session is over.<br/>
          <b>Once current session is archived, the action cannot be reversed and the database will be ready for the new session.</b>
      </small></p>
    </div>
  </div>
  <br/>
</div>

<?php require_once('../templates/footer.php');?>