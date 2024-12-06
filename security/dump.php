<?php
session_start();
include('../config.php'); // Include the database connection

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch vehicle logs for graph
$sql_logs = "SELECT plate_number, entry_exit, date_time, gate_number FROM vehicle_logs";
$logs_result = mysqli_query($conn, $sql_logs);

// Process report generation
$report_type = isset($_POST['report_type']) ? $_POST['report_type'] : '';

if ($report_type) {
    $date_condition = "";
    $title = "";

    switch ($report_type) {
        case 'daily':
            $date_condition = "DATE(date_time) = CURDATE()";
            $title = "Daily Vehicle Log Report";
            break;
        case 'weekly':
            $date_condition = "WEEK(date_time) = WEEK(CURDATE())";
            $title = "Weekly Vehicle Log Report";
            break;
        case 'monthly':
            $date_condition = "MONTH(date_time) = MONTH(CURDATE())";
            $title = "Monthly Vehicle Log Report";
            break;
        default:
            $date_condition = "";
            break;
    }

    // Fetch vehicle logs based on selected report period
    if ($date_condition) {
        $report_sql = "SELECT * FROM vehicle_logs WHERE $date_condition";
        $report_result = mysqli_query($conn, $report_sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #2A3663;
            margin-top: 20px;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .btn {
            background-color: #2A3663;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            opacity: 0.8;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #2A3663;
            color: white;
        }

        .report-form select {
            padding: 8px;
            margin-right: 10px;
            background-color: #D8DBBD;
            border: none;
            border-radius: 5px;
        }

        .report-form button {
            background-color: #2A3663;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>View Vehicle Logs and Reports</h1>

    <div class="container">
        <div class="report-form">
            <form method="POST">
                <label for="report_type">Generate Report: </label>
                <select name="report_type" id="report_type" required>
                    <option value="">Select Report Type</option>
                    <option value="daily" <?php echo ($report_type == 'daily') ? 'selected' : ''; ?>>Daily</option>
                    <option value="weekly" <?php echo ($report_type == 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                    <option value="monthly" <?php echo ($report_type == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                </select>
                <button type="submit" class="btn">Generate Report</button>
            </form>
        </div>

        <h2 class="text-2xl font-bold mb-4">Vehicle Log Graph</h2>
        <canvas id="logChart" width="400" height="200"></canvas>

        <h2 class="text-2xl font-bold mb-4"><?php echo $title ?: 'Vehicle Logs'; ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Plate Number</th>
                    <th>Entry/Exit</th>
                    <th>Date and Time</th>
                    <th>Gate Number</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($log = mysqli_fetch_assoc($logs_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['plate_number']); ?></td>
                        <td><?php echo htmlspecialchars($log['entry_exit']); ?></td>
                        <td><?php echo htmlspecialchars($log['date_time']); ?></td>
                        <td><?php echo htmlspecialchars($log['gate_number']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if ($report_result && mysqli_num_rows($report_result) > 0) { ?>
            <h3>Generated Report:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Plate Number</th>
                        <th>Entry/Exit</th>
                        <th>Date and Time</th>
                        <th>Gate Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = mysqli_fetch_assoc($report_result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['plate_number']); ?></td>
                            <td><?php echo htmlspecialchars($report['entry_exit']); ?></td>
                            <td><?php echo htmlspecialchars($report['date_time']); ?></td>
                            <td><?php echo htmlspecialchars($report['gate_number']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <footer>
        <p>&copy; 2024 Your Company Name. All Rights Reserved.</p>
    </footer>

    <script>
        var ctx = document.getElementById('logChart').getContext('2d');
        var logChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Gate 1', 'Gate 2', 'Gate 3', 'Gate 4'], // You can customize this to reflect actual data
                datasets: [{
                    label: 'Vehicle Logs',
                    data: [12, 19, 3, 5], // Example data, update with actual data
                    backgroundColor: [
                        '#2A3663',
                        '#D8DBBD',
                        '#B59F78',
                        '#FAF6E3'
                    ],
                    borderColor: [
                        '#2A3663',
                        '#D8DBBD',
                        '#B59F78',
                        '#FAF6E3'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
