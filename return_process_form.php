<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php'; // Load PHPMailer from Composer

// Start the session
session_start();

// Retrieve the student number from the session
$studentNumber = $_SESSION['student_number'];

//  database connection 
include('dbconn.php');

// Retrieve the email from the database
$emailQuery = "SELECT email FROM student WHERE student_number = ?";
$emailStatement = $conn->prepare($emailQuery);
$emailStatement->bind_param("s", $studentNumber);
$emailStatement->execute();
$emailResult = $emailStatement->get_result()->fetch_assoc();

// Retrieve the email value
$email = $emailResult['email'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected transaction IDs
    $transactionIds = $_POST['transaction_ids'];

    // Update the status of selected transactions from "pending" to "cleared"
    foreach ($transactionIds as $transactionId) {
        $updateQuery = "UPDATE transactions SET status = 'cleared' WHERE transaction_id = ? AND student_number = ?";
        $updateStatement = $conn->prepare($updateQuery);
        $updateStatement->bind_param("is", $transactionId, $studentNumber);
        $updateStatement->execute();
    }

    // Retrieve the details of the cleared equipment from the inventory table
    $equipmentQuery = "SELECT t.transaction_id, i.equipment_name, i.equipment_make, t.number, t.deadline
                       FROM transactions AS t
                       INNER JOIN inventory AS i ON t.equipment_id = i.id
                       WHERE t.transaction_id IN (" . implode(',', $transactionIds) . ")";
    $equipmentResult = $conn->query($equipmentQuery);

    // Create the email content
    $emailContent = 'Dear student,' . "\n\n";
    $emailContent .= 'The following equipment has been cleared:' . "\n\n";

    while ($row = $equipmentResult->fetch_assoc()) {
        $emailContent .= 'Transaction ID: ' . $row['transaction_id'] . "\n";
        $emailContent .= 'Equipment Name: ' . $row['equipment_name'] . "\n";
        $emailContent .= 'Equipment Make: ' . $row['equipment_make'] . "\n";
        $emailContent .= 'Equipment Number: ' . $row['number'] . "\n";
        $emailContent .= 'Deadline: ' . $row['deadline'] . "\n\n";
    }

    $emailContent .= 'Thank you for returning the equipment.' . "\n";

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
        $mail->Password = $_ENV['EMAIL_PASSWORD']; // Fetching from .env file

        // Recipients
        $mail->setFrom($_ENV['EMAIL_USERNAME'], 'Magasinier'); // Replace 'Magasinier' with your name or organization name
        $mail->addAddress($email); // Add the student's email address

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Cleared Equipment List';
        $mail->Body = $emailContent;

        // Send the email
        $mail->send();

        // Close the database connection
        $conn->close();

        // Redirect or display a success message
        header("Location: return_returns.php"); // Redirect to a success page
        exit();
    } catch (Exception $e) {
        // Handle the exception and display an error message
        $_SESSION['alert_message'] = 'Failed to send email: ' . $mail->ErrorInfo;
        $_SESSION['alert_class'] = 'alert-danger';
        header("Location: return_returns.php"); // Redirect to an error page
        exit();
    }
}
?>
