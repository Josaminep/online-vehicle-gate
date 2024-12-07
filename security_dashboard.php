<?php
session_start();

// Check if the user is logged in and has a 'security' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header('Location: login.php');
    exit;
}

include('config.php'); // Include database connection

// Fetch the assigned gate number for the logged-in user
$user_id = $_SESSION['id'];
$query_gate = "SELECT gate_number FROM users WHERE id = ?";
$stmt_gate = $conn->prepare($query_gate);
$stmt_gate->bind_param("i", $user_id);
$stmt_gate->execute();
$result_gate = $stmt_gate->get_result();

$assigned_gate = ($result_gate && $result_gate->num_rows > 0) 
    ? $result_gate->fetch_assoc()['gate_number'] 
    : "Not Assigned";

// Default query to fetch all vehicle logs
$query = "SELECT * FROM vehicle_logs ORDER BY date_time DESC";

// Search functionality
if (isset($_POST['search'])) {
    $search_term = trim($_POST['search_term']);
    $query = "SELECT * FROM vehicle_logs WHERE plate_number LIKE ? ORDER BY date_time DESC";
    $stmt = $conn->prepare($query);
    $search_like = "%" . $search_term . "%";
    $stmt->bind_param("s", $search_like);
    $stmt->execute();
    $result_logs = $stmt->get_result();
} else {
    $result_logs = $conn->query($query);
}

// Query to fetch vehicles with "approved" status
$query_vehicles = "SELECT * FROM vehicles WHERE status = 'approved' ORDER BY plate_number ASC";
$result_vehicles = $conn->query($query_vehicles);

// Handle vehicle entry submission
if (isset($_POST['add_vehicle'])) {
    $plate_number = trim($_POST['plate_number']);
    $entry_exit = $_POST['entry_exit'];
    $gate_number = $assigned_gate; // Use assigned gate number
    $date_time = date('Y-m-d H:i:s');

    $insert_query = "INSERT INTO vehicle_logs (plate_number, entry_exit, gate_number, date_time) 
                     VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("ssss", $plate_number, $entry_exit, $gate_number, $date_time);
    if ($stmt_insert->execute()) {
        echo "<script>alert('Vehicle entry added successfully');</script>";
    } else {
        echo "<script>alert('Error adding vehicle entry: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard</title>
    <style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #B2C9AD; /* Soft green background */
        margin: 0;
        padding: 0;
        color: #4B5945; /* Olive green text */
    }

    h1, h2 {
        color: #4B5945; /* Olive green headings */
        text-align: center;
    }

    nav {
        background-color: #66785F; /* Dark green background */
        padding: 15px;
        text-align: right;
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: inline-block;
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
        color: #91AC8F; /* Light green hover effect */
    }

    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 85%;
        margin: 20px auto;
        padding: 20px;
        background-color: #91AC8F; /* Muted green container */
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        gap: 20px; /* Space between the left and right columns */
    }

    .left-column, .right-column {
        flex: 1 1 48%;
    }

    /* Form and Table Styles */
    .form-container, .table-container {
        padding: 20px;
        background-color: #FFFFFF;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .form-container input, .form-container select {
        padding: 10px;
        width: 100%;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #66785F;
        box-sizing: border-box; /* Ensures padding doesn't affect width */
    }

    .form-container button, .search-bar button {
        padding: 10px 16px;
        background-color: #4B5945;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%; /* Makes the button full width */
        box-sizing: border-box; /* Ensures padding doesn't affect width */
    }

    .form-container button:hover, .search-bar button:hover {
        background-color: #66785F;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    .table-container th, .table-container td {
        padding: 12px;
        border: 1px solid #91AC8F;
        text-align: center;
    }

    .table-container th {
        background-color: #4B5945;
        color: white;
    }

    .table-container tbody tr:hover {
        background-color: #B2C9AD;
    }

    footer {
        text-align: center;
        padding: 10px;
        background-color: #66785F;
        color: white;
    }

    /* Date and Time Styles */
    .date-time {
        color: #4B5945;
        font-size: 18px;
        position: absolute;
        top: 10px;
        right: 20px;
        font-weight: bold;
    }

    .gate-info {
        text-align: center;
        margin: 20px 0;
        font-size: 18px;
        color: #4B5945;
    }

    .logout-btn {
        background-color: #91AC8F;
        padding: 10px 20px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
    }

    .logout-btn:hover {
        background-color: #4B5945;
    }

    .add-vehicle-entry {
        margin-bottom: 40px; /* Space below Add Vehicle Entry */
    }

    .vehicle-records {
        margin-top: 20px; /* Space above Vehicle Records */
    }

    /* Adjustments for smaller screen sizes */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .left-column, .right-column {
            flex: 1 1 100%;
        }

        .form-container button {
            width: 100%; /* Make button full width on smaller screens */
        }
    }
</style>

</head>
<body>

<div class="date-time" id="datetime"></div>
<h1>Welcome, Security Personnel</h1>

<nav>
    <ul>
        <!--<li><a href="dashboard.php">Dashboard</a></li>-->
        <li><a href="vehicle_logs.php">Vehicle Logs</a></li>
        <li><a href="logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="gate-info">Assigned Gate: <?= htmlspecialchars($assigned_gate); ?></div>

<div class="container">
    <div class="left-column">
        <div class="form-container">
            <h2>Add Vehicle Entry</h2>
            <form method="POST" action="security_dashboard.php">
                <input type="text" name="plate_number" placeholder="Enter Plate Number" required>
                <select name="entry_exit" required>
                    <option value="entry">Entry</option>
                    <option value="exit">Exit</option>
                </select>
                <label for="gate_number">Gate Number:</label>
                <input type="text" name="gate_number" value="<?= htmlspecialchars($assigned_gate); ?>" readonly>
                <button type="submit" name="add_vehicle">Add Vehicle</button>
            </form>
        </div>
    </div>

    <div class="right-column">
        <div class="table-container">
            <h2>Vehicle Records</h2>
            <table>
                <thead>
                    <tr>
                        <th>Plate Number</th>
                        <th>Vehicle Type</th>
                        <th>Owner Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_vehicles) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result_vehicles)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['plate_number']); ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['vehicle_type'])); ?></td>
                                <td><?= htmlspecialchars($row['owner_name']); ?></td>
                                <td><?= htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function updateTime() {
        const now = new Date().toLocaleString('en-US', {
            weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
        });
        document.getElementById('datetime').textContent = now;
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>

</body>
</html>
