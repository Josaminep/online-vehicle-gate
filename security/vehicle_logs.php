<?php
session_start();
// Check if user is logged in and has security role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header('Location: login.php');
    exit;
}

include('../config.php'); // Include database connection

// Handle vehicle log entry and exit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle_number']);
    $entry_exit = mysqli_real_escape_string($conn, $_POST['entry_exit']); // 'entry' or 'exit'
    $date_time = date('Y-m-d H:i:s'); // Current date and time

    // Insert vehicle log into database
    $sql = "INSERT INTO vehicle_logs (vehicle_number, entry_exit, date_time) VALUES ('$vehicle_number', '$entry_exit', '$date_time')";
    if (mysqli_query($conn, $sql)) {
        $message = "Vehicle log recorded successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Handle vehicle log search
$search_results = [];
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search_term']);
    $sql = "SELECT * FROM vehicle_logs WHERE vehicle_number LIKE '%$search_term%' OR date_time LIKE '%$search_term%'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $search_results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Logs - Security Personnel</title>
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

        .form-container {
            margin-bottom: 20px;
        }

        .form-container input, .form-container select, .form-container button {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .back-btn {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: #28a745;
        }
    </style>
</head>
<body>

    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="vehicle_logs.php">Vehicle Logs</a></li>
            <li><a href="../logout.php" class="back-btn">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Vehicle Logs - Security Personnel</h1>

        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

        <!-- Display Success/Failure Message -->
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Log Entry or Exit Form -->
        <div class="form-container">
            <h3>Log Vehicle Entry/Exit</h3>
            <form method="POST" action="vehicle_logs.php">
                <input type="text" name="vehicle_number" placeholder="Vehicle Number" required>
                <select name="entry_exit" required>
                    <option value="entry">Entry</option>
                    <option value="exit">Exit</option>
                </select>
                <button type="submit">Log Vehicle</button>
            </form>
        </div>

        <!-- Search Logs Form -->
        <div class="form-container">
            <h3>Search Vehicle Logs</h3>
            <form method="POST" action="vehicle_logs.php">
                <input type="text" name="search_term" placeholder="Search by Vehicle Number or Date" required>
                <button type="submit" name="search">Search</button>
            </form>
        </div>

        <!-- Display Vehicle Logs if any search results -->
        <?php if (!empty($search_results)): ?>
            <table>
                <tr>
                    <th>Vehicle Number</th>
                    <th>Entry/Exit</th>
                    <th>Date and Time</th>
                </tr>
                <?php foreach ($search_results as $log): ?>
                    <tr>
                        <td><?php echo $log['vehicle_number']; ?></td>
                        <td><?php echo ucfirst($log['entry_exit']); ?></td>
                        <td><?php echo $log['date_time']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
