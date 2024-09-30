<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You are not logged in.']);
    exit;
}

// Retrieve the user_id from session and get data from the request
$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'];
$address = $data['address'];

// Validate the order belongs to the logged-in user
$orderQuery = $connection->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$orderQuery->bind_param("ii", $order_id, $user_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();

if ($orderResult->num_rows > 0) {
    // Update the delivery details
    // ** Error fixed here: Remove the comma before `delivery_address` in the SQL query
    $updateQuery = $connection->prepare("UPDATE orders SET delivery_address = ? WHERE order_id = ?");
    $updateQuery->bind_param("si", $address, $order_id);
    
    // Execute the update query and check if it was successful
    if ($updateQuery->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order']);
    }

    $updateQuery->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found or you are not authorized']);
}

$orderQuery->close();
$connection->close();
?>
