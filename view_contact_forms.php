<?php
// Include your existing database connector
include('server.php.php');  // Assuming your connector file is named 'db_connector.php'

// SQL query to fetch all contact form submissions from the database
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch all results
    $contact_forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching contact forms: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submitted Contact Forms</title>
</head>
<body>
    <h2>Submitted Contact Forms</h2>

    <!-- Table to display the contact form submissions -->
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($contact_forms) > 0) {
                foreach ($contact_forms as $form) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($form['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($form['email']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($form['message'])) . "</td>";
                    echo "<td>" . htmlspecialchars($form['created_at']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No contact forms submitted yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>