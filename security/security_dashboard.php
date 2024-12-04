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

// Query to fetch vehicles with "approved" status
$query_vehicles = "SELECT * FROM vehicles WHERE status = 'approved' ORDER BY plate_number ASC";
$result_vehicles = mysqli_query($conn, $query_vehicles);

if (!$result_vehicles) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result_vehicles) == 0) {
    echo "No approved vehicles found";
}

// Handle vehicle entry submission
if (isset($_POST['add_vehicle'])) {
    // Get form data
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['plate_number']);
    $entry_exit = mysqli_real_escape_string($conn, $_POST['entry_exit']);
    $gate_number = mysqli_real_escape_string($conn, $_POST['gate_number']);
    $date_time = date('Y-m-d H:i:s'); // Use current date and time

    // Insert new vehicle entry into the vehicle_logs table
    $insert_query = "INSERT INTO vehicle_logs (plate_number, entry_exit, gate_number, date_time) 
                     VALUES ('$vehicle_number', '$entry_exit', '$gate_number', '$date_time')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Vehicle entry added successfully');</script>";
    } else {
        echo "<script>alert('Error adding vehicle entry: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch vehicle logs
$result_logs = mysqli_query($conn, $query);
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
            display: flex;
            justify-content: space-between;
            width: 85%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .left-column, .right-column {
            width: 48%;
        }

        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            width: 60%;
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

        .vehicle-table, .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .vehicle-table th, .log-table th, .vehicle-table td, .log-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .vehicle-table th, .log-table th {
            background-color: #f4f4f4;
            color: #333;
        }

        .vehicle-table tbody tr:hover, .log-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .form-container input[type="text"], .form-container select {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container button {
            padding: 8px 16px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #00bcd4;
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
        .responsive-input {
            width: 100%;     
            max-width: 400px;     
            padding: 8px;     
            border-radius: 5px;   
            border: 1px solid #ccc;
            box-sizing: border-box; 
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
        <div class="left-column">
            <div class="search-bar">
                <form method="POST" action="security_dashboard.php">
                    <input type="text" name="search_term" placeholder="Search by vehicle number..." required>
                    <button type="submit" name="search">Search</button>
                </form>
            </div>

            <!-- Vehicle Table -->
            <table class="vehicle-table">
                <thead>
                    <tr>
                        <th>Vehicle Number</th>
                        <th>Vehicle Type</th>
                        <th>Owner Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result_vehicles) > 0) {
                        while ($row = mysqli_fetch_assoc($result_vehicles)) {
                            echo "<tr>";
                            echo "<td>" . $row['plate_number'] . "</td>";
                            echo "<td>" . ucfirst($row['vehicle_type']) . "</td>";
                            echo "<td>" . $row['owner_name'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>

        <div class="right-column">
            <div class="form-container">
                <h2>Add Vehicle Entry</h2>
                <form method="POST" action="security_dashboard.php">
                    
                <input type="text" name="plate_number" placeholder="Enter Plate Number" required class="responsive-input">


                    <select name="entry_exit" required>
                        <option value="entry">Entry</option>
                        <option value="exit">Exit</option>
                    </select>

                    <select name="gate_number" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>                    
                    <button type="submit" name="add_vehicle">Add Vehicle</button>
                </form>
            </div>
        </div>
    </div>

    <div class="vehicle-logs">
        <h2>Vehicle Logs</h2>
        <table class="log-table">
            <thead>
                <tr>
                    <th>Plate Number</th>
                    <th>Entry/Exit</th>
                    <th>Gate Number</th>
                    <th>Date and Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_logs) > 0) {
                    while ($log = mysqli_fetch_assoc($result_logs)) {
                        echo "<tr>";
                        echo "<td>" . $log['plate_number'] . "</td>";
                        echo "<td>" . $log['entry_exit'] . "</td>";
                        echo "<td>" . $log['gate_number'] . "</td>";
                        echo "<td>" . $log['date_time'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No logs available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
