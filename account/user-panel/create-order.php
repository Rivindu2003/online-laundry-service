<?php
session_start();
include('../../global-assets/db.php');


$data = json_decode(file_get_contents("php://input"), true);
$selectedServices = $data['selectedServices'];
$address = $data['address'];
$deliveryDate = $data['deliveryDate'];
$total = $data['total'];


$currentDate = new DateTime();
$deliveryDateObj = new DateTime($deliveryDate);

if ($deliveryDateObj < $currentDate) {
    echo json_encode(['success' => false, 'message' => 'Delivery date cannot be in the past.']);
    exit;
}


$userId = $_SESSION['user_id'];
$orderDate = date('Y-m-d H:i:s'); 
$status = 'Pending'; 
$paymentStatus = 'Unpaid'; 


$stmt = $connection->prepare("INSERT INTO orders (customer_id, order_date, total_amount, status, delivery_address, payment_status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $userId, $orderDate, $total, $status, $address, $paymentStatus);
$stmt->execute();
$orderId = $stmt->insert_id; 


foreach ($selectedServices as $service) {
    $serviceId = $service['service_id'];
    $serviceDate = $deliveryDate; 
    $notes = ''; 
    $serviceStatus = 'Pending'; 

    $serviceStmt = $connection->prepare("INSERT INTO service_orders (order_id, service_id, order_date, status, service_date, delivery_address, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $serviceStmt->bind_param("iisssss", $orderId, $serviceId, $orderDate, $serviceStatus, $serviceDate, $address, $paymentStatus);
    $serviceStmt->execute();
}

echo json_encode(['success' => true]);
?>