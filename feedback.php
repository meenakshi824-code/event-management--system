<?php
// Start session
session_start();

// Include database connection
include('server.php.php');

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

// Fetch user details from session
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$username = $user['username'];
$email = $user['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback Page</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <style>
  
/* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

/* Body Styling */
body {
  background-color: #f9f9f9;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
.feedback-container {
  background-color: #fff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  max-width: 500px;
  width: 100%;
  text-align: center;
}

.feedback-container h1 {
  color: #333;
  margin-bottom: 15px;
}

.feedback-container p {
  color: #666;
  margin-bottom: 20px;
}

/* Form Styling */
.feedback-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.feedback-form label {
  text-align: left;
  font-weight: bold;
  color: #333;
}

.feedback-form input,
.feedback-form select,
.feedback-form textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.feedback-form input:focus,
.feedback-form select:focus,
.feedback-form textarea:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.feedback-form button {
  padding: 12px;
  border: none;
  border-radius: 4px;
  background-color: #007bff;
  color: white;
  font-size: 16px;
  cursor: pointer;
}

.feedback-form button:hover {
  background-color: #0056b3;
}

/* Back Button */
.back-button {
  margin-top: 20px;
  padding: 10px 15px;
  background-color: #6c757d;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
}

.back-button:hover {
  background-color: #5a6268;
}
  </style>
  <div class="feedback-container">
    <h1>We Value Your Feedback</h1>
    <p>Tell us about your experience with our event management services.</p>
    
    <form action="submit_feedback.php" method="POST" class="feedback-form">
      <label for="name">Your Name:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" readonly>
      
      <label for="email">Your Email:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
      
      <label for="event">Event Name:</label>
      <input type="text" id="event" name="event" placeholder="Enter the event name" required>
      
      <label for="rating">Rate Our Services:</label>
      <select id="rating" name="rating" required>
        <option value="" disabled selected>Select a rating</option>
        <option value="excellent">Excellent</option>
        <option value="good">Good</option>
        <option value="average">Average</option>
        <option value="poor">Poor</option>
      </select>
      
      <label for="comments">Your Feedback:</label>
      <textarea id="comments" name="comments" rows="5" placeholder="Share your experience..." required></textarea>
      
      <button type="submit">Submit Feedback</button>
    </form>

    <!-- Back to Dashboard Button -->
    <a href="user_dashboard.php" class="back-button">Back to Dashboard</a>
  </div>

</body>
</html>