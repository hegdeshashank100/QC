<?php
$host = 'localhost';
$user = 'root'; // Default user for XAMPP
$password = ''; // Default password for XAMPP is empty
$database = 'course_db'; // Replace 'course_db' with your actual database name

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the charset to UTF-8 for proper encoding
$conn->set_charset('utf8');

// Optional: Uncomment the following line to ensure successful connection
// echo "Connected successfully to the database!";
?>
