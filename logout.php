<?php
// Start the session
session_start();

// Destroy all session data
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page or home page
header("Location: index2.php"); // Change this to the desired page
exit();
?>