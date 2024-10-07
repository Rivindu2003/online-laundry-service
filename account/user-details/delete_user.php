<?php
session_start();
include '../../global-assets/db.php'; 


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}


$user_id = $_SESSION['user_id'];


$check_orders_query = "SELECT COUNT(*) as order_count FROM orders WHERE customer_id = ?";
$stmt = $connection->prepare($check_orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order_data = $order_result->fetch_assoc();
$stmt->close();


$check_reviews_query = "SELECT COUNT(*) as review_count FROM reviews WHERE user_id = ?";
$stmt = $connection->prepare($check_reviews_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$review_result = $stmt->get_result();
$review_data = $review_result->fetch_assoc();
$stmt->close();


if ($order_data['order_count'] > 0 || $review_data['review_count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete user account. There are existing orders or reviews associated with this account.']);
    exit;
}


$connection->begin_transaction();

try {
    
    $delete_reviews_query = "DELETE FROM reviews WHERE user_id = ?";
    $stmt = $connection->prepare($delete_reviews_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $connection->prepare($delete_query);
    $stmt->bind_param("i", $user_id);
    $success = $stmt->execute();

    if ($success) {
        
        if ($stmt->affected_rows > 0) {
            
            session_destroy();
            echo json_encode(['success' => true, 'message' => 'User account deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found or already deleted.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
    }

    
    $connection->commit();
} catch (Exception $e) {
    
    $connection->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}


$stmt->close();

$connection->close();
?>
