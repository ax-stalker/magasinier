<!-- get equipment php for inventory page -->

<?php
// Database conn settings
include('dbconn.php');

// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipmentId = $_POST['id'];

    // Retrieve department from session
    session_start();
    $department = $_SESSION['department'];

    // Retrieve equipment details from the database based on department
    $query = "SELECT * FROM inventory WHERE id = '$equipmentId' AND department = '$department'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $equipment = [
            'id' => $row['id'],
            'equipment_name' => $row['equipment_name'],
            'equipment_make' => $row['equipment_make'],
            'equipment_number' => $row['equipment_number']
        ];

        echo json_encode($equipment);
    } else {
        echo 'Equipment not found.';
    }
}

// Close the database conn
$conn->close();
?>
