<?php
session_start();
include('../../global-assets/db.php');

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
    // Update the orders table
    $updateOrderQuery = $connection->prepare("UPDATE orders SET delivery_address = ? WHERE order_id = ?");
    $updateOrderQuery->bind_param("si", $address, $order_id);
    
    if ($updateOrderQuery->execute()) {
        // Update the service_orders table
        $updateServiceOrdersQuery = $connection->prepare("UPDATE service_orders SET delivery_address = ? WHERE order_id = ?");
        $updateServiceOrdersQuery->bind_param("si", $address, $order_id);
        
        if ($updateServiceOrdersQuery->execute()) {
            echo json_encode(['success' => true, 'message' => 'Order and Service Order updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update Service Order']);
        }
        
        $updateServiceOrdersQuery->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order']);
    }

    $updateOrderQuery->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found or you are not authorized']);
}

$orderQuery->close();
$connection->close();
?>
