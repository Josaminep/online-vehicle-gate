<?php
session_start();

// Ensure only the 'owner' role can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: login.php');
    exit;
}

// Include the database configuration
include('../config.php'); // This will include the connection to the database

// Fetch the logged-in owner's ID from the session
$owner_id = $_SESSION['id'];

// SQL Query to fetch entry/exit logs for the owner's vehicles
$sql = "SELECT v.plate_number, v.vehicle_type, e.entry_time, e.exit_time
        FROM entry_exit_logs e
        JOIN vehicles v ON e.vehicle_id = v.id
        WHERE v.id = ?";  // Change e.owner_id to v.owner_id assuming that vehicles table holds owner_id

// Prepare the statement
$stmt = $conn->prepare($sql);

// Check if the statement preparation was successful
if ($stmt === false) {
    die("Error preparing the SQL query: " . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entry/Exit History</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Entry/Exit History</h2>
<table>
    <thead>
        <tr>
            <th>Plate Number</th>
            <th>Vehicle Model</th>
            <th>Entry Time</th>
            <th>Exit Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['plate_number']) ?></td>
                    <td><?= htmlspecialchars($row['vehicle_model']) ?></td>
                    <td><?= htmlspecialchars($row['entry_time']) ?></td>
                    <td><?= htmlspecialchars($row['exit_time'] ?: 'N/A') ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No entry/exit logs found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>

<?php
// Close the statement and database connection
$stmt->close();
$conn->close();
?>
