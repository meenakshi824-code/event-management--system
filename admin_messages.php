<?php
include('server.php.php'); // Database connection

// Mark message as read when admin clicks on it
if (isset($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);  // Sanitize input to prevent SQL injection
    $conn->query("UPDATE contact_messages SET is_read = 1 WHERE id = $id");
    header("Location: admin_messages.php"); // Redirect to refresh the page
    exit();
}

// Fetch messages from the database
$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f4f4f4; }
        .unread { background-color: #ffdddd; }
        .read { background-color: #e0ffe0; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h2>Admin Messages</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Submitted At</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr class="<?php echo $row['is_read'] ? 'read' : 'unread'; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['message']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['is_read'] ? 'Read' : 'Unread'; ?></td>
                <td>
                    <?php if (!$row['is_read']) { ?>
                        <a href="admin_messages.php?mark_read=<?php echo $row['id']; ?>">Mark as Read</a>
                    <?php } else { ?>
                        Already Read
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
