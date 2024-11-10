<?php
session_start();
include 'db_connect.php';

$error = ""; // To store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve participant details based on the provided Username
    $sql = "SELECT P_ID, Username, Event_ID, Event_Name, Status, Password FROM Participant WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $participant = $result->fetch_assoc();

        // Verify the provided password against the hashed password in the database
        if (password_verify($password, $participant['Password'])) {
            // Store participant session information
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['P_ID'] = $participant['P_ID'];
            $_SESSION['Event_ID'] = $participant['Event_ID'];
            $_SESSION['Event_Name'] = $participant['Event_Name'];
            $_SESSION['Status'] = $participant['Status'];
            
        } else {
            // Password does not match
            $error = "Incorrect password. Please try again.";
            $result = null;
        }
    } else {
        // Username not found
        $error = "No participant found with the given username.";
        $result = null;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Participant Status</title>
    <style>
        /* General reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        /* Body styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f6f8;
            padding: 20px;
            color: #333;
        }

        /* Centered container */
        .container {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin: 20px;
            text-align: center;
        }

        /* Header styling */
        h2 {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form input styling */
        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 150, 0, 0.2);
        }

        /* Button styling */
        button[type="submit"],
        .secondary-button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover,
        .secondary-button:hover {
            background-color: #388E3C;
            transform: translateY(-1px);
        }

        /* Error message styling */
        .error {
            color: #E53935;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Table styling */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border-radius: 8px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: 600;
        }

        td {
            color: #555;
        }

        /* Feedback and logout button styling */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .secondary-button {
            background-color: #FF5722;
            color: #fff;
            font-size: 15px;
            flex: 1;
        }

        .secondary-button.logout {
            background-color: #E53935;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 22px;
            }

            th, td {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Check Participant Status</h2>

    <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
        <form method="post">
            <label for="username">Enter Username:</label>
            <input type="text" name="username" required>
            <label for="password">Enter Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Check Status</button>
        </form>
    <?php endif; ?>
    

    <!-- Display error message if there's any -->
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Display participant information if login is successful -->
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        
        <table>
            <tr>
                <th>Participant ID</th>
                <th>Username</th>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><?php echo $_SESSION['P_ID']; ?></td>
                <td><?php echo $_SESSION['username']; ?></td>
                <td><?php echo $_SESSION['Event_ID']; ?></td>
                <td><?php echo $_SESSION['Event_Name']; ?></td>
                <td><?php echo $_SESSION['Status']; ?></td>
            </tr>
        </table>

        <!-- Button group for feedback and logout -->
        <div class="button-group">
            <form action="FeedbackForm.php" method="post">
                <button type="submit" class="secondary-button">Give Feedback</button>
            </form>
            <form action="logoutParticipant.php" method="post">
                <button type="submit" class="secondary-button logout">Logout</button>
            </form>
        </div>
    <?php elseif ($result !== null): ?>
        <p>No participant found with the given username and password.</p>
    <?php endif; ?>
</div>
</body>
</html>
