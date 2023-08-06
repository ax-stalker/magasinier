<?php
session_start();
include('dbconn.php');
if (isset($_GET['verify_token'])){
    $verify_token= $_GET['verify_token'];
    $verify_query="SELECT verify_token, verify_status FROM users WHERE verify_token ='$verify_token' LIMIT 1";
    $verify_query_run= mysqli_query($conn, $verify_query);

    if (mysqli_num_rows($verify_query_run)> 0)
    {
        $row=mysqli_fetch_array($verify_query_run);
        
        // CHECK VERIFICATION STATUS
        if ($row['verify_status']=="0")
        {
            $clicked_token= $row['verify_token'];
            $update_query="UPDATE users SET verify_status='1' WHERE verify_token='$clicked_token' LIMIT 1";
            $update_query_run= mysqli_query($conn, $update_query);

            if ($update_query_run)
            {
                $_SESSION['status']= "Your account has been verified successfully. Please login!";
            header('location: index.php');
            exit(0);
            }
            else
            {
                $_SESSION['status']= "Verification Fail";
                header('location: index.php');
                exit(0); 
            }

        }
        else
        {
            $_SESSION['status']= "This email is already verified. Please login";
            header('location: index.php');
            exit(0);
            
        }

    }
    else
    {
        $_SESSION['status']= "This token does not exist";
            header('location: index.php');
    }



}
else{
    $_SESSION['status']= "Not Allowed";
    header('location: index.php');
}



?>