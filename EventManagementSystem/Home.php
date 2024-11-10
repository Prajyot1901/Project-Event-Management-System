<!-- Home.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Home</title>
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

        /* Button Styling */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .register-button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .register-button:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .container h1 {
                font-size: 28px;
            }

            .register-button {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .container h1 {
                font-size: 24px;
            }

            .register-button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Event Management System</h1>
        <div class="button-group">
            <a href="index.php" class="register-button">Admin Registration</a>
            <a href="EventRegistration.php" class="register-button">Event Registration</a>
            <a href="RegisterParticipants.php" class="register-button">Participant Registration</a>
            <a href="CheckStatus.php" class="register-button">Check Event Status</a> <!-- Existing Button -->
            <a href="CheckParticipantStatus.php" class="register-button">Check Participant Status</a> <!-- New Button -->
        </div>
    </div>
</body>
</html>
