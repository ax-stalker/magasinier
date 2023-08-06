<?php
// Load dotenv library
require './vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$dbHost = $_ENV['DB_HOST'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD']; // Set to an empty string if blank
$dbName = $_ENV['DB_NAME'];

// Create database connection
$connection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$searchQuery = $_GET['search'];
$searchQuery = mysqli_real_escape_string($connection, $searchQuery);
$searchQuery = '%' . $searchQuery . '%';

$query = "SELECT t.*, i.equipment_name, i.equipment_make, s.email, s.phone_number, s.department, s.names
          FROM transactions t
          INNER JOIN inventory i ON t.equipment_id = i.id
          INNER JOIN student s ON t.student_number = s.student_number
          WHERE t.deadline < CURDATE() AND t.status = 'pending' AND (i.equipment_name LIKE ? OR i.equipment_make LIKE ?)";

// Use prepared statement to prevent SQL injection
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'ss', $searchQuery, $searchQuery);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$equipmentData = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $equipmentData[] = $row;
    }
}

mysqli_stmt_close($stmt);
mysqli_close($connection);

header('Content-Type: application/json');
echo json_encode($equipmentData);
?>
