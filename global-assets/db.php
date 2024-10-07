<?php


$host = 'localhost'; 
$username = 'bubb_rivindu'; 
$password = 'fJNwC%AtfDA!2mPe'; 
$database = 'bubb_bubbleshine'; 


$connection = mysqli_connect($host, $username, $password, $database);


if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
