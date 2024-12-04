<?php
session_start();
// Ensure the user is not logged in if accessing this page directly
if (isset($_SESSION['role'])) {
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
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

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #333;
            color: white;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome to the Vehicle Registration System</h1>

        <!-- Button Container -->
        <div class="button-container">
            <a href="login.php" class="button">Login</a>
            <a href="register_vehicle.php" class="button">Register Vehicle</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Vehicle Registration System</p>
    </footer>

</body>
</html>
