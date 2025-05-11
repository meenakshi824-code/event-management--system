<?php
// Include the database connection file
include('server.php.php');  // Update with your actual database connection file

// Check if event_id is passed in the URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch the event details from the database
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the event is found, store the details in a variable
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        // If event doesn't exist, redirect to manage events page
        header('Location: manage_events.php');
        exit;
    }
} else {
    // If no event_id is passed, redirect to manage events page
    header('Location: manage_events.php');
    exit;
}

// Add Event logic (POST request to add a new event)
if (isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_price = $_POST['event_price'];

    // Insert the new event into the database
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_description, event_date, event_location, event_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $event_name, $event_description, $event_date, $event_location, $event_price);
    $stmt->execute();
    $stmt->close();
    
    echo "<script>alert('Event added successfully');</script>";
}

// Update Event logic (POST request to update existing event)
if (isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_price = $_POST['event_price'];

    // Update event details in the database
    $stmt = $conn->prepare("UPDATE events SET event_name = ?, event_description = ?, event_date = ?, event_location = ?, event_price = ?, created_at = CURRENT_TIMESTAMP WHERE event_id = ?");
    $stmt->bind_param("ssssdi", $event_name, $event_description, $event_date, $event_location, $event_price, $event_id);
    $stmt->execute();
    $stmt->close();
    
    echo "<script>alert('Event updated successfully');</script>";
}

// Fetch the existing events to display
$query = "SELECT event_id, event_name, event_description, event_date, event_location, event_price FROM events";
$event_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            text-align: left;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        form {
            margin: 20px 0;
        }

        input, textarea, button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        input[type="number"] {
            width: auto;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Manage Events</h2>

        <!-- Add Event Form -->
        <form action="manage_events.php" method="POST">
            <h3>Add New Event</h3>
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" required><br><br>

            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required><br><br>

            <label for="event_location">Event Location:</label>
            <input type="text" id="event_location" name="event_location"><br><br>

            <label for="event_description">Event Description:</label>
            <textarea id="event_description" name="event_description" required></textarea><br><br>

            <label for="event_price">Event Price:</label>
            <input type="number" step="0.01" id="event_price" name="event_price" required><br><br>

            <button type="submit" name="add_event">Add Event</button>
        </form>

        <!-- Existing Events Display and Update -->
        <h3>Existing Events</h3>
        <?php if ($event_result && $event_result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $event_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['event_name']; ?></td>
                            <td><?php echo $row['event_date']; ?></td>
                            <td><?php echo $row['event_location']; ?></td>
                            <td><?php echo $row['event_description']; ?></td>
                            <td><?php echo $row['event_price']; ?></td>
                            
                            <td>
                                <!-- Update Event Form -->
                                <form action="manage_events.php" method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                    <input type="text" name="event_name" value="<?php echo $row['event_name']; ?>">
                                    <input type="date" name="event_date" value="<?php echo $row['event_date']; ?>">
                                    <input type="text" name="event_location" value="<?php echo $row['event_location']; ?>">
                                    <textarea name="event_description"><?php echo $row['event_description']; ?></textarea>
                                    <input type="number" step="0.01" name="event_price" value="<?php echo $row['event_price']; ?>">
                                    <button type="submit" name="update_event">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No events found</p>
        <?php } ?>
    </div>

</body>
</html>
