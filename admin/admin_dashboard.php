<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('../config.php'); // Include database connection

// Fetch vehicles with "pending" status
$sql = "SELECT * FROM vehicles WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);
$pending_count = mysqli_num_rows($result); // Count the number of pending vehicles
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .card {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h3 {
            margin: 0;
            color: #333;
        }

        .card p {
            color: #666;
            font-size: 16px;
        }

        .reminder {
            background-color: #ffeb3b;
            color: #333;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #333;
            color: white;
        }

        .logout-btn {
            background-color: #e91e63;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #c2185b;
        }

        .notification {
            color: red;
            font-weight: bold;
            font-size: 18px;
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <h1>Welcome Admin</h1>

    <nav>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="view_reports.php">View Reports</a></li>
            <li>
                <a href="manage_vehicles.php">Manage Vehicles</a>
                <?php if ($pending_count > 0): ?>
                    <span class="notification">1</span> <!-- Notification Badge -->
                <?php endif; ?>
            </li>
            <li><a href="manage_gates.php">Manage Gates</a></li>
            <li><a href="../logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>

</body>
</html>
