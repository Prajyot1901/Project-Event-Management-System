<?php
session_start();
include 'db_connect.php';

// Ensure the participant is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: CheckParticipantStatus.php");
    exit();
}

// Get the participant and event details
$participant_id = $_SESSION['P_ID'];
$event_id = $_SESSION['Event_ID'];

// Check if the participant is approved
$sql = "SELECT Status FROM Participants WHERE P_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $participant_id);
$stmt->execute();
$result = $stmt->get_result();
$participant = $result->fetch_assoc();

if ($participant['Status'] !== 'approved') {
    $feedback_message = "You are not approved yet.";
} else {
    // Fetch the auditorium associated with the event
    $sql = "SELECT Audi_ID FROM AdminEvent WHERE Event_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $auditorium = $result->fetch_assoc();
    $audi_id = $auditorium['Audi_ID'];

    // Handle feedback form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $feedback_text = trim($_POST['feedback_text'] ?? '');

        if (!empty($feedback_text)) {
            // Insert feedback into the Feedback table
            $sql = "INSERT INTO Feedback (P_ID, Audi_ID, Feedback_Text, Date) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $participant_id, $audi_id, $feedback_text);
            if ($stmt->execute()) {
                // Redirect to CheckParticipantStatus.php after successful feedback submission
                header("Location: CheckParticipantStatus.php");
                exit(); // Make sure to exit after redirect
            } else {
                echo "<p>Error: Unable to submit feedback.</p>";
            }
        } else {
            echo "<p>Error: Feedback text is required.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditorium Feedback</title>
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

        .container h2 {
            font-size: 28px;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
            margin-top: 20px;
        }

        label {
            display: block;
            font-size: 16px;
            color: #333;
            text-align: left;
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            font-size: 15px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
        }

        p {
            color: red;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .container h2 {
                font-size: 24px;
            }

            button {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .container h2 {
                font-size: 20px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Auditorium Feedback</h2>
    <?php if (isset($feedback_message)): ?>
        <p><?php echo $feedback_message; ?></p>
    <?php else: ?>
        <form method="post" action="FeedbackForm.php">
            <label for="feedback_text">Your Feedback:</label>
            <textarea name="feedback_text" required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
