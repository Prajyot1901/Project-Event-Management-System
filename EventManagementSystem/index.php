<?php
session_start();  // Start the session at the top of the file
include 'db_connect.php';  // Include your database connection file
error_reporting(0);  // Suppress errors (optional)

// Check if the form is submitted for login processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    echo "$username";

    // Prepare and execute a statement to prevent SQL injection
    $sql = "SELECT * FROM Admin WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Check if a matching record was found
    if ($admin && $password==$admin['Password']) {
        // Login successful, set session variable for Admin_ID
        $_SESSION['Admin_ID'] = $admin['Admin_ID'];  // Store admin ID in session
        $_SESSION['username'] = $username;

        // Redirect to Approval.php after successful login
        header("Location: Approval.php");
        exit();
    } else {
        // Set error message if login fails and redirect back to index.php
        $_SESSION['message'] = "Invalid username or password";
        header("Location: index.php");
        exit();
    }
}

// Redirect to Approval.php if admin is already logged in
if (isset($_SESSION['Admin_ID'])) {
    header("Location: Approval.php");
    exit();
}

// Display any error message from the session if login failed
if (isset($_SESSION['message'])) {
    echo "<script type='text/javascript'>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['message']); // Clear the message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            font-size: 28px;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        .form-group input[type="submit"]:hover {
            background-color: #388E3C;
            transform: translateY(-2px);
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 38px;
        }

        .register-button {
            display: inline-block;
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .register-button:hover {
            background-color: #0056b3; /* Darker shade for hover effect */
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="index.php" method="post"> <!-- Form submits to the same page -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group form-group-password">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="password-toggle" onclick="togglePassword()">👁️</span>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
        </form>
        <a href="./RegisterAdmin.php" class="register-button">Register</a>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordToggle = document.querySelector('.password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                passwordToggle.textContent = '👁️';
            }
        }
    </script>

</body>
</html>
