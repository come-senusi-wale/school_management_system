<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;

include('database.php');

if (isset($_POST['submit'])) {
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "SELECT * FROM finance_clerk_registration_table WHERE email = '$email'";

    $query_run = mysqli_query($conn, $query);

    $num = mysqli_num_rows($query_run);

    if ($num > 0) {

        $pwd_code = substr(uniqid(), 8);
        $pwd_token = rand(534322, 893772);

        $query_two = "UPDATE finance_clerk_registration_table SET pwd_code = '$pwd_code', pwd_token = '$pwd_token' WHERE email = '$email'";
        $query_run_two = mysqli_query($conn, $query_two);

        if ($query_run_two) {
            
            $school_mail = "eduspringofgrace@gmail.com";
            $name = "email varification";
            $subject = "code to varified your email before reseting ur password";
            $body = "copy this code  ".$pwd_code." into space provided and reset ur password";
            $pwd = "08104322128";
            
            require_once "phpmailer/PHPMailer.php";
            require_once "phpmailer/SMTP.php";
            require_once "phpmailer/Exception.php";
            
            $mail = new PHPMailer();

            
            $mail->IsSMTP();  // telling the class to use SMTP
            //$mail->SMTPDebug = 2;
            $mail->Mailer = "smtp";
            $mail->Host = "ssl://smtp.gmail.com";
            //$mail->Port = 587;
            $mail->Port = 465;
            $mail->SMTPAuth = true; // turn on SMTP authentication
            
            $mail->Username = "eduspringofgrace@gmail.com"; // SMTP username
            $mail->Password = "qcygveozmfpfacjw"; // SMTP password
            //$Mail->Priority = 1;
            $mail->AddAddress($email);
            $mail->SetFrom($school_mail, $name);
            //$mail->AddReplyTo('akinyemisaheedwale@gmail.com');
            $mail->Subject  = $subject;
            $mail->Body     = $body;
            $mail->WordWrap = 50;


            if ($mail->send()) {

                
                
                header("location: ../finanace_officer_password_reset.php?token=$pwd_token");

                

            }else{
                
                
                $result = 'fail to send code please resend ur email';
                header("location: ../finance_officer_forgot_password.php?result=$result");
            }

        }else{

            
            $result = 'fail to send please resend ur email';
            header("location: ../finance_officer_forgot_password.php?result=$result");
        }
        
        
    }else{

        $result = 'no such email exit';
        header("location: ../finance_officer_forgot_password.php?result=$result");
    }
}

?>