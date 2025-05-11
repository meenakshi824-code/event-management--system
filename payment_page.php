<?php
// Include the database connection file (server.php)
include('server.php.php');

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User ID not found in session. Please log in again.");
}

$Id = $_SESSION['user_id']; // Get the logged-in user ID

// Get the event ID and amount from URL parameters
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';

// Fetch event details from the database using the event ID
$query = "SELECT event_name, event_date, event_location FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->bind_result($event_name, $event_date, $event_location);
$stmt->fetch();
$stmt->close();

// Initialize variables for receipt section
$paymentSuccessful = false;
$paymentMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulate payment process (you would replace this with actual payment gateway logic)
    $paymentSuccessful = true; // Set to true to simulate a successful payment
    $paymentMessage = "Payment was successful!";
    
    if ($paymentSuccessful) {
        // Update the booking status to 'paid'
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE event_id = ? AND user_id = ?");
        $status = 'paid'; // Set payment status to 'paid'
        $stmt->bind_param("sii", $status, $event_id, $Id);
        
        if ($stmt->execute()) {
            // Payment successful and booking updated
            // Optionally, you can add email confirmation here
        } else {
            $paymentMessage = "Error updating booking status.";
        }
    } else {
        $paymentMessage = "Payment failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            background-color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .receipt-section {
            background-color: #e0ffe0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            display: <?php echo $paymentSuccessful ? 'block' : 'none'; ?>;
        }

        .payment-status {
            font-size: 16px;
            color: green;
            font-weight: bold;
        }

        .receipt-details {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Event Payment</h2>

        <!-- Payment Form -->
        <form action="payment_page.php?event_id=<?php echo $event_id; ?>&amount=<?php echo $amount; ?>" method="POST">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">

            <label for="name">Cardholder Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" maxlength="19" required>

            <label for="expiry-date">Expiry Date</label>
            <input type="month" id="expiry-date" name="expiry-date" required>

            <label for="cvv">CVV</label>
            <input type="password" id="cvv" name="cvv" placeholder="123" maxlength="3" required>

            <label for="card-type">Card Type</label>
            <select id="card-type" name="card-type" required>
                <option value="visa">Visa</option>
                <option value="mastercard">MasterCard</option>
                <option value="rupay">RuPay</option>
                <option value="amex">American Express</option>
            </select>

            <button type="submit">Pay Now</button>
        </form>

        <!-- Receipt Section -->
        <div class="receipt-section">
            <p class="payment-status"><?php echo $paymentMessage; ?></p>

            <?php if ($paymentSuccessful): ?>
                <div class="receipt-details">
                    <h3>Receipt</h3>
                    <p><strong>Event:</strong> <?php echo htmlspecialchars($event_name); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($event_date); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event_location); ?></p>
                    <p><strong>Amount Paid:</strong> $<?php echo htmlspecialchars($amount); ?></p>
                    <p>A receipt has been sent to your email.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
