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
                header('Location: admin/admin_dashboard.php');
            } elseif ($user['role'] == 'security') {
                header('Location: security/security_dashboard.php');
            } elseif ($user['role'] == 'owner') {
                header('Location: owner/owner_dashboard.php');
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

<form method="POST" action="login.php">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
</form>
