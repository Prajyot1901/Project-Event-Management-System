<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['auditorium_id'])) {
    $auditorium_id = $_GET['auditorium_id'];

    // Fetch the auditorium details for the selected auditorium
    $sql = "SELECT Name, Capacity, Projector, Address, Sound_System 
            FROM Auditorium 
            WHERE Audi_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $auditorium_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $auditorium = $result->fetch_assoc();

    if (!$auditorium) {
        echo "Auditorium not found.";
        exit();
    }
} else {
    echo "No auditorium ID provided.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update auditorium details
    $auditorium_name = $_POST['auditorium-name'];
    $projector = $_POST['projector'];
    $address = $_POST['address'];
    $sound_system = $_POST['sound-system'];
    $capacity = $_POST['capacity'];

    $update_sql = "UPDATE Auditorium 
                   SET Name = ?, Capacity = ?, Projector = ?, Address = ?, Sound_System = ? 
                   WHERE Audi_ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sisssi", $auditorium_name, $capacity, $projector, $address, $sound_system, $auditorium_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Auditorium updated successfully!";
        header("Location: Approval.php");
        exit();
    } else {
        echo "Error updating auditorium: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Auditorium</title>
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
        <h2>Update Auditorium</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="auditorium-name">Auditorium Name</label>
                <input type="text" id="auditorium-name" name="auditorium-name" value="<?php echo htmlspecialchars($auditorium['Name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="projector">Projector Availability</label>
                <select id="projector" name="projector" required>
                    <option value="Yes" <?php if ($auditorium['Projector'] == "Yes") echo "selected"; ?>>Yes</option>
                    <option value="No" <?php if ($auditorium['Projector'] == "No") echo "selected"; ?>>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Location</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($auditorium['Address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="sound-system">Sound System</label>
                <select id="sound-system" name="sound-system" required>
                    <option value="Yes" <?php if ($auditorium['Sound_System'] == "Yes") echo "selected"; ?>>Yes</option>
                    <option value="No" <?php if ($auditorium['Sound_System'] == "No") echo "selected"; ?>>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($auditorium['Capacity']); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Auditorium">
            </div>
        </form>
    </div>

</body>
</html>
