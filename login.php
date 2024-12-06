<?php
session_start();
include('config.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if user exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Debugging output
        echo "Stored Password: " . $user['password']; // Check stored password hash
        echo "Input Password: " . $password; // Check input password

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables based on the user role
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the respective dashboard based on role
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] == 'security') {
                header('Location: security_dashboard.php');
            } elseif ($user['role'] == 'owner') {
                header('Location: owner_dashboard.php');
            }
            exit;
        } else {
            echo "Invalid login credentials!";
        }
    } else {
        echo "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3; /* Dark Olive Green */
            margin: 0;
            padding: 0;
        }

        .login-container {
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
            box-sizing: border-box;
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
            box-sizing: border-box;
        }

        button:hover {
            background-color: #3E4B38; /* Darker Olive Green */
        }

        .signup-link {
            text-align: center;
            margin-top: 10px;
        }

        .signup-link a {
            color: #D8DBBD; /* Soft Green */
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

    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
        </form>

        <div class="signup-link">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>

</body>
</html>
