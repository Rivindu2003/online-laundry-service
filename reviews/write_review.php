<?php
session_start(); // Start session to check login status

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /login/login.php"); // Redirect to login page if not logged in
    exit();
}

include '../global-assets/db.php';

$userID = $_SESSION['user_id'];

$userQuery = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt = $connection->prepare($userQuery);
$stmt->bind_param("i", $userID);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
    $fullName = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
    $firstName = htmlspecialchars($user['first_name']); // Store first name separately for HTML output
} else {
    echo "User not found.";
    exit();
}

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
    <link rel="stylesheet" href="../css/header-footer-sidebar.css"> 
    <link rel="stylesheet" href="../css/position.css">
</head>
<body>
        <?php $IPATH = "../global-assets/"; include($IPATH."header.html"); ?>
        <?php $IPATH = "../global-assets/"; include($IPATH."sidebar.html"); ?>


<div class="form-container"> <!-- New wrapper for styling -->
        <h1>Write Your Review</h1>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Your Name" value="<?php echo $fullName ?>" required>
     
            <textarea name="comment" placeholder="Your Comment" required></textarea>
            <button type="submit">Submit Review</button>
        </form>
    </div>
    <?php $IPATH = "../global-assets/"; include($IPATH."footer.html"); ?>
</body>
</html>
