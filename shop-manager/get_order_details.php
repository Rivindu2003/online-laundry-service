<?php
// get_order_details.php

// Include database connection
include 'db_connection.php'; // Adjust this as necessary

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Fetch the order details
    $query = "SELECT * FROM orders WHERE id = $orderId"; // Be careful with SQL injection; consider using prepared statements
    $result = mysqli_query($connection, $query);
    $order = mysqli_fetch_assoc($result);
    
    if ($order) {
        // Return order details as JSON
        echo json_encode($order);
    } else {
        echo json_encode(['error' => 'Order not found.']);
    }
} else {
    echo json_encode(['error' => 'No order specified.']);
}

// Close the database connection if applicable
mysqli_close($connection);
?>
