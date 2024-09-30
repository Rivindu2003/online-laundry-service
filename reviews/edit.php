<?php
session_start();
require '../signup-logins-users/db.php'; // Include your database connection file

if (!isset($_SESSION['username']) || !isset($_GET['id'])) {
    header('Location: login.php'); // Redirect if not logged in or no ID provided
    exit();
}

$reviewId = $_GET['id'];

// Fetch the review from the database
$stmt = $conn->prepare("SELECT * FROM reviews WHERE review_id = ? AND user_id = ?");
$stmt->bind_param("ii", $reviewId, $_SESSION['user_id']); // Ensure the user owns the review
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

if (!$review) {
    echo "Review not found or you do not have permission to edit this review.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedReview = $_POST['review'];

    // Update the review in the database
    $stmt = $conn->prepare("UPDATE reviews SET review = ? WHERE review_id = ?");
    $stmt->bind_param("si", $updatedReview, $reviewId);
    $stmt->execute();

    header('Location: reviews.php'); // Redirect to reviews page after editing
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
</head>
<body>
    <h1>Edit Your Review</h1>
    <form method="POST">
        <textarea name="review" rows="5" required><?php echo htmlspecialchars($review['review']); ?></textarea>
        <button type="submit">Update Review</button>
    </form>
</body>
</html>
