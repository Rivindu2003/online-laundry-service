<?php
// Assuming you have a connection to your database in this file
include('../global-assets/db.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the review ID from the POST request
    $review_id = $_POST['review_id'];

    // Prepare and execute the deletion statement
    $stmt = $connection->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id); // 'i' indicates the type (integer)

    if ($stmt->execute()) {
        // Success: Redirect or display success message
        echo "<script>alert('Feedback deleted successfully.'); window.location.href='manage-feedback.php';</script>";
    } else {
        // Error: Display an error message
        echo "<script>alert('Error deleting feedback. Please try again.'); window.location.href='manage-feedback.php';</script>";
    }

    $stmt->close();
    $connection->close();
}
?>
