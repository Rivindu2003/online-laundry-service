<?php
session_start();
include '../../global-assets/db.php'; 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['address'])) {
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];

    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ?, address = ? WHERE id = ?";
    $stmt = $connection->prepare($update_query);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $_SESSION['user_id']);
    
    // Execute and check for success
    $success = $stmt->execute();
    
    // Return response
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
