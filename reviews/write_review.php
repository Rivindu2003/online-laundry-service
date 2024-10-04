<?php
session_start(); // Start session to check login status

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include '../signup-logins-users/db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $userID = $_SESSION['user_id'];

    // Insert review into database (rating removed)
    $sql = "INSERT INTO reviews (user_id, user_name, review) VALUES ($userID, '$name', '$comment')";
    
    if ($connection->query($sql) === TRUE) {
        echo "Review submitted successfully!";
        header("Location: reviews.php"); // Redirect back to testimonials page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" .  $connection->error;
    }

    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Your Review</title>
    <link rel="stylesheet" href="styles/write_reviews.css"> <!-- Link to your CSS file -->
</head>
<body>
<div class="form-container"> <!-- New wrapper for styling -->
        <h1>Write Your Review</h1>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Your Name" required>
     
            <textarea name="comment" placeholder="Your Comment" required></textarea>
            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>
</html>
