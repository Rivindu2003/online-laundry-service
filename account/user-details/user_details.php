<?php
session_start();
include '../../global-assets/db.php'; 

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
    exit;
}

// Fetch user details
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$query = "SELECT first_name, last_name, username, email, address, phone_number FROM users WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id to the prepared statement
if ($connection->connect_error) {
    // Display error message if connection fails
    die("Connection failed: " . $connection->connect_error);
} 

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch the user data
} else {
    // Handle user not found
    echo "User not found.";
}

?>
<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../../css/header-footer-sidebar.css">
    <link rel="stylesheet" href="../../css/user-details.css">
    <link rel="stylesheet" href="../../css/position.css">
    <script src="../../sweetalert/docs/assets/sweetalert/sweetalert.min.js"></script>
</head>
<body>
   <?php $IPATH = "../../global-assets/"; include($IPATH."header.html"); ?>

    <?php $IPATH = "../../global-assets/"; include($IPATH."sidebar.html"); ?>
    
    <div class="container">
  
       
        <div class="profile">
            <img src="../../images/avatar.png" alt="User Avatar">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Welcome to BubbleShine</p>
            <p>Contact us at bubbleshine@gmail.com</p>
        </div>

        
        <div class="details">
            <form id="user-form">
                <div class="detail-item">
                    <label>First Name</label>
                    <p class="editable-text" id="first-name"><?php echo htmlspecialchars($user['first_name'] ?? ''); ?></p>
                    <input type="text" class="editable-input" id="first-name-input" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" style="display:none;">
                </div>
                <div class="detail-item">
                    <label>Last Name</label>
                    <p class="editable-text" id="last-name"><?php echo htmlspecialchars($user['last_name']  ?? ''); ?></p>
                    <input type="text" class="editable-input" id="last-name-input" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" style="display:none;">
                </div>
                <div class="detail-item">
                    <label>Email</label>
                    <p class="editable-text" id="email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                    <input type="email" class="editable-input" id="email-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" style="display:none;">
                </div>
                <div class="detail-item">
                    <label>Phone</label>
                    <p class="editable-text" id="phone"><?php echo htmlspecialchars($user['phone_number'] ?? ''); ?></p>
                    <input type="text" class="editable-input" id="phone-input" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" style="display:none;">
                </div>
                
                <div class="detail-item">
                    <label>Address</label>
                    <p class="editable-text" id="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></p>
                    <input type="text" class="editable-input" id="address-input" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" style="display:none;">
                </div>
                <button type="button" id="edit-btn">Edit</button>
                <button type="button" id="delete-btn">Delete User Account</button>
                <button type="submit" id="save-btn" style="display:none;">Save</button>
            </form>
        </div>
    </div>
    <?php $IPATH = "../../global-assets/"; include($IPATH."footer.html"); ?>
   
    <script src="../../js/user_details.js"></script>
</body>
</html>