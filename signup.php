<?php
session_start();
include('config.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already taken!";
    } else {
        // Set default role as 'owner'
        $role = 'owner'; // Default role
        $status = 'active'; // Default status
        $created_at = date('Y-m-d H:i:s'); // Current timestamp
        $updated_at = date('Y-m-d H:i:s'); // Current timestamp

        // Insert the user into the database
        $sql = "INSERT INTO users (fname, lname, username, password, role, status, created_at, updated_at) 
                VALUES ('$fname', '$lname', '$username', '$hashedPassword', '$role', '$status', '$created_at', '$updated_at')";

        if (mysqli_query($conn, $sql)) {
            echo "Registration successful!";
            // Optionally, you can log the user in after successful registration
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            header("Location: login.php"); // Redirect to login page
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3; /* Light Cream */
            margin: 0;
            padding: 0;
        }

        .signup-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #D8DBBD; /* Light Green */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2A3663; /* Dark Blue */
        }

        input {
            width: 100%; /* Ensure input fields don't overflow */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #B59F78; /* Light Brown */
            border-radius: 5px;
            background-color: #FFF;
            box-sizing: border-box; /* Include padding in width calculation */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2A3663; /* Dark Blue */
            color: #FFF;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box; /* Include padding in width calculation */
        }

        button:hover {
            background-color: #1d2a47; /* Darker Blue */
        }

        .signin-link {
            text-align: center;
            margin-top: 10px;
        }

        .signin-link a {
            color: #2A3663; /* Dark Blue */
            text-decoration: none;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #2A3663;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="POST" action="signup.php">
            <input type="text" name="fname" required placeholder="First Name">
            <input type="text" name="lname" required placeholder="Last Name">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <input type="password" name="confirm_password" required placeholder="Confirm Password">
            <button type="submit">Sign Up</button>
        </form>

        <div class="signin-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Online Vehicle Gate System. All Rights Reserved.</p>
    </footer>

</body>
</html>

