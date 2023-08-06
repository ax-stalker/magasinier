<!-- delete equipment for inventory page -->
<?php
include('dbconn.php');
// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipmentId = $_POST['id'];
    
    // Delete equipment from the database
    $query = "DELETE FROM inventory WHERE id = '$equipmentId'";
    
    if ($conn->query($query) === true) {
        echo 'Equipment deleted.';
    } else {
        echo 'Error deleting equipment: ' . $conn->error;
    }
}

// Close the database conn
$conn->close();
?>
