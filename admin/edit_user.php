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
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Hash password if changed
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update user details
    $sql = "UPDATE users SET fname = '$fname', lname = '$lname', username = '$username', password = '$hashed_password', role = '$role' WHERE id = '$id'";
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
            background-color: #FAF6E3; /* Pale yellow */
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #2A3663; /* Deep blue */
            margin-top: 20px;
        }

        .container {
            width: 90%;
            max-width: 600px; /* Restricting width */
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #D8DBBD; /* Light greenish beige */
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1 1 calc(50% - 20px); /* 50% width minus the gap */
        }

        label {
            font-size: 16px;
            color: #2A3663; /* Deep blue */
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #B59F78; /* Light brown */
            font-size: 14px;
            box-sizing: border-box; /* Ensures padding doesn't affect width */
        }

        button {
            background-color: #2A3663; /* Deep blue */
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #1a234d; /* Darker blue */
        }

        .back-btn {
            background-color: #B59F78; /* Light brown */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #9e8d64; /* Darker light brown */
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #2A3663; /* Deep blue */
            color: white;
        }

    </style>
</head>
<body>

    <!-- Back Button at the Top -->
    <div style="text-align: left; margin-bottom: 20px;">
        <a href="manage_users.php" class="back-btn">Back</a>
    </div>

    <h1>Edit User</h1>

    <div class="container">
        <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" name="fname" id="fname" value="<?php echo $user['fname']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" name="lname" id="lname" value="<?php echo $user['lname']; ?>" required>
                </div>
            </div>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="role">Role:</label>
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
