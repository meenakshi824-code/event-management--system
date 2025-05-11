<?php
session_start();

// Include the database connection file
include('server.php.php');

// Function to generate OTP
function generateOTP()
{
    return rand(100000, 999999); // Generates a 6-digit OTP
}

// Handle Email Reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Simulate sending a reset link (In real scenarios, integrate email service)
        echo "<script>alert('A reset link has been sent to $email.');</script>";
    } else {
        echo "<script>alert('Email not found. Please try again.');</script>";
    }
}

// Handle Phone OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_otp'])) {
    $phone = $_POST['phone'];

    // Find the corresponding email for the phone number
    $stmt = $conn->prepare("SELECT email FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        $_SESSION['otp'] = generateOTP(); // Save OTP in the session
        $_SESSION['phone'] = $phone;

        // Simulate sending OTP (In real scenarios, integrate SMS service)
        echo "<script>alert('OTP has been sent to $phone.');</script>";
    } else {
        echo "<script>alert('Phone number not found. Please try again.');</script>";
    }
}

// Verify OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $enteredOtp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        echo "<script>alert('OTP verified successfully! You can now reset your password.');</script>";
        unset($_SESSION['otp']); // Clear OTP after successful verification
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        /* Add your styles from the provided HTML code */
    </style>
</head>
<body>

    <div class="container">
        <h2>Forgot Password</h2>
        <p>Choose an option to reset your password:</p>
        <p>
            <a onclick="showEmailInput()">Reset via Email</a> | 
            <a onclick="showPhoneInput()">Reset via Phone</a>
        </p>
        <form method="POST" action="">
            <input type="email" id="email" name="email" placeholder="Email" style="display: none;">
            <button type="submit" name="reset_email" id="send-email" style="display: none;">Send Reset Link</button>
        </form>
        <form method="POST" action="">
            <input type="tel" id="phone" name="phone" placeholder="Phone Number" style="display: none;">
            <button type="submit" name="send_otp" id="send-otp" style="display: none;">Send OTP</button>
        </form>
        <div class="otp-section" id="otp-section" style="display: none;">
            <form method="POST" action="">
                <p>Enter the OTP sent to your phone:</p>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                <button type="submit" name="verify_otp">Verify OTP</button>
            </form>
        </div>
        <p><a href="index.php">Back to Login</a></p>
    </div>

    <script>
        function showEmailInput() {
            document.getElementById('email').style.display = 'block';
            document.getElementById('phone').style.display = 'none';
            document.getElementById('send-email').style.display = 'block';
            document.getElementById('send-otp').style.display = 'none';
            document.getElementById('otp-section').style.display = 'none';
        }

        function showPhoneInput() {
            document.getElementById('phone').style.display = 'block';
            document.getElementById('email').style.display = 'none';
            document.getElementById('send-otp').style.display = 'block';
            document.getElementById('send-email').style.display = 'none';
            document.getElementById('otp-section').style.display = 'none';
        }
    </script>

</body>
</html>
