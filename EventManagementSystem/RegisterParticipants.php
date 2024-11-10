<?php
session_start();
include 'db_connect.php';
error_reporting(E_ALL); 
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $name = $_POST['full-name'];
    $email = $_POST['email'];
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];

    // Validate password confirmation
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href = './RegisterParticipants.php';</script>";
        exit();
    }

    // Check if the event ID exists
    $checkEventSql = "SELECT Name FROM Events WHERE Event_ID = ?";
    $stmt = $conn->prepare($checkEventSql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        $event_name = $event['Name'];

        // Register participant with pending status
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO Participant (Username, Password, Name, Email_ID, Event_ID, Event_Name, Status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $username, $password_hashed, $name, $email, $event_id, $event_name);

        if ($stmt->execute()) {
            $participant_id = $stmt->insert_id;
            echo "<script>alert('Event registered successfully! Your Participation ID is: $participant_id'); window.location.href = './Home.php';</script>";
        } else {
            echo "<script>alert('Error registering. Please try again.'); window.location.href = './RegisterParticipants.php';</script>";
        }
    } else {
        echo "<script>alert('Event ID does not exist. Please check again.'); window.location.href = './RegisterParticipants.php';</script>";
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
    <title>Participant Registration</title>
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

        .container:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
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

        .form-group input::placeholder {
            color: #aaa;
        }

        /* Password Visibility Toggle */
        .form-group .password-toggle {
            position: absolute;
            right: 15px;
            top: 55%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #aaa;
        }

        .form-group-password {
            position: relative;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .container h2 {
                font-size: 28px;
            }

            .form-group input {
                font-size: 14px;
                padding: 10px;
            }

            .form-group input[type="submit"] {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .container h2 {
                font-size: 24px;
            }

            .form-group input {
                font-size: 13px;
                padding: 8px;
            }

            .form-group input[type="submit"] {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Participant Registration</h2>
        <form action="./RegisterParticipants.php" method="post">  
            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="full-name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter a unique username" required>
            </div>
            <div class="form-group form-group-password">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="password-toggle" onclick="togglePassword()">👁️</span>
            </div>
            <div class="form-group form-group-password">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                <span class="password-toggle" onclick="toggleConfirmPassword()">👁️</span>
            </div>
            <div class="form-group">
                <label for="event-id">Event ID</label>
                <input type="number" id="event-id" name="event_id" placeholder="Enter Event ID" required>
            </div>
            <div class="form-group">
                <label for="event-name">Event Name</label>
                <input type="text" id="event-name" name="event_name" placeholder="Enter Event Name" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
    
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordToggle = document.querySelector('.form-group-password .password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.textContent = '🙈'; // Change icon to closed eye
            } else {
                passwordField.type = 'password';
                passwordToggle.textContent = '👁️'; // Change icon to open eye
            }
        }

        function toggleConfirmPassword() {
            const confirmPasswordField = document.getElementById('confirm-password');
            const confirmPasswordToggle = document.querySelectorAll('.form-group-password .password-toggle')[1];
            if (confirmPasswordField.type === 'password') {
                confirmPasswordField.type = 'text';
                confirmPasswordToggle.textContent = '🙈'; // Change icon to closed eye
            } else {
                confirmPasswordField.type = 'password';
                confirmPasswordToggle.textContent = '👁️'; // Change icon to open eye
            }
        }
    </script>
</body>
</html>
