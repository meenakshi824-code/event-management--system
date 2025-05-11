<?php
// delete_feedback.php

// Start session
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo "You are not logged in as admin.";
    exit;
}

// Include database connection
include('server.php.php');



// Check if feedback ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL query to delete feedback
    $delete_query = "DELETE FROM feedback WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
   

    // Execute the query and check if successful
    if ($stmt->execute()) {
        // After successful deletion, redirect to the feedback page
        echo "<script>alert('Feedback deleted successfully');</script>";
        header('Location: Dashboard.php');
        exit;
    } else {
        // Redirect with error message if deletion failed
        echo "<script>alert('Error deleting feedback');</script>";
        header('Location: Dashboard.php');
        exit;
    }
} else {
    // Log the error if ID is missing
    error_log("No feedback ID provided.");
    header('Location: Dashboard.php?error=Invalid request.');
    exit;
} 
?>