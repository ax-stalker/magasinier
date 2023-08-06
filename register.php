<?php
session_start();
$page_title='Registration form';
include('./includes/header.php');
// include('./includes/navbar.php');
?>
 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6  ">
                <div class='alert'>
                    <?php if (isset($_SESSION['status'])){
                        echo "<h4>".$_SESSION['status']."</h4>";
                        unset($_SESSION['status']);
                    }
                    ?>
                </div>
                <div class="card shadow">
                        <div class="card-header">
                            <h3 class="card-title">Registration form</h3>
                        </div>
                    <div class="card-body">

                        <form action="process_registration.php" method="post">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control mb-2" id="name" name="name" placeholder="Enter name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control mb-2" id="email" name="email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" class="form-control mb-2" id="department" name="department" placeholder="Enter Department">
                            </div>
                            <div class="form-group">
                                <label for="phone_number">phone number</label>
                                <input type="number" class="form-control " id="phone_number" name="phone_number" placeholder="Enter phone number">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control " id="password" name="password" placeholder="Enter password">
                            </div>
                            <div class="form-group mb-3">
                                <label for="confirm_password"> Confirm Password</label>
                                <input type="confirm_password" class="form-control " id="confirm_password" name="confirm_password" placeholder="confirm password">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="register_btn" >Register</button>
                            </div>
                            <div class="form-group">
                                <p>Already a user? <a href="index.php" class="link link-primary">login</a>
                            </div> 

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




<?php include('./includes/footer.php');?>
