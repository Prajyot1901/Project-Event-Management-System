<?php
include 'db_connect.php';
session_start();

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Reject the event
    $reject_sql = "UPDATE Events SET Status = 'Rejected' WHERE Event_ID = ?";
    $reject_stmt = $conn->prepare($reject_sql);
    $reject_stmt->bind_param("i", $event_id);
    
    if ($reject_stmt->execute()) {
        // Optionally, redirect back to AdminHome with a success message
        header("Location: AdminHome.php?status=rejected");
        exit();
    } else {
        echo "Error rejecting event: " . $conn->error;
    }
} else {
    echo "No event ID specified.";
}

$conn->close();
?>
