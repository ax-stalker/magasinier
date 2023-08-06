
<!-- header -->
 <?php
       session_start();
       $page_title='EQUIPMENT ISSUANCE FORM';
       include('./includes/header.php');
       include('./includes/navbar.php');?>

  <!-- Alert Section -->
  <div class="container mt-5">
        <?php
        // Check if the alert message session is set
        if (isset($_SESSION['alert_message']) && isset($_SESSION['alert_class'])) {
            $alertMessage = $_SESSION['alert_message'];
            $alertClass = $_SESSION['alert_class'];

            // Unset the session variables
            unset($_SESSION['alert_message']);
            unset($_SESSION['alert_class']);
            ?>
            <div class="alert <?php echo $alertClass; ?> mt-4"><?php echo $alertMessage; ?></div>
        <?php } ?>
    </div>


    <div class="container mt-5">
        <!-- Equipment issuance form -->
        <!-- <h2>Equipment Issuance</h2> -->


        <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Equipment Issuance</h3>
                    <form id="equipmentForm" method="post" action="issue_equipment.php">
                        <div class="form-group">
                            <label for="equipmentName">Equipment Name:</label>
                            <select class="form-control" id="equipmentName" name="equipment_name" required>
                                <option selected disabled value="">Select Equipment Name</option>
                                <!-- <option selected> Select equipment name</option> -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="equipmentMake">Equipment Make:</label>
                            <select class="form-control" id="equipmentMake" name="equipment_make" required>
                                <option value="">Select Equipment Make</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="equipmentNumber">Equipment Number:</label>
                            <input type="number" class="form-control" id="equipmentNumber" name="equipment_number" required>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline:</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" placeholder="Due Date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Issue Equipment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        // Function to fetch equipment names based on selected department
        function getEquipmentNames() {
            $.ajax({
                url: "issue_get_equipment_names.php",
                type: "GET",
                success: function(response) {
                    $("#equipmentName").html(response);
                }
            });
        }

        // Function to fetch equipment makes based on selected name
        function getEquipmentMakes() {
            var name = $("#equipmentName").val();
            if (name !== "") {
                $.ajax({
                    url: "issue_get_equipment_makes.php",
                    type: "POST",
                    data: { name: name },
                    success: function(response) {
                        $("#equipmentMake").html(response);
                    }
                });
            } else {
                $("#equipmentMake").html('<option value="">Select Equipment Make</option>');
            }
        }

        // Fetch equipment names when the page loads
        $(document).ready(function() {
            getEquipmentNames();
        });

        // Update equipment makes when the equipment name is changed
        $("#equipmentName").change(function() {
            getEquipmentMakes();
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
