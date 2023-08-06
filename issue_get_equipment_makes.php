<?php
// Start the session
session_start();

// database connection
require 'dbconn.php';

// Get the selected equipment name and department from the session
$name = $_POST['name'];
$department= $_SESSION['auth_user']['department'];

// Fetch equipment makes based on the selected equipment name and department
$query = "SELECT  equipment_make FROM inventory WHERE equipment_name = '$name' AND department = '$department'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['equipment_make'] . '">' . $row['equipment_make'] . '</option>';
    }
} else {
    echo '<option value="">No equipment makes found</option>';
}

// Close the database connection
$conn->close();
?>
