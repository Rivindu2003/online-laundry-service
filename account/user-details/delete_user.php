<?php
session_start();
include '../../global-assets/db.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check for existing orders
$check_orders_query = "SELECT COUNT(*) as order_count FROM orders WHERE customer_id = ?";
$stmt = $connection->prepare($check_orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order_data = $order_result->fetch_assoc();
$stmt->close();

// Check for existing reviews
$check_reviews_query = "SELECT COUNT(*) as review_count FROM reviews WHERE user_id = ?";
$stmt = $connection->prepare($check_reviews_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$review_result = $stmt->get_result();
$review_data = $review_result->fetch_assoc();
$stmt->close();

// If there are existing orders or reviews, return an error message
if ($order_data['order_count'] > 0 || $review_data['review_count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete user account. There are existing orders or reviews associated with this account.']);
    exit;
}

// Start a transaction
$connection->begin_transaction();

try {
    // Delete user's reviews first
    $delete_reviews_query = "DELETE FROM reviews WHERE user_id = ?";
    $stmt = $connection->prepare($delete_reviews_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Now delete the user
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $connection->prepare($delete_query);
    $stmt->bind_param("i", $user_id);
    $success = $stmt->execute();

    if ($success) {
        // Check if any rows were affected (i.e., user deleted)
        if ($stmt->affected_rows > 0) {
            // Optionally, destroy the session to log the user out
            session_destroy();
            echo json_encode(['success' => true, 'message' => 'User account deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found or already deleted.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
    }

    // Commit the transaction
    $connection->commit();
} catch (Exception $e) {
    // Rollback the transaction if something failed
    $connection->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

// Close the statement
$stmt->close();
// Close the database connection if not using persistent connections
$connection->close();
?>
