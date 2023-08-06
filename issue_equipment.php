<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('./vendor/autoload.php'); // Load PHPMailer from Composer

// Start the session
session_start();

// Database configuration
require('dbconn.php');

// Get the student number from the session
$studentNumber = $_SESSION['student_number'];

// Get the form inputs
$equipmentName = $_POST['equipment_name'];
$equipmentMake = $_POST['equipment_make'];
$equipmentNumber = $_POST['equipment_number'];
$deadLine = $_POST['deadline'];

// Convert the date format from 'mm/dd/yyyy' to 'yyyy-mm-dd'
$deadline = date('Y-m-d', strtotime($deadLine));

// Check if the equipment's status is cleared for the given student
$checkQuery = $conn->prepare("SELECT COUNT(*) FROM transactions 
                              WHERE student_number = ? 
                              AND equipment_id = (SELECT id FROM inventory WHERE equipment_name = ? AND equipment_make = ?) 
                              AND status != 'cleared'");
$checkQuery->bind_param("iss", $studentNumber, $equipmentName, $equipmentMake);
$checkQuery->execute();
$checkResult = $checkQuery->get_result()->fetch_assoc();
$checkQuery->close();

if ($checkResult['COUNT(*)'] > 0) {
    $_SESSION['alert_message'] = "The equipment's status is not cleared. Cannot issue the equipment.";
    $_SESSION['alert_class'] = "alert-danger";
} else {
    // Prepare the statement for inserting the transaction details
    $insertQuery = $conn->prepare("INSERT INTO transactions (student_number, equipment_id, number, deadline, status) 
                                  VALUES (?, (SELECT id FROM inventory WHERE equipment_name = ? AND equipment_make = ?), ?, ?, 'pending')");
    $insertQuery->bind_param("issss", $studentNumber, $equipmentName, $equipmentMake, $equipmentNumber, $deadline);

    if ($insertQuery->execute()) {
        $_SESSION['alert_message'] = 'Equipment issued successfully.';
        $_SESSION['alert_class'] = 'alert-success';

        // Send email to the student
        $emailQuery = $conn->prepare("SELECT email FROM student WHERE student_number = ?");
        $emailQuery->bind_param("i", $studentNumber);
        $emailQuery->execute();
        $emailResult = $emailQuery->get_result()->fetch_assoc();
        $emailQuery->close();

        $to = $emailResult['email']; // Email address of the student
        $subject = "Equipment Issuance Confirmation";
        $message = "Dear student,\n\nYou have successfully been issued the following equipment:\n\n";
        $message .= "Equipment Name: " . $equipmentName . "\n";
        $message .= "Equipment Make: " . $equipmentMake . "\n";
        $message .= "Equipment Number: " . $equipmentNumber . "\n";
        $message .= "Deadline: " . $deadline . "\n\n";
        $message .= "Please ensure to return the equipment on time.\n\n";
        $message .= "Thank you.\n";

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_USERNAME']; // Fetching from .env file
            $mail->Password = $_ENV['EMAIL_PASSWORD'];
            // Recipients
            $mail->setFrom($_ENV['EMAIL_USERNAME'], 'Magasinier'); // Replace 'Your Name' with your name or organization name
            $mail->addAddress($to);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send the email
            $mail->send();
            $_SESSION['alert_message'] .= " Email sent to student.";
        } catch (Exception $e) {
            $_SESSION['alert_message'] .= " Failed to send email.";
        }
    } else {
        $_SESSION['alert_message'] = 'Error: ' . $insertQuery->error;
        $_SESSION['alert_class'] = 'alert-danger';
    }

    // Close the prepared statement
    $insertQuery->close();
}

// Close the database connection
$conn->close();

// Redirect to the index page
header("Location: issue.php");
exit();
?>
