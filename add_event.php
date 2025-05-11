<?php
// Include database connection
include('server.php.php');



// Check if the form is submitted to add a new event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_price = $_POST['event_price'];

    // Insert the new event into the database
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_location, event_description, event_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_name, $event_date, $event_location, $event_description, $event_price);

    if ($stmt->execute()) {
        echo "<script>alert('Event added successfully');</script>";
        header("Location: manage_events.php");
    } else {
        echo "<script>alert('Error adding event');</script>";
    }

    $stmt->close();
}
?>

<!-- HTML Form for Adding a New Event -->
<div id="add_event">
    <h2>Add New Event</h2>
    <form method="POST" action="add_event.php">
        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name" required><br><br>

        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" required><br><br>

        <label for="event_location">Event Location:</label>
        <input type="text" id="event_location" name="event_location" required><br><br>

        <label for="event_description">Event Description:</label>
        <textarea id="event_description" name="event_description" required></textarea><br><br>

        <label for="event_price">Event Price:</label>
        <input type="number" id="event_price" name="event_price" required><br><br>

        <button type="submit" name="add_event">Add Event</button>
    </form>
</div>
