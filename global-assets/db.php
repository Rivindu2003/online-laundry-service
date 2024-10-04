<?php

// Database credentials
$host = 'localhost'; // Usually 'localhost'
$username = 'bubb_rivindu'; // Your database username
$password = 'fJNwC%AtfDA!2mPe'; // Your database password
$database = 'bubb_bubbleshin'; // Your database name

// Create a connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
