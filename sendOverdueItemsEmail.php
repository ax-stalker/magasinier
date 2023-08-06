<?php
session_start();
require('dbconn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$email = $_GET['email'];

$query = "SELECT t.*, i.equipment_name, i.equipment_make, s.names, t.deadline
          FROM transactions t
          INNER JOIN inventory i ON t.equipment_id = i.id
          INNER JOIN student s ON t.student_number = s.student_number
          WHERE t.deadline < CURDATE() AND t.status = 'pending' AND s.email = ?";

// Use prepared statement to prevent SQL injection
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$equipmentData = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $equipmentData[] = $row;
    }
}

// Email content
$emailContent = "Hello,\n\nHere is a list of your overdue items:\n\n";
foreach ($equipmentData as $row) {
    $emailContent .= "- Equipment Name: {$row['equipment_name']}, Make: {$row['equipment_make']}, Deadline: {$row['deadline']}\n";
}
$emailContent .= "\nPlease return the items as soon as possible.\n\nBest regards,\nThe Equipment Management Team";

// Initialize PHPMailer
$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Update with your SMTP host
$mail->SMTPAuth = true;
$mail->Username = $_ENV['EMAIL_USERNAME']; // Fetching from .env file
$mail->Password = $_ENV['EMAIL_PASSWORD']; // Fetching from .env file
$mail->SMTPSecure = 'tls'; // Update with your preferred encryption method (tls or ssl)
$mail->Port = 587; // Update with the appropriate port

$mail->setFrom($_ENV['EMAIL_USERNAME'], 'Your Name'); // Update with your email and name
$mail->addAddress($email); // Add the student's email address

$mail->isHTML(false);
$mail->Subject = 'Overdue Items Reminder';
$mail->Body = $emailContent;

// Send the email
if ($mail->send()) {
    echo json_encode(array('message' => 'Email sent successfully'));
} else {
    echo json_encode(array('message' => 'Email could not be sent'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
