<?php
session_start();
include '../signup-logins-users/db.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch feedbacks for the current logged-in user
$sql = "SELECT * FROM reviews WHERE user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$feedbacks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Feedbacks</title>
    <link rel="stylesheet" href="styles/manage-feedbacks.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="../signup-logins-users/styles/header-footer-sidebar.css">
    <link rel="stylesheet" href="../signup-logins-users/styles/position.css">
</head>
<body>
    <?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."header.html"); ?>
    <?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."sidebar.html"); ?>
    <div class="container">
        <h1>Manage Your Feedbacks</h1>
        <table>
            <thead>
                <tr>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['review']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($feedback['review_date'])); ?></td>
                        <td>
                            <button class="edit-btn" onclick="editFeedback(<?php echo $feedback['review_id']; ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteFeedback(<?php echo $feedback['review_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php $IPATH = "../signup-logins-users/assets/"; include($IPATH."footer.html"); ?>
    <script src="js/manage-feedbacks.js"></script> <!-- Link to JavaScript file -->
</body>
</html>
