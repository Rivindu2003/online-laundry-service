<?php
session_start();
include('../../global-assets/db.php');

// Get data from the AJAX request
$data = json_decode(file_get_contents("php://input"), true);
$selectedServices = $data['selectedServices'];
$address = $data['address'];
$deliveryDate = $data['deliveryDate'];
$total = $data['total'];

// Validate the delivery date (server-side)
$currentDate = new DateTime();
$deliveryDateObj = new DateTime($deliveryDate);

if ($deliveryDateObj < $currentDate) {
    echo json_encode(['success' => false, 'message' => 'Delivery date cannot be in the past.']);
    exit;
}

// Insert into orders table
$userId = $_SESSION['user_id'];
$orderDate = date('Y-m-d H:i:s'); // Current date
$status = 'Pending'; // Set default status
$paymentStatus = 'Unpaid'; // Set default payment status

// Insert into orders table
$stmt = $connection->prepare("INSERT INTO orders (customer_id, order_date, total_amount, status, delivery_address, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $userId, $orderDate, $total, $status, $address, $paymentStatus);
$stmt->execute();
$orderId = $stmt->insert_id; // Get the last inserted order ID

// Insert into service_orders table
foreach ($selectedServices as $service) {
    $serviceId = $service['service_id'];
    $serviceDate = $deliveryDate; // Use the same date for service
    $notes = ''; // You can customize this if needed
    $serviceStatus = 'Pending'; // Set default status for service

    $serviceStmt = $connection->prepare("INSERT INTO service_orders (order_id, service_id, order_date, status, service_date, delivery_address, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $serviceStmt->bind_param("iisssss", $orderId, $serviceId, $orderDate, $serviceStatus, $serviceDate, $address, $paymentStatus);
    $serviceStmt->execute();
}

echo json_encode(['success' => true]);
?>