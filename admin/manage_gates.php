<?php
session_start();
include('../config.php'); // Include the database connection

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch users and their current gate assignments
$sql = "SELECT * FROM users WHERE role != 'admin'"; // Only non-admin users
$result = mysqli_query($conn, $sql);

// Update gate assignments
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $gate_number = $_POST['gate_number'];
    
    // Update the user's role to 'security' for the selected gate
    $update_sql = "UPDATE users SET role='security', gate_number='$gate_number' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('User role updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating user role.');</script>";
    }
}

// Fetch updated list of users after the role is updated
$sql_updated = "SELECT * FROM users WHERE role != 'admin'"; // Only non-admin users
$result_updated = mysqli_query($conn, $sql_updated);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FAF6E3;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #2A3663;
            margin-top: 20px;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2A3663;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #D8DBBD;
        }

        tr:hover {
            background-color: #D3F1DF;
        }

        .btn {
            background-color: #2A3663;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            opacity: 0.8;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #2A3663;
            color: white;
        }
    </style>
</head>
<body>

    <h1>Manage Gate Assignments</h1>

    <div class="container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>

        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Current Role</th>
                    <th>Assigned Gate</th>
                    <th>Assign Gate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result_updated)) { ?>
                    <tr>
                        <form method="POST">
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo ucfirst($user['role']); ?></td>
                            <td>
                                <?php
                                    // Show assigned gate, if available
                                    echo $user['gate_number'] ? 'Gate ' . $user['gate_number'] : 'Not Assigned';
                                ?>
                            </td>
                            <td>
                                <select name="gate_number" required>
                                    <option value="">Select Gate</option>
                                    <option value="1" <?php echo ($user['gate_number'] == 1) ? 'selected' : ''; ?>>Gate 1</option>
                                    <option value="2" <?php echo ($user['gate_number'] == 2) ? 'selected' : ''; ?>>Gate 2</option>
                                    <option value="3" <?php echo ($user['gate_number'] == 3) ? 'selected' : ''; ?>>Gate 3</option>
                                    <option value="4" <?php echo ($user['gate_number'] == 4) ? 'selected' : ''; ?>>Gate 4</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn">Assign Security Role</button>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
