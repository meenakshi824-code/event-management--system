<?php
// get_event.php
include('server.php.php');

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $query = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }
}
?>