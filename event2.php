<?php
// Include the database connection file (server.php)
include('server.php.php');

// Start the session
session_start();


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  die("Error: User ID not found in session. Please log in again.");
}

$Id = $_SESSION['user_id']; // Now, user_id is correctly set




// Retrieve event details from URL parameters
$eventName = isset($_GET['eventName']) ? $_GET['eventName'] : '';
$eventDescription = isset($_GET['eventDescription']) ? $_GET['eventDescription'] : '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from POST request
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $eventLocation = $_POST['eventLocation'];
    $eventDescription = $_POST['eventDescription'];
    $maxGuests = $_POST['eventGuests'];

    // Check if maxGuests is empty
    if (empty($maxGuests)) {
        echo "The number of guests is required and cannot be empty.";
        exit();
    }

     // Prepare SQL query to insert the event
     $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_location, event_description, max_guests) VALUES (?, ?, ?, ?, ?)");
     $stmt->bind_param("ssssi", $eventName, $eventDate, $eventLocation, $eventDescription, $maxGuests);
 
     // Execute the query
     if ($stmt->execute()) {
         // Get the inserted event ID for the booking
         $eventId = $stmt->insert_id;
 
         
 

      //Insert booking into database
      $bookingStmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, event_name, event_date, status) VALUES (?, ?, ?, ?, 'pending')");
      $bookingStmt->bind_param("iiss", $Id, $eventId, $eventName, $eventDate);
      
      if ($bookingStmt->execute()) {
        // Get the booking ID and amount
        $bookingId = $bookingStmt->insert_id;
        

        // Redirect to payment page with booking ID and amount
        header("Location: payment_page.php?bookingId=$bookingId");
        exit();
    } else {
        echo "<script>alert('Error creating booking: " . $bookingStmt->error . "');</script>";
    }

    $bookingStmt->close();
} else {
    echo "<script>alert('Error: " . $stmt->error . "');</script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Event</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    header {
      background-color: #0073e6;
      color: white;
      padding: 10px 20px;
      text-align: center;
    }

    .container {
      width: 80%;
      margin: 20px auto;
    }

    form {
      background: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }

    form input, form textarea, form button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    form button {
      background-color: #0073e6;
      color: white;
      border: none;
      cursor: pointer;
    }

    form button:hover {
      background-color: #005bb5;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      text-decoration: none;
      color: #0073e6;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <header>
    <h1>Register Your Event</h1>
  </header>

  <div class="container">
    <!-- Updated Back Link -->
    <a href="Eventpages.html" class="back-link">&larr; Back to Events</a>

     <!-- Registration Form -->
    <form action="event2.php?eventName=<?php echo urlencode($eventName); ?>&eventDescription=<?php echo urlencode($eventDescription); ?>" method="POST">
        <label for="eventName">Event Name:</label>
        <input type="text" id="eventName" name="eventName" value="<?php echo htmlspecialchars($eventName); ?>" required readonly>

        <label for="eventDate">Event Date:</label>
        <input type="date" id="eventDate" name="eventDate" required>

        <label for="eventLocation">Event Location:</label>
        <input type="text" id="eventLocation" name="eventLocation" placeholder="Enter event location" required>

        <label for="eventDescription">Event Description:</label>
        <textarea id="eventDescription" name="eventDescription" rows="4" required readonly><?php echo htmlspecialchars($eventDescription); ?></textarea>

        <label for="eventGuests">Number of Guests:</label>
        <input type="number" id="eventGuests" name="eventGuests" min="1" required>

        <button type="submit">Register Event</button>
    </form>
  </div>
  
  
</body>
</html>
