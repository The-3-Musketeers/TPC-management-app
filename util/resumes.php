<?php
  // Start the session
  require_once('../templates/startSession.php');
  require_once('../connectVars.php');

  // Authenticate user
  require_once('../templates/auth.php');
  checkUserRole('admin', $auth_error);

  // Check user roll admin
  if($_SESSION['user_role']!='admin'){
    exit();
  }

  // Fetch job ID
  $job_id = $_GET['job_id'];

  // Connect to Database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Fetch applicants list
  $app_list = array();
  $query="SELECT * FROM jobs WHERE job_id='". $job_id ."'";
  $get_all_jobs_query=mysqli_query($dbc,$query);
  $num=mysqli_num_rows($get_all_jobs_query);
  if($num==1){
    $applicant_query="SELECT * FROM applications WHERE job_id='" . $job_id ."'";
    $applicant_data=mysqli_query($dbc,$applicant_query);
    $applicant_num=mysqli_num_rows($applicant_data);
    if($applicant_num!=0){
      while($applicant_row=mysqli_fetch_assoc($applicant_data)){
        $app_roll_no = $applicant_row['student_roll_number'];
        array_push($app_list, $app_roll_no . '.pdf');
      }
    }
  }

  // create zip file
  $zip = new ZipArchive();
  $filename = "./resumes.zip";
  if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename>\n");
  }
  $dir = '../resume/';
  $dir1 = 'resume/';
  createZip($zip, $dir, $dir1, $app_list);
  $zip->close();

  function createZip($zip,$dir, $dir1, $app_list){
    if (is_dir($dir)){
      if ($dh = opendir($dir)){
        foreach ($app_list as $file) {
          if(is_file($dir.$file)){
            if($file != '' && $file != '.' && $file != '..'){
              $zip->addFile($dir.$file, $dir1.$file);
            }
          }
        }
         closedir($dh);
       }
    }
  }
  $filename = "resumes.zip";

  if (file_exists($filename)) {
     header('Content-Type: application/zip');
     header('Content-Disposition: attachment; filename="'.basename($filename).'"');
     header('Content-Length: ' . filesize($filename));
     flush();
     readfile($filename);
     unlink($filename);

   }
?>
