<?php
session_start();
include 'db.php'; 

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user details
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$query = "SELECT full_name, username, email, address, phone_number FROM users WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id to the prepared statement
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
    <link rel="stylesheet" href="styles/user-profile.css">
</head>
<body>
    <!-- User Details Section -->
    <div class="container">
        <!-- User Image and Info -->
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar"></div>
                <p>rivindu.rathnayake</p>
                <p>rivindu.rathnayake@icloud.com</p>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Downloads</a></li>
                    <li><a href="#">Addresses</a></li>
                    <li><a href="#">Account Details</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        
        <div class="profile">
            <img src="images\avatar.png" alt="User Avatar">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Welcome to BubbleShine</p>
            <p>Contact us at bubbleshine@gmail.com</p>
        </div>

        <!-- User Details Section -->
        <div class="details">
            <form id="user-form">
                <div class="detail-item">
                    <label>Full Name</label>
                    <p class="editable-text" id="name"><?php echo htmlspecialchars($user['full_name']); ?></p>
                    <input type="text" class="editable-input" id="name-input" value="<?php echo htmlspecialchars($user['username']); ?>" style="display:none;">
                </div>
                <div class="detail-item">
                    <label>Email</label>
                    <p class="editable-text" id="email"><?php echo htmlspecialchars($user['email']); ?></p>
                    <input type="email" class="editable-input" id="email-input" value="<?php echo htmlspecialchars($user['email']); ?>" style="display:none;">
                </div>
                <div class="detail-item">
                    <label>Phone</label>
                    <p class="editable-text" id="phone"><?php echo htmlspecialchars($user['phone_number']); ?></p>
                    <input type="text" class="editable-input" id="phone-input" value="<?php echo htmlspecialchars($user['phone_number']); ?>" style="display:none;">
                </div>
                
                <div class="detail-item">
                    <label>Address</label>
                    <p class="editable-text" id="address"><?php echo htmlspecialchars($user['address']); ?></p>
                    <input type="text" class="editable-input" id="address-input" value="<?php echo htmlspecialchars($user['address']); ?>" style="display:none;">
                </div>
                <button type="button" id="edit-btn">Edit</button>
                <button type="button" id="delete-btn">Delete User Account</button>
                <button type="submit" id="save-btn" style="display:none;">Save</button>
            </form>
        </div>
    </div>

    <script src="js/user_details.js"></script>
</body>
</html>

