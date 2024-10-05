<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login-admin.php");
    exit();
}

include '../global-assets/db.php';

// Get username from query string
if (isset($_GET['username'])) {
    $username = htmlspecialchars($_GET['username']);

    // Fetch user details from the database
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("User not found.");
    }

    $user = $result->fetch_assoc();
} else {
    die("Username not provided.");
}

// Handle update user request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update user details
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE username = ?";
    $update_stmt = $connection->prepare($update_query);
    $update_stmt->bind_param("sssss", $first_name, $last_name, $email, $phone_number, $username);
    $update_stmt->execute();

    echo "<script>alert('User updated successfully!');
            window.location.href = 'manage-regular-users.php';</script>";
}

// Handle delete user request
if (isset($_POST['delete_user'])) {
    $delete_query = "DELETE FROM users WHERE username = ?";
    $delete_stmt = $connection->prepare($delete_query);
    $delete_stmt->bind_param("s", $username);
    $delete_stmt->execute();

    header("Location: manage-regular-users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../global-assets/admin-sidebar.css">
    <link rel="stylesheet" href="styles/admin-panel.css">
</head>
<body>
    <?php $IPATH = "../global-assets/"; include($IPATH . "admin-sidebar.html"); ?>
    
    <div class="edit-user-container">
        <h1>Edit User Details</h1>
        <form method="POST" action="edit-user-customer.php?username=<?php echo urlencode(htmlspecialchars($user['username'])); ?>">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
            <div>
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div>
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div>
                <label>Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="button-group">
                <button class="manage-btn" onclick="window.location.href='edit-user-customer.php?username=<?php echo urlencode(htmlspecialchars($user['username'])); ?>'">Save Changes ✅</button>
                <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
            </div>
        </form>
    </div>
</body>
</html>

<style>
    .edit-user-container {
        width: 50%;
        margin: 0 auto; /* Center the form */
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff; /* White background */
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .button-group {
        display: flex;
        justify-content: space-around; /* Space buttons evenly */
    }

    button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #007BFF; /* Primary color */
        color: white;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3; /* Darker shade on hover */
    }
</style>
