<?php
// Include database connection
include('server.php.php');



// Check if the event ID is provided
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch the existing event details
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();

    // If the form is submitted, update the event details
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_event'])) {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_location = $_POST['event_location'];
        $event_description = $_POST['event_description'];
        $event_price = $_POST['event_price'];

        // Update the event in the database
        $stmt = $conn->prepare("UPDATE events SET event_name = ?, event_date = ?, event_location = ?, event_description = ?, event_price = ? WHERE event_id = ?");
        $stmt->bind_param("sssssi", $event_name, $event_date, $event_location, $event_description, $event_price, $event_id);

        if ($stmt->execute()) {
            echo "<script>alert('Event updated successfully');</script>";
            header("Location: manage_events.php");
        } else {
            echo "<script>alert('Error updating event');</script>";
        }

        $stmt->close();
    }
} else {
    echo "<script>alert('Event not found');</script>";
    exit;
}
?>

<!-- HTML Form for Updating an Existing Event -->
<div id="update_event">
    <h2>Update Event</h2>
    <form method="POST" action="update_event.php?event_id=<?php echo $event['event_id']; ?>">
        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

        <label for="event_name">Event Name:</label>
        <input type="text" id="event_name" name="event_name" value="<?php echo $event['event_name']; ?>" required><br><br>

        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required><br><br>

        <label for="event_location">Event Location:</label>
        <input type="text" id="event_location" name="event_location" value="<?php echo $event['event_location']; ?>" required><br><br>

        <label for="event_description">Event Description:</label>
        <textarea id="event_description" name="event_description" required><?php echo $event['event_description']; ?></textarea><br><br>

        <label for="event_price">Event Price:</label>
        <input type="number" id="event_price" name="event_price" value="<?php echo $event['event_price']; ?>" required><br><br>

        <button type="submit" name="update_event">Update Event</button>
    </form>
</div>