<?php

// Database credentials
$host = 'localhost'; // Usually 'localhost'
$username = 'rivindu'; // Your database username
$password = 'Rivindu@1234'; // Your database password
$database = 'shop_management'; // Your database name

// Create a connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
