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
            background-color: #B2C9AD; /* Light greenish background */
            margin: 0;
            padding: 0;
            color: #000; /* Default text color: black */
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: #FFFFFF; /* White background for the form */
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #4B5945; /* Deep olive green */
            margin-bottom: 30px;
            font-size: 24px;
        }

        label {
            font-size: 14px;
            color: #4B5945; /* Deep olive green for labels */
            margin-bottom: 3px; /* Reduced space between label and input */
            font-weight: bold;
        }

        input {
            width: 100%; /* Ensure inputs fill their grid space */
            padding: 10px; /* Slightly reduced padding */
            border: 1px solid #91AC8F; /* Medium green for border */
            border-radius: 8px;
            font-size: 14px;
            background-color: #F5F5F5; /* Light gray for input background */
            color: #000; /* Black text */
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns */
            gap: 15px; /* Reduced space between fields */
        }


        input:focus {
            border-color: #66785F; /* Darker green on focus */
            outline: none;
            box-shadow: 0 0 6px #91AC8F; /* Subtle green glow */
        }

        input[readonly] {
            background-color: #E9ECEA; 
            color: #66785F;
        }

        button {
            grid-column: 1 / 1; 
            padding: 12px;
            background-color: #4B5945; 
            color: #FFFFFF; 
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            width: 80pt;
        }

        button:hover {
            background-color: #66785F; /* Lighter green on hover */
        }

        .back-btn {
            background-color: #91AC8F; /* Medium green */
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
            margin-top: 30px;
            margin-left: 50px;
        }

        .back-btn:hover {
            background-color: #66785F; /* Darker green on hover */
        }

        @media (max-width: 600px) {
            form {
                grid-template-columns: 1fr; /* Single column for smaller screens */
            }

            button {
                grid-column: span 1;
            }
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
        <input type="text" id="plate_number" name="plate_number" required>

        <label for="vehicle_type">Vehicle Type:</label>
        <input type="text" id="vehicle_type" name="vehicle_type" required>

        <label for="owner_name">Owner Name:</label>
        <input type="text" id="owner_name" name="owner_name" value="<?php echo $owner_name; ?>" readonly>

        <label for="owner_contact">Contact Number:</label>
        <input type="text" id="owner_contact" name="owner_contact" required>

        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
