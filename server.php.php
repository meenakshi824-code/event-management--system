<?php
// Database connection parameters
$servername = "localhost"; // Change if using a remote database
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "event_management"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, 'event_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally print a success message (remove in production)
//echo "Connected successfully";
?>
