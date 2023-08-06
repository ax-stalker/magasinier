<?php
session_start();
$page_title='Login form';
 include('./includes/header.php');
// include('../includes/navbar.php');
?>
 <div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6  ">
                <?php
                    if (isset($_SESSION['status']))
                    {
                        ?>
                        <div class="alert alert-success">
                    </h5><?=$_SESSION['status'];?></h5>
                    </div>
                    <?php
                    unset($_SESSION['status']);
                    }
                ?>
                <div class="card shadow">
                    
                        <div class="card-header">
                            <h3 class="card-title">Login form</h3>
                        </div>
                    <div class="card-body">
                        <form action="process_login.php" method="post"> 
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control mb-3" id="password" name="password" placeholder="Enter password">
                            </div>
                            <div class="form-group d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary col-4" id="loginbtn" name="loginbtn">Login</button>
                                <a href="password_reset.php">Forgot password</a>
                            </div>
 
                            <div class="form-group">
                                <p>Not a user? <a href="register.php" class="link link-primary">create account</a>
                            </div>   
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>




