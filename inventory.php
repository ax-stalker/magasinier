<!-- inventory index page -->
 <?php 
        session_start();
       $page_title='Equipment Inventory';
       include('./includes/header.php');
       include('./includes/navbar.php');?>
       
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <div class="container">
        <h2>Equipment Inventory</h2>
        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addModal">Add Equipment</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Equipment Name</th>
                    <th>Equipment Make</th>
                    <th>Equipment Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database conn settings
                include('dbconn.php');
                // Retrieve equipment from the database descending order
                // $query = "SELECT * FROM inventory ORDER BY id DESC";

                // Retrieve equipment from the database alphabetical order
                $query = "SELECT * FROM inventory ORDER BY equipment_name ASC";

                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row['equipment_name'].'</td>';
                    echo '<td>'.$row['equipment_make'].'</td>';
                    echo '<td>'.$row['equipment_number'].'</td>';
                    echo '<td>
                            <button type="button" class="btn btn-sm btn-info edit-btn" data-toggle="modal" data-target="#editModal" data-id="'.$row['id'].'">Edit</button>
                             <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="'.$row['id'].'">Delete</button>
                          </td>';
                    echo '</tr>';
                }

                // Close the database conn
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Equipment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="equipmentName">Equipment Name:</label>
                            <input type="text" class="form-control" id="equipmentName" required>
                        </div>
                        <div class="form-group">
                            <label for="equipmentMake">Equipment Make:</label>
                            <input type="text" class="form-control" id="equipmentMake" required>
                        </div>
                        <div class="form-group">
                            <label for="equipmentNumber">Equipment Number:</label>
                            <input type="text" class="form-control" id="equipmentNumber" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Equipment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editEquipmentName">Equipment Name:</label>
                            <input type="text" class="form-control" id="editEquipmentName" required>
                        </div>
                        <div class="form-group">
                            <label for="editEquipmentMake">Equipment Make:</label>
                            <input type="text" class="form-control" id="editEquipmentMake" required>
                        </div>
                        <div class="form-group">
                            <label for="editEquipmentNumber">Equipment Number:</label>
                            <input type="text" class="form-control" id="editEquipmentNumber" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="editEquipmentId">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Add equipment form submission
        $('#addForm').submit(function(e) {
            e.preventDefault();
            
            var equipmentName = $('#equipmentName').val();
            var equipmentMake = $('#equipmentMake').val();
            var equipmentNumber = $('#equipmentNumber').val();
            
            $.ajax({
                type: 'POST',
                url: 'inventory_add_equipment.php',
                data: {
                    equipment_name: equipmentName,
                    equipment_make: equipmentMake,
                    equipment_number: equipmentNumber
                },
                // crucial section for alerts
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'error') {
                        $('.modal-body').prepend('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            result.message +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button></div>');
                    } else if (result.status === 'success') {
                        $('.modal-body').prepend('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            result.message +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button></div>');
                        setTimeout(function() {
                            location.reload();
                        }, 1500); // Reload the page after 1.5 seconds (1500 milliseconds)
                    }
                },
                complete: function() {
                    $('#addModal').modal('handleUpdate');
                }



            });
        });
        
        // Edit equipment form submission
        $('#editForm').submit(function(e) {
            e.preventDefault();
            
            var equipmentId = $('#editEquipmentId').val();
            var equipmentName = $('#editEquipmentName').val();
            var equipmentMake = $('#editEquipmentMake').val();
            var equipmentNumber = $('#editEquipmentNumber').val();
            
            $.ajax({
                type: 'POST',
                url: 'inventory_edit_equipment.php',
                data: {
                    id: equipmentId,
                    equipment_name: equipmentName,
                    equipment_make: equipmentMake,
                    equipment_number: equipmentNumber
                },
                success: function(response) {
                    location.reload();
                }
            });
        });
        
        // Edit button click handler
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            
            // Retrieve equipment details for the selected ID
            $.ajax({
                type: 'POST',
                url: 'inventory_get_equipment.php',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    $('#editEquipmentId').val(response.id);
                    $('#editEquipmentName').val(response.equipment_name);
                    $('#editEquipmentMake').val(response.equipment_make);
                    $('#editEquipmentNumber').val(response.equipment_number);
                }
            });
        });
        
        // Delete button click handler
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            
            if (confirm('Are you sure you want to delete this equipment?')) {
                // Delete the equipment from the database
                $.ajax({
                    type: 'POST',
                    url: 'inventory_delete_equipment.php',
                    data: { id: id },
                    success: function(response) {
                        location.reload();
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
