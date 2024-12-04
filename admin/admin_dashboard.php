<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}
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

    </style>
</head>
<body>

    <h1>Welcome Admin</h1>

    <nav>
        <ul>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="view_reports.php">View Reports</a></li>
            <li><a href="manage_vehicles.php">Manage Vehicles</a></li>
            <li><a href="manage_gates.php">Manage Gates</a></li>
            <li><a href="../logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>

    <!---<div class="container">
        <div class="card">
            <h3>Manage Users</h3>
            <p>View, edit, and delete user accounts.</p>
            <a href="manage_users.php" class="logout-btn">Go to Manage Users</a>
        </div>
        <div class="card">
            <h3>View Reports</h3>
            <p>Generate and view reports for vehicle movements.</p>
            <a href="view_reports.php" class="logout-btn">Go to Reports</a>
        </div>
        <div class="card">
            <h3>Manage Vehicles</h3>
            <p>Add, update, or delete vehicle data.</p>
            <a href="manage_vehicles.php" class="logout-btn">Go to Vehicle Management</a>
        </div>
        <div class="card">
            <h3>Manage Gates</h3>
            <p>Configure and manage gate access points.</p>
            <a href="manage_gates.php" class="logout-btn">Go to Gate Management</a>
        </div>
    </div>--->

</body>
</html>
