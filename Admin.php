<?php
// Include the database connection
include('server.php.php');
include('password_helper.php');

// Start session to store login information
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate fields are not empty
    if (!empty($userName) && !empty($password)) {
        // Prepare the query to fetch user details by username
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $userName);  // "s" means string

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Admin found, fetch the details
            $admin = $result->fetch_assoc();

            // Verify the password using password_verify function from password_helper.php
            if (verifyPassword($password, $admin['password'])) {
                // Password is correct, start the session
                //$_SESSION['username'] = $admin['username'];
                $_SESSION['password'] = $admin['password'];

                // Redirect to the dashboard or admin page
                echo "<script type='text/javascript'> 
                alert('Login Successful!'); 
                window.location.href='Dashboard.html'; </script>";
            } else {
                // Invalid password
                echo "<script type='text/javascript'> alert('Invalid credentials.'); </script>";
            }
        } else {
            // No admin found with that username
            echo "<script type='text/javascript'> alert('No admin found with that username.'); </script>";
        }

        $stmt->close();
    } else {
        // Missing username or password
        echo "<script type='text/javascript'> alert('Please enter valid information'); </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
   
    <style>
        /* Css styling*/


body {
    font-family: Arial, sans-serif;
    background-color: #b7f795;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px;
    color: #333;
}

/* Login Container */
.login-container {
    background-color: rgb(161, 241, 151);
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
}

/* Input Styles */
.input-group {
    margin-bottom: 20px;
}

.input-group label {
    font-size: 16px;
    color: #555;
    display: block;
    margin-bottom: 8px;
}

.input-group input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #f6c5c5;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Button Styles */
.login-btn {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    color: white;
    background-color: #3498db;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.login-btn:hover {
    background-color: #2980b9;
}

.login-btn:active {
    background-color: #1d6fa5;
}
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="Admin.php" method="POST" class="login-form">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>