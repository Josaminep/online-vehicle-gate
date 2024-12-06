<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('config.php'); // Include database connection

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Fetch vehicles with "pending" status
$sql = "SELECT * FROM vehicles WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);
$pending_count = mysqli_num_rows($result); // Count the number of pending vehicles

// Fetch vehicle logs
$log_sql = "SELECT * FROM vehicle_logs";
$log_result = mysqli_query($conn, $log_sql);

$sql_vehicles = "SELECT * FROM vehicles WHERE status = 'approved'"; // Fetch vehicles with status 'approved'
$vehicles_result = mysqli_query($conn, $sql_vehicles);

// Get the current date and time (without milliseconds) in Philippine timezone
$current_datetime = date('l, F j, Y h:i:s', time()); // Time without milliseconds
?>

<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-main: #4B5945; /* Dark green */
            --bg-sidebar: #66785F; /* Olive green */
            --bg-highlight: #91AC8F; /* Soft green */
            --text-main: #FFFFFF; /* White text */
            --text-sidebar: #FFFFFF; /* White text */
            --text-highlight: #FFFFFF; /* White text */
            --bg-footer: #66785F; /* Olive green */
            --text-table: #000000; /* Default black text for table content */
        }

        /* Fixed height and scrollable for both vehicle logs and registered vehicles */
        .scrollable-container {
            max-height: 400px; /* Fixed height */
            overflow-y: auto;  /* Vertical scroll */
            border: 1px solid #ddd; /* Optional border for container */
            margin-bottom: 20px; /* Space between the containers */
        }

        .container-flex {
            display: flex;
            gap: 20px; /* Space between the two containers */
        }

        .container {
            flex: 1; /* This makes both containers take equal width */
        }
    </style>
</head>
<body class="bg-[var(--bg-main)]">
<div class="flex">
    <!-- Sidebar -->
    <div class="w-1/5 bg-[var(--bg-sidebar)] text-[var(--text-sidebar)] h-screen p-6 flex flex-col justify-between">
        <div>
        <h2 class="text-2xl font-bold mb-8 mt-8 text-center">Online Vehicle Gate System</h2>
        <ul>
                <li class="mb-6 text-lg">
                    <a href="manage_users.php" class="flex items-center hover:text-gray-400 py-2">
                        <i class="fas fa-users mr-3"></i> Manage Users
                    </a>
                </li>
                <li class="mb-6 flex items-center text-lg">
                    <i class="fas fa-car mr-3"></i>
                    <a href="manage_vehicles.php" class="flex items-center hover:text-gray-400 py-2">Manage Vehicles</a>
                    <?php if ($pending_count > 0): ?>
                        <span class="notification ml-2 bg-red-500 text-white rounded-full px-2 py-1 text-sm" style="font-weight: bold;"><?php echo $pending_count; ?></span> <!-- Notification Badge -->
                    <?php endif; ?>
                </li>

                <li class="mb-6 text-lg">
                    <a href="manage_gates.php" class="flex items-center hover:text-gray-400 py-2">
                        <i class="fas fa-door-open mr-3"></i> Manage Gates
                    </a>
                </li>
                <li class="mb-6 text-lg">
                    <a href="view_reports.php" class="flex items-center hover:text-gray-400 py-2">
                        <i class="fas fa-chart-line mr-3"></i> View Reports
                    </a>
                </li>
            </ul>
        </div>

        <button onclick="window.location.href='logout.php'" class="bg-[var(--bg-highlight)] text-[var(--text-highlight)] py-2 px-4 rounded hover:bg-opacity-75 flex items-center justify-center">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
    </div>

    <!-- Main Content -->
    <div class="w-3/4 p-6">
        <h1 class="text-3xl font-bold mb-4 text-[var(--text-main)]">Dashboard</h1>

        <div class="datetime bg-[var(--bg-highlight)] text-[var(--text-highlight)] p-4 rounded mb-6 flex items-center justify-between">
            <p id="datetime" class="text-lg font-semibold"></p>
        </div>

        <div class="container-flex">
            <!-- Vehicle Logs Section -->
            <div class="container">
                <h2 class="text-2xl font-bold mb-4 text-[var(--text-main)]">Vehicle Logs</h2>
                <div class="scrollable-container bg-white">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Plate Number</th>
                                <th class="py-2 px-4 border-b">Entry/Exit</th>
                                <th class="py-2 px-4 border-b">Date and Time</th>
                                <th class="py-2 px-4 border-b">Gate Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($log = mysqli_fetch_assoc($log_result)): ?>
                                <tr>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($log['plate_number']); ?></td>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($log['entry_exit']); ?></td>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($log['date_time']); ?></td>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($log['gate_number']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Registered Vehicles Section -->
            <div class="container">
                <h2 class="text-2xl font-bold mb-4 text-[var(--text-main)]">Registered Vehicles</h2>
                <div class="scrollable-container bg-white">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Plate Number</th>
                                <th class="py-2 px-4 border-b">Vehicle Type</th>
                                <th class="py-2 px-4 border-b">Owner Name</th>
                                <th class="py-2 px-4 border-b">Owner Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($vehicle = mysqli_fetch_assoc($vehicles_result)): ?>
                                <tr>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($vehicle['plate_number']); ?></td>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></td>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($vehicle['owner_name']); ?></td>
                                    <td class="py-2 px-4 border-b" style="color: var(--text-table);"><?php echo htmlspecialchars($vehicle['owner_contact']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
    function updateTime() {
        const now = new Date();
        const formattedTime = now.toLocaleString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('datetime').textContent = formattedTime;
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>
</body>
</html>
