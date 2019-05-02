<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

// fetch company data
$company_id = $_POST['company_id'];
$hr_email = $_POST['hr_email'];
$hr_name = $_POST['hr_name'];
// sender's email
$tpc_email = 'tpc@iitp.ac.in';
$msg='Thank you for signing up for the TPC portal of IIT Patna. Your company ID is' . $company_id . ' for signing in.';
// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(TRUE);

/* Open the try/catch block. */
try {
  $mail->isSMTP();
  $mail->Host = "smtp.example.com";

// optional
// used only when SMTP requires authentication
$mail->SMTPAuth = true;
$mail->Username = 'smtp_username';
$mail->Password = 'smtp_password';
   /* Set the mail sender. */
   $mail->SetFrom($tpc_email, 'TPC IIT Patna', 0);

   /* Add a recipient. */
   $mail->addAddress($hr_email);

   /* Set the subject. */
   $mail->Subject = 'Email Confirmation and Company ID';

   /* Set the mail message body. */
   $mail->Body = $msg;

   /* Finally send the mail. */
   $mail->send();
}
catch (Exception $e)
{
   /* PHPMailer exception. */
   echo $e->errorMessage();
}
catch (\Exception $e)
{
   /* PHP exception (note the backslash to select the global namespace Exception class). */
   echo $e->getMessage();
}
?>
