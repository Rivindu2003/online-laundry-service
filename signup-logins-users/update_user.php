<?php
session_start();
include 'db.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Get input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (isset($data['name'], $data['email'], $data['phone'], $data['address'])) {
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];

    // Update query
    $update_query = "UPDATE users SET full_name = ?, email = ?, phone_number = ?, address = ? WHERE id = ?";
    $stmt = $connection->prepare($update_query);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $_SESSION['user_id']);
    
    // Execute and check for success
    $success = $stmt->execute();
    
    // Return response
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
