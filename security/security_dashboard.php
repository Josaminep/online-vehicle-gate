<?php
session_start();

// Check if the user is logged in and has a 'security' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header('Location: login.php');
    exit;
}

include('../config.php'); // Include database connection

// Default query to fetch all vehicle logs
$query = "SELECT * FROM vehicle_logs ORDER BY date_time DESC";

// Search functionality
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $query = "SELECT * FROM vehicle_logs WHERE vehicle_number LIKE '%$search_term%' ORDER BY date_time DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard</title>
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

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            width: 50%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-bar button {
            padding: 8px 16px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #00bcd4;
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .log-table th, .log-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .log-table th {
            background-color: #f4f4f4;
            color: #333;
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

    <h1>Welcome Security Personnel</h1>

    <nav>
        <ul>
            <li><a href="security_dashboard.php">Dashboard</a></li>
            <li><a href="../logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="search-bar">
            <form method="POST" action="security_dashboard.php">
                <input type="text" name="search_term" placeholder="Search by vehicle number..." required>
                <button type="submit" name="search">Search</button>
            </form>
        </div>

        <!-- Vehicle Logs Table -->
        <table class="log-table">
            <thead>
                <tr>
                    <th>Vehicle Number</th>
                    <th>Entry/Exit</th>
                    <th>Date & Time</th>
                    <th>Gate Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['vehicle_number'] . "</td>";
                        echo "<td>" . ucfirst($row['entry_exit']) . "</td>";
                        echo "<td>" . $row['date_time'] . "</td>";
                        echo "<td>" . $row['gate_number'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
