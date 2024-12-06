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
include('../config.php');

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
$vehicle_sql = "SELECT plate_number, vehicle_type, owner_name, owner_contact, registration_date, status FROM vehicles WHERE id = '$owner_id'";
$vehicle_result = mysqli_query($conn, $vehicle_sql);

if (mysqli_num_rows($vehicle_result) > 0) {
    $vehicles = mysqli_fetch_all($vehicle_result, MYSQLI_ASSOC);
} else {
    $vehicles = []; // No vehicles found
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: ../logout.php'); // Redirect to the login page
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
            background-color: #FAF6E3; /* Light Cream background */
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 100px auto;
            text-align: center;
            background-color: #D8DBBD; /* Light Green background */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2A3663; /* Dark Blue text */
            margin-bottom: 20px;
        }

        p {
            color: #2A3663; /* Dark Blue text for content */
            font-size: 18px;
        }

        .vehicle-list {
            margin-top: 30px;
            text-align: left;
            padding-left: 20px;
        }

        .vehicle-list th, .vehicle-list td {
            padding: 10px;
            border-bottom: 1px solid #2A3663;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .button {
            background-color: #2A3663; /* Dark Blue button */
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            width: 200px;
            text-align: center;
        }

        .button:hover {
            background-color: #1d2a47; /* Darker Blue on hover */
        }

        .button:focus {
            outline: none;
        }

        .logout-button {
            background-color: #B59F78; /* Gold button */
            color: white;
        }

        .logout-button:hover {
            background-color: #a17e55; /* Darker Gold on hover */
        }

        .footer {
            margin-top: 50px;
            color: #B59F78; /* Gold text for footer */
        }

        .date-time {
            color: #B59F78; /* Gold text for the current date and time */
            font-size: 16px;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #2A3663;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>

    <!-- Display the Owner's ID -->
    <p style="text-align: right;"><strong>Your Owner ID: </strong><?= htmlspecialchars($owner_id) ?></p>

<!-- Display vehicles registered by the owner -->
<div style="text-align: left; color: #DA8359;">
    <h3>Your Registered Vehicles:</h3>

<?php if (count($vehicles) > 0): ?>
    <div style="overflow-x: auto; margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; background-color: #FAF6E3; border: 1px solid #D8DBBD;">
            <thead>
                <tr style="background-color: #D8DBBD; color: #2A3663;">
                    <th style="padding: 10px; border: 1px solid #B59F78;">Plate Number</th>
                    <th style="padding: 10px; border: 1px solid #B59F78;">Vehicle Type</th>
                    <th style="padding: 10px; border: 1px solid #B59F78;">Owner Name</th>
                    <th style="padding: 10px; border: 1px solid #B59F78;">Owner Contact</th>
                    <th style="padding: 10px; border: 1px solid #B59F78;">Registration Date</th>
                    <th style="padding: 10px; border: 1px solid #B59F78;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr style="background-color: #ffffff; color: #2A3663;">
                        <td style="padding: 10px; border: 1px solid #D8DBBD;"><?php echo htmlspecialchars($vehicle['plate_number']); ?></td>
                        <td style="padding: 10px; border: 1px solid #D8DBBD;"><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></td>
                        <td style="padding: 10px; border: 1px solid #D8DBBD;"><?php echo htmlspecialchars($vehicle['owner_name']); ?></td>
                        <td style="padding: 10px; border: 1px solid #D8DBBD;"><?php echo htmlspecialchars($vehicle['owner_contact']); ?></td>
                        <td style="padding: 10px; border: 1px solid #D8DBBD;"><?php echo htmlspecialchars($vehicle['registration_date']); ?></td>
                        <td style="padding: 10px; border: 1px solid #D8DBBD;"><?php echo htmlspecialchars($vehicle['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p style="color: #2A3663; margin-top: 20px;">No vehicles registered.</p>
<?php endif; ?>
</div>


    <!-- Button Container -->
    <div class="button-container">
        <a href="register_vehicle.php" class="button">Register Vehicle</a>
        <!-- Logout Button -->
        <form method="POST">
            <button type="submit" name="logout" class="button logout-button">Logout</button>
        </form>
    </div>

</div>

<!-- Display current date and time on the right -->
<div class="date-time">
    <p id="datetime"></p>
</div>

<footer class="footer">
    <p>&copy; 2024 Online Vehicle Gate System. All Rights Reserved.</p>
</footer>

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
            timeZone: 'Asia/Manila'  // Set timezone to Philippine Time
        });
        document.getElementById('datetime').textContent = now;
    }

    setInterval(updateTime, 1000); // Update time every second
    updateTime(); // Call function immediately to display initial time
</script>

</body>
</html>
