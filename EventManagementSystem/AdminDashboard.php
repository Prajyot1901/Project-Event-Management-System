<?php
include 'db_connect.php';
session_start();

// Redirect to login if admin is not logged in
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: index.php");
    exit();
}

// Fetch approved events
$sql_approved = "SELECT * FROM Events WHERE Status = 'Approved'";
$result_approved = $conn->query($sql_approved);

// Fetch pending events
$sql_pending = "SELECT * FROM Participant WHERE Status = 'approved'";
$result_pending = $conn->query($sql_pending);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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

        /* Button and Select Styling */
        .assign-button, .approve-button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .assign-button:hover, .approve-button:hover {
            background-color: #388E3C;
        }

        select {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            width: 100%;
            max-width: 200px;
            margin-right: 10px;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .container h2 {
                font-size: 28px;
            }

            table {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .container h2 {
                font-size: 24px;
            }

            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Approved Events</h2>
        <?php
        if ($result_approved->num_rows > 0) {
            echo "<table><tr><th>Event ID</th><th>Event Name</th><th>Capacity</th><th>Start Time</th><th>End Time</th><th>Projector</th><th>Sound System</th></tr>";
            while ($row = $result_approved->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Event_ID"] . "</td>
                        <td>" . $row["Name"] . "</td> <!-- Corrected from Event_Name to Name -->
                        <td>" . $row["Capacity"] . "</td>
                        <td>" . $row["Start_Time"] . "</td>
                        <td>" . $row["End_Time"] . "</td>
                        <td>" . $row["Projection"] . "</td>
                        <td>" . $row["Sound_System"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No approved events.</p>";
        }
        ?>

        <h2>Approved Participants</h2>
        <?php
        if ($result_pending->num_rows > 0) {
            echo "<table><tr><th>Username</th><th>Name</th><th>Email ID</th><th>Event_ID</th><th>Event Name</th></tr>";
            while ($row = $result_pending->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Username"] . "</td>
                        <td>" . $row["Name"] . "</td> <!-- Corrected from Event_Name to Name -->
                        <td>" . $row["Email_ID"] . "</td>
                        <td>" . $row["Event_ID"] . "</td>
                        <td>" . $row["Event_Name"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No approved Participants.</p>";
        }
        ?>

        
    </div>
</body>
</html>
