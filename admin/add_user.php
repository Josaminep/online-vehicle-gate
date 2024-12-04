<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('../config.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
    if (mysqli_query($conn, $sql)) {
        header('Location: manage_users.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h1>Add User</h1>
    <form method="POST" action="add_user.php">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="security">Security</option>
            <option value="owner">Owner</option>
        </select><br>
        <button type="submit">Add User</button>
    </form>
</body>
</html>
