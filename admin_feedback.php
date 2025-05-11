<?php
// admin_feedback.php

// Start session
session_start();

// Include database connection
include('server.php.php');

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: login2.php'); // Redirect to login page if not an admin
    exit;
}

// Fetch all feedback from the database
$feedback_query = "SELECT feedback.id, users.username, users.email, feedback.event_name, feedback.rating, feedback.feedback_text, feedback.created_at 
                   FROM feedback
                   JOIN users ON feedback.user_id = users.user_id";
$feedback_result = $conn->query($feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Feedback</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #f4f7fa;
            font-size: 16px;
            margin: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Button Styling */
        .btn-delete {
            padding: 8px 12px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h1>User Feedback</h1>
    
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Event Name</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Date Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($feedback = $feedback_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($feedback['username']); ?></td>
                <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                <td><?php echo htmlspecialchars($feedback['event_name']); ?></td>
                <td><?php echo htmlspecialchars($feedback['rating']); ?></td>
                <td><?php echo htmlspecialchars($feedback['feedback_text']); ?></td>
                <td><?php echo $feedback['created_at']; ?></td>
                <td>
                    <!-- Admin can delete feedback -->
                    <a href="delete_feedback.php?id=<?php echo $feedback['id']; ?>" class="btn-delete">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
