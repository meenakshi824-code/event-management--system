<?php 
include('server.php.php') ;

// Start session
session_start();

// Generate OTP function
function generateOTP() {
    return rand(100000, 999999); // Generates a 6-digit OTP
}


// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id']; // Store user ID
        $_SESSION['role'] = 'user'; // Store user role
        header("Location: user_dashboard.php"); // Redirect to user dashboard
        exit();
    }

    // If not found in users, check in the admin table
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $email); // Assuming username is used instead of email for admin login
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['username'] = $admin['username'];
        $_SESSION['id'] = $admin['id'];
        $_SESSION['role'] = 'admin'; // Mark as admin
        header("Location: Dashboard.php"); // Redirect to admin dashboard
        exit();
    }

    // If neither user nor admin is found
    $_SESSION['error'] = "Invalid email/username or password.";
    header("Location: login2.php");
    exit();
}


// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Successfully Registered. Please Login.";
            header("Location: login2.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $conn->error;
        }
    }
}

// Handle forgot password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $otp = generateOTP();
        $_SESSION['otp'] = $otp;
        $_SESSION['reset_email'] = $email;
        echo "<script>alert('An OTP has been sent to your email.');</script>";
    } else {
        $_SESSION['error'] = "No account found with this email.";
    }
}

// Verify OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $enteredOtp = $_POST['otp'];

    if ($_SESSION['otp'] == $enteredOtp) {
        $_SESSION['otp_verified'] = true;
        echo "<script>alert('OTP verified. Please reset your password.');</script>";
    } else {
        echo "<script>alert('Invalid OTP. Try again.');</script>";
    }
}

// Reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $email = $_SESSION['reset_email'];

        $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Password reset successfully. Please login.');</script>";
            unset($_SESSION['reset_email'], $_SESSION['otp'], $_SESSION['otp_verified']);
        } else {
            echo "<script>alert('Error resetting password.');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration & Login</title>
    <style>
        /* Your CSS styles here */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #9ef5d8;
            background: url('jj.jpeg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 350px;
            background-color: #f9c1c1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 15px;
            padding: 20px;
        }

        .form-container {
            display: none;
            padding: 20px;
        }

        .form-container.active {
            display: block;
        }

        .form-container h2 {
            margin-bottom: 24px;
            color: #333;
        }

        .form-container input {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 14px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .form-container input:focus {
            outline: none;
            border-color: #007bff;
            background-color: #fff;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .form-container button:hover {
            background: linear-gradient(135deg, #0056b3, #007bff);
        }

        .form-container p {
            color: #555;
            font-size: 14px;
            text-align: center;
        }

        .form-container p a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Display error or success messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Login Form -->
    <div id="login-form" class="form-container active">
        <h2>Login to Your Account</h2>
        <form method="POST" action="login2.php">
            <input type="email" name="email" id="login-email" placeholder="Email" required>
            <input type="password" name="password" id="login-password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="#" onclick="showRegister()">Register here</a></p>
        <p><a href="#" onclick="showForgotPassword()">Forgot Password?</a></p>
    </div>

    <!-- Registration Form -->
    <div id="registration-form" class="form-container">
        <h2>Create Account</h2>
        <form method="POST" action="login2.php">
            <input type="text" name="fullname" id="reg-fullname" placeholder="Fullname" required>
            <input type="text" name="username" id="reg-username" placeholder="Username" required>
            <input type="email" name="email" id="reg-email" placeholder="Email" required>
            <input type="password" name="password" id="reg-password" placeholder="Password" required>
            <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="#" onclick="showLogin()">Login here</a></p>
    </div>

    <!-- Forgot Password Form -->
    <div id="forgot-password-form" class="form-container">
        <h2>Forgot Password</h2>
        <button onclick="showResetOptions()">Choose Reset Method</button>
        <p><a href="#" onclick="showLogin()">Back to Login</a></p>
    </div>

    <!-- Reset Options -->
    <div id="reset-options" class="form-container">
        <h2>Reset Password Options</h2>
        <button onclick="showEmailReset()">Reset via Email</button>
        <button onclick="showPhoneReset()">Reset via Phone</button>
        <p><a href="#" onclick="showForgotPassword()">Back to Forgot Password</a></p>
    </div>

    <!-- Email Reset Form -->
    <div id="email-reset-form" class="form-container">
        <h2>Reset via Email</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="reset_email">Send OTP</button>
        </form>
        <p><a href="#" onclick="showResetOptions()">Back to Reset Options</a></p>
    </div>
</div>

<script>
    function showForm(formId) {
        // Hide all forms
        const forms = document.querySelectorAll('.form-container');
        forms.forEach(form => form.classList.remove('active'));

        // Show the selected form
        document.getElementById(formId).classList.add('active');
    }

    function showLogin() {
        showForm('login-form');
    }

    function showRegister() {
        showForm('registration-form');
    }

    function showForgotPassword() {
        showForm('forgot-password-form');
    }

    function showResetOptions() {
        showForm('reset-options');
    }

    function showEmailReset() {
        showForm('email-reset-form');
    }

    function showPhoneReset() {
        alert('Phone reset functionality is not implemented yet.');
    }
</script>

</body>
</html>
