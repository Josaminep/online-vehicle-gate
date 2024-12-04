<?php
session_start();
include('../config.php'); // Include database connection

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$vehicle_id = $_GET['id'];
$action = $_GET['action'];
$admin_id = $_SESSION['id']; // Admin ID from session

// Check if the action is approve or deny
if ($action == 'approve') {
    $status = 'approved';
} elseif ($action == 'deny') {
    $status = 'denied';
} else {
    echo "Invalid action.";
    exit;
}

// Update vehicle status
$sql = "UPDATE vehicles SET status = '$status', approved_by = '$admin_id' WHERE id = '$vehicle_id'";

if (mysqli_query($conn, $sql)) {
    header('Location: manage_vehicles.php');
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
