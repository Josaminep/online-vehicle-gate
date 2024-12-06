<?php
session_start();
include('config.php'); // Include database connection

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch vehicles with "pending" status
$sql = "SELECT * FROM vehicles WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #FAF6E3; /* Light cream */
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #2A3663; /* Deep blue */
            margin-top: 20px;
            font-size: 28px;
        }

        nav {
            background-color: #2A3663; /* Deep blue */
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
            color: #D8DBBD; /* Soft beige */
        }

        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #D8DBBD; /* Soft beige */
        }

        th {
            background-color: #2A3663; /* Deep blue */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #D8DBBD; /* Soft beige */
        }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
            font-size: 14px;
            color: white;
            cursor: pointer;
        }

        .btn-approve {
            background-color: #B59F78; /* Muted gold */
        }

        .btn-deny {
            background-color: #f44336;
        }

        .btn-back {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        .btn:hover {
            opacity: 0.8;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #2A3663; /* Deep blue */
            color: white;
        }
    </style>
</head>
<body>

    <h1>Manage Vehicle Registrations</h1>

    <div class="container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="btn btn-back">Back to Dashboard</a>

        <!-- Vehicle Management Table -->
        <table>
            <thead>
                <tr>
                    <th>Plate Number</th>
                    <th>Vehicle Type</th>
                    <th>Owner Name</th>
                    <th>Owner Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($vehicle = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $vehicle['plate_number']; ?></td>
                        <td><?php echo $vehicle['vehicle_type']; ?></td>
                        <td><?php echo $vehicle['owner_name']; ?></td>
                        <td><?php echo $vehicle['owner_contact']; ?></td>
                        <td>
                            <a href="approve_vehicle.php?id=<?php echo $vehicle['id']; ?>&action=approve" class="btn btn-approve">Approve</a>
                            <a href="approve_vehicle.php?id=<?php echo $vehicle['id']; ?>&action=deny" class="btn btn-deny">Deny</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
