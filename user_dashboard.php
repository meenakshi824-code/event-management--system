<?php
session_start();
include('server.php.php'); // Include database connection


// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // If not, redirect to login page
    header("Location: login2.php");
    exit();
}

// ✅ Retrieve username and user_id from session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle password change (if submitted via form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New password and confirm password do not match.";
    } else {
        // Fetch the user's current password hash from the database
        $query = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($current_password, $user['password'])) {
                // Update the password in the database
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("si", $hashed_password, $username);

                if ($update_stmt->execute()) {
                    $_SESSION['success'] = "Password changed successfully.";
                } else {
                    $_SESSION['error'] = "Error updating password.";
                }
            } else {
                $_SESSION['error'] = "Current password is incorrect.";
            }
        } else {
            $_SESSION['error'] = "User not found.";
        }
    }
}
// Fetch user's booking details
$query = "SELECT e.event_name, e.event_date, b.status FROM bookings b 
          JOIN events e ON b.event_id = e.event_id 
          WHERE b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            border-radius: 8px;
            overflow: hidden;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            z-index: 1000;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        .sidebar {
            background-color: #2d3e50;
            color: #fff;
            width: 250px;
            padding: 30px;
            box-sizing: border-box;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            margin-top: 60px; /* Add margin to move the heading below the back button */
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 50px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #4CAF50;
        }

        .main-content {
            margin-left: 250px;
            padding: 40px;
            background-color: #fff;
            width: 100%;
        }

        .section {
            display: none;
        }

        .active-section {
            display: block;
        }

        .user-info, .user-events, .user-actions, .password-change-form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .user-info h3, .user-events h3, .user-actions h3, .password-change-form h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }

        .user-info p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        .user-events table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-events th, .user-events td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-events th {
            background-color: #f4f4f4;
            font-weight: 600;
        }

        .password-change-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .password-change-form button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .password-change-form button:hover {
            background-color: #45a049;
        }

        .user-actions button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 15px;
        }

        .user-actions button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
     <!-- Back to Homepage Button -->
     <a href="index2.php" class="back-button">Back </a>

    <div class="dashboard-container">
        <div class="sidebar">
            <h2>User Dashboard</h2>
            <ul>
                <li><a href="javascript:void(0);" onclick="showSection('profile')">My Profile</a></li>
                <li><a href="javascript:void(0);" onclick="showSection('bookings')">Booking Details</a></li>
                <li><a href="javascript:void(0);" onclick="showSection('password')">Change Password</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="logout.php">Logout</a></li>
                 
            </ul>
        </div>

        <div class="main-content">
            <!-- My Profile Section -->
            <div id="profile" class="section active-section user-info">
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                
                
            </div>

            <!-- Booking Details Section -->
            <div id="bookings" class="section user-events">
                <h3>Your Booking Details</h3>
                <table>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                    <td><?php echo htmlspecialchars($booking['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td> 
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3">No bookings found.</td>
                    </tr>
                    <?php endif; ?>        
                    

                </table>
            </div>

            <!-- Change Password Section -->
            <div id="password" class="section password-change-form">
                <h3>Change Password</h3>
                
                <!-- Display any errors or success messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                <?php endif; ?>
                <form method="POST" action="user_dashboard.php">
                    <label for="current-password">Current Password:</label>
                    <input type="password" id="current-password" name="current_password" required>

                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new_password" required>

                    <label for="confirm-password">Confirm New Password:</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>

                    <button type="submit" name="change_password">Change Password</button>
                </form>
            </div>
        </div>
    </div>

            <!-- User Actions Section -->
            <div id="actions" class="section user-actions">
                <h3>Actions</h3>
                <button onclick="showSection('profile')">Update Profile</button>
                <button onclick="showSection('password')">Change Password</button>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle between sections
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(function(section) {
                section.classList.remove('active-section');
            });

            // Show the selected section
            document.getElementById(sectionId).classList.add('active-section');
        }

        // Logout function (just simulates logout)
        function logout() {
            alert("You have logged out successfully.");
            window.location.login2.php; // You can replace this with a redirect to login page
        }

        // Handle password change form submission
        document.getElementById('passwordForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                alert("New password and confirm password do not match!");
                return;
            }

            alert("Password changed successfully!");
        });
    </script>
</body>
</html>