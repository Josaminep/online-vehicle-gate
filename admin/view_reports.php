<?php
session_start();
include('../config.php'); // Include the database connection

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Process report generation
$report_type = isset($_POST['report_type']) ? $_POST['report_type'] : '';
$report_result = null;
$title = "Vehicle Logs"; // Default title in case no report type is selected

// Fetch vehicle logs from the database
$vehicle_logs_sql = "SELECT plate_number, entry_exit, date_time, gate_number FROM vehicle_logs";
$vehicle_logs_result = mysqli_query($conn, $vehicle_logs_sql);

// Check for query execution errors
if (!$vehicle_logs_result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

// Prepare data for the chart
$log_dates = [];
$entry_exit_counts = ['Entry' => 0, 'Exit' => 0];
$gate_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

while ($log = mysqli_fetch_assoc($vehicle_logs_result)) {
    // Prepare data for date-based grouping (if necessary)
    $date = date('Y-m-d', strtotime($log['date_time']));
    if (!in_array($date, $log_dates)) {
        $log_dates[] = $date;
    }

    // Count entry/exit and gate number occurrences
    if ($log['entry_exit'] == 'Entry') {
        $entry_exit_counts['Entry']++;
    } else {
        $entry_exit_counts['Exit']++;
    }

    if (isset($gate_counts[$log['gate_number']])) {
        $gate_counts[$log['gate_number']]++;
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
        .back-btn {
            background-color: #F44336; /* Red for back button */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            margin-left: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #D32F2F; /* Darker red on hover */
        }
    </style>
</head>
<body>
<a href="admin_dashboard.php" class="back-btn">Back</a>

    <h1>View Vehicle Logs and Reports</h1>

    <div class="container">

        <div class="report-form">
            <form method="POST" target="_blank" action="generate_report.php">
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


    </div>

    <script>
        // Prepare data for the chart
        const logDates = <?php echo json_encode($log_dates); ?>;
        const entryExitCounts = <?php echo json_encode($entry_exit_counts); ?>;
        const gateCounts = <?php echo json_encode($gate_counts); ?>;

        // Create the chart
        const ctx = document.getElementById('logChart').getContext('2d');
        const logChart = new Chart(ctx, {
            type: 'bar', // Change chart type as needed (e.g., 'bar', 'line', 'pie')
            data: {
                labels: logDates, // Date labels for X-axis
                datasets: [{
                    label: 'Entries vs Exits',
                    data: [entryExitCounts['Entry'], entryExitCounts['Exit']], // Data for Entry and Exit
                    backgroundColor: ['#4CAF50', '#F44336'], // Colors for Entry and Exit
                    borderColor: ['#388E3C', '#D32F2F'],
                    borderWidth: 1
                }, {
                    label: 'Gate 1',
                    data: [gateCounts[1]],
                    backgroundColor: '#FAF6E3',
                    borderColor: '#FAF6E3',
                    borderWidth: 1
                }, {
                    label: 'Gate 2',
                    data: [gateCounts[2]],
                    backgroundColor: '#D8DBBD',
                    borderColor: '#D8DBBD',
                    borderWidth: 1
                }, {
                    label: 'Gate 3',
                    data: [gateCounts[3]],
                    backgroundColor: '#B59F78',
                    borderColor: '#B59F78',
                    borderWidth: 1
                }, {
                    label: 'Gate 4',
                    data: [gateCounts[4]],
                    backgroundColor: '#2A3663',
                    borderColor: '#2A3663',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
