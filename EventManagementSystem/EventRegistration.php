<?php
include 'db_connect.php';
session_start();

$event_id = null; // Initialize variable to hold the Event ID
$message = ""; // Initialize message variable for user feedback
$redirect_page = "./Home.php"; // Specify the page to redirect after registration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event-name']; // Get event name from POST data
    $capacity = $_POST['capacity'];
    $requirements = $_POST['requirements'];
    $start_time = $_POST['start-time'];
    $end_time = $_POST['end-time'];
    $projection = $_POST['projection'];
    $sound_system = $_POST['sound-system'];

    $sql = "INSERT INTO Events (Name, Capacity, Requirements, Start_Time, End_Time, Projection, Sound_System, Status) 
            VALUES ('$event_name', '$capacity', '$requirements', '$start_time', '$end_time', '$projection', '$sound_system', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        // Get the last inserted ID
        $event_id = $conn->insert_id;
        $message = "Event registered successfully! Your Event ID is: " . $event_id;

        // Output the JavaScript alert and redirect
        echo "<script type='text/javascript'>alert('Your Event ID is: $event_id'); window.location.href = '$redirect_page';</script>";
        exit(); // Ensure no further output after redirect
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registration</title>
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
            min-height: 100vh;
            background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%);
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 450px;
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

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            font-size: 18px;
            cursor: pointer;
            padding: 12px;
            border-radius: 8px;
        }

        /* Status Message */
        .status-message {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #4CAF50;
            background-color: #e0f7fa;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register an Event</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="event-name">Event Name</label>
                <input type="text" id="event-name" name="event-name" placeholder="Enter the event name" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" placeholder="Enter the capacity" required>
            </div>
            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements" placeholder="Enter any specific requirements" required></textarea>
            </div>
            <div class="form-group">
                <label for="start-time">Start Time</label>
                <input type="datetime-local" id="start-time" name="start-time" required>
            </div>
            <div class="form-group">
                <label for="end-time">End Time</label>
                <input type="datetime-local" id="end-time" name="end-time" required>
            </div>
            <div class="form-group">
                <label for="projection">Projection</label>
                <select id="projection" name="projection" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sound-system">Sound System</label>
                <select id="sound-system" name="sound-system" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Register Event">
            </div>
        </form>
        <?php if (!empty($message)): ?>
            <div class="status-message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
