<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  $page_title = 'Students';
  require_once('../templates/header.php');
  require_once('../templates/navbar.php');

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  function GenerateTable($db_id){
    $html_content = "<table class='table'>" .
                      "<thead class='thead-light'>" .
                        "<tr>" .
                          "<th scope='col'>Roll No.</th>" .
                          "<th scope='col'>Name</th>" .
                          "<th scope='col'>Email</th>" .
                          "<th scope='col'>Mobile No.</th>" .
                        "</tr>" .
                      "</thead>";

    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query = "SELECT roll_number, current_cpi, mobile_number FROM students_data WHERE db_id='$db_id' ORDER BY roll_number ASC";
    $data = mysqli_query($dbc, $query);
    if(!$data){
      die("ERROR: QUERY FAILED ".mysqli_error($dbc));
    }
    if(mysqli_num_rows($data) > 0){
      $html_content .= "<tbody>";
      $curr = 1;
      while($row = mysqli_fetch_array($data)){
        $roll = $row['roll_number'];
        $query_student = "SELECT username, webmail_id FROM students WHERE roll_number='$roll'";
        $data_student = mysqli_query($dbc, $query_student);
        $row_student = mysqli_fetch_array($data_student);
        $username = $row_student['username'];
        $webmail_id = $row_student['webmail_id'];
        $roll_number = $row['mobile_number'];
        $html_content .= "<tr>" .
                          "<td>$roll</th>" .
                          "<td><a href='./student.php?roll=$roll'>$username</td>" .
                          "<td>$webmail_id</td>" .
                          "<td>$roll_number</td>" .
                          "</tr>";
        $curr = $curr + 1;
      }
      $html_content .= "</tbody>";
    } else {
      $html_content .= "<tr>" .
                        "<td>No data</td>" .
                        "</tr>";
    }
    $html_content .= "</table>";
    return $html_content;
  }

?>

<div class="container">
  <ul class="nav nav-tabs" id="studentTabs" role="tablist">
    <?php
    $query_degree = "SELECT * FROM degree";
    $data_degree = mysqli_query($dbc, $query_degree);
    $first_entry = TRUE;
    while($row = mysqli_fetch_assoc($data_degree)){
      $curr_degree_id = $row['degree_id'];
      $curr_degree_name = $row['degree_name'];
      if($first_entry){
        echo "<li class='nav-item'>
                <a class='nav-link active' id='$curr_degree_id-tab' data-toggle='tab' href='#$curr_degree_id' role='tab' aria-selected='true'>$curr_degree_name</a>
              </li>";
        $first_entry = FALSE;
      }else{
        echo "<li class='nav-item'>
                <a class='nav-link' id='$curr_degree_id-tab' data-toggle='tab' href='#$curr_degree_id' role='tab' aria-selected='false'>$curr_degree_name</a>
              </li>";
      }
    }
    ?>
  </ul>
  <div class="tab-content" id="studentsTabContent">
    <?php
      $query_degree = "SELECT degree_id FROM degree";
      $data_degree = mysqli_query($dbc, $query_degree);
      $first_entry = TRUE;
      while($row = mysqli_fetch_assoc($data_degree)){
        $curr_degree_id = $row['degree_id'];
        if($first_entry){
          echo "<div class='tab-pane fade show active' id='$curr_degree_id' role='tabpanel' aria-labelledby='$curr_degree_id-tab'>
          <div class='accordion' id='accordion$curr_degree_id'>";
          $first_entry = FALSE;
        }else{
          echo "<div class='tab-pane fade' id='$curr_degree_id' role='tabpanel' aria-labelledby='$curr_degree_id-tab'>
          <div class='accordion' id='accordion$curr_degree_id'>";
        }
        $query_get_branches = "SELECT db_id, branch.branch_id, branch.branch_name FROM degree_branch, branch WHERE degree_branch.branch_id=branch.branch_id AND degree_id='$curr_degree_id'";
        $data_get_branches = mysqli_query($dbc, $query_get_branches);

        $first = TRUE;
        while($row_branches = mysqli_fetch_assoc($data_get_branches)){
          $branch_id = $row_branches['branch_id'];
          $branch_name = $row_branches['branch_name'];
          $db_id = $row_branches['db_id'];
          $table_content = GenerateTable($db_id);
          
          $html_content = "<div class='card'>" .
                            "<div class='card-header' id='heading-$branch_id'>" .
                            "<h2 class='mb-0'>";
          if($first){
            $html_content .= "<button class='btn btn-link' type='button' data-toggle='collapse' data-target='#collapse$branch_id' aria-expanded='true' aria-controls='collapse$branch_id'>$branch_name</button></h2></div>" .
              "<div id='collapse$branch_id' class='collapse show' aria-labelledby='heading-$branch_id' data-parent='#accordion$curr_degree_id'>" .
              "<div class='card-body'>";
            $first = FALSE;
          }else{
            $html_content .= "<button class='btn btn-link collapsed' type='button' data-toggle='collapse' data-target='#collapse$branch_id' aria-expanded='false' aria-controls='collapse$branch_id'>$branch_name</button></h2></div>" .
              "<div id='collapse$branch_id' class='collapse' aria-labelledby='heading-$branch_id' data-parent='#accordion$curr_degree_id'>" .
              "<div class='card-body'>";
          }
          $html_content .= $table_content;
          $html_content .= "</div></div></div>";
          echo $html_content;
        }
        echo "</div>
        </div>";
      } 
    ?>
  </div>

</div>

<?php require_once('../templates/footer.php');?>
