<?php 
  session_start(); 
  
  
  // Ensure the homepage (`index2.php`) loads without redirect loops
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
}
  


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <style>
        /* CSS Styling */
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8b4f3;
        }

        header {
            background: #f830f8d0;
            color: #faf6f6;
            padding: 10px 20px;
            text-align: center;
        }
        .content {
            padding: 50px;
        }
        button {
            padding: 5px 5px;
            font-size: 18px;
            background-color: #b7f5ef;
            color: rgb(9, 0, 0);
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4e9df1;
        }
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            height: 80%;
            max-height: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .popup-content iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 18px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #333;
        }

        nav {
            display: flex;
            justify-content: center;
            background: #333;
            padding: 10px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 15px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .hero {
            text-align: center;
            padding: 200px;
            color: #f9faf5;
            background: url('coll.jpg') no-repeat center center/cover;
        }

        .hero h1 {
            font-size: 30px;
            margin: 0;
        }

        .hero p {
            font-size: 20px;
        }

        .events {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 15px;
        }

        .event {
            background: #ade8ef;
            margin: 10px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 150px;
            text-align: center;
        }

        .event h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .event p {
            font-size: 16px;
        }
        
        footer {
            text-align: center;
            background: #333;
            color: #fff;
            padding: 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .admin-btn {
        background-color: #ff6347;  /* Add any color you prefer */
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        margin-left: 15px;
        }

        .admin-btn:hover {
        background-color: #d35400;  /* Add hover effect */
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Jubilee Junction</h1>
    </header>

    <nav>
        <a href="#home">Home</a>
        
        <a href="about_us.php">About Us</a>
        <a href="Eventpages.html">Events</a>
        <?php if (isset($_SESSION['username'])) : ?>
        <!-- Show "My Account" and "Logout" if the user is logged in -->
        <a href="user_dashboard.php">My Account</a>
        
        
        <?php 
        // Check if the user is an admin
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
            <!-- Show "Admin" link if the user is an admin -->
            <a href="Dashboard.php">Admin Dashboard</a>
        <?php endif; ?>

    <?php else : ?>
        <!-- Show "Login" if the user is not logged in -->
        <button onclick="showPopup()">Login</button>
        <!-- Add Admin Login link -->
        <a href="admin_register.php" class="admin-btn">Admin Login</a>
    <?php endif; ?>
            
    </nav>

    

    <div class="hero">
        <h1>Your Gateway to Memorable Events</h1>
        <p>Find and organize events effortlessly</p>
    </div>

    <!-- Popup Overlay -->
    <?php if (!isset($_SESSION['username'])): ?>
    <div class="popup-overlay" id="loginPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup()">×</button>
            <!-- Embed Existing Login Page -->
            <iframe src="login2.php"></iframe>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Function to show the popup
        function showPopup() {
            document.getElementById('loginPopup').style.display = 'flex';
        }

        // Function to hide the popup
        function closePopup() {
            document.getElementById('loginPopup').style.display = 'none';
        }
    </script>
    
    
    <footer>
        <p> 2025 Event Management. All Rights Reserved.</p>
    </footer>
</body>
</html>