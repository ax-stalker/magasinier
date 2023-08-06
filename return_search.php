
<!-- header -->
<?php
       session_start();
       $page_title='RETURNS FORM';
       include('./includes/header.php');
       include('./includes/navbar.php');?>


<div class="container mt-4">
    <div class="text-center">
        <h2>EQUIPMENT RETURNS FORM</h2>
    </div>
</div>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8">
            <form method="POST" action="">
                <div class="form-group mb-4">
                    <input type="text" class="form-control text-center" id="studentNumber" name="studentNumber" placeholder="Please enter Student Number">
                </div>
                <div class="text-center">
                    <button type="submit" name="search" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php
    // Database connection configuration
    include("dbconn.php");

    // Check if the form is submitted
    if (isset($_POST['search'])) {
        $studentNumber = $_POST['studentNumber'];
        $_SESSION['student_number']= $studentNumber;

       // Prepare the SQL statement
$stmt = $conn->prepare("SELECT * FROM student s INNER JOIN departments d ON s.department = d.dept_id WHERE s.student_number = ?");
$stmt->bind_param("s", $studentNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Student found, display student details
    $row = $result->fetch_assoc();
    ?>
    <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6 col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            Student Details
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Student Name: <?php echo $row['names']; ?></h5>
                            <p class="card-text">Phone Number: <?php echo $row['phone_number']; ?></p>
                            <p class="card-text">Department: <?php echo $row['dept_name']; ?></p>
                            <a href="return_returns.php" class="btn btn-success">return</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
} else {
    // Student not found
    echo '<div class="container mt-4 "><div class="alert alert-danger">Student not found.</div></div>';
}

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>
