<?php
session_start();
include 'db_connect.php';

// Redirect to login if admin is not logged in
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: index.php");
    exit();
}

$admin_id = $_SESSION['Admin_ID'];

// Fetch all auditoriums registered to this admin
$sql = "SELECT a.Audi_ID, a.Name, a.Capacity, a.Projector, a.Address, a.Sound_System 
        FROM Auditorium a
        JOIN AdminAuditorium aa ON a.Audi_ID = aa.Audi_ID
        WHERE aa.Admin_ID = '$admin_id'";
$result = $conn->query($sql);
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
    <h2>Update Auditoriums</h2>
    <table>
        <thead>
            <tr>
                <th>Auditorium ID</th>
                <th>Name</th>
                <th>Capacity</th>
                <th>Projector</th>
                <th>Address</th>
                <th>Sound System</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Audi_ID']; ?></td>
                    <td><?php echo $row['Name']; ?></td>
                    <td><?php echo $row['Capacity']; ?></td>
                    <td><?php echo $row['Projector']; ?></td>
                    <td><?php echo $row['Address']; ?></td>
                    <td><?php echo $row['Sound_System']; ?></td>
                    <td>
                        <form action="UpdateAuditoriumForm.php" method="post">
                            <input type="hidden" name="audi_id" value="<?php echo $row['Audi_ID']; ?>">
                            <input type="submit" value="Update">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
