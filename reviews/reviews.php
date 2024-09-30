<?php
// Assuming you're connected to a database
include '../signup-logins-users/db.php'; 
session_start(); // For user authentication

// Function to fetch reviews
function fetchReviews($limit = 4) {
    // Database connection (assuming $conn is the mysqli connection)
    global $connection;

    // Query to retrieve reviews (you may need to adjust the table and column names)
    $query = "SELECT user_name, review FROM reviews ORDER BY review_date DESC LIMIT ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
    return $reviews;
}

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']);
$reviews = fetchReviews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
</head>
<body>
<div class="review-container">
        <h2>Customer Reviews</h2>

        <div id="reviews">
            <?php foreach ($reviews as $review) : ?>
                <div class="review">
                    <h4><?php echo htmlspecialchars($review['user_name']); ?></h4>
                    <p><?php echo htmlspecialchars($review['review']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <button id="loadMoreBtn">Load More Reviews</button>

        <?php if ($loggedIn): ?>
            <a href="create_review.php" class="create-review-btn">Create Your Review</a>
        <?php endif; ?>
    </div>
    <script src="js/reviews.js"></script>
</body>
</html>