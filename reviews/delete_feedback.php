<?php
session_start();
include '../signup-logins-users/db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Delete the feedback from the database
    $sql = "DELETE FROM reviews WHERE review_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Feedback deleted successfully";
    } else {
        echo "Error deleting feedback";
    }
}
?>
