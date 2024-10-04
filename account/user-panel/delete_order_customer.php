<?php
include('../../global-assets/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Decode the JSON request body
$data = json_decode(file_get_contents('php://input'), true);
$order_id = $data['order_id'];

// Delete the order from the orders table where the order ID and customer ID match
$deleteOrderQuery = $connection->prepare("DELETE FROM orders WHERE order_id = ? AND customer_id = ? AND status = 'pending'");
$deleteOrderQuery->bind_param("ii", $order_id, $user_id);
$deleteOrderQuery->execute();

if ($deleteOrderQuery->affected_rows > 0) {
    // Also delete related records from the service_orders table if necessary
    $deleteServiceOrdersQuery = $connection->prepare("DELETE FROM service_orders WHERE order_id = ?");
    $deleteServiceOrdersQuery->bind_param("i", $order_id);
    $deleteServiceOrdersQuery->execute();
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Order cannot be canceled or does not exist']);
}

$deleteOrderQuery->close();
$connection->close();
?>
