<!--edit equipment for inventory page -->
<?php
// Database conn settings
include('dbconn.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipmentId = $_POST['id'];
    $equipmentName = $_POST['equipment_name'];
    $equipmentMake = $_POST['equipment_make'];
    $equipmentNumber = $_POST['equipment_number'];
    
    // Update equipment in the database
    $equipmentId = $conn->real_escape_string($equipmentId);
    $equipmentName = $conn->real_escape_string($equipmentName);
    $equipmentMake = $conn->real_escape_string($equipmentMake);
    $equipmentNumber = $conn->real_escape_string($equipmentNumber);
    
    $query = "UPDATE inventory SET equipment_name = '$equipmentName', equipment_make = '$equipmentMake', equipment_number = '$equipmentNumber' WHERE id = '$equipmentId'";
    
    if ($conn->query($query) === true) {
        echo 'Equipment updated.';
    } else {
        echo 'Error updating equipment: ' . $conn->error;
    }
}

// Close the database conn
$conn->close();
?>
