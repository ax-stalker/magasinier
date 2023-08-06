<?php
session_start();
$page_title ="Password Reset Page";
include("./includes/header.php");
// include("../includes/navbar.php");
?>


<div class ="py-5">
    <div class= "container">
        <div class =" row justify-content-center">
            <div class = "col-md-6">

                <?php
                    if(isset($_SESSION['status']))
                    {
                        ?>
                        <div class ="alert alert-success">
                            <h5><?=$_SESSION['status'];?></h5>
                        </div>
                        <?php
                        unset($_SESSION['status']);
                    }
                    ?>

                    <div class ="card">
                        <div class ="card-header">
                            <h5> Reset Password</h5>
                        </div>
                        <div class="card-body p-4">

                            <form action ="process_password_reset.php" method="POST">

                                <div class="form-group mb-3">
                                    <label>Email address</label>
                                    <input type="text" name="email" class="form-control" placeholder="Enter email address">
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" name= "password_reset_link" class="btn btn-primary"> Send password reset link</button>
                                </div>

                            </form>

                        </div>
                    </div>
            </div>  
            
        </div>
    </div>
</div>







