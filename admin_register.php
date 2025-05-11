<?php
// Include database connection
include('server.php.php');

// Include password helper
include('password_helper.php'); 

// Start session for login
session_start();

// Handle the form submission (Register or Login)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) { {
       
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!empty($username) && !empty($password)) {
            // Fetch admin by username
            $query = "SELECT * FROM admin WHERE username = ?" ;
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                if (verifyPassword($password, $admin['password'])) {
                    // Set session and redirect to the dashboard
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['user_role'] = 'admin';  // Set user role to 'admin
                    header("Location: Dashboard.php");
                    exit;
                } else {
                    echo "<script>alert('Incorrect password.');</script>";
                }
            } else {
                echo "<script>alert('Admin not found.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('All fields are required.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration & Login</title>
    <style>
        /* Simple styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Admin Authentication</h2>

        
        <!-- Login Form (Hidden by default) -->
        <div id="login-form" class="form-section">
            <h3>Login</h3>
            <form action="admin_register.php" method="POST">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="button">Login</button>
            </form>
        </div>
    </div>

    <script>
        // Function to show the selected form and hide the other
        function showForm(formType) {
             if (formType === 'login') {
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('register-form').style.display = 'none';
                document.getElementById('register-btn').style.display = 'none';
                document.getElementById('login-btn').style.display = 'none';
            }
        }
    </script>
</body>
</html>
