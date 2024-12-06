<?php
session_start();
include('config.php'); // Include database connection

// Ensure only registered vehicle owners can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: login.php');
    exit;
}

// Fetch owner ID from the session
$owner_id = $_SESSION['id']; // Assuming the user ID is stored in the session during login

// Fetch the owner's first name and last name from the database
$sql = "SELECT fname, lname FROM users WHERE id = '$owner_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $owner_name = $user['fname'] . ' ' . $user['lname']; // Concatenate first and last name
} else {
    $owner_name = "Owner"; // Default to 'Owner' if no name is found
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values from form
    $plate_number = mysqli_real_escape_string($conn, $_POST['plate_number']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $owner_contact = mysqli_real_escape_string($conn, $_POST['owner_contact']);

    // Insert vehicle details into the database with owner_id
    $sql = "INSERT INTO vehicles (plate_number, vehicle_type, owner_id, owner_name, owner_contact, status, approved_by) 
            VALUES ('$plate_number', '$vehicle_type', '$owner_id', '$owner_name', '$owner_contact', 'pending', NULL)";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='success'>Vehicle registered successfully! Please wait for approval.</div>";
    } else {
        echo "<div class='error'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3; /* Light cream background */
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #FFFFFF; /* White background for form */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2A3663; /* Deep blue color */
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
            color: #2A3663; /* Deep blue for label */
        }

        input {
            width: calc(100% - 24px); /* Ensures input doesn't overflow container */
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #D8DBBD; /* Light greenish border */
            border-radius: 5px;
            font-size: 16px;
            background-color: #FAF6E3; /* Light cream for input */
        }

        input[readonly] {
            background-color: #D8DBBD; /* Light greenish for readonly input */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2A3663; /* Deep blue background */
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #B59F78; /* Light brown on hover */
        }

        .success {
            color: #4CAF50; /* Green for success messages */
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: #E74C3C; /* Red for error messages */
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #2A3663; /* Deep blue for footer */
            color: white;
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

<?php
include('config.php'); // Include database connection

// Ensure only registered vehicle owners can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'owner') {
    header('Location: login.php');
    exit;
}

// Fetch owner ID from the session
$owner_id = $_SESSION['id']; // Assuming the user ID is stored in the session during login

// Fetch the owner's first name and last name from the database
$sql = "SELECT fname, lname FROM users WHERE id = '$owner_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $owner_name = $user['fname'] . ' ' . $user['lname']; // Concatenate first and last name
} else {
    $owner_name = "Owner"; // Default to 'Owner' if no name is found
}
?>
<a href="owner_dashboard.php" class="back-btn">Back</a>

<div class="container">
    <h1>Register Vehicle</h1>
    <form method="POST" action="register_vehicle.php">
        <label for="plate_number">Plate Number:</label>
        <input type="text" id="plate_number" name="plate_number" required><br>

        <label for="vehicle_type">Vehicle Type:</label>
        <input type="text" id="vehicle_type" name="vehicle_type" required><br>

        <!-- Display owner name in a readonly input field -->
        <label for="owner_name">Owner Name:</label>
        <input type="text" id="owner_name" name="owner_name" value="<?php echo $owner_name; ?>" readonly><br>

        <label for="owner_contact">Owner Contact:</label>
        <input type="text" id="owner_contact" name="owner_contact" required><br>

        <button type="submit">Register Vehicle</button>
    </form>
</div>

</body>
</html>
