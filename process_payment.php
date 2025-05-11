<?php
// Include the database connection file (server.php)
include('server.php.php');

// Start the session
session_start();

// Check if payment data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'], $_POST['amount'], $_POST['name'], $_POST['card-number'], $_POST['expiry-date'], $_POST['cvv'], $_POST['card-type'])) {
    $event_id = $_POST['event_id'];
    $amount = $_POST['amount'];
    $name = $_POST['name'];
    $cardNumber = $_POST['card-number'];
    $expiryDate = $_POST['expiry-date'];
    $cvv = $_POST['cvv'];
    $cardType = $_POST['card-type'];

    // Simulate a payment process (you can replace this with actual payment gateway logic)
    $paymentSuccessful = true; // This would be a response from the payment gateway

    if ($paymentSuccessful) {
        // Update booking status to "paid" in the database
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE event_id = ? AND user_id = ?");
        $status = 'paid'; // Set payment status to 'paid'
        $stmt->bind_param("sii", $status, $event_id, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Payment was successful, redirect to the confirmation page
            header("Location: payment_page.php?event_id=$event_id&amount=$amount");
            exit();
        } else {
            echo "Error updating booking status: " . $stmt->error;
        }
    } else {
        echo "Payment failed. Please try again.";
    }
} else {
    echo "Invalid payment details.";
}

$conn->close();
?>
