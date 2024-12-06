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
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3; /* Light cream background */
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #2A3663; /* Dark blue for headers */
            margin-top: 20px;
        }

        nav {
            background-color: #2A3663; /* Dark blue */
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
            color: #D8DBBD; /* Soft green hover */
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
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #2A3663; /* Dark blue header */
            color: white;
        }

        table td a {
            text-decoration: none;
            padding: 5px 10px;
            background-color: #B59F78; /* Warm beige for action buttons */
            color: white;
            border-radius: 5px;
            margin-right: 10px;
        }

        table td a:hover {
            background-color: #D8DBBD; /* Soft green hover */
        }

        .add-btn {
            background-color: #B59F78; /* Warm beige */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #D8DBBD; /* Soft green hover */
        }

        .back-btn {
            background-color: #F44336; /* Red for back button */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #D32F2F; /* Darker red on hover */
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #2A3663; /* Dark blue footer */
            color: white;
        }
    </style>
</head>
<body>

    <h1>Manage Users</h1>
    
    <div class="container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="back-btn">Back</a>
        
        <a href="add_user.php" class="add-btn">Add New User</a>
        
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
