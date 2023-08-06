<?php
session_start();

$page_title="Password change update";
include('./includes/header.php');
include('./includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php
                        if(isset($_SESSION['status']))
                        {
                            ?>
                           <div class= "alert alert-success">
                            <h5><?=$_SESSION['status'];?></h5>
                            </div>
                            <?php
                            unset($_SESSION['status']);
                        }
                        ?>
                    <div class="card">
                        <div class="card-header">
                                <h5>Update Password</h5>
                        </div>
                        <div class="card-body p-4">
                          
                            <form action="process_password_reset.php" method="POST">
                            <input type="" name="verify_token" value="<?php if(isset($_GET['verify_token'])) {echo $_GET['verify_token']; }?>">

                            <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($_GET['email'])) {echo $_GET['email']; }?>" placeholder="Confirm email">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="new_password">New Password</label>
                                    <input type="text" class="form-control" id="new_password" name="new_password" placeholder="new password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password">
                                </div>
                                <div class="form-group">
                                    
                                    <button type="submit" name="password_update" class="btn btn-primary W-100">Update</button>
                                </div>
                                <div class="form-group">
</div>
