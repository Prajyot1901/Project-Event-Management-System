<?php
session_start();
include 'db_connect.php';

// Check if the admin is logged in and retrieve their ID from the session
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: index.php");
    exit();
}
$admin_id = $_SESSION['Admin_ID'];

// Query to retrieve pending participant requests for events managed by this admin
$sql = "
    SELECT p.P_ID, p.Name, p.Event_ID, p.Event_Name, p.Status 
    FROM Participant p
    JOIN AdminEvent ae ON p.Event_ID = ae.Event_ID
    JOIN AdminAuditorium aa ON ae.Audi_ID = aa.Audi_ID
    WHERE p.Status = 'pending' AND aa.Admin_ID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle approval or rejection actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = $_POST['p_id'];
    $event_id = $_POST['event_id'];
    $action = $_POST['action']; // 'approve' or 'reject'
    
    // Determine the new status
    $newStatus = ($action === 'approve') ? 'approved' : 'disapproved';
    
    // Update the Participant table with the new status
    $updateSql = "UPDATE Participant SET Status = ? WHERE P_ID = ? AND Status = 'pending'";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $newStatus, $p_id);
    
    if ($updateStmt->execute()) {
        // Update the ParticipantEvent table with the new status
        $updateParticipantEventSql = "UPDATE ParticipantEvent SET Status = ? WHERE P_ID = ? AND Event_ID = ?";
        $updateParticipantEventStmt = $conn->prepare($updateParticipantEventSql);
        $updateParticipantEventStmt->bind_param("sii", $newStatus, $p_id, $event_id);
        
        if ($updateParticipantEventStmt->execute()) {
            // Add the action to the ParticipantAdmin table (assuming this table exists)
            $insertParticipantAdminSql = "
                INSERT INTO ParticipantAdmin (P_ID, Admin_ID, Event_ID, Action, Action_Date) 
                VALUES (?, ?, ?, ?, NOW())
            ";
            $insertStmt = $conn->prepare($insertParticipantAdminSql);
            $insertStmt->bind_param("iiis", $p_id, $admin_id, $event_id, $newStatus);
            $insertStmt->execute();

            echo "<script>alert('Request has been $newStatus successfully'); window.location.href = 'AdminApproval.php';</script>";
        } else {
            echo "Error updating ParticipantEvent status: " . $conn->error;
        }
    } else {
        echo "Error updating request status: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve Participant Requests</title>
    <style>
        /* Main Container */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }

        h2 {
            font-size: 26px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Button Styles */
        .approve-btn, .reject-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .approve-btn {
            background-color: #4CAF50;
            color: white;
        }

        .approve-btn:hover {
            background-color: #388E3C;
        }

        .reject-btn {
            background-color: #e74c3c;
            color: white;
            margin-left: 8px;
        }

        .reject-btn:hover {
            background-color: #c0392b;
        }

        /* Select Dropdown */
        select {
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            .approve-btn, .reject-btn {
                font-size: 13px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 22px;
            }

            .approve-btn, .reject-btn {
                font-size: 12px;
                padding: 5px 8px;
            }
        }

        /* No Pending Requests Message */
        .no-requests {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Pending Participant Requests</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Participant ID</th>
                <th>Participant Name</th>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['P_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Event_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Event_Name']); ?></td>
                    <td>
                        <!-- Approve button form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="p_id" value="<?php echo htmlspecialchars($row['P_ID']); ?>">
                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($row['Event_ID']); ?>">
                            <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                        </form>
                        <!-- Reject button form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="p_id" value="<?php echo htmlspecialchars($row['P_ID']); ?>">
                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($row['Event_ID']); ?>">
                            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-requests">No pending requests at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
