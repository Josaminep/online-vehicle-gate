<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('config.php'); // Include database connection

$id = $_GET['id'];

// Delete user from database
$sql = "DELETE FROM users WHERE id = '$id'";
if (mysqli_query($conn, $sql)) {
    header('Location: manage_users.php');
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
