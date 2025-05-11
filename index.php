<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login2.php');
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
            background: url('collage.jpg') no-repeat center center/cover;
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
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Jubilee Junction</h1>
    </header>

    <nav>
        <a href="#home">Home</a>
        <a href="#contact">Contact</a>
        <a href="#about">About Us</a>
        <a href="Eventpages.html">Go to Events</a>
        <?php if (isset($_SESSION['username'])) : ?>
            <a href="index.php?logout='1'" style="color: red;">logout</a>
        <?php else : ?>
            <button onclick="showPopup()">Login</button>
        <?php endif; ?>
    </nav>

    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success" >
            <h3>
            <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
            ?>
            </h3>
        </div>
        <?php endif ?>
  
      <!-- logged in user information -->
      <?php  if (isset($_SESSION['username'])) : ?>
          <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
          
      <?php endif ?>
  </div>

    <div class="hero">
        <h1>Your Gateway to Memorable Events</h1>
        <p>Find and organize events effortlessly</p>
    </div>

    <!-- Popup Overlay -->
    <div class="popup-overlay" id="loginPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup()">×</button>
            <!-- Embed Existing Login Page -->
            <iframe src="login2.html"></iframe>
        </div>
        
    </div>

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

    <section id="about">
        <h2 style="text-align: center; margin-top: 20px;">About Us</h2>
        <p style="text-align: center;">At Jubilee Junction, we believe every event has a unique story to tell. Our team has managed many events, ranging from intimate gatherings to grand celebrations. We are dedicated to transforming your ideas into extraordinary experiences that exceed expectations.</p>
    
        <h3 style="text-align: center; margin-top: 20px;">Our Mission</h3>
        <p  style="text-align: center;">To craft unforgettable event experiences that inspire joy and connection, tailored to our clients' unique stories.</p>
    
        <h3 style="text-align: center; margin-top: 20px;">Our Vision</h3>
        <p style="text-align: center;">To be the most trusted and innovative event management company worldwide.</p>
    
        <h3 style="text-align: center; margin-top: 20px;">Core values</h3>
        <ul>
            <li><strong>Innovation:</strong> Always bringing fresh ideas to life.</li>
            <li><strong>Dedication:</strong> Committed to delivering perfection in every event.</li>
            <li><strong>Sustainability:</strong> Promoting eco-friendly practices in event planning.</li>
        </ul>
        <p>Let us turn your dreams into reality. <strong>Contact us today!</strong></p>
       
    </section>
    <section id="contact">
        <h2 style="text-align: center; margin-top: 20px;">Contact Us</h2>
        <p>We’d love to hear from you! Here’s how you can reach us:</p>
        <div class="contact-info">
            <p><strong>Phone:</strong> +91 9892169335</p>
            <p><strong>Email:</strong> eventmanagement@gmail.com</p>
            <p><strong>Address:</strong>jubliee Junction,Mumbai , India</p>
            <p><strong>Business Hours:</strong> Mon-Fri: 9am - 6pm</p>
        </div>
    </section>
    <footer>
        <p> 2024 Event Management. All Rights Reserved.</p>
    </footer>
</body>
</html>
