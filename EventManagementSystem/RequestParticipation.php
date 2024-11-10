<?php
session_start();
include 'db_connect.php';

$p_id = $_SESSION['p_id'];  // Assuming p_id is stored in the session after registration

// Retrieve the status of the participant's event request
$sql = "SELECT Event_ID, Event_Name, Status FROM Participant WHERE P_ID = '$p_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Status</title>
</head>
<body>
    <h2>Event Participation Request Status</h2>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Status</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Event_ID']; ?></td>
                    <td><?php echo $row['Event_Name']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No requests found for your ID.</p>
    <?php endif; ?>

</body>
</html>
