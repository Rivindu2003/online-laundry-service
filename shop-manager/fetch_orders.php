<?php
// Connect to the database
$servername = "localhost";  
$username = "rivindu";       
$password = "Rivindu@1234";             
$dbname = "shop_management";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch recent 5 orders
$sql = "SELECT LPAD(id, 5, '0') AS id, customer_name, product_name, quantity, price, order_date, status
        FROM orders 
        ORDER BY order_date DESC 
        LIMIT 5";

$result = $conn->query($sql);

$orders = array();
if ($result->num_rows > 0) {
    // Fetch all rows into the $orders array
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Return the results as a JSON response
echo json_encode($orders);

$conn->close();
?>
