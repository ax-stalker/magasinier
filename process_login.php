<?php
session_start();
include('dbconn.php');

if (isset($_POST['loginbtn'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $login_query = "SELECT * FROM users WHERE email ='$email' AND password ='$password' LIMIT 1";
        $login_query_run = mysqli_query($conn, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);
            if ($row['verify_status'] == 1) {
                $_SESSION['authenticated'] = true;
                $_SESSION['auth_user'] = [
                    'name' => $row['name'],
                    'phone_number' => $row['phone_number'],
                    'department' => $row['department'],
                    'email' => $row['email'],
                ];

                // Check if the user is an admin and redirect accordingly
                if ($row['is_admin'] == 1) {
                    header("Location: ../admin/index.php");
                } else {
                    header("Location: home_search.php");
                }
                exit();
            } else {
                $_SESSION['status'] = 'Please verify your account to login.';
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['status'] = 'Invalid email or password.';
            header('Location: index.php');
            exit();
        }
    } else {
        $_SESSION['status'] = 'All fields are mandatory.';
        header('Location: index.php');
        exit();
    }
}
?>
