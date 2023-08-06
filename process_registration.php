<?php
session_start();
include('dbconn.php');

require 'vendor/autoload.php'; // Load PHPMailer from Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['EMAIL_USERNAME']; // Fetching from .env file
    $mail->Password = $_ENV['EMAIL_PASSWORD']; // Fetching from .env file

    //Recipients  
    $mail->setFrom($_ENV['EMAIL_USERNAME'], $name);
    $mail->addAddress($email, $name); //Add a recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Email verification';
    $email_template = "<p>Please verify your email address to proceed.</p> <br/><a href='http://localhost/Procurement/branch/verify-email.php?verify_token=$verify_token'>verify account</a>";
    $mail->Body = $email_template;

    if ($mail->send()) {
        echo 'Message has been sent';
    } else {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    }
}

if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];
    $password2 = $_POST['confirm_password'];
    $verify_token = md5(rand());

    // check if email exists
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($conn, $check_email_query);
    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = 'Email id already exists';
        header("location: register.php");
    } else {
        // register user
        $register_query = "INSERT INTO users (name, email, department, phone_number, password, verify_token) VALUES ('$name', '$email', '$department', '$phone', '$password', '$verify_token')";
        $register_query_run = mysqli_query($conn, $register_query);
        if ($register_query_run) {
            sendemail_verify($name, $email, $verify_token);
            $_SESSION['status'] = 'Registered Successfully. Check email for verification link';
            header("location: register.php");
        } else {
            $_SESSION['status'] = 'Something went wrong';
            header("location: register.php");
        }
    }
}
?>
