<?php
$host = 'localhost'; // Database host
$dbname = 'vehicle-gate'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
