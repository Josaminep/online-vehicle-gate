<?php
session_start();
// Check if user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

include('config.php'); // Include database connection

// Fetch users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* Base styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3; /* Light cream background */
            margin: 0;
            padding: 0;
            color: black; /* Default text color */
        }

        h2 {
            text-align: center;
            color: #4B5945; /* Dark olive for headers */
            margin-top: 20px;
        }

        nav {
            background-color: #66785F; /* Muted olive-green */
            color: white;
            padding: 15px;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #B2C9AD; /* Light mint green hover */
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px; /* Increased padding for larger font */
            text-align: left;
            border: 1px solid #ddd;
            font-size: 1.1em; /* Adjusted font size */
        }

        table th {
            background-color: #4B5945; /* Dark olive background for table headers */
            color: white;
        }

        table td {
            background-color: #91AC8F; /* Light olive background for table data */
        }

        table td a {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #B2C9AD; /* Light mint green for action buttons */
            color: white;
            border-radius: 5px;
            margin-right: 10px;
        }

        table td a:hover {
            background-color: #66785F; /* Muted olive-green hover */
        }

        .add-btn {
            background-color: #B2C9AD; /* Light mint green */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #91AC8F; /* Slightly darker mint on hover */
        }

        .back-btn {
            background-color: #4B5945; /* Dark olive for back button */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #66785F; /* Muted olive-green on hover */
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #4B5945; /* Dark olive footer */
            color: white;
        }
    </style>
</head>
<body>    
    <div class="container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="back-btn">Back</a>
        
        <a href="add_user.php" class="add-btn">Add User</a>
        
        <h2>Manage Users</h2>

        <table>
            <tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php 
            // Assuming $result contains the fetched user data
            while ($user = mysqli_fetch_assoc($result)) { 
            ?>
                <tr>
                    <!-- Display Full Name (fname and lname concatenated) -->
                    <td><?php echo $user['fname'] . ' ' . $user['lname']; ?></td>
                    
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo ucfirst($user['role']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
