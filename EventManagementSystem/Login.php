<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM Admin WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            $_SESSION['Admin_id'] = $admin['Admin_id'];
            header("Location: Approval.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password";
            header("Location: index.php");
            exit();
        }
    } else {
        echo "Query Error: " . $conn->error;
    }
}
?>
