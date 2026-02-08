<?php
// Database connection configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "enrollment_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Log error securely (in production, log to file)
    error_log("Database connection failed: " . $conn->connect_error);
    die("Unable to connect to the database. Please contact the administrator.");
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
