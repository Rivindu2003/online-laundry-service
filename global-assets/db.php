<?php


$host = 'localhost'; 
$username = 'bubb_rivindu'; 
$password = '2FHyNql#@98xOwt9'; 
$database = 'bubb_bubbleshine'; 


$connection = mysqli_connect($host, $username, $password, $database);


if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
