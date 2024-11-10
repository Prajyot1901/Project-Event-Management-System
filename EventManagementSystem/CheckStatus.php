<?php
include 'db_connect.php';
session_start();

$status_message = "";
$event_details = ""; // Variable to hold formatted event details

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    
    // Fetch event details based on Event_ID and join with AdminEvent and Auditorium to get auditorium details
    $sql = "SELECT e.*, a.Audi_ID, au.Name, au.Address, au.Capacity 
            FROM Events e 
            LEFT JOIN AdminEvent a ON e.Event_ID = a.Event_ID 
            LEFT JOIN Auditorium au ON a.Audi_ID = au.Audi_ID 
            WHERE e.Event_ID = '$event_id'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        // Prepare event details for display
        $event_details = "<div class='event-info'>" .
                         "<h2>Event Details</h2>" .
                         "<p><strong>Status:</strong> " . $event['Status'] . "</p>" .
                         "<p><strong>Event Name:</strong> " . $event['Event_Name'] . "</p>" .
                         "<p><strong>Capacity:</strong> " . $event['Capacity'] . "</p>" .
                         "<p><strong>Requirements:</strong> " . $event['Requirements'] . "</p>" .
                         "<p><strong>Start Time:</strong> " . $event['Start_Time'] . "</p>" .
                         "<p><strong>End Time:</strong> " . $event['End_Time'] . "</p>" .
                         "<p><strong>Projection:</strong> " . $event['Projection'] . "</p>" .
                         "<p><strong>Sound System:</strong> " . $event['Sound_System'] . "</p>";

        // If the event status is approved, show auditorium details
        if ($event['Status'] === 'Approved') {
            $event_details .= "<h3>Auditorium Details</h3>" .
                              "<p><strong>Name:</strong> " . $event['Name'] . "</p>" .
                              "<p><strong>Address:</strong> " . $event['Address'] . "</p>" .
                              "<p><strong>Capacity:</strong> " . $event['Capacity'] . "</p>";
        }

        $event_details .= "</div>"; // Close event-info div
    } else {
        $status_message = "No event found with that Event ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Event Status</title>
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
            max-width: 600px;
            width: 100%;
            text-align: center;
            margin: 20px auto;
        }

        .container h1 {
            font-size: 32px;
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #4CAF50;
            background-color: #ffffff;
            outline: none;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.2);
        }

        /* Submit Button */
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            font-size: 18px;
            cursor: pointer;
            padding: 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
        }

        /* Status Message */
        .status-message {
            margin-top: 20px;
            font-size: 18px;
            color: #f44336; /* Red color for error message */
        }

        /* Event Details Styling */
        .event-info {
            margin-top: 30px;
            padding: 20px;
            background-color: #e7f3fe; /* Light blue background */
            border: 1px solid #2196F3; /* Blue border */
            border-radius: 8px;
            text-align: left;
            color: #333;
        }

        .event-info h2 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #2196F3;
        }

        .event-info p {
            font-size: 16px;
            margin: 5px 0;
        }

        .event-info strong {
            color: #2196F3; /* Strong text color */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Check Event Status</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="event_id">Enter Event ID:</label>
                <input type="text" id="event_id" name="event_id" placeholder="Enter your Event ID" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Check Status">
            </div>
        </form>
        <div class="status-message">
            <?php echo $status_message; ?>
        </div>
        <?php if (!empty($event_details)): ?>
            <div class="event-info">
                <?php echo $event_details; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
