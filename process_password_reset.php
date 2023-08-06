<?php
session_start();
include('dbconn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_password_reset($get_name, $get_email, $verify_token)
{
    require 'vendor/autoload.php'; // Load PHPMailer from Composer

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
    $mail->setFrom($_ENV['EMAIL_USERNAME'], $get_name);
    $mail->addAddress($get_email, $get_name); //Add a recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Password reset code';
    $email_template = "<p>You are receiving this email because we received a password change request from your account.</p> <br/><a href='http://localhost/Procurement/branch/password_change.php?verify_token=$verify_token&email=$get_email'>Verify Account</a>";
    $mail->Body = $email_template;

    if ($mail->send()) {
        echo 'Message has been sent';
    } else {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    }
}

if (isset($_POST['password_reset_link'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $verify_token = md5(rand());

    // check if email is verified
    $check_email = "SELECT email FROM users WHERE email ='$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row["name"];
        $get_email = $row["email"];

        // query to update token
        $update_token = "UPDATE users SET verify_token='$verify_token' WHERE email ='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);

        if ($update_token_run) {
            send_password_reset($get_name, $get_email, $verify_token);
            $_SESSION['status'] = "We e-mailed you a password reset link. Check your email";
            header("location: password_reset.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Something went wrong. #1";
            header("location: password_reset.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "No email found";
        header("location: password_reset.php");
        exit(0);
    }
}

// after getting code from
if (isset($_POST["password_update"])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $verify_token = mysqli_real_escape_string($conn, $_POST['verify_token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (!empty($verify_token)) {
        if (!empty($email) && !empty($new_password) && !empty($confirm_password)) {
            // check token validity
            $check_token = "SELECT verify_token FROM users WHERE verify_token ='$verify_token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);
            if (mysqli_num_rows($check_token_run) > 0) {
                $row = mysqli_fetch_array($check_token_run);
                $verify_token_db = $row["verify_token"];
                if ($verify_token === $verify_token_db) {
                    if ($new_password === $confirm_password) {
                        $update_password = "UPDATE users SET password='$new_password' WHERE email ='$email' LIMIT 1";
                        $update_password_run = mysqli_query($conn, $update_password);
                        if ($update_password_run) {
                            $_SESSION['status'] = "Password updated successfully";
                            header("location: index.php");
                            exit(0);
                        } else {
                            $_SESSION['status'] = "Something went wrong. #2";
                            header("location: password_reset.php");
                            exit(0);
                        }
                    } else {
                        $_SESSION['status'] = "Passwords do not match";
                        header("location: password_reset.php");
                        exit(0);
                    }
                } else {
                    $_SESSION['status'] = "Invalid token";
                    header("location: password_reset.php");
                    exit(0);
                }
            } else {
                $_SESSION['status'] = "No token available";
                header("location: password_reset.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "All fields are required";
            header("location: password_change.php?verify_token=$verify_token&email=$email");
            exit(0);
        }
    }
}
?>
