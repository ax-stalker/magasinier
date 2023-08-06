<!-- add equipment form for inventory page -->
<?php
// Database conn settings
include('dbconn.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $department = $_SESSION['department'];

    $equipmentName = $_POST['equipment_name'];
    $equipmentMake = $_POST['equipment_make'];
    $equipmentNumber = $_POST['equipment_number'];

    // Check if the equipment entry already exists in the database
    $equipmentName = $conn->real_escape_string($equipmentName);
    $equipmentMake = $conn->real_escape_string($equipmentMake);
    $equipmentNumber = $conn->real_escape_string($equipmentNumber);

    $query = "SELECT * FROM inventory WHERE equipment_name = '$equipmentName' AND equipment_make = '$equipmentMake'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // echo 'Equipment entry already exists in the database.';
     
        $response = array(
            'status' => 'error',
            'message' => 'Equipment entry already exists in the database, please edit instead.'
        );
        echo json_encode($response);
        
    } else {
        // Insert equipment into the database
        $query = "INSERT INTO inventory (equipment_name, equipment_make, equipment_number, department) VALUES ('$equipmentName', '$equipmentMake', '$equipmentNumber', '$department')";

        if ($conn->query($query) === true) {
            // echo 'Equipment added to the database.';
            $response = array(
                'status' => 'success',
                'message' => 'Equipment added to the database.'
            );
            echo json_encode($response);
            
        } else {
            // echo 'Error inserting equipment: ' . $conn->error;

            $response = array(
                'status' => 'error',
                'message' => 'Error inserting equipment: ' . $conn->error
            );
            echo json_encode($response);
            
        }
    }
}

// Close the database conn
$conn->close();
?>
