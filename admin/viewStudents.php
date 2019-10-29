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

  function GenerateTable($dept, $course){
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
    $query = "SELECT roll_number, current_cpi, mobile_number FROM students_data WHERE course='$course' AND department='$dept' ORDER BY roll_number ASC";
    $data = mysqli_query($dbc, $query);
    if(mysqli_num_rows($data) != 0){
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
    <li class="nav-item">
      <a class="nav-link active" id="btech-tab" data-toggle="tab" href="#btech" role="tab" aria-selected="true">BTech</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="mtech-tab" data-toggle="tab" href="#mtech" role="tab" aria-selected="false">MTech</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="msc-tab" data-toggle="tab" href="#msc" role="tab" aria-selected="false">MSc</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="phd-tab" data-toggle="tab" href="#phd" role="tab" aria-selected="false">PHD</a>
    </li>
  </ul>
  <div class="tab-content" id="studentsTabContent">
    <div class="tab-pane fade show active" id="btech" role="tabpanel" aria-labelledby="btech-tab">
      <div class="accordion" id="accordionBtech">
        <?php
          $branches = ["CS"=>"Computer Science and Engineering",
                        "EE"=>"Electrical Engineering",
                        "ME"=>"Mechanical Engineering",
                        "CE"=>"Civil and Environmental Engineering",
                        "CB"=>"Chemical and Biochemical Engineering"
                          ];
          $first = TRUE;
          foreach($branches as $code => $name){
            $table_content = GenerateTable($code, "Btech");
            $html_content = "<div class='card'>" .
                              "<div class='card-header' id='heading-$code'>" .
                              "<h2 class='mb-0'>";
            if($first){
              $html_content .= "<button class='btn btn-link' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='true' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse show' aria-labelledby='heading-$code' data-parent='#accordionBtech'>" .
              "<div class='card-body'>";
              $first = FALSE;
            }else{
              $html_content .= "<button class='btn btn-link collapsed' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='false' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse' aria-labelledby='heading-$code' data-parent='#accordionBtech'>" .
              "<div class='card-body'>";
            }
            $html_content .= $table_content;
            $html_content .= "</div></div></div>";
            echo $html_content;
          }
        ?>
      </div>
    </div>

    <div class="tab-pane fade" id="mtech" role="tabpanel" aria-labelledby="mtech-tab">
      <div class="accordion" id="accordionMtech">
        <?php
          $branches = ["mech"=>"Mechatronics",
                        "mnc"=>"Mathematics & Computing",
                        "nano"=>"Nano Science & Technology",
                        "cse"=>"Computer Science & Engineering",
                        "comm"=>"Communication System Engineering",
                        "me"=>"Mechanical Engineering",
                        "ce"=>"Civil & Infrastructure Engineering",
                        "mse"=>"Materials Science & Engineering",
                        "vlsi"=>"VLSI & Embedded Systems",
                          ];
          $first = TRUE;
          foreach($branches as $code => $name){
            $table_content = GenerateTable($code, "Mtech");
            $html_content = "<div class='card'>" .
                              "<div class='card-header' id='heading-$code'>" .
                              "<h2 class='mb-0'>";
            if($first){
              $html_content .= "<button class='btn btn-link' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='true' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse show' aria-labelledby='heading-$code' data-parent='#accordionMtech'>" .
              "<div class='card-body'>";
              $first = FALSE;
            }else{
              $html_content .= "<button class='btn btn-link collapsed' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='false' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse' aria-labelledby='heading-$code' data-parent='#accordionMtech'>" .
              "<div class='card-body'>";
            }
            $html_content .= $table_content;
            $html_content .= "</div></div></div>";
            echo $html_content;
          }
        ?>
      </div>
    </div>

    <div class="tab-pane fade" id="msc" role="tabpanel" aria-labelledby="msc-tab">
      <div class="accordion" id="accordionMSC">
        <?php
          $branches = ["math"=>"Mathematics",
                        "phy"=>"Physics",
                        "chem"=>"Chemistry"
                          ];
          $first = TRUE;
          foreach($branches as $code => $name){
            $table_content = GenerateTable($code, "Msc");
            $html_content = "<div class='card'>" .
                              "<div class='card-header' id='heading-$code'>" .
                              "<h2 class='mb-0'>";
            if($first){
              $html_content .= "<button class='btn btn-link' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='true' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse show' aria-labelledby='heading-$code' data-parent='#accordionMSC'>" .
              "<div class='card-body'>";
              $first = FALSE;
            }else{
              $html_content .= "<button class='btn btn-link collapsed' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='false' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse' aria-labelledby='heading-$code' data-parent='#accordionMSC'>" .
              "<div class='card-body'>";
            }
            $html_content .= $table_content;
            $html_content .= "</div></div></div>";
            echo $html_content;
          }
        ?>
      </div>
    </div>

    <div class="tab-pane fade" id="phd" role="tabpanel" aria-labelledby="phd-tab">
      <div class="accordion" id="accordionPHD">
        <?php
          $branches = ["cse_phd"=>"Computer Science & Engineering",
                        "ee_phd"=>"Electrical Engineering",
                        "me_phd"=>"Mechanical Engineering",
                        "ce_phd"=>"Civil & Environment Engineering",
                        "cb_phd"=>"Chemical & Biochemical Engineering",
                        "mse_phd"=>"Material Science & Engineering",
                        "math_phd"=>"Mathematics",
                        "phy_phd"=>"Physics",
                        "chem_phd"=>"Chemistry",
                        "humanities_phd"=>"Humanities and Social Sciences",
                          ];
          $first = TRUE;
          foreach($branches as $code => $name){
            $table_content = GenerateTable($code, "PHD");
            $html_content = "<div class='card'>" .
                              "<div class='card-header' id='heading-$code'>" .
                              "<h2 class='mb-0'>";
            if($first){
              $html_content .= "<button class='btn btn-link' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='true' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse show' aria-labelledby='heading-$code' data-parent='#accordionPHD'>" .
              "<div class='card-body'>";
              $first = FALSE;
            }else{
              $html_content .= "<button class='btn btn-link collapsed' type='button' data-toggle='collapse' data-target='#collapse$code' aria-expanded='false' aria-controls='collapse$code'>$name</button></h2></div>" .
              "<div id='collapse$code' class='collapse' aria-labelledby='heading-$code' data-parent='#accordionPHD'>" .
              "<div class='card-body'>";
            }
            $html_content .= $table_content;
            $html_content .= "</div></div></div>";
            echo $html_content;
          }
        ?>
      </div>
    </div>
  </div>

</div>

<?php require_once('../templates/footer.php');?>
