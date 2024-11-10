<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_SESSION['Admin_id'];
    $event_id = $_POST['event_id'];
    $audi_id = $_POST['audi_id'];

    // Insert into AdminEvent table
    $sql = "INSERT INTO AdminEvent (Admin_ID, Audi_ID, Event_ID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $admin_id, $audi_id, $event_id);
    
    if ($stmt->execute()) {
        // Update event status to 'Approved' in Events table
        $update_sql = "UPDATE Events SET Status = 'Approved' WHERE Event_ID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $event_id);
        $update_stmt->execute();

        // Redirect back to AdminHome after approval
        header("Location: AdminHome.php?status=approved");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
