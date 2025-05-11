<?php
// Include database connection
include('server.php.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $event = $_POST['event'];
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];

    // Fetch user details from database
    $query = "SELECT username, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $username = $user['username'];
    $email = $user['email'];

    // Insert feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, name, email, event_name, rating, feedback_text) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $username, $email, $event, $rating, $comments);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href = 'user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again later.');</script>";
    }

    $stmt->close();
}
?>
