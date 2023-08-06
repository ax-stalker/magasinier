<?php
// Load dotenv library
require './vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Debug: Print environment variable values
echo 'DB_HOST: ' . $_ENV['DB_HOST'] . PHP_EOL;
echo 'DB_USERNAME: ' . $_ENV['DB_USERNAME'] . PHP_EOL;
echo 'DB_PASSWORD: ' .$_ENV['DB_PASSWORD'] . PHP_EOL;
echo 'DB_NAME: ' .$_ENV['DB_NAME']. PHP_EOL;

// Database configuration
$dbHost = $_ENV['DB_HOST'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];  // Set to an empty string if blank
$dbName = $_ENV['DB_NAME'];

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Your database connection is now established and ready to use.
// You can perform database queries and operations using the $conn object.
