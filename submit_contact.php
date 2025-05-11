<?php
// Include your existing database connector
include('server.php.php');  // Assuming your connector file is named 'db_connector.php'

// Fetch unread notification count
$query = "SELECT COUNT(*) AS unread_count FROM contact_messages WHERE status = 'unread'"; 
$result = $connection->query($query);
$row = $result->fetch_assoc();
$unread_count = $row['unread_count'];


// Function to handle contact form submission
function submit_contact($name, $email, $message) {
    global $connection;  // Use the existing mysqli connection from the server.php file

    try {
        // SQL query to insert contact form data into the database
        $sql = "INSERT INTO contact_messages (name, email, message, created_at) 
                VALUES (?, ?, ?, NOW())";
        
        // Prepare the SQL statement
        $stmt = $connection->prepare($sql);
        
        // Bind values to the SQL query
        $stmt->bind_param("sss", $name, $email, $message);

        // Execute the SQL query to insert the data into the table
        $stmt->execute();

        // Return a success message
        return "Your message has been submitted successfully!";
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Call the function to submit the contact form
    $response = submit_contact($name, $email, $message);

    // Output the response message
    echo $response;
}
?>