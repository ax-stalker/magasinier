<?php
session_start();

if (isset($_SESSION['authenticated']));
{
    $_SESSION['status'] =' Please login';
    header('location:index.php');
    exit(0);
}

?>