<?php
// Start the session to check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_register.php"); // Redirect to login if not authenticated
    exit;
}

// Database connection (Replace with your own connection code)
include('server.php.php');


// Fetch feedback data
$feedback_query = "SELECT feedback.id, users.username, feedback.event_name, feedback.rating, feedback.feedback_text, feedback.created_at
                   FROM feedback
                   JOIN users ON feedback.user_id = users.user_id";
$feedback_result = $conn->query($feedback_query);

// Fetching user data
$user_query = "SELECT user_id, username, email, created_at FROM users";
$user_result = $conn->query($user_query);

// Fetch event details and booking status
$query = "SELECT * FROM events";
$event_result = $conn->query($query);

// Fetching dashboard data
$dashboard_data = [
    'total_events' => $conn->query("SELECT COUNT(*) AS total FROM events")->fetch_assoc()['total'],
    'total_users' => $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'],
    'total_bookings' => $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'],
    'new_bookings' => $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'Pending'")->fetch_assoc()['total'],
    'confirmed_bookings' => $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'Approved'")->fetch_assoc()['total'],
    'cancelled_bookings' => $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'Cancelled'")->fetch_assoc()['total']
];

    // SQL query to fetch the latest contact form submissions
$sql = "SELECT * FROM contact_messages ORDER BY submitted_at DESC LIMIT 5";  // Fetch the latest 5 submissions

try {
    // Execute the query
    $result = $conn->query($sql);  // Use MySQLi's query method

    // Fetch the latest contact form submissions
    $contact_forms = [];
    while ($row = $result->fetch_assoc()) {
        $contact_forms[] = $row;
    }
} catch (Exception $e) {
    echo "Error fetching contact forms: " . $e->getMessage();
    // Optionally, log the error message for debugging without stopping the script
    error_log($e->getMessage());
    // You can also redirect the user or provide a user-friendly error message
}


    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style> 
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        /* Main content styles */
        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
            background-color: #ecf0f1;
            overflow-y: auto;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
        }

        .navbar-left h1 {
            font-size: 24px;
        }

        .navbar-right span {
            font-size: 18px;
        }

        .panel {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .panel h2 {
            font-size: 26px;
            margin-bottom: 20px;
        }

        .panel-content {
            font-size: 18px;
            color: #34495e;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            color: #555;
        }

        /* User Section */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            width: 45%;  /* Adjust width for two cards per row */
            max-width: 450px;
            margin: 10px;
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            color: #555;
        }

        /* Responsive Adjustment */
        @media (max-width: 768px) {
            .card {
                width: 90%;  /* Single card per row on small screens */
            }

            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
            }
        }

        
    </style>
    <script>
        function showCards(module) {
            const sections = document.querySelectorAll('.panel');
            sections.forEach(section => section.style.display = 'none');
            document.getElementById(module).style.display = 'block';
        }
        
        
    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#" onclick="showCards('dashboard')">Dashboard</a></li>
            <li><a href="#" onclick="showCards('events')">Manage Events</a></li>
            <li><a href="#" onclick="showCards('users')">Manage Users</a></li>
            <li><a href="#" onclick="showCards('bookings')">Manage Bookings</a></li>
            <li><a href="#" onclick="showCards('feedback')">Manage Feedback</a></li>
            
            <li><a href="#" onclick="showCards('notification')">Notification</a></li>
            <li><a href="logout.php">Logout</a></li>
            
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <!-- Top Navigation Bar -->
        <div class="navbar">
            <div class="navbar-left">
                <h1>Welcome, Admin</h1>
            </div>
    
    </div> 

        <!-- Dashboard Section -->
        <div id="dashboard" class="panel">
            <h2>Dashboard</h2>
            <div class="panel-content">
                <p>Total Events: <b><?php echo $dashboard_data['total_events']; ?></b></p>
                <p>Total Users: <b><?php echo $dashboard_data['total_users']; ?></b></p>
                <p>Total Bookings: <b><?php echo $dashboard_data['total_bookings']; ?></b></p>
                <p>New Bookings: <b><?php echo $dashboard_data['new_bookings']; ?></b></p>
                <p>Confirmed Bookings: <b><?php echo $dashboard_data['confirmed_bookings']; ?></b></p>
                <p>Cancelled Bookings: <b><?php echo $dashboard_data['cancelled_bookings']; ?></b></p>
            </div>
        </div>


        <!-- Booking Section -->
        <div id="bookings" class="panel" style="display: none;">  <!-- Adjust display as needed -->
    <h2>Manage Bookings</h2>
    <div class="card-container">
        <?php 
        // Check if the form to update status was submitted
        if (isset($_POST['update_status'])) {
            // Get the form data
            $id = $_POST['id'];
            $new_status = $_POST['status'];

            // Use a prepared statement to update the booking status in the database
            $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $id);
            $stmt->execute();
            $stmt->close();
            
            // Refresh the page to reflect changes
            header("Location: Dashboard.php");
            exit();
        }

        // Fetch the bookings from the database
        $query = "SELECT bookings.id, users.username, events.event_name, bookings.event_date, bookings.status
                  FROM bookings
                  JOIN users ON bookings.user_id = users.user_id
                  JOIN events ON bookings.event_id = events.event_id";
        $bookings_result = $conn->query($query);

        // Check if there are any bookings
        if ($bookings_result && $bookings_result->num_rows > 0) {
            // Loop through the bookings and display each one
            while ($row = $bookings_result->fetch_assoc()) {
        ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($row['username']); ?></h3>
                    <p>Event: <?php echo htmlspecialchars($row['event_name']); ?></p>
                    <p>Event Date: <?php echo htmlspecialchars($row['event_date']); ?></p>
                    <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>

                    <!-- Form to update the status -->
                    <form action="Dashboard.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <select name="status" required>
                            <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Approved" <?php if ($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                            <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                </div>
        <?php 
            }
        } else {
            echo "<p>No bookings available.</p>";
        }
        ?>
    </div>
