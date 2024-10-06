<?php

include('../global-assets/db.php'); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $review_id = $_POST['review_id'];

    
    $stmt = $connection->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id); 

    if ($stmt->execute()) {
        
        echo "<script>alert('Feedback deleted successfully.'); window.location.href='manage-feedback.php';</script>";
    } else {
        
        echo "<script>alert('Error deleting feedback. Please try again.'); window.location.href='manage-feedback.php';</script>";
    }

    $stmt->close();
    $connection->close();
}
?>
