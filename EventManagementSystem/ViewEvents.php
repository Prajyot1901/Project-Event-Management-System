<?php
include 'db_connect.php';
session_start();

// Fetch all events with their associated auditoriums
$sql = "SELECT e.Event_ID, e.Event_Name, e.Status, a.Name AS Auditorium_Name
        FROM Events e
        LEFT JOIN AdminEvent ae ON e.Event_ID = ae.Event_ID
        LEFT JOIN Auditorium a ON ae.Audi_ID = a.Audi_ID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Information</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Main Body Styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%);
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            margin: 20px auto;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            color: #333;
            font-weight: bold;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Event Information</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Event ID</th><th>Event Name</th><th>Status</th><th>Auditorium Name</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Event_ID"] . "</td>
                        <td>" . $row["Event_Name"] . "</td>
                        <td>" . $row["Status"] . "</td>
                        <td>" . ($row["Auditorium_Name"] ?? 'Not Assigned') . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </div>
</body>
</html>
