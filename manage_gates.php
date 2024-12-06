<?php
session_start();
include('config.php'); // Include the database connection

// Ensure only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch only users with the role 'security'
$sql = "SELECT * FROM users WHERE role = 'security'";
$result = mysqli_query($conn, $sql);

// Update gate assignments
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $gate_number = $_POST['gate_number'];
    
    // Update the user's gate assignment
    $update_sql = "UPDATE users SET gate_number='$gate_number' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Gate assignment updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating gate assignment.');</script>";
    }
}

// Fetch updated list of users after any potential updates
$sql_updated = "SELECT * FROM users WHERE role = 'security'";
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
            background-color: #FAF6E3; /* Light Cream */
            margin: 0;
            padding: 0;
            font-size: 16px; /* Slightly smaller font size */
        }

        h2 {
            text-align: center;
            color: #4B5945; /* Dark Olive */
            margin-top: 20px;
            font-size: 28px; /* Slightly smaller heading */
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 18px; /* Slightly smaller font size */
        }

        th, td {
            padding: 14px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 18px; /* Slightly smaller font size */
        }

        th {
            background-color: #4B5945; /* Dark Olive */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #D8DBBD; /* Soft Beige */
        }

        tr:hover {
            background-color: #D3F1DF; /* Soft Green */
        }

        .btn {
            background-color: #4B5945; /* Dark Olive */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            font-size: 18px; /* Slightly smaller font size */
        }

        .btn:hover {
            opacity: 0.8;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #4B5945; /* Dark Olive */
            color: white;
        }

        select {
            padding: 8px;
            font-size: 16px; /* Slightly smaller font size */
            border: 1px solid #66785F; /* Muted Olive */
            border-radius: 5px;
        }

        select:focus {
            outline-color: #91AC8F; /* Soft Green */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="btn">Back</a>

        <h2>Manage Gate Assignments</h2>

        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Role</th>
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
                                <button type="submit" class="btn">Assign</button>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>

