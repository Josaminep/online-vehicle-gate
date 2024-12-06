<?php
session_start();
// Check if user is logged in and has security role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header('Location: login.php');
    exit;
}

include('config.php'); // Include database connection

// Handle vehicle access approval/denial
if (isset($_GET['action']) && isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $action = $_GET['action']; // either 'approve' or 'deny'

    if ($action == 'approve') {
        $status = 'Approved';
    } else if ($action == 'deny') {
        $status = 'Denied';
    }

    // Update vehicle access status in the database
    $sql = "UPDATE vehicle_access SET status = '$status' WHERE vehicle_id = '$vehicle_id'";
    if (mysqli_query($conn, $sql)) {
        header('Location: gate_access_dashboard.php'); // Refresh page
        exit;
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch pending vehicle access requests
$sql = "SELECT * FROM vehicle_access WHERE status = 'Pending'";
$result = mysqli_query($conn, $sql);
$pending_requests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pending_requests[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Access Management - Security</title>
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

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #45a049;
        }

        .deny-button {
            background-color: #f44336;
        }

        .deny-button:hover {
            background-color: #e53935;
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
    </style>
</head>
<body>

    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="gate_access_dashboard.php">Gate Access</a></li>
            <li><a href="../logout.php" class="back-btn">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Gate Access Management - Security</h1>

        <!-- Display Success/Failure Message -->
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Pending Access Requests -->
        <h3>Pending Vehicle Access Requests</h3>
        <table>
            <tr>
                <th>Vehicle Number</th>
                <th>Entry Time</th>
                <th>Action</th>
            </tr>
            <?php foreach ($pending_requests as $request): ?>
                <tr>
                    <td><?php echo $request['vehicle_number']; ?></td>
                    <td><?php echo $request['entry_time']; ?></td>
                    <td>
                        <a href="gate_access_dashboard.php?action=approve&vehicle_id=<?php echo $request['vehicle_id']; ?>" class="button">Approve</a>
                        <a href="gate_access_dashboard.php?action=deny&vehicle_id=<?php echo $request['vehicle_id']; ?>" class="deny-button">Deny</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

</body>
</html>
