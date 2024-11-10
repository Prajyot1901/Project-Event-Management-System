<?php
include 'db_connect.php';
session_start();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auditorium_name = $_POST['auditorium-name'];
    $projector = $_POST['projector'];
    $address = $_POST['address'];
    $sound_system = $_POST['sound-system'];
    $capacity = $_POST['capacity'];
    $admin_id = $_SESSION['Admin_ID']; // Admin ID from session

    // Insert into Auditorium table without specifying Audi_ID, as it is auto-incremented
    $sql_auditorium = "INSERT INTO Auditorium (Name, Capacity, Projector, Address, Sound_System) 
                       VALUES ('$auditorium_name', '$capacity', '$projector', '$address', '$sound_system')";

    if ($conn->query($sql_auditorium) === TRUE) {
        // Get the last inserted auto-increment ID
        $last_audi_id = $conn->insert_id;

        // Insert into AdminAuditorium table with the retrieved Audi_ID
        $sql_admin_auditorium = "INSERT INTO AdminAuditorium (Admin_ID, Audi_ID) VALUES ('$admin_id', '$last_audi_id')";
        
        if ($conn->query($sql_admin_auditorium) === TRUE) {
            $_SESSION['message'] = "You are registered successfully";
            header("Location: ./Approval.php");
            exit();
        } else {
            echo "Error: " . $sql_admin_auditorium . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_auditorium . "<br>" . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditorium Registration</title>
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4CAF50;
            background-color: #ffffff;
            outline: none;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.2);
        }

        .form-group input::placeholder {
            color: #aaa;
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

            .form-group input,
            .form-group select {
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

            .form-group input,
            .form-group select {
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
        <h2>Auditorium Registration</h2>
        <form  action="./RegisterAuditorium.php" method="post">
            
            <div class="form-group">
                <label for="auditorium-name">Auditorium Name</label>
                <input type="text" id="auditorium-name" name="auditorium-name" placeholder="Enter the auditorium name" required>
            </div>
            <div class="form-group">
                <label for="projector">Projector Availability</label>
                <select id="projector" name="projector" required>
                    <option value="" disabled selected>Select projector availability</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Location</label>
                <input type="text" id="address" name="address" placeholder="Enter the auditorium location" required>
            </div>
            <div class="form-group">
                <label for="sound-system">Sound System</label>
                <select id="sound-system" name="sound-system" required>
                    <option value="" disabled selected>Select sound system availability</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" placeholder="Enter the seating capacity" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Register Auditorium">
            </div>
        </form>
    </div>

</body>
</html>