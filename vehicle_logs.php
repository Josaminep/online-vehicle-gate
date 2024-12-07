<?php
session_start();

// Check if the user is logged in and has a 'security' role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header('Location: login.php');
    exit;
}

include('config.php'); // Include database connection

// Query to get vehicle logs
$query = "SELECT * FROM `vehicle_logs`";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <a href="security_dashboard.php" class="btn btn-back">Back</a>

    <div class="container">
        <h2>Vehicle Logs</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="search" placeholder="Search by plate number, entry/exit, or gate number..." onkeyup="searchLogs()">
        </div>

        <!-- Vehicle Logs Table -->
        <div class="table-container">
            <table id="vehicleLogsTable">
                <thead>
                    <tr>
                        <th>Plate Number</th>
                        <th>Entry/Exit</th>
                        <th>Date and Time</th>
                        <th>Gate Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['plate_number']); ?></td>
                                <td><?= htmlspecialchars($row['entry_exit']); ?></td>
                                <td><?= htmlspecialchars($row['date_time']); ?></td>
                                <td><?= htmlspecialchars($row['gate_number']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to filter table rows based on search input
        function searchLogs() {
            const input = document.getElementById('search').value.toUpperCase();
            const table = document.getElementById('vehicleLogsTable');
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header
                const cells = rows[i].getElementsByTagName('td');
                const plateNumber = cells[0].textContent || cells[0].innerText;
                const entryExit = cells[1].textContent || cells[1].innerText;
                const gateNumber = cells[3].textContent || cells[3].innerText;

                if (
                    plateNumber.toUpperCase().indexOf(input) > -1 ||
                    entryExit.toUpperCase().indexOf(input) > -1 ||
                    gateNumber.toUpperCase().indexOf(input) > -1
                ) {
                    rows[i].style.display = ""; // Show row if there's a match
                } else {
                    rows[i].style.display = "none"; // Hide row if no match
                }
            }
        }
    </script>
</body>
</html>
