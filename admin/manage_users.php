<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('../config.php'); // Include database connection

// Fetch users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        nav {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #00bcd4;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: white;
        }

        table td a {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            margin-right: 10px;
        }

        table td a:hover {
            background-color: #45a049;
        }

        .add-btn {
            background-color: #00bcd4;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #0097a7;
        }

        .back-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
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

    <h1>Manage Users</h1>
    
    <div class="container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
        
        <a href="add_user.php" class="add-btn">Add New User</a>
        
        <table>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo ucfirst($user['role']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>


</body>
</html>
