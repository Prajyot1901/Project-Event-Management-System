<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to Home.php
header("Location: Home.php");
exit;
?>