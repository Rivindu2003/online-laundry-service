<?php
session_start();
include '../global-assets/db.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $feedback_id = intval($_GET['id']);

    // Fetch the feedback details from the database
    $sql = "SELECT * FROM reviews WHERE review_id = ? AND user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $feedback_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Feedback not found or you do not have permission to edit this feedback.";
        exit();
    }

    $feedback = $result->fetch_assoc();
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_feedback = trim($_POST['feedback']);

    // Update the feedback in the database
    $sql = "UPDATE reviews SET review = ? WHERE review_id = ? AND user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sii", $updated_feedback, $feedback_id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: manage-feedback.php"); // Redirect back to the feedback manager
        exit();
    } else {
        echo "Error updating feedback. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback</title>
    <link rel="stylesheet" href="styles/edit_feedback.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="../signup-logins-users/styles/header-footer-sidebar.css">
    <link rel="stylesheet" href="../signup-logins-users/styles/position.css">
</head>
<body>
<?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."header.html"); ?>
<?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."sidebar.html"); ?>
    <div class="container">
        <h1>Edit Your Feedback</h1>
        <form action="" method="POST">
            <textarea name="feedback" rows="5" required><?php echo htmlspecialchars($feedback['review']); ?></textarea>
            <br>
            <button type="submit">Update Feedback</button>
            <a href="manage-feedback.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
    <?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."footer.html"); ?>
</body>
</html>
