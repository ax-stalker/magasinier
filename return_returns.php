<!-- header -->
<?php
session_start();

$page_title = 'RETURNS FORM';
include './includes/header.php';
include './includes/navbar.php';
?>

<style>
    .deadline-past {
        color: red;
    }

    .deadline-future {
        color: green;
    }
</style>

<div class="container">
    <?php
    //  database connection 
    include('dbconn.php');
  
    $student_number = $_SESSION['student_number'];
    // Retrieve equipment data from the transactions and inventory tables
    $query = "SELECT t.transaction_id, i.equipment_name, i.equipment_make, t.number, t.deadline 
              FROM transactions AS t 
              INNER JOIN inventory AS i ON t.equipment_id = i.id 
              WHERE t.status = 'pending' AND student_number = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("s", $student_number);
    $statement->execute();
    $result = $statement->get_result();
    $equipmentData = $result->fetch_all(MYSQLI_ASSOC);

    // Check if the student has any pending equipment
    if (count($equipmentData) === 0) {
        echo '<div class="alert alert-success">You have no pending equipment. You are cleared.</div>';
    }
    ?>

    <?php if (count($equipmentData) > 0) { ?>
        <form action="return_process_form.php" method="POST">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Equipment Name</th>
                        <th>Equipment Make</th>
                        <th>Number</th>
                        <th>Deadline</th>
                        <th>Checkbox</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipmentData as $equipment) { ?>
                        <?php
                        $deadline = strtotime($equipment['deadline']);
                        $today = strtotime(date('Y-m-d'));
                        $deadlineClass = $deadline < $today ? 'deadline-past' : 'deadline-future';
                        ?>
                        <tr>
                            <td><?php echo $equipment['equipment_name']; ?></td>
                            <td><?php echo $equipment['equipment_make']; ?></td>
                            <td><?php echo $equipment['number']; ?></td>
                            <td><span class="<?php echo $deadlineClass; ?>"><?php echo $equipment['deadline']; ?></span></td>
                            <td><input type="checkbox" name="transaction_ids[]" value="<?php echo $equipment['transaction_id']; ?>"></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    <?php } ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
