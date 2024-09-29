<?php
session_start();
include 'db.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Delete query
$delete_query = "DELETE FROM users WHERE id = ?";
$stmt = $connection->prepare($delete_query);
$stmt->bind_param("i", $user_id);

// Execute and check for success
if ($stmt->execute()) {
    // Clear the session and redirect the user
    session_destroy();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user account.']);
}
