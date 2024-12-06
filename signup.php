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
            background-color: #FAF6E3;
            margin: 0;
            padding: 0;
        }

        .signup-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #66785F; /* Muted Olive Green */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #FFFFFF; /* White Text */
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #91AC8F; /* Soft Green */
            border-radius: 5px;
            background-color: #B2C9AD; /* Light Sage */
            color: #FFFFFF; /* White Text */
            box-sizing: border-box; /* Include padding in width calculation */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4B5945; /* Dark Olive Green */
            color: #FFFFFF; /* White Text */
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box; /* Include padding in width calculation */
        }

        button:hover {
            background-color: #3E4B38; /* Darker Olive Green */
        }

        .signin-link {
            text-align: center;
            margin-top: 10px;
        }

        .signin-link a {
            color: #91AC8F; /* Soft Green */
            text-decoration: none;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #FFFFFF; /* White Text */
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
</body>
</html>
