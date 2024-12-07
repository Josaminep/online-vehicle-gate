<?php
session_start();

// Ensure only the 'owner' role can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: login.php');
    exit;
}

// Fetch owner ID from the session
$owner_id = $_SESSION['id']; // Assuming the user ID is stored in the session during login

// Connect to the database
include('config.php');

// Fetch the owner's first name and last name from the database using the owner ID
$sql = "SELECT fname, lname FROM users WHERE id = '$owner_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $first_name = $user['fname']; // Store the first name in a variable
    $last_name = $user['lname'];  // Store the last name in a variable
    $full_name = $first_name . ' ' . $last_name; // Concatenate first and last name
} else {
    $full_name = "Owner"; // Default to 'Owner' if no name is found
}

// Fetch vehicles registered by the owner
$vehicle_sql = "SELECT plate_number, vehicle_type, owner_id, owner_name, owner_contact, registration_date, status FROM vehicles WHERE owner_id = '$owner_id'";
$vehicle_result = mysqli_query($conn, $vehicle_sql);

if (mysqli_num_rows($vehicle_result) > 0) {
    $vehicles = mysqli_fetch_all($vehicle_result, MYSQLI_ASSOC);
} else {
    $vehicles = []; // No vehicles found
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: logout.php'); // Redirect to the login page
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #B2C9AD; /* Light green background */
            color: #4B5945; /* Text color */
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 80px auto;
            text-align: center;
            background-color: #91AC8F; /* Background for the container */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        h1 {
            color: #4B5945; /* Main text color */
            font-size: 32px;
            margin-bottom: 20px;
        }

        p {
            font-size: 20px;
            color: #4B5945; /* Main text color */
        }

        .vehicle-list {
            margin-top: 30px;
            text-align: left;
        }

        .vehicle-list table {
            width: 100%;
            border-collapse: collapse;
            background-color: white; /* Table background */
        }

        .vehicle-list th, .vehicle-list td {
            padding: 12px;
            border: 1px solid #66785F;
            text-align: left;
            font-size: 18px;
        }

        .vehicle-list th {
            background-color: #66785F; /* Header background */
            color: white; /* Header text */
        }

        .vehicle-list tr:nth-child(even) {
            background-color: #F5F5F5; /* Alternate row color */
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .button {
            background-color: #4B5945; /* Dark button */
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            width: 200px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #66785F; /* Hover color */
        }

        .logout-button {
            background-color: #91AC8F;
            color: white;
        }

        .logout-button:hover {
            background-color: #66785F;
        }

        .date-time {
            color: #4B5945;
            font-size: 16px;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 16px;
            color: #4B5945;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

    <p><strong>Your Owner ID: </strong><?= htmlspecialchars($owner_id) ?></p>

    <div class="vehicle-list">
        <h3>Your Registered Vehicles:</h3>

        <?php if (count($vehicles) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Plate Number</th>
                        <th>Vehicle Type</th>
                        <th>Owner Name</th>
                        <th>Owner Contact</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vehicle['plate_number']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['owner_name']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['owner_contact']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['registration_date']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No vehicles registered.</p>
        <?php endif; ?>
    </div>

    <div class="button-container">
        <a href="register_vehicle.php" class="button">Register Vehicle</a>
        <form method="POST">
            <button type="submit" name="logout" class="button logout-button">Logout</button>
        </form>
    </div>
</div>

<div class="date-time">
    <p id="datetime"></p>
</div>

<script>
    function updateTime() {
        const now = new Date().toLocaleString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
            timeZone: 'Asia/Manila'
        });
        document.getElementById('datetime').textContent = now;
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>

</body>
</html>
