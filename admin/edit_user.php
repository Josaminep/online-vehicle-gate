<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('../config.php'); // Include database connection

$id = $_GET['id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Hash password if changed
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update user details
    $sql = "UPDATE users SET username = '$username', password = '$hashed_password', role = '$role' WHERE id = '$id'";
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
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .container {
            width: 50%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-btn:hover {
            background-color: #d32f2f;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>

    <a href="manage_users.php" class="back-btn">Back to Manage Users</a>

    <h1>Edit User</h1>

    <div class="container">
        <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="security" <?php echo ($user['role'] == 'security') ? 'selected' : ''; ?>>Security</option>
                <option value="owner" <?php echo ($user['role'] == 'owner') ? 'selected' : ''; ?>>Owner</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </div>

</body>
</html>
