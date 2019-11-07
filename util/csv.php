<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  // Connect to Database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Fetch job ID
  $job_id = $_GET['job_id'];

  // Fetch applicants data
  $query="SELECT * FROM jobs WHERE job_id='". $job_id ."'";
  $get_all_jobs_query=mysqli_query($dbc,$query);
  $num=mysqli_num_rows($get_all_jobs_query);
  if($num==1){
    $applicant_query="SELECT * FROM applications WHERE job_id='" . $job_id ."' ORDER BY student_roll_number ASC";
    $applicant_data=mysqli_query($dbc,$applicant_query);
    $applicant_num=mysqli_num_rows($applicant_data);
    if($applicant_num!=0){
      $csv_data = array(array("S.No", "Roll Number", "Name", "Email", "Applied on", "Status", "Attendance"));
      $sno=1;
      while($applicant_row=mysqli_fetch_assoc($applicant_data)){
        $app_roll_no=$applicant_row['student_roll_number'];
        $student_query="SELECT * FROM students WHERE roll_number='" . $app_roll_no ."'";
        $student_data=mysqli_query($dbc,$student_query);
        $student_num=mysqli_num_rows($student_data);
        if($student_num==1){
          $student_row=mysqli_fetch_assoc($student_data);
          $student_name=$student_row['username'];
          $student_email=$student_row['webmail_id'];
          $applied_on=$applicant_row['applied_on'];
          $app_status=$applicant_row['application_status'];
          array_push($csv_data, array($sno, $app_roll_no, $student_name, $student_email, $applied_on, $app_status, ""));
          $sno+=1;
        }
      }
      outputCSV($csv_data,'applicants list.csv');
    }
  }

  function outputCSV($data, $file_name) {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        # Start the ouput
        $output = fopen("php://output", "w");

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
    }
?>
