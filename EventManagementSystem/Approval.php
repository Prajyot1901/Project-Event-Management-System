<?php
session_start();
include 'db_connect.php';

// Redirect to login if admin is not logged in
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: index.php");
    exit();
}

// Fetch auditoriums registered to the admin
$admin_id = $_SESSION['Admin_ID'];
$sql = "SELECT A.Audi_ID, A.Name, A.Capacity, A.Projector, A.Address, A.Sound_System 
        FROM Auditorium A 
        JOIN AdminAuditorium AA ON A.Audi_ID = AA.Audi_ID 
        WHERE AA.Admin_ID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval Dashboard</title>
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
    align-items: flex-center;
    flex-direction: column;
    min-height: 100vh;
    background: linear-gradient(135deg, #FFDEE9 0%, #B5FFFC 100%);
    padding: 20px;
}

h1 {
    color: #80CED6;
    margin-bottom: 40px;
    text-align: center;
    font-size: 2.5em; /* Make the heading larger */
}

.button-container {
    margin-bottom: 20px;
    text-align: center; /* Center the button container */
}

.approval-button, .logout-button, .update-button, .dashboard-button {
    background-color: #4CAF50; /* Green for buttons */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s ease;
    margin: 0 10px; /* Space between buttons */
}

.approval-button:hover, .logout-button:hover, .update-button:hover, .dashboard-button:hover {
    background-color: #388E3C; /* Darker shade for hover effect */
    transform: translateY(-2px);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ccc;
}

th {
    background-color: #4CAF50;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #e9ecef; /* Light gray background on hover */
}

h3 {
    color: #333;
    margin-top: 20px;
    text-align: center; /* Center the subheading */
}

    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>
    
    <div class="button-container">
        <form action="AdminHome.php" method="get" style="display: inline;">
            <button type="submit" class="approval-button event-approve">Event Approval</button>
        </form>
        <form action="AdminApproval.php" method="get" style="display: inline;">
            <button type="submit" class="approval-button participant-approve">Participant Approval</button>
        </form>
        <form action="RegisterAuditorium.php" method="get" style="display: inline;">
            <button type="submit" class="approval-button register-auditorium">Register Auditorium</button>
        </form>
        <!-- Add Dashboard Button -->
        <form action="AdminDashboard.php" method="get" style="display: inline;">
            <button type="submit" class="dashboard-button">Go to Dashboard</button>
        </form>
        <!-- Logout button -->
        <form action="Logout.php" method="post" style="display: inline;">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>

    <h3>Registered Auditoriums</h3>
    <table>
        <tr>
            <th>Auditorium Name</th>
            <th>Projector</th>
            <th>Capacity</th>
            <th>Address</th>
            <th>Sound System</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['Projector']; ?></td>
                <td><?php echo $row['Capacity']; ?></td>
                <td><?php echo $row['Address']; ?></td>
                <td><?php echo $row['Sound_System']; ?></td>
                <td>
                    <a href="UpdateAuditoriumForm.php?auditorium_id=<?php echo $row['Audi_ID']; ?>">
                        <button class="update-button">Update</button>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
