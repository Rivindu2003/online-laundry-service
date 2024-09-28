<?php
// update_order.php

// Include database connection
include 'db_connection.php'; // Adjust this as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'];
    $customerName = $_POST['customerName'];
    $orderStatus = $_POST['orderStatus'];

    // Update the order details
    $query = "UPDATE orders SET customer_name = '$customerName', status = '$orderStatus' WHERE id = $orderId";
    if (mysqli_query($connection, $query)) {
        echo "Order updated successfully!";
    } else {
        echo "Error updating order: " . mysqli_error($connection);
    }
}

// Close the database connection if applicable
mysqli_close($connection);
?>
