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
      $table_content='';
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
          $table_content .= '
              <tr>
                <td>'. $sno .'</td>
                <td>'. $app_roll_no .'</td>
                <td><a href="./admin/student.php?roll='. $app_roll_no .'" target="_blank">'. $student_name .'</a></td>
                <td>'. $student_email .'</td>
                <td>';
          if($app_status == "accepted"){
            $table_content .= '<span class="badge badge-success">Accepted</span>';
          }elseif($app_status == "pending") {
            $table_content .= '<span class="badge badge-warning">Pending</span>';
          }elseif($app_status == "rejected") {
            $table_content .= '<span class="badge badge-danger">Rejected</span>';
          }
          $table_content .= '
                </td>
                <td></td>
              </tr>';
          $sno+=1;
        }
      }
      generatePDF($table_content);
    }
  }
  function generatePDF($table_content){
    require_once('../lib/tcpdf/tcpdf.php');
    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Applicant list");
    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $obj_pdf->SetDefaultMonospacedFont('helvetica');
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 10);
    $obj_pdf->AddPage();
    $content = '
    <h3 align="center">List of applicants</h3><br /><br />
    <table border="1" cellspacing="0" cellpadding="5">
      <tr>
        <th width="6%">S.No</th>
        <th width="14%">Roll Number</th>
        <th width="27%">Name</th>
        <th width="30%">Email</th>
        <th width="10%">Status</th>
        <th width="12%">Attendance</th>
      </tr>
    ';
    $content .= $table_content;
    $content .= '</table>';
    $obj_pdf->writeHTML($content);
    $obj_pdf->Output('applicants.pdf', 'I');
  }
?>
