<?php
    session_start();
include 'db_connect.php';

// Insert Admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO Admin (Admin_ID,Username, Password, Name, Email_ID, Ph_No) VALUES ('$id', '$username', '$password', '$name', '$email', '$phone')";
    $result = $conn->query($sql);
    
    if ($result) {
        $_SESSION['message']="you are registered succesfully";
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// Display Admins
// $result = $conn->query("SELECT * FROM Admin");
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
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
            min-height: 100vh; /* Allow scrolling */
            background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%); /* Softer pastel gradient */
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
            margin: 20px auto; /* Allow space for scrolling */
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
            border-color: #4CAF50; /* Friendly green on focus */
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
            background-color: #4CAF50; /* Friendly green button */
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
        <h2>Admin Registration</h2>
        <form action="./index.php" method="post">
            <div class="form-group">
                <label for="id">Admin ID</label>
                <input type="number" id="id" name="id" placeholder="Admin ID" required>
            </div>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="ntext" id="name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter a unique username" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            <div class="form-group">
                <label for="email">Email ID</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group form-group-password">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="password-toggle" onclick="togglePassword()">👁️</span>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordToggle = document.querySelector('.password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.textContent = '🙈'; // Change icon to closed eye
            } else {
                passwordField.type = 'password';
                passwordToggle.textContent = '👁️'; // Change icon to open eye
            }
        }
    </script>

</body>
</html>



