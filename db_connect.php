<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default is empty for XAMPP
$dbname = "course_db"; // Ensure this is the correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
