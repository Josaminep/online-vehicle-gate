<?php
session_start();

// Ensure only the 'owner' role can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: login.php');
    exit;
}

// Fetch owner ID from the session
$owner_id = $_SESSION['id']; // Assuming the user ID is stored in the session during login
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
            background-color: #f7f7f7;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 100px auto;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .button {
            background-color: #4caf50;
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
            background-color: #45a049;
        }

        .button:focus {
            outline: none;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome, Owner</h1>
        
        <!-- Display the Owner's ID -->
        <p><strong>Your Owner ID: </strong><?= htmlspecialchars($owner_id) ?></p>

        <!-- Button Container -->
        <div class="button-container">
            <a href="register_vehicle.php" class="button">Register Vehicle</a>
            <a href="entry_exit_history.php" class="button">View Entry/Exit History</a>
        </div>
    </div>

</body>
</html>
