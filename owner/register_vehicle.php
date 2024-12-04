<?php
session_start();
include('../config.php'); // Include database connection
include('../phpqrcode/qrlib.php'); // Include the QR code library

// Ensure only registered vehicle owners can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plate_number = mysqli_real_escape_string($conn, $_POST['plate_number']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $owner_contact = mysqli_real_escape_string($conn, $_POST['owner_contact']);
    $owner_id = $_SESSION['id']; // Get the owner's ID from the session

    // Insert vehicle details into the database
    $sql = "INSERT INTO vehicles (plate_number, vehicle_type, owner_name, owner_contact, status, approved_by) 
            VALUES ('$plate_number', '$vehicle_type', '$owner_name', '$owner_contact', 'pending', NULL)";

    if (mysqli_query($conn, $sql)) {
        // Get the last inserted vehicle ID
        $vehicle_id = mysqli_insert_id($conn);

        // Generate QR code for the vehicle
        $qrCodeContent = "vehicle_id=$vehicle_id"; // You can also include other details like plate number if needed
        $qrFilename = "qrcodes/vehicle_" . $vehicle_id . ".png";
        
        // Generate and save the QR code image
        QRcode::png($qrCodeContent, $qrFilename, 'L', 4, 4);

        echo "<div class='success'>Vehicle registered successfully! Please wait for approval. <br> QR Code generated: <img src='$qrFilename' alt='QR Code'></div>";
    } else {
        echo "<div class='error'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!-- HTML Form remains the same -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .success {
            color: green;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
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
        <h1>Register Vehicle</h1>
        <form method="POST" action="register_vehicle.php">
            <label for="plate_number">Plate Number:</label>
            <input type="text" id="plate_number" name="plate_number" required><br>

            <label for="vehicle_type">Vehicle Type:</label>
            <input type="text" id="vehicle_type" name="vehicle_type" required><br>

            <label for="owner_name">Owner Name:</label>
            <input type="text" id="owner_name" name="owner_name" required><br>

            <label for="owner_contact">Owner Contact:</label>
            <input type="text" id="owner_contact" name="owner_contact" required><br>

            <button type="submit">Register Vehicle</button>
        </form>
    </div>

</body>
</html>