</div>

        <!-- Feedback Section -->
        <div id="feedback" class="panel" style="display:none;">
            <h2>Manage Feedback</h2>
            <div class="card-container">
            <?php
            
        // Check if feedback exists
        if ($feedback_result->num_rows > 0) {
            while ($feedback = $feedback_result->fetch_assoc()) { ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($feedback['username']); ?></h3>
                    <p>Event: <?php echo htmlspecialchars($feedback['event_name']); ?></p>
                    <p>Rating: <?php echo htmlspecialchars($feedback['rating']); ?></p>
                    <p>Feedback: <?php echo htmlspecialchars($feedback['feedback_text']); ?></p>
                    <p>Created at: <?php echo $feedback['created_at']; ?></p>
                    <form action="delete_feedback.php" method="GET">
                        <input type="hidden" name="id" value="<?php echo $feedback['id']; ?>">
                        <button type="submit" class="btn btn-delete">Delete Feedback</button>
                    </form>
                </div>
            <?php }
        } else {
            echo "<p>No feedback available.</p>";
        }
        ?>
    </div>
</div>
        <!-- User Section -->
            <div id="users" class="panel" style="display:none;">
                <h2>Manage Users</h2>
                <div class="card-container">
                <?php 
            if ($user_result && $user_result->num_rows > 0) {
                while ($row = $user_result->fetch_assoc()) {
        ?>
            <div class="card">
                <h3><?php echo $row['username']; ?></h3>
                <p>Email: <?php echo $row['email']; ?></p>
                <p>Registered on: <?php echo $row['created_at']; ?></p>
            </div>
        <?php 
                }
            } else {
                echo "<p>No users found.</p>";
            }
        ?>
    </div>
</div>

    
        <!-- Event Panel -->
        <div id="events" class="panel" style="display:none;">
    <h2>Manage Events</h2>
    <div class="card-container">
        <!-- Show existing events for the admin to update -->
    <div class="card-container">
        <?php while ($row = $event_result->fetch_assoc()) { ?>
            <div class="card">
                <h3><?php echo $row['event_name']; ?></h3>
                <p>Date: <?php echo $row['event_date']; ?></p>
                <p>Location: <?php echo $row['event_location']; ?></p>
                <p>Description: <?php echo $row['event_description']; ?></p>
                <p>Price: <?php echo $row['event_price']; ?></p>

                <!-- Link to update event -->
                <a href="manage_events.php?event_id=<?php echo $row['event_id']; ?>">Edit Event</a>
            </div>
        <?php } ?>
    </div>

    <!-- Link to Add New Event -->
    <a href="add_event.php">Add New Event</a>
</div>
        
       
<!-- Notification Badge in Admin Panel -->

        
</body>
</html>
            