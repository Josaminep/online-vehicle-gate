<?php
session_start();
include('config.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // Insert the user into the database
        $role = 'owner'; // Default role (you can change based on your needs)
        $status = 'active'; // Default status
        $created_at = date('Y-m-d H:i:s'); // Current timestamp
        $updated_at = date('Y-m-d H:i:s'); // Current timestamp

        $sql = "INSERT INTO users (username, password, role, status, created_at, updated_at) 
                VALUES ('$username', '$hashedPassword', '$role', '$status', '$created_at', '$updated_at')";
        
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

<!-- HTML Form for Sign Up -->
<form method="POST" action="signup.php">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <input type="password" name="confirm_password" required placeholder="Confirm Password">
    <button type="submit">Sign Up</button>
</form>
