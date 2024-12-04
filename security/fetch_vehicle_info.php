<?php
include('../config.php'); // Include database connection

// Check if vehicle_number is provided
if (isset($_POST['vehicle_number'])) {
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle_number']);

    // Query to fetch vehicle details based on the vehicle number
    $query = "SELECT * FROM vehicle_logs WHERE vehicle_number = '$vehicle_number' ORDER BY date_time DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the vehicle details and return as JSON response
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'data' => $row
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
