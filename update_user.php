<?php
// update_user.php

session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo "You are not logged in as admin.";
    exit;
}

include('server.php.php');

// Check if the form data is submitted
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate the data (you can add more validations as needed)
    if (empty($username) || empty($email)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Prepare SQL query to update user details
    $update_query = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $username, $email, $user_id);

    if ($stmt->execute()) {
        echo "User details updated successfully!";
        header('Location: Dashboard.php'); // Redirect back to the admin dashboard
        exit;
    } else {
        echo "Error updating user details.";
    }
} else {
    echo "No user ID provided.";
    exit;
}
?>