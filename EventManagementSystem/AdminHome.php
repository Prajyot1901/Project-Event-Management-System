<?php
include 'db_connect.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: index.php");
    exit();
}

$admin_id = $_SESSION['Admin_ID'];

// Fetch auditoriums associated with the logged-in admin
$audi_query = "SELECT A.Audi_ID, A.Name FROM AdminAuditorium AA JOIN Auditorium A ON AA.Audi_ID = A.Audi_ID WHERE AA.Admin_ID = ?";
$audi_stmt = $conn->prepare($audi_query);
$audi_stmt->bind_param("i", $admin_id);
$audi_stmt->execute();
$audi_result = $audi_stmt->get_result();

$auditoriums = [];
while ($row = $audi_result->fetch_assoc()) {
    $auditoriums[] = $row;
}

// Fetch all pending events
$sql = "SELECT * FROM Events WHERE Status = 'Pending'";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching events: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Event Requests</title>
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
        .approve-button, .reject-button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .approve-button {
            background-color: #4CAF50;
            color: white;
        }

        .approve-button:hover {
            background-color: #388E3C;
        }

        .reject-button {
            background-color: #e74c3c;
            color: white;
            margin-left: 8px;
        }

        .reject-button:hover {
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

            .approve-button, .reject-button {
                font-size: 13px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 22px;
            }

            .approve-button, .reject-button {
                font-size: 12px;
                padding: 5px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Event Requests</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Event ID</th><th>Event Name</th><th>Capacity</th><th>Requirements</th><th>Start Time</th><th>End Time</th><th>Projection</th><th>Sound System</th><th>Action</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["Event_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["Name"]) . "</td>
                        <td>" . htmlspecialchars($row["Capacity"]) . "</td>
                        <td>" . htmlspecialchars($row["Requirements"]) . "</td>
                        <td>" . htmlspecialchars($row["Start_Time"]) . "</td>
                        <td>" . htmlspecialchars($row["End_Time"]) . "</td>
                        <td>" . htmlspecialchars($row["Projection"]) . "</td>
                        <td>" . htmlspecialchars($row["Sound_System"]) . "</td>
                        <td class='action-buttons'>
                            <form action='Approve_Event.php' method='post' style='display:inline;'>
                                <input type='hidden' name='event_id' value='" . htmlspecialchars($row["Event_ID"]) . "'>
                                <label for='audi_id'>Select Auditorium:</label>
                                <select name='audi_id' required>
                                    <option value=''>Select</option>";
                                    foreach ($auditoriums as $audi) {
                                        echo "<option value='" . htmlspecialchars($audi['Audi_ID']) . "'>" . htmlspecialchars($audi['Name']) . " (ID: " . htmlspecialchars($audi['Audi_ID']) . ")</option>";
                                    }
                                echo "</select>
                                <button type='submit' name='approve_event' class='approve-button'>Approve</button>
                            </form>
                            <form action='Reject_Event.php' method='get' style='display:inline;'>
                                <input type='hidden' name='event_id' value='" . htmlspecialchars($row["Event_ID"]) . "'>
                                <button type='submit' class='reject-button'>Reject</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No pending events found.</p>";
        }
        ?>
    </div>
</body>
</html>
