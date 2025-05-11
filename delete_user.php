<?php
// delete_user.php

session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo "You are not logged in as admin.";
    exit;
}

include('server.php.php');

// Check if the user ID is provided
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Prepare SQL query to delete user
    $delete_query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
        header('Location: Dashboard.php'); // Redirect back to the dashboard
        exit;
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "No user ID provided.";
    exit;
}
?>