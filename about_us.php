
<?php
// about_us.php

// Include the database connection
include('server.php.php'); // Your database connection file

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    // Sanitize input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, is_read) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $stmt->close();
        // Redirect to the same page with a success message
        header("Location: about_us.php?message_sent=true");
        exit;
    } else {
        echo "<script>alert('Error sending message. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Jubilee Junction</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <style>
  body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    color: #333;
    background-color: #f9f9f9;
}

header {
    background: #4CAF50;
    color: white;
    text-align: center;
    padding: 1.5rem 0;
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
}

header p {
    font-size: 1.2rem;
}

section {
    padding: 2rem;
    margin: 2rem auto;
    max-width: 800px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #4CAF50;
}

ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    margin-bottom: 0.5rem;
}

#contact-us form {
    display: flex;
    flex-direction: column;
}

#contact-us label {
    margin-top: 1rem;
    font-weight: bold;
}

#contact-us input,
#contact-us textarea {
    margin-top: 0.5rem;
    padding: 0.5rem;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

#contact-us button {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
}

#contact-us button:hover {
    background: #45a049;
}

footer {
    text-align: center;
    padding: 1rem;
    background: #4CAF50;
    color: white;
    margin-top: 2rem;
    font-size: 0.9rem;
}
  </style>
    <header>
        <h1>Jubilee Junction</h1>
        <p>Transforming Ideas Into Extraordinary Experiences</p>
    </header>

    <!-- Back Button -->
    <a href="index2.php" class="back-btn">&larr; Back </a>

    <section id="about-us">
        <h2>About Us</h2>
        <p>At Jubilee Junction, we believe every event has a unique story to tell. Our team has managed many events, ranging from intimate gatherings to grand celebrations. We are dedicated to transforming your ideas into extraordinary experiences that exceed expectations.</p>
    </section>

    <section id="mission-vision">
        <h2>Our Mission</h2>
        <p>To craft unforgettable event experiences that inspire joy and connection, tailored to our clients' unique stories.</p>

        <h2>Our Vision</h2>
        <p>To be the most trusted and innovative event management company worldwide.</p>
    </section>

    <section id="core-values">
        <h2>Core Values</h2>
        <ul>
            <li><strong>Innovation:</strong> Always bringing fresh ideas to life.</li>
            <li><strong>Dedication:</strong> Committed to delivering perfection in every event.</li>
            <li><strong>Sustainability:</strong> Promoting eco-friendly practices in event planning.</li>
        </ul>
    </section>

    <section id="contact-us">
        <h2>Contact Us</h2>
        <form action="about_us.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your Name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your Email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>

            <button type="submit" name="send_message">Send Message</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 Jubilee Junction. All Rights Reserved.</p>
    </footer>
</body>
</html>