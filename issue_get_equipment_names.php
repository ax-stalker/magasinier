<?php
// Start the session
session_start();

// connect to database
require "dbconn.php";

// Get the department from the session
$department= $_SESSION['auth_user']['department'];

// Fetch distinct equipment names based on the department
$query = "SELECT DISTINCT equipment_name FROM inventory WHERE department = '$department'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<option selected disabled value="">Select Equipment Name</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['equipment_name'] . '">' . $row['equipment_name'] . '</option>';
    }
} else {
    echo '<option value="">No equipment found</option>';
}

// Close the database connection
$conn->close();
?>
