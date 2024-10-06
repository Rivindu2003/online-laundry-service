<?php
session_start();
include '../global-assets/db.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $feedback_id = intval($_GET['id']);

    
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_feedback = trim($_POST['feedback']);

    
    $sql = "UPDATE reviews SET review = ? WHERE review_id = ? AND user_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sii", $updated_feedback, $feedback_id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: manage-feedback.php"); 
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
    <link rel="stylesheet" href="styles/edit_feedback.css"> 
    <link rel="stylesheet" href="../css/header-footer-sidebar.css">
    <link rel="stylesheet" href="../css/position.css">
</head>
<body>
    <?php $IPATH = "../global-assets/"; include($IPATH."header.html"); ?>
    <?php $IPATH = "../global-assets/"; include($IPATH."sidebar.html"); ?>

    <div class="container">
        <h1>Edit Your Feedback</h1>
        <form action="" method="POST">
            <textarea name="feedback" rows="5" required><?php echo htmlspecialchars($feedback['review']); ?></textarea>
            <br>
            <button type="submit">Update Feedback</button>
            <a href="manage-feedback.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
    <?php $IPATH = "../global-assets/"; include($IPATH."footer.html"); ?>
</body>
</html>
